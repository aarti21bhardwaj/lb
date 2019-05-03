<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Log\Log;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * Evidences Controller
 *
 * @property \App\Model\Table\EvidencesTable $Evidences
 *
 * @method \App\Model\Entity\Evidence[] paginate($object = null, array $settings = [])
 */
class TeacherEvidencesController extends ApiController
{

  public function initialize()
  {
      parent::initialize();
  }

  public function getEvidenceImpactScores($id = null){
    if(!$this->request->isGet()){
      throw new MethodNotAllowedException();
    }

    $teacherEvidences = $this->TeacherEvidences->findById($id)
                                 ->contain(['TeacherEvidenceImpacts.Impacts.ImpactCategories','TeacherEvidenceImpacts' => function($q){
                                    return $q->contain(['TeacherEvidenceImpactScores','Impacts']);
                                 }])
                                 ->first();

    $this->loadModel('ImpactCategories');
    $impact_categories = $this->ImpactCategories->find()
                                                ->matching('Impacts.TeacherEvidenceImpacts.TeacherEvidences', 
                                                            function($q) use ($id){
                                                              return $q->where(['TeacherEvidences.id' =>$id]);
                                                  })
                                                ->all()
                                                ->indexBy('id')
                                                ->toArray();

    $teacherEvidences->impact_categories = array_values($impact_categories);

    $this->loadModel('Scales');
    $sessionData = $this->request->session()->read('campusSettings');
    
    //Pull from settings and set the key for the scale here.
    $teacherEvidences->impacts_scale = $this->Scales->findById($sessionData['Impact Scale']->value)->contain(['ScaleValues'])->first()->toArray();
    $teacherEvidenceImpactScores = (new Collection ($teacherEvidences->teacher_evidence_impacts))->indexBy('impact_id')->extract('teacher_evidence_impact_score')->toArray();
    // pr($evidences->evidence_impacts);die;
    // pr($evidenceImpactScores);die;

    $teacherEvidences->teacher_evidence_impact_scores = $teacherEvidenceImpactScores;

    $impacts = new Collection ($teacherEvidences->teacher_evidence_impacts);
    $impacts = $impacts->groupBy('impact.impact_category_id')->toArray();
    $teacherEvidences->impacts = $impacts;

    $impacts = new Collection ($teacherEvidences->teacher_evidence_impacts);
    unset($teacherEvidences->teacher_evidence_impacts);
    
    
    $success = true;

    $this->set('data',$teacherEvidences);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']);

  }

  public function saveEvidenceImpactScores(){

    if(!$this->request->is(['post'])){
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->data;
    $connection = ConnectionManager::get('default');
    $response = $connection->transactional(function () use ($data){
      $evidenceImpactScores = [];
      if(!empty($data['teacher_evidence_impact_scores'])){
        $this->loadModel('TeacherEvidenceImpactScores');
        $teacherEvidenceImpactIds = (new Collection($data['teacher_evidence_impact_scores']))->extract('teacher_evidence_impact_id')->toArray();
        $teacherEvidenceImpactIds = array_unique($teacherEvidenceImpactIds);
        
        $delete = $this->TeacherEvidenceImpactScores->deleteAll([
                                                            'teacher_evidence_impact_id IN' => $teacherEvidenceImpactIds
                                                        ]);          

        $teacherEvidenceImpactScores = $this->TeacherEvidenceImpactScores->newEntities($data['teacher_evidence_impact_scores']);
        
        $teacherEvidenceImpactScores = $this->TeacherEvidenceImpactScores->patchEntities($teacherEvidenceImpactScores, $data['teacher_evidence_impact_scores']);

        if (!$this->TeacherEvidenceImpactScores->saveMany($teacherEvidenceImpactScores)) {   
          Log::write('error',$teacherEvidenceImpactScores);
          throw new Exception("TeacherEvidenceImpactScores could not be saved.");
        }

        Log::write('debug',$teacherEvidenceImpactScores);
      }

      $success = true;

      $this->set('status',$success);
      $this->set('teacherEvidenceImpactScores',$teacherEvidenceImpactScores);
      $this->set('_serialize', ['status','teacherEvidenceImpactScores']);

    });
  }

  public function index($teacher_id = null){
    
    if(!$this->request->is('get')){
      throw new MethodNotAllowedException("Method not allowed", 1);
    }
    
    if($this->Auth->user('role')['name'] == 'teacher'){
      $teacherId = $this->Auth->user('id');
    
    } else {

      if(!$teacher_id){
        throw new BadRequestException("Missing Teacher id");
      }
      $teacherId = $teacher_id;
    }
    
    $teacherEvidences = $this->TeacherEvidences->find()
                                              ->where(['teacher_id' => $teacherId])
                                              ->contain(['TeacherEvidenceSections.Sections.Courses','TeacherEvidenceImpacts.Impacts'])
                                              ->order(['created' => 'DESC'])
                                              ->all();
    
    $teacherEvidences = $teacherEvidences->map(function ($value, $key) {
      $impact_names = (new Collection ($value->teacher_evidence_impacts))->extract('impact.name')->toArray();
      $course_names = (new Collection ($value->teacher_evidence_sections))->extract('section.course.name')->toArray();
      $value->impact_and_course_names = array_merge($impact_names, $course_names);
      $value->impact_and_course_names = array_map('strtolower', $value->impact_and_course_names);
      return $value;
    });

    $success = true;

    $this->set('data',$teacherEvidences);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']); 
  }

  public function view($id){

    if(!$this->request->is('get')){
      throw new MethodNotAllowedException("Method not allowed", 1);
    }
    if(!$id){
      throw new BadRequestException("Missing Teacher Evidence Id");
    }

    $teacherId = $this->Auth->user('id');

    $this->loadModel('TeacherEvidences');
    $teacherEvidence = $this->TeacherEvidences->findById($id)
                                      ->where(['teacher_id' => $teacherId])
                                      ->contain(['TeacherEvidenceSections.Sections.Courses','TeacherEvidenceImpacts.Impacts'])
                                      ->first();

    $success = true;

    $this->set('data',$teacherEvidence);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']); 
  }

  public function add() {
    if(!$this->request->is(['post'])){
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->getData();
    if(!isset($data['teacher_id']) || !$data['teacher_id']){
      throw new BadRequestException("Missing Teacher id");
    }
    if(!isset($data['title']) || !$data['title']){
      throw new BadRequestException("Missing Title");
    }

    $this->loadModel('TeacherEvidences');

    $teacherEvidence = $this->TeacherEvidences->newEntity();
    $teacherEvidence = $this->TeacherEvidences->patchEntity($teacherEvidence, $data
      // ,  ['associated' => ['TeacherEvidenceSections']]
      );

    if (!$this->TeacherEvidences->save($teacherEvidence
      // ,['associated' => ['TeacherEvidenceSections']]
      )) {
      throw new Exception("Teacher Evidences could not be saved.");
    }

    $success = true;

    $this->set('data',$teacherEvidence);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']);
  }

  public function edit($id = null) {

    if (!$this->request->is(['patch', 'post', 'put'])) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->getData();

    if(!isset($data['teacher_id']) || !$data['teacher_id']){
      throw new BadRequestException("Missing Teacher id");
    }
    if(!isset($data['title']) || !$data['title']){
      throw new BadRequestException("Missing Title");
    }

    $this->loadModel('TeacherEvidences');
    $teacherEvidence = $this->TeacherEvidences->get($id, [
          'contain' => ['TeacherEvidenceSections']
    ]);
   
    $teacherEvidence = $this->TeacherEvidences->patchEntity($teacherEvidence, $data, ['associated' => ['TeacherEvidenceSections']]);
    if (!$this->TeacherEvidences->save($teacherEvidence, ['associated' => ['TeacherEvidenceSections']])) {   
      Log::write('error',$teacherEvidence);
      throw new Exception("Teacher Evidences could not be saved.");
    }

    $success = true;

    $this->set('data',$teacherEvidence);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']);
  }

  public function delete($id = null){
  
    if (!$this->request->is(['delete'])) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    if(!$id){
      throw new BadRequestException("Missing Teacher Evidence Id");
    }

    $this->loadModel('TeacherEvidences');
    $evidence = $this->TeacherEvidences->get($id);
    if (!$this->TeacherEvidences->delete($evidence)) {
        throw new Exception("Evidence could not be deleted.");
    } 
      
    $success = true;
    
    $this->set(compact('success'));
    $this->set('_serialize', ['success']);
  }

  public function uploadResources(){
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
    //  pr($_FILES);
      $target_path = WWW_ROOT . Configure::read('FileUpload.uploadPathForEvidence');
      $file = $_FILES['fileKey']['name'];
      //sanitize filename
      $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
      $file = time().$file;
      $success = false;
      $data = [];
      if ($file != '') {
        if (move_uploaded_file($_FILES['fileKey']['tmp_name'], $target_path.DS.$file)) {
            
            $data = [
                      'file_path' => 'webroot'.DS.Configure::read('FileUpload.uploadPathForEvidence').DS,
                      'file_name' => $file
                    ];
                  
            $success = true;
        } 
      }

      $this->set('status',$success);
      $this->set('data',$data);
      $this->set('_serialize', ['status','data']);
  }

  public function addImpact($teacherEvidenceId){

      if(!$this->request->is('post')){
          throw new MethodNotAllowedException("Request is not post");
      }

      if(!$teacherEvidenceId){
          throw new BadRequestException("Missing Teacher Evidence Id");
      } 

      $data = $this->request->getData();
      if(!isset($data['impact_id']) && empty($data['impact_id'])){
          throw new BadRequestException('Missing impact id');
      }
      $teacherEvidenceImpactData = [
                                        'teacher_evidence_id' => $teacherEvidenceId,
                                        'impact_id' => $data['impact_id']
                                    ];

      $this->loadModel('TeacherEvidenceImpacts');
      $teacherEvidenceImpact = $this->TeacherEvidenceImpacts->newEntity();
      $teacherEvidenceImpact = $this->TeacherEvidenceImpacts->patchEntity($teacherEvidenceImpact, $teacherEvidenceImpactData);

      if(!$this->TeacherEvidenceImpacts->save($teacherEvidenceImpact)){
          throw new InternalErrorException('Something went wrong');
      }

      $response = array();
      $response['status'] = true;
      $response['data'] = $this->TeacherEvidenceImpacts->findById($teacherEvidenceImpact->id)->contain(['Impacts.ImpactCategories'])->first();

      $this->set('response', $response);
      $this->set('_serialize', ['response']);
  }

    public function indexImpacts($teacherEvidenceId){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request is not get");
            
        }

        if(!$teacherEvidenceId){
            throw new BadRequestException("Missing Teacher Evidence Id");
            
        } 

        $this->loadModel('TeacherEvidenceImpacts');
        $teacherEvidenceImpacts = $this->TeacherEvidenceImpacts->find()->where(['teacher_evidence_id' => $teacherEvidenceId])
                                                   ->contain(['Impacts.ImpactCategories'])
                                                   ->all()
                                                   ->toArray();

        $response = array();
        $response['status'] = true;
        $response['data'] = $teacherEvidenceImpacts;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

    public function deleteImpact($impactId, $teacherEvidenceId){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        if(!$impactId){
            throw new BadRequestException("Missing field impact_id", 1);
        }

        if(!$teacherEvidenceId){
            throw new BadRequestException("Missing Teacher Evidence Id");
            
        }

        $this->loadModel('TeacherEvidenceImpacts');
        $teacherEvidenceImpact = $this->TeacherEvidenceImpacts->find()->where(['teacher_evidence_id' => $teacherEvidenceId,
                                                                  'impact_id' => $impactId
                                                            ])
                                                         ->first();
        if(!$teacherEvidenceImpact){
            throw new BadRequestException('Record Not Found');
        }
        $response = array(); 
        if ($this->TeacherEvidenceImpacts->delete($teacherEvidenceImpact)) {
            $response['status'] = true;
            $response['data'] = $teacherEvidenceImpact->id; 
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }
}