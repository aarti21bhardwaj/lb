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
class EvidencesController extends ApiController
{

  public function initialize()
  {
      parent::initialize();
  }

  public function getEvidenceImpactScores($id = null){
    if(!$this->request->isGet()){
      throw new MethodNotAllowedException();
    }

    $evidences = $this->Evidences->findById($id)
                                 ->contain(['EvidenceImpacts.Impacts.ImpactCategories','EvidenceImpacts' => function($q){
                                    return $q->contain(['EvidenceImpactScores','Impacts']);
                                 }])
                                 ->first();

    $this->loadModel('ImpactCategories');
    $impact_categories = $this->ImpactCategories->find()
                                                ->matching('Impacts.EvidenceImpacts.Evidences', 
                                                            function($q) use ($id){
                                                              return $q->where(['Evidences.id' =>$id]);
                                                  })
                                                ->all()
                                                ->indexBy('id')
                                                ->toArray();

    $evidences->impact_categories = array_values($impact_categories);

    $this->loadModel('Scales');
    $sessionData = $this->request->session()->read('campusSettings');
    
    //Pull from settings and set the key for the scale here.
    $evidences->impacts_scale = $this->Scales->findById($sessionData['Impact Scale']->value)->contain(['ScaleValues'])->first()->toArray();
    $evidenceImpactScores = (new Collection ($evidences->evidence_impacts))->indexBy('impact_id')->extract('evidence_impact_score')->toArray();
    // pr($evidences->evidence_impacts);die;
    // pr($evidenceImpactScores);die;

    $evidences->evidence_impact_scores = $evidenceImpactScores;


    // $impacts_score = new Collection ($evaluations->evaluation_impact_scores);
    //   $evaluations->evaluation_impact_scores = $impacts_score->indexBy('impact_id')->toArray();
    // pr($evidences->evidence_impacts);die;
    $impacts = new Collection ($evidences->evidence_impacts);
    $impacts = $impacts->groupBy('impact.impact_category_id')->toArray();
    $evidences->impacts = $impacts;

    $impacts = new Collection ($evidences->evidence_impacts);
    unset($evidences->evidence_impacts);
    
    
    $success = true;

    $this->set('data',$evidences);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']);

  }

  public function saveEvidenceImpactScores(){

    if(!$this->request->is(['post'])){
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->data;
    // pr($data); die;
    $connection = ConnectionManager::get('default');
    $response = $connection->transactional(function () use ($data){
      $evidenceImpactScores = [];
      if(!empty($data['evidence_impact_scores'])){
        $this->loadModel('EvidenceImpactScores');
        $evidenceImpactIds = (new Collection($data['evidence_impact_scores']))->extract('evidence_impact_id')->toArray();
        $evidenceImpactIds = array_unique($evidenceImpactIds);
        
        $delete = $this->EvidenceImpactScores->deleteAll([
                                                            'evidence_impact_id IN' => $evidenceImpactIds
                                                        ]);          

        $evidenceImpactScores = $this->EvidenceImpactScores->newEntities($data['evidence_impact_scores']);
        
        $evidenceImpactScores = $this->EvidenceImpactScores->patchEntities($evidenceImpactScores, $data['evidence_impact_scores']);

        if (!$this->EvidenceImpactScores->saveMany($evidenceImpactScores)) {   
          Log::write('error',$evidenceImpactScores);
          throw new Exception("EvidenceImpactScores could not be saved.");
        }

        Log::write('debug',$evidenceImpactScores);
      }

      $success = true;

      $this->set('status',$success);
      $this->set('evidenceImpactScores',$evidenceImpactScores);
      $this->set('_serialize', ['status','evidenceImpactScores']);

    });
  }

  public function index($student_id = null){
    
    if(!$this->request->is('get')){
      throw new MethodNotAllowedException("Method not allowed", 1);
    }
    
    if($this->Auth->user('role')['name'] == 'student'){
      $studentId = $this->Auth->user('id');
    
    } else {

      if(!$student_id){
        throw new BadRequestException("Missing Student id");
      }
      $studentId = $student_id;
    }
    
    $evidences = $this->Evidences->find()
                                ->where(['student_id' => $studentId])
                                ->contain(['EvidenceSections.Sections.Courses', "EvidenceContexts.Contexts",
                                  'EvidenceImpacts'=> function($q){
                                    return $q->contain(['EvidenceImpactScores.ScaleValues','Impacts']);
                                 },
                                'EvidenceImpacts.Impacts', 'EvidenceContexts.Contexts','EvidenceContents.ContentValues'])
                                ->order(['created' => 'DESC'])
                                ->all();

    $evidences = $evidences->map(function ($value, $key) {
      $impact_names = (new Collection ($value->evidence_impacts))->extract('impact.name')->toArray();
      $course_names = (new Collection ($value->evidence_sections))->extract('section.course.name')->toArray();
      $context_names = (new Collection ($value->evidence_contexts))->extract('context.name')->toArray();
      $content_names = (new Collection ($value->evidence_contents))->extract('content_value.text')->toArray();
      $submitted_date[] = $value->created->i18nFormat('MM/dd/yyyy');
      // pr($submitted_date);die;

      $value->impact_and_course_names = array_merge($impact_names, $course_names);
      $value->impact_and_course_names = array_merge($value->impact_and_course_names, $context_names);
      $value->impact_and_course_names = array_merge($value->impact_and_course_names, $content_names);
      $value->impact_and_course_names = array_map('strtolower', $value->impact_and_course_names);
      $value->impact_and_course_names = array_merge($value->impact_and_course_names, $submitted_date);
      // pr($value->impact_and_course_names);die;
      return $value;
    });

    $success = true;

    $this->set('data',$evidences);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']); 
  }

  public function view($evidenceId, $studentId = null){

    if(!$this->request->is('get')){
      throw new MethodNotAllowedException("Method not allowed", 1);
    }
    if(!$evidenceId){
      throw new BadRequestException("Missing Evidence Id");
    }

    if($studentId == null){
      $studentId = $this->Auth->user('id');
    }


    $this->loadModel('Evidences');
    $evidence = $this->Evidences->findById($evidenceId)
                                ->where(['student_id' => $studentId])
                                ->contain(['EvidenceSections.Sections.Courses',
                                  'EvidenceImpacts'=> function($q){
                                    return $q->contain(['EvidenceImpactScores.ScaleValues','Impacts']);
                                 },
                                'EvidenceImpacts.Impacts', 'EvidenceContexts.Contexts','EvidenceContents.ContentValues'])
                                ->first();
    // pr($evidence); die;

    $success = true;

    $this->set('data',$evidence);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']); 
  }

  public function add() {

    if(!$this->request->is(['post'])){
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->getData();

    if(!isset($data['student_id']) || !$data['student_id']){
      throw new BadRequestException("Missing Student id");
    }
    if(!isset($data['title']) || !$data['title']){
      throw new BadRequestException("Missing Title");
    }

    $this->loadModel('Evidences');
    $evidence = $this->Evidences->newEntity();
    $evidence = $this->Evidences->patchEntity($evidence, $data, ['associated' => ['EvidenceSections','EvidenceContexts', 'EvidenceContents']]);
      
    if (!$this->Evidences->save($evidence, ['associated' => ['EvidenceSections','EvidenceContexts', 'EvidenceContents']])) {
      throw new Exception("Evidences could not be saved.");
    }

    $success = true;

    $this->set('data',$evidence);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']);
  }

  public function edit($id = null) {

    if (!$this->request->is(['patch', 'post', 'put'])) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    $data = $this->request->getData();

    if(!isset($data['student_id']) || !$data['student_id']){
      throw new BadRequestException("Missing Student id");
    }
    if(!isset($data['title']) || !$data['title']){
      throw new BadRequestException("Missing Title");
    }


    $this->loadModel('Evidences');
    $evidence = $this->Evidences->get($id, [
          'contain' => ['EvidenceSections','EvidenceContexts']
    ]);

    $evidence = $this->Evidences->patchEntity($evidence, $data, ['associated' => ['EvidenceSections','EvidenceContexts']]);
      
    if (!$this->Evidences->save($evidence, ['associated' => ['EvidenceSections','EvidenceContexts']])) {   
      Log::write('error',$evidence);
      throw new Exception("Evidences could not be saved.");
    }

    $success = true;

    $this->set('data',$evidence);
    $this->set('status',$success);
    $this->set('_serialize', ['status','data']);
  }

  public function delete($id = null){
  
    if (!$this->request->is(['delete'])) {
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }

    if(!$id){
      throw new BadRequestException("Missing Evidence Id");
    }

    $this->loadModel('Evidences');
    $evidence = $this->Evidences->get($id);
    if (!$this->Evidences->delete($evidence)) {
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

  public function addImpact($evidenceId){

      if(!$this->request->is('post')){
          throw new MethodNotAllowedException("Request is not post");
      }

      if(!$evidenceId){
          throw new BadRequestException("Missing Evidence Id");
      } 

      $data = $this->request->getData();
      if(!isset($data['impact_id']) && empty($data['impact_id'])){
          throw new BadRequestException('Missing impact id');
      }
      $evidenceImpactData = [
                              'evidence_id' => $evidenceId,
                              'impact_id' => $data['impact_id']
                          ];

      $this->loadModel('EvidenceImpacts');
      $evidenceImpact = $this->EvidenceImpacts->newEntity();
      $evidenceImpact = $this->EvidenceImpacts->patchEntity($evidenceImpact, $evidenceImpactData);

      if(!$this->EvidenceImpacts->save($evidenceImpact)){
          throw new InternalErrorException('Something went wrong');
      }

      $response = array();
      $response['status'] = true;
      $response['data'] = $this->EvidenceImpacts->findById($evidenceImpact->id)->contain(['Impacts.ImpactCategories'])->first();

      $this->set('response', $response);
      $this->set('_serialize', ['response']);
  }

    public function indexImpacts($evidenceId){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request is not get");
            
        }

        if(!$evidenceId){
            throw new BadRequestException("Missing Evidence Id");
            
        } 

        $this->loadModel('EvidenceImpacts');
        $evidenceImpacts = $this->EvidenceImpacts->find()->where(['evidence_id' => $evidenceId])
                                                   ->contain(['Impacts.ImpactCategories'])
                                                   ->all()
                                                   ->toArray();

        $response = array();
        $response['status'] = true;
        $response['data'] = $evidenceImpacts;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

    public function deleteImpact($impactId, $evidenceId){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        if(!$impactId){
            throw new BadRequestException("Missing field impact_id", 1);
        }

        if(!$evidenceId){
            throw new BadRequestException("Missing Unit Id");
            
        }

        $this->loadModel('EvidenceImpacts');
        $evidenceImpact = $this->EvidenceImpacts->find()->where(['evidence_id' => $evidenceId,
                                                          'impact_id' => $impactId
                                                    ])
                                                 ->first();
        if(!$evidenceImpact){
            throw new BadRequestException('Record Not Found');
        }
        $response = array(); 
        if ($this->EvidenceImpacts->delete($evidenceImpact)) {
            $response['status'] = true;
            $response['data'] = $evidenceImpact->id; 
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

    public function getDigitalStrategies($contentCategoryKey) {
       if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Method not allowed"); 
        }
        if(!$contentCategoryKey){
            throw new BadRequestException("Missing content category key");
        }
        $this->loadModel('ContentCategories');

        $digitalStrategies = $this->ContentCategories->find()
                                                     ->where(['type' => $contentCategoryKey])
                                                      ->contain(['ContentValues'])
                                                      ->first();

        if(!$digitalStrategies || empty($digitalStrategies)) {
          throw new NotFoundException("Digital strategies are not set.");
        }



        $digitalStrategies->content_values = (new Collection ($digitalStrategies->content_values))->map(function($value,$key){
          if($value->parent_id == NULL){
                                        $value->is_selectable = true;
                                         }
                                         return $value;
                                       })
                                        ->nest('id', 'parent_id')
                                        ->toArray();
      

        $response = array();
        $response['status'] = true;
        $response['data'] = $digitalStrategies;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }
}
