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
use Cake\Log\Log;
use Cake\I18n\Time;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class ContentValuesController extends ApiController
{


    public function initialize()
    {
        parent::initialize();
    }

    public function contentsByContentCategory($contentCategoryId){
      if(!$contentCategoryId){
        throw new BadRequestException("Missing content_category_id", 1);
      }

      if(!$this->request->is('post')){
        throw new MethodNotAllowedException("Request method is not post", 1);
        
      }

      $contents = $this->ContentValues->find()->where(['content_category_id' => $contentCategoryId])
                                              ->all()
                                              ->toArray();

      $contents = (new Collection($contents))->nest('id','parent_id')->toArray();
      $response = array();
      if(!$contents){
          $response['status'] = false;
          $response['message'] = 'No contents till yet for this category';
      }else{
          $response['status'] = true;
          $response['data'] = $contents;
      }

      $this->set('response', $response);
      $this->set('_serialize', ['response']);
    }

    public function add(){
      if(!$this->request->is('post')){
        throw new MethodNotAllowedException("Request method is not post", 1);
      }

      $data = $this->request->getData();

      if(empty($data['text'])){
        throw new BadRequestException("Missing field text", 1);
      }
      if(empty($data['content_category_id'])){
        throw new BadRequestException("Missing field content_category_id", 1);
      }
      $contentValue = $this->ContentValues->newEntity();
      $contentValue = $this->ContentValues->patchEntity($contentValue, $data);
      if(!$this->ContentValues->save($contentValue)){
        throw new InternalErrorException("Internal Server Error", 1);
        
      }
      $response = array();
      $response['status'] = true;
      $response['data'] = $contentValue;

      $this->set(compact('response'));
      $this->set('_serialize', ['response']);
    }

    /**
     * View method
     *
     * @param string|null $id Content Value id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
       if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Bad Request');
        }

        $contentValue = $this->ContentValues->findById($id)->first();

        if(!$contentValue){
            throw new BadRequestException("Record not found");
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $contentValue;

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

     /**
     * Edit method
     *
     * @param string|null $id Content Value id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if(!$this->request->is('put')){
            throw new MethodNotAllowedException('Bad Request');
        }
        $data = $this->request->data; 

        if(empty($data)){
            throw new BadRequestException("Empty request data");
            
        }

        $contentValue = $this->ContentValues->find()->where(['id' => $id])->first();

        if(!$contentValue){
            throw new BadRequestException("Record not found");
        }

        $contentValue = $this->ContentValues->patchEntity($contentValue, $data);

        if(!$this->ContentValues->save($contentValue)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $contentValue;
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if(!$this->request->is(['post','delete'])){
            throw new MethodNotAllowedException('Resquest is not delete');
        }

        $contentValue = $this->ContentValues->find()->where(['id' => $id])->first();

        if(!$contentValue){
            throw new BadRequestException("Record not found");
        }


         if ($this->ContentValues->delete($contentValue)) {
            $response['status'] = true;
            $response['message'] = "Deleted Successfully";
        } else {
            throw new InternalErrorException('Something went wrong');
            
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
