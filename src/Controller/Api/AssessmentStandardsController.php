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
class AssessmentStandardsController extends ApiController
{
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
            throw new BadRequestException("Missing Assessment Id");
            
        } 

        $data = $this->request->getData();
        if(!isset($data['standard_id']) && empty($data['standard_id'])){
            throw new BadRequestException('Missing standard id');
        }
        $assessmentStandardData = [
                                        'assessment_id' => $assessmentId,
                                        'standard_id' => $data['standard_id']
                                 ];
        $assessmentStandard = $this->AssessmentStandards->newEntity();
        $assessmentStandard = $this->AssessmentStandards->patchEntity($assessmentStandard, $assessmentStandardData);
  
        if(!$this->AssessmentStandards->save($assessmentStandard)){
            throw new InternalErrorException('Something went wrong in adding standards');
        }

        $success = true;

        $this->set('data',$assessmentStandard);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }


    public function delete($standardId){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        if(!$standardId){
            throw new BadRequestException("Missing field standard_id", 1);
            
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


        $assessmentStandard = $this->AssessmentStandards->find()->where(['assessment_id' => $assessmentId,
                                                             'standard_id' => $standardId
                                                    ])
                                                    ->first();
        if(!$assessmentStandard){
            throw new BadRequestException('Record Not Found');
        }
        $response = array(); 
        if ($this->AssessmentStandards->delete($assessmentStandard)) {
            $response['status'] = true;
            $response['data'] = $assessmentStandard; 
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

}
