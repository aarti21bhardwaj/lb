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
 * UnitStandards Controller
 *
 * @property \App\Model\Table\UnitStandardsTable $UnitStandards
 *
 * @method \App\Model\Entity\UnitStandard[] paginate($object = null, array $settings = [])
 */
class UnitStandardsController extends ApiController
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
            throw new BadRequestException("Missing Unit Id");
            
        } 

        $data = $this->request->getData();
        if(!isset($data['standard_id']) && empty($data['standard_id'])){
            throw new BadRequestException('Missing standard id');
        }
        $unitStandardData = [
                                'unit_id' => $unitId,
                                'standard_id' => $data['standard_id']
                            ];
        $unitStandard = $this->UnitStandards->newEntity();
        $unitStandard = $this->UnitStandards->patchEntity($unitStandard, $unitStandardData);
  
        if(!$this->UnitStandards->save($unitStandard)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $this->UnitStandards->findById($unitStandard->id)->contain(['Standards'])->first();

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

    public function index(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request is not get");
            
        }
        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $unitId = $this->request->getParam('unit_id');

        if(!$unitId){
            throw new BadRequestException("Missing Unit Id");
            
        } 

        $unitStandards = $this->UnitStandards->find()->where(['unit_id' => $unitId])
                                                     ->contain(['Standards'])
                                                     ->all()
                                                     ->toArray();

        $response = array();
        $response['status'] = true;
        $response['data'] = $unitStandards;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
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
            throw new BadRequestException("Missing Unit Id");
            
        }


        $unitStandard = $this->UnitStandards->find()->where(['unit_id' => $unitId,
                                                             'standard_id' => $standardId
                                                    ])
                                                    ->first();
        if(!$unitStandard){
            throw new BadRequestException('Record Not Found');
        }
        $response = array(); 
        if ($this->UnitStandards->delete($unitStandard)) {
            $response['status'] = true;
            $response['data'] = $unitStandard->id; 
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

}
