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

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class AssessmentReflectionsController extends ApiController
{
    public function index(){
      if(!$this->request->isGet()){
        throw new MethodNotAllowedException();
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      $assessmentId = $this->request->getParam('assessment_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      if(!isset($assessmentId) || !$assessmentId){
        throw new BadRequestException("Missing Assessment idId");
      }
      $this->loadModel('UnitReflections');
      $resources = $this->UnitReflections->find()->where(['object_identifier IS NOT NULL','object_name IS NOT NULL'])->all();
        
      $success = true;

      $this->set('data',$resources);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function add(){
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      $assessmentId = $this->request->getParam('assessment_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      if(!isset($assessmentId) || !$assessmentId){
        throw new BadRequestException("Missing Assessment idId");
      }
      $data = $this->request->data;
      if(!isset($data['description']) || !$data['description']){
        throw new BadRequestException("Missing Description");
      }
      if(!isset($data['reflection_category_id']) || !$data['reflection_category_id']){
        throw new BadRequestException("Missing Reflection category id");
      }
      $this->loadModel('Assessments');
      $assessmentData = $this->Assessments->findById($assessmentId)->first();
      if(!$assessmentData){
        throw new BadRequestException("No record found in Assessment Table with the given Assessment Id");
      }
      $data['unit_id'] = $unitId;
      $data['object_identifier'] = $assessmentId;
      $data['object_name'] = $assessmentData->name;
      $this->loadModel('UnitReflections');
      $reflections = $this->UnitReflections->newEntity();
      $reflections = $this->UnitReflections->patchEntity($reflections,$data);
      if (!$this->UnitReflections->save($reflections)) { 
        throw new Exception("Assessment Reflections could not be saved.");
      }
      $success = true;

      $this->set('data',$reflections);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function edit($id = null){
      if (!$this->request->is(['patch', 'post', 'put'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $this->loadModel('UnitReflections');
      $resources = $this->UnitReflections->get($id, [
            'contain' => []
      ]);
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      $assessmentId = $this->request->getParam('assessment_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      if(!isset($assessmentId) || !$assessmentId){
        throw new BadRequestException("Missing Assessment idId");
      }
      $this->loadModel('Assessments');
      $assessmentData = $this->Assessments->findById($assessmentId)->first();
      if(!$assessmentData){
        throw new BadRequestException("No record found in Assessment Table with the given Assessment Id");
      }
      $data = $this->request->data;
      $data['object_identifier'] = $assessmentId;
      $data['object_name'] = $assessmentData->name;
      $resources = $this->UnitReflections->patchEntity($resources,$data);
      
      if (!$this->UnitReflections->save($resources)) {  
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
      $assessmentId = $this->request->getParam('assessment_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      if(!isset($assessmentId) || !$assessmentId){
        throw new BadRequestException("Missing Assessment idId");
      }
      $this->loadModel('UnitReflections');
      $resources = $this->UnitReflections->get($id);
      if (!$this->UnitReflections->delete($resources)) {
          throw new Exception("Resource could not be deleted.");
      } 
        
      $success = true;
      
      $this->set(compact('success'));
      $this->set('_serialize', ['success']);
    }
}
