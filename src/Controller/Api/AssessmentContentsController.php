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

/**
 * AssessmentStandards Controller
 *
 * @property \App\Model\Table\AssessmentStandardsTable $AssessmentStandards
 *
 * @method \App\Model\Entity\AssessmentStandard[] paginate($object = null, array $settings = [])
 */
class AssessmentContentsController extends ApiController
{
    //Adds Assessment Content Values
    public function add(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request is not post");
            
        }
        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }


        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing unit Id");
            
        }


        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing assessment Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        }

        $data = $this->request->getData();

        if(!$data){
           throw new BadRequestException("Request Data is empty"); 
        }
      
        if(!isset($data['content_value_id'])){
            $data['content_value_id'] = NULL;
            if(!isset($data['unit_specific_content_id']) && empty($data['unit_specific_content_id'])){
                throw new BadRequestException("Missing unit content category id");
            }
        }

        if(!isset($data['unit_specific_content_id'])){
            $data['unit_specific_content_id']= NULL;
            if(!isset($data['content_value_id']) && empty($data['content_value_id'])){
                throw new BadRequestException("Missing  content value id");
            }
        }

        $assessmentContentData = [
                                'assessment_id' => $assessmentId,
                                'content_category_id' => $contentCategoryId,
                                'content_value_id' => $data['content_value_id'],
                                'unit_specific_content_id' => $data['unit_specific_content_id'],
                            ];
        $assessmentContent = $this->AssessmentContents->newEntity();
        $assessmentContent = $this->AssessmentContents->patchEntity($assessmentContent, $assessmentContentData);
  
        if(!$this->AssessmentContents->save($assessmentContent)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $assessmentContent;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

    //Lists Assessment Content Values for a category and a given Assessment
    public function index(){
      if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Method not allowed");
            
      }

        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing unit Id");
            
        }

        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing Assessment Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        }

        $assessmentContents = $this->AssessmentContents->ContentCategories
                                                       ->findById($contentCategoryId)
                                                       ->contain(['AssessmentContents.ContentValues', 'AssessmentSpecificContents'])
                                                       ->first();

        $response = array();
        $response['status'] = true;
        $response['data'] = $assessmentContents;
        $this->set('response', $response);
        $this->set('_serialize', ['response']);  
    }

    public function removeAssessmentContent(){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing unit Id");
            
        }

        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing Assessment Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        }

        if(empty($this->request->query['content_value_id']) && empty($this->request->query['unit_specific_content_id'])){
            throw new BadRequestException("Missing content_value_id and unit_specific_content_id", 1);
        }
        
        
        if(empty($this->request->query['content_value_id'])){
            if(isset($this->request->query['unit_specific_content_id']) && empty($this->request->query['unit_specific_content_id'])){
                throw new BadRequestException("Missing unit_specific_content_id", 1);
                
            }

            $unitSpecificContentId = $this->request->query['unit_specific_content_id'];
            $assessmentContent = $this->AssessmentContents->find()
                                          ->where(['assessment_id' => $assessmentId, 'content_category_id' => $contentCategoryId, 'unit_specific_content_id' => $unitSpecificContentId])
                                          ->first();

        }

        if(empty($this->request->query['unit_specific_content_id'])){
            if(isset($this->request->query['content_value_id']) && empty($this->request->query['content_value_id'])){
                throw new BadRequestException("Missing content_value_id", 1);
            }

            $contentValueId = $this->request->query['content_value_id'];
            $assessmentContent = $this->AssessmentContents->find()
                                          ->where(['assessment_id' => $assessmentId, 'content_category_id' => $contentCategoryId, 'content_value_id' => $contentValueId])
                                          ->first();
        }

        if(!$assessmentContent){
            throw new BadRequestException("Record Not Found", 1);
            
        }

        if($this->AssessmentContents->delete($assessmentContent)){
            $response= array();
            $response['status'] = true;
            $response['data'] = $assessmentContent;
        }else{
            throw new InternalErrorException("Something went wrong in delete", 1);
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

    public function addSpecificContent(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request is not post");
            
        }
        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing unit Id");
            
        }

        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing assessment Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        } 

        $data = $this->request->getData();

        if(!isset($data['description']) && empty($data['description'])){
            throw new BadRequestException('Missing field text', 1);
        }

        $assessmentSpecificContentData = [
                                'assessment_id' => $assessmentId,
                                'content_category_id' => $contentCategoryId,
                                'description' => $data['description']
                            ];

        $this->loadModel('AssessmentSpecificContents');

        $assessmentSpecificContent = $this->AssessmentSpecificContents->newEntity();
        $assessmentSpecificContent = $this->AssessmentSpecificContents->patchEntity($assessmentSpecificContent, $assessmentSpecificContentData);

        if(!$this->AssessmentSpecificContents->save($assessmentSpecificContent)){
            throw new InternalErrorException("Something went wrong in saving data", 1);
        } 

        $respone = array();
        $response['status'] = true;
        $response['data'] = $assessmentSpecificContent;

        $this->set('response', $response);
        $this->set('_serialize', ['response']); 
    }

    public function removeSpecificContent($id = null){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Request is not post");
            
        }

        if(!$id){
            throw new BadRequestException("Missing assessment specfic content id");
        }
        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing unit Id");
            
        }

        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing assessment Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        } 

        $this->loadModel('AssessmentSpecificContents');
        $assessmentSpecificContent = $this->AssessmentSpecificContents->findById($id)->first();
        
        if(!$this->AssessmentSpecificContents->delete($assessmentSpecificContent)){
            throw new InternalErrorException("Something went wrong in saving data", 1);
        } 

        $respone = array();
        $response['status'] = true;
        $response['data'] = $assessmentSpecificContent;

        $this->set('response', $response);
        $this->set('_serialize', ['response']); 
    }

    public function indexSpecificContent(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request is not post");
            
        }
        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing unit Id");
            
        }

        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing assessment Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        }

        $this->loadModel('AssessmentSpecificContents');
        $assessmentSpecificContent = $this->AssessmentSpecificContents->findByAssessmentId($assessmentId)
                                                                      ->where(['content_category_id' => $contentCategoryId])
                                                                      ->first();

        
        $response = array();
        $response['status'] = true;
        $response['data'] = $assessmentSpecificContent;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

    public function editSpecificContent($id = null){
       if(!$this->request->is('put')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }
        
       if(!$id){
         throw new BadRequestException("Missing id", 1);
         
       }

       $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing Unit Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        }

        $assessmentId = $this->request->getParam('assessment_id');

        if(!$assessmentId){
            throw new BadRequestException("Missing assessment Id");
            
        }

        $this->loadModel('AssessmentSpecificContents');
        $assessmentSpecificContent = $this->AssessmentSpecificContents->findById($id)->first();
        if(!$assessmentSpecificContent){
            throw new BadRequestException("Entity not found", 1);
        }

        $assessmentSpecificContent = $this->AssessmentSpecificContents->patchEntity($assessmentSpecificContent, $this->request->getData());

        if($this->AssessmentSpecificContents->save($assessmentSpecificContent)){
            $response= array();
            $response['status'] = true;
            $response['data'] = $assessmentSpecificContent;
        }else{
            throw new InternalErrorException("Something went wrong in update", 1);
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']); 
    }

}
