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
 * AssessmentImpacts Controller
 *
 * @property \App\Model\Table\AssessmentImpactsTable $AssessmentImpacts
 *
 * @method \App\Model\Entity\AssessmentImpact[] paginate($object = null, array $settings = [])
 */
class AssessmentImpactsController extends ApiController
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
            throw new BadRequestException("Missing assessment Id");
            
        } 

        $data = $this->request->getData();
        if(!isset($data['impact_id']) && empty($data['impact_id'])){
            throw new BadRequestException('Missing impact id');
        }
        $assessmentImpactData = [
                                    'assessment_id' => $assessmentId,
                                    'impact_id' => $data['impact_id']
                                ];
        $assessmentImpact = $this->AssessmentImpacts->newEntity();
        $assessmentImpact = $this->AssessmentImpacts->patchEntity($assessmentImpact, $assessmentImpactData);
  
        if(!$this->AssessmentImpacts->save($assessmentImpact)){
            // pr($assessmentImpact);die;
            throw new InternalErrorException('Something went wrong');
        }

        $success = true;

        $this->set('data',$assessmentImpact);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    public function delete($impactId){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        if(!$impactId){
            throw new BadRequestException("Missing field impact_id", 1);
            
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


         $assessmentImpact = $this->AssessmentImpacts->find()->where(['assessment_id' => $assessmentId,
                                                          'impact_id' => $impactId
                                                    ])
                                                 ->first();
        if(!$assessmentImpact){
            throw new BadRequestException('Record Not Found');
        }
        $response = array();
        // pr($assessmentImpact);die; 
        if ($this->AssessmentImpacts->delete($assessmentImpact)) {
            $response['status'] = true;
            $response['data'] = $assessmentImpact; 
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

}
