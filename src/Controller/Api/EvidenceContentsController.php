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
class EvidenceContentsController extends ApiController
{
        /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request is not post');
            
        }
        $data = $this->request->getData();
        if(!isset($data['evidence_id']) && empty($data['evidence_id'])){
                throw new BadRequestException("Missing field evidence_id");
        }

        if(!isset($data['content_category_id']) && empty($data['content_category_id'])){
            throw new BadRequestException("Missing field content_category_id");
        }

        if(!isset($data['content_value_id']) && empty($data['content_value_id'])){
            throw new BadRequestException("Missing field content_value_id");
        }

        $evidenceContent = $this->EvidenceContents->newEntity();
        $evidenceContent = $this->EvidenceContents->patchEntity($evidenceContent, $data);

        // pr($evidenceContent); die;

        if(!$this->EvidenceContents->save($evidenceContent)){
            throw new InternalErrorException("Something went wrong in saving entity", 1);
            
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $evidenceContent;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

    public function removeEvidenceContent(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        $data = $this->request->getData();
        
        if(!isset($data['evidence_id']) && empty($data['evidence_id'])){
                throw new BadRequestException("Missing field evidence_id");
        }

        if(!isset($data['content_value_id']) && empty($data['content_value_id'])){
            throw new BadRequestException("Missing field content_value_id");
        }

        $evidenceContentData  = $this->EvidenceContents->find()
                                                       ->where(['evidence_id' => $data['evidence_id'], 'content_value_id' => $data['content_value_id']])
                                                       ->first();


        if(!$evidenceContentData){
            throw new BadRequestException('Record Not Found');
        }

        $response = array(); 
        if ($this->EvidenceContents->delete($evidenceContentData)) {
            $response['status'] = true;
            $response['data'] = [
                                    'evidence_content_id' => $evidenceContentData->id,
                                    'evidence_id' => $data['evidence_id'],
                                    'content_value_id' => $data['content_value_id']
                                ];
                                     
        } else {
            throw new InternalErrorException("Something went wrong");
            
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

    public function getEvidenceContents($evidenceId){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        $evidenceContent = $this->EvidenceContents->find()
                                                  ->where(['evidence_id' => $evidenceId])
                                                  ->toArray();

        $response = array();
        if(!$evidenceContent){
            $response['status'] = false;
            $response['data'] = 'No digital strategies set';
        }else{
            $response['status'] = true;
            $response['data'] = $evidenceContent;
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

}
