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
use Cake\Core\Configure;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class UnitResourcesController extends ApiController
{
    public function index(){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');

      $object_name = $this->request->getQuery('object_name');
      $object_id = $this->request->getQuery('object_identifier');

      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }

      if($object_name && $object_id) {
        $resources = $this->UnitResources->findByUnitId($unitId)
        ->contain(['Assessments.AssessmentTypes'])
        ->where(['object_name' => $object_name, 'object_identifier' => $object_id])
                                        ->groupBy('assessment.assessment_type.name')

        // /->all()        
                                        ->toArray();
      } else {
        $resources = $this->UnitResources->findByUnitId($unitId)
                                         ->contain(['Assessments.AssessmentTypes','Units'])
                                         ->groupBy('assessment.assessment_type.name')
                                         ->toArray();

        $resources['General'] = $this->UnitResources->findByUnitId($unitId)
                                          ->contain(['Units'])
                                          ->where(['object_name' => 'unit'])
                                          ->toArray();
      }


//      pr($resources); die;
      $sortOrder = [
        'Learning Experiences' => 2,
        'Performance Tasks' => 1,
        'Formative Assessments' =>2,
        'Summative Assessments' => 1,
        'Learning Activities' => 3,
        'General' => 0
      ];

      $final = [];

      asort($sortOrder, SORT_NUMERIC);

      foreach ($sortOrder as $key => $value) {
        if(isset($resources[$key])) {
          $final[$key] = $resources[$key];
        }
      }
      $resources = $final;

      unset($final);

      $success = true;

      $this->set('data',$resources);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function view($id){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}

      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
   		if(!$id){
   			throw new BadRequestException();
   		}

      $resource = $this->UnitResources->get($id);
      
      $success = true;

      $this->set('data',$resource);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function add(){
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      $data = $this->request->data;
      if($data['object_name'] == NULL) {
        $data['object_name'] = 'unit';
        $data['object_identifier'] = $unitId; 
      }
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      if(!isset($data['name']) || !$data['name']){
        throw new MethodNotAllowedException(__('MANDATORY_FIELD_MISSING Name'));
      }
      if(!isset($data['resource_type']) || !$data['resource_type']){
        throw new BadRequestException("Missing Resource Type");
      }
      $data['unit_id'] = $unitId;
      $resources = $this->UnitResources->newEntity();
      $resources = $this->UnitResources->patchEntity($resources,$data);
      
      if (!$this->UnitResources->save($resources)) { 
        throw new Exception("Unit Resources could not be saved.");
      }
      $success = true;

      $this->set('data',$resources);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function edit($id = null){
      if (!$this->request->is(['patch', 'post', 'put'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $resources = $this->UnitResources->get($id, [
            'contain' => []
      ]);

      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      $data = $this->request->data;

      if($data['object_name'] == NULL) {
        $data['object_name'] = $resources->object_name;
        $data['object_identifier'] = $resources->object_identifier; 
      }
      
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }

      $resources = $this->UnitResources->patchEntity($resources,$data);
      
      if (!$this->UnitResources->save($resources)) {  
        throw new Exception("Unit Resources could not be saved.");
      }

      $success = true;

      $this->set('data',$resources);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function delete($id = null){
      if (!$this->request->is(['delete'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      $resources = $this->UnitResources->get($id);

      if (!$this->UnitResources->delete($resources)) {
          throw new Exception("Resource could not be deleted.");
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
      $target_path = WWW_ROOT . Configure::read('ImageUpload.uploadPathForUnitResources');
      $file = $_FILES['fileKey']['name'];
      //sanitize filename
      $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
      $file = time().$file;
      $success = false;
      $data = [];
      if ($file != '') {
        if (move_uploaded_file($_FILES['fileKey']['tmp_name'], $target_path.DS.$file)) {
            
            $data = [
                      'file_path' => 'webroot'.DS.Configure::read('ImageUpload.uploadPathForUnitResources').DS,
                      'file_name' => $file
                    ];
                  
            $success = true;
        } 
      }
      
      $this->set('status',$success);
      $this->set('data',$data);
      $this->set('_serialize', ['status','data']);
    }
}
