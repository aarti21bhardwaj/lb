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

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class ReflectionCategoriesController extends ApiController
{
    public function index(){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}


      $reflectionCategories = $this->ReflectionCategories->find()->all();
       	
      $success = true;

      $this->set('data',$reflectionCategories);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function view($id){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}

   		if(!$id){
   			throw new BadRequestException();
   		}

      $reflectionCategory = $this->ReflectionCategories->get($id);
      
      $success = true;

      $this->set('data',$reflectionCategory);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }
}
