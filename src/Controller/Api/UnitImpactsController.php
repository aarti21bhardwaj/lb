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
 * UnitImpacts Controller
 *
 * @property \App\Model\Table\UnitImpactsTable $UnitImpacts
 *
 * @method \App\Model\Entity\UnitImpact[] paginate($object = null, array $settings = [])
 */
class UnitImpactsController extends ApiController
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
        if(!isset($data['impact_id']) && empty($data['impact_id'])){
            throw new BadRequestException('Missing impact id');
        }
        $unitImpactData = [
                                'unit_id' => $unitId,
                                'impact_id' => $data['impact_id']
                            ];
        $unitImpact = $this->UnitImpacts->newEntity();
        $unitImpact = $this->UnitImpacts->patchEntity($unitImpact, $unitImpactData);
  
        if(!$this->UnitImpacts->save($unitImpact)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $this->UnitImpacts->findById($unitImpact->id)->contain(['Impacts.ImpactCategories'])->first();

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

        $unitImpacts = $this->UnitImpacts->find()->where(['unit_id' => $unitId])
                                                   ->contain(['Impacts.ImpactCategories'])
                                                   ->all()
                                                   ->toArray();

        $response = array();
        $response['status'] = true;
        $response['data'] = $unitImpacts;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
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
            throw new BadRequestException("Missing Unit Id");
            
        }


         $unitImpact = $this->UnitImpacts->find()->where(['unit_id' => $unitId,
                                                          'impact_id' => $impactId
                                                    ])
                                                 ->first();
        if(!$unitImpact){
            throw new BadRequestException('Record Not Found');
        }
        $response = array(); 
        if ($this->UnitImpacts->delete($unitImpact)) {
            $response['status'] = true;
            $response['data'] = $unitImpact->id; 
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

}
