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
 * AssessmentStrands Controller
 *
 * @property \App\Model\Table\AssessmentStrandsTable $AssessmentStrands
 *
 * @method \App\Model\Entity\AssessmentStrands[] paginate($object = null, array $settings = [])
 */
class AssessmentStrandsController extends ApiController
{
    public function add(){
        if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
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

      $reqData = $this->request->getData();

      $strandIds = $this->AssessmentStrands->Strands->find()->where(['learning_area_id' => $reqData['learning_area_id']])
                                                          ->all()
                                                          ->extract('id')
                                                          ->toArray();
      
      $data = [];
      foreach ($strandIds as  $strand_id) {
           $data[] = [
                    'assessment_id' => $assessmentId,
                    'grade_id' => $reqData['grade_id'],
                    'strand_id' => $strand_id
                   ];
       }

       $assessmentStrands = $this->AssessmentStrands->newEntities($data);
       if(!$this->AssessmentStrands->saveMany($assessmentStrands)){
            throw new InternalErrorException('Something wrong in saving entity');
       } 

       $response = array();
       $response['status'] = true;
       $response['data']['message'] = "Saved Successfully";

       $this->set('response', $response);
       $this->set('_serialize', ['response']); 

    }

}
