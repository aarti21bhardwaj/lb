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
 * UnitStrands Controller
 *
 * @property \App\Model\Table\UnitStrandsTable $UnitStrands
 *
 * @method \App\Model\Entity\UnitStrand[] paginate($object = null, array $settings = [])
 */
class UnitStrandsController extends ApiController
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

      $reqData = $this->request->getData();

      $strandIds = $this->UnitStrands->Strands->find()->where(['learning_area_id' => $reqData['learning_area_id']])
                                                          	->all()
                                                          	->extract('id')
                                                          	->toArray();
      
      $data = [];
      foreach ($strandIds as  $strand_id) {
           $data[] = [
                    'unit_id' => $unitId,
                    'grade_id' => $reqData['grade_id'],
                    'strand_id' => $strand_id
                   ];
       }

       $unitStrands = $this->UnitStrands->newEntities($data);
       if(!$this->UnitStrands->saveMany($unitStrands)){
            throw new InternalErrorException('Something wrong in saving entity');
       } 

       $response = array();
       $response['status'] = true;
       $response['data']['message'] = "Saved Successfully";

       $this->set('response', $response);
       $this->set('_serialize', ['response']); 

    }
    

}
