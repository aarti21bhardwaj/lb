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
use Cake\I18n\Date;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class AssessmentsController extends ApiController
{
    public function index(){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(isset($this->request->query['assessment_type_id']) && $this->request->query['assessment_type_id']) {
      $assessmentTypeId = $this->request->query['assessment_type_id'];
      }
      $unitId = $this->request->getParam('unit_id');
      if(!$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!$unitId){
        throw new BadRequestException("Missing Unit Id");
      }
      $publishAssessmentIds = $this->Assessments->Evaluations->find()->all()->extract('assessment_id')->toArray();
      $publishAssessmentIds = array_unique($publishAssessmentIds);
      $assessments = $this->Assessments->findByUnitId($unitId)
                                       ->contain(['AssessmentStandards.Standards', 'AssessmentImpacts.Impacts','AssessmentContents']);

      if(isset($assessmentTypeId)){
        $assessments = $assessments->where(['assessment_type_id'=>$assessmentTypeId]);
      }
      $assessments = $assessments->map(function($value, $key) use($publishAssessmentIds){
                                          if(in_array($value->id, $publishAssessmentIds)){
                                            $value->isPublished = true;
                                          }else{
                                            $value->isPublished = false;
                                          }
                                          return $value;
                                       })->toArray(); 	
      $success = true;

      $this->set('data',$assessments);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);

    }

     public function view($id = null){
      if(!$this->request->isGet()){
        throw new MethodNotAllowedException();
      }
      if(!$id){
        throw new BadRequestException("Missing Id");
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!$unitId){
        throw new BadRequestException("Missing Unit Id");
      }
      $assessments = $this->Assessments->findById($id)->contain(['AssessmentStandards.Standards', 'AssessmentImpacts.Impacts'])->all();
        
      $success = true;

      $this->set('data',$assessments);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }


    public function add(){

      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!$unitId){
        throw new BadRequestException("Missing Unit Id");
      }
      $data = $this->request->data;
      // pr($data); die;
      if(!isset($data['assessment_type_id']) || !$data['assessment_type_id']){
        throw new BadRequestException("Missing Assessment Type Id");
      }
      if(!isset($data['name']) || !$data['name']){
        throw new BadRequestException("Missing Name");
      }
      if((isset($data['start_date']) && !empty($data['start_date'])) && (isset($data['end_date']) && !empty($data['end_date']))){
          $data['start_date'] = new Date($data['start_date']);
          $data['end_date'] = new Date($data['end_date']);
      }
      // if($data['assessment_subtype_id'] != null) {

      // }
      $data['unit_id'] = $unitId;
      
      $assessments = $this->Assessments->newEntity();
      $assessments = $this->Assessments->patchEntity($assessments,$data);
      // pr($assessments); die;

      if (!$this->Assessments->save($assessments)) { 
        throw new Exception("Assessment could not be saved.");
      }
      $success = true;
      $assessments = $this->Assessments->findById($assessments->id)->contain(['AssessmentStandards.Standards', 'AssessmentImpacts.Impacts','AssessmentContents'])->first();
      $this->set('data',$assessments);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function edit($id = null){
      if (!$this->request->is(['patch', 'post', 'put'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!$unitId){
        throw new BadRequestException("Missing Unit Id");
      }
      $assessments = $this->Assessments->get($id, [
            'contain' => []
      ]);
      $data = $this->request->data;
      // pr($data);die;
      if((isset($data['start_date']) && !empty($data['start_date'])) && (isset($data['end_date']) && !empty($data['end_date']))){
          $data['start_date'] = new Date($data['start_date']);
          $data['end_date'] = new Date($data['end_date']);
      }
        // pr('here2');die;
      $assessments = $this->Assessments->patchEntity($assessments,$data);
      
      if (!$this->Assessments->save($assessments)) {  
        throw new Exception("Assessments could not be saved.");
      }

      $success = true;

      $this->set('data',$assessments);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function delete($id = null){
      if (!$this->request->is(['delete'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!$unitId){
        throw new BadRequestException("Missing Unit Id");
      }
      $assessments = $this->Assessments->get($id);
      if (!$this->Assessments->delete($assessments)) {
          throw new Exception("Resource could not be deleted.");
      } 
        
      $success = true;
      
      $this->set(compact('success'));
      $this->set('_serialize', ['success']);
    }
}
