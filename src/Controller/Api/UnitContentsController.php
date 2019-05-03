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
class UnitContentsController extends ApiController
{

    //Adds Unit Content Values
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

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        } 

        $data = $this->request->getData();
        if(!isset($data['content_value_id']) && empty($data['content_value_id'])){
            throw new BadRequestException('Missing content value id', 1);
        }
        $unitContentData = [
                                'unit_id' => $unitId,
                                'content_category_id' => $contentCategoryId,
                                'content_value_id' => $data['content_value_id']
                            ];
        $unitContent = $this->UnitContents->newEntity();
        $unitContent = $this->UnitContents->patchEntity($unitContent, $unitContentData);
  
        if(!$this->UnitContents->save($unitContent)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $unitContent->id;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

    //Lists Unit Content Values for a category and a given unit
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
            throw new BadRequestException("Missing Unit Id");
            
        }

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        }

        // $unitContents = $this->UnitContents->find()
        //                                    ->where(['unit_id' => $unitId, 'content_category_id' => $contentCategoryId])
        //                                    ->contain(['ContentCategories.ContentValues'])
        //                                    ->all()
        //                                    ->toArray();

        $unitContents = $this->UnitContents->ContentCategories->findById($contentCategoryId)
                                           ->contain(['UnitContents' => function($q) use($unitId){
                                              return $q->where(['unit_id' => $unitId]);
                                           }, 'UnitContents.ContentValues','UnitSpecificContents' =>function($q) use($unitId){
                                             return $q->where(['unit_id' => $unitId]);
                                           }])
                                           ->first();
        // pr($unitContents); die;

        $response = array();
        $response['status'] = true;
        $response['data'] = $unitContents;
        $this->set('response', $response);
        $this->set('_serialize', ['response']);  
    }

    public function delete($contentValueId){
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Method not allowed");
            
        }

        if(!$contentValueId){
            throw new BadRequestException("Missing field content_value_id", 1);
            
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

        $unitContent = $this->UnitContents->find()
                                          ->where(['unit_id' => $unitId, 'content_category_id' => $contentCategoryId, 'content_value_id' => $contentValueId])
                                          ->first();

        if($this->UnitContents->delete($unitContent)){
            $response= array();
            $response['status'] = true;
            $response['data'] = $unitContent->id;
        }else{
            throw new InternalErrorException("Something went wrong in delete", 1);
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }

    public function addContent(){
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

        $contentCategoryId = $this->request->getParam('content_category_id');

        if(!$contentCategoryId){
            throw new BadRequestException("Missing Content Category id", 1);
            
        } 

        $data = $this->request->getData();
        if(!isset($data['text']) && empty($data['text'])){
            throw new BadRequestException('Missing field text', 1);
        }
         
        $unitSpecificContentData = [
                                     'unit_id' => $unitId,
                                     'content_category_id' => $contentCategoryId,
                                     'text' => $data['text']
                                   ];

        // pr($unitSpecificContentData); die;
        $this->loadModel('UnitSpecificContents');

        $unitSpecificContent = $this->UnitSpecificContents->newEntity();
        $unitSpecificContent = $this->UnitSpecificContents->patchEntity($unitSpecificContent, $unitSpecificContentData);

        if(!$this->UnitSpecificContents->save($unitSpecificContent)){
            throw new InternalErrorException("Something went wrong", 1);
            
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = $unitSpecificContent;

        $this->set('response', $response);
        $this->set('_serialize', ['response']);

    }


    public function removeContent($id =  null){
       if(!$this->request->is(['post', 'delete'])){
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
        
        $this->loadModel('UnitSpecificContents');
        $unitSpecificContent = $this->UnitSpecificContents->findById($id)->first();
        if(!$unitSpecificContent){
            throw new BadRequestException("Entity not found", 1);
        }

        if($this->UnitSpecificContents->delete($unitSpecificContent)){
            $response= array();
            $response['status'] = true;
            $response['data']['id'] = $unitSpecificContent->id;
        }else{
            throw new InternalErrorException("Something went wrong in delete", 1);
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']); 
    }

    public function editContent($id = null){
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

        $this->loadModel('UnitSpecificContents');
        $unitSpecificContent = $this->UnitSpecificContents->findById($id)->first();
        if(!$unitSpecificContent){
            throw new BadRequestException("Entity not found", 1);
        }

        $data = $this->request->getData();
        $unitSpecificContent = $this->UnitSpecificContents->patchEntity($unitSpecificContent, $data);

        // pr($unitSpecificContent); die;
        if($this->UnitSpecificContents->save($unitSpecificContent)){
            $response= array();
            $response['status'] = true;
            $response['data'] = $unitSpecificContent;
        }else{
            throw new InternalErrorException("Something went wrong in update", 1);
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']); 
    }

    public function indexSpecificContent(){
      if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Method not allowed");
            
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

        $this->loadModel('UnitSpecificContents');
        $unitContents = $this->UnitSpecificContents->ContentCategories->find()
                                           ->where(['id' => $contentCategoryId])
                                           ->contain(['UnitSpecificContents' =>function($q) use($unitId){
                                             return $q->where(['unit_id' => $unitId]);
                                           }])
                                           ->first();
                                           // ->toArray();

        // $response = array();
        $response['status'] = true;
        $response['data'] = $unitContents;
        $this->set('response', $response);
        $this->set('_serialize', ['response']);  
    }
    // public function viewContent(){
    //   if(!$this->request->is('delete')){
    //         throw new MethodNotAllowedException("Method not allowed");
            
    //     }
        
    //    if(!$id){
    //      throw new BadRequestException("Missing id", 1);
         
    //    }

    //    $courseId = $this->request->getParam('course_id');

    //     if(!$courseId){
    //         throw new BadRequestException("Missing Course Id");
            
    //     }

    //     $unitId = $this->request->getParam('unit_id');

    //     if(!$unitId){
    //         throw new BadRequestException("Missing Unit Id");
            
    //     }

    //     $contentCategoryId = $this->request->getParam('content_category_id');

    //     if(!$contentCategoryId){
    //         throw new BadRequestException("Missing Content Category id", 1);
            
    //     }  
    // }

}
