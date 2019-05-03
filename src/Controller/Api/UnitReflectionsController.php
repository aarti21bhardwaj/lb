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
class UnitReflectionsController extends ApiController
{
    public function index(){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');

      $object_name = $this->request->getQuery('object_name');
      $object_id = $this->request->getQuery('object_identifier');

      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }

      if($object_name && $object_id) {
        $reflections = $this->UnitReflections->findByUnitId($unitId)
                                            ->where(['object_name' => $object_name, 'object_identifier' => $object_id])
                                            ->contain(['Assessments.AssessmentTypes'])
                                            ->groupBy('assessment.assessment_type.name')
                                            ->toArray();        
      } else {
        $assessment_reflections = $this->UnitReflections->findByUnitId($unitId)
                                              ->contain(['Assessments.AssessmentTypes','Units'])
                                              ->groupBy('assessment.assessment_type.name')
                                              ->toArray();

        $unit_reflections['General'] = $this->UnitReflections->findByUnitId($unitId)
                                              ->contain(['Units'])
                                              ->where(['object_name' => 'unit'])
                                              ->toArray();
        $reflections = array_merge($assessment_reflections, $unit_reflections);

      }

      $userIds = [];
      foreach ($reflections as $key => $reflection) {
        foreach ($reflection as $key => $value) {
          $userIds[] = $value->created_by;
        }
      }
      // pr($userIds); die;
      $this->loadModel('Users');
      $users = $this->Users->find()->where(['id IN' => $userIds])->all()
                                                                 ->indexBy('id')
                                                                 ->toArray();

      $reflections = new Collection($reflections);
      $reflections = $reflections->map( function($v, $k) use ($users){ 
                        return (new Collection($v)) ->map(function($value, $key) use($users){
                            // pr($value->created_by);
                            if($users[$value->created_by]){
                              $createdName = str_split($users[$value->created_by]->first_name[0]);
                              $value->created_by = $users[$value->created_by]->last_name.', '.$createdName[0];
                            }
                            if($users[$value->modified_by]){

                            $modifiedName = str_split($users[$value->modified_by]->first_name[0]);
                            $value->modified_by = $users[$value->modified_by]->last_name.', '.$modifiedName[0];
                            }
                            $value->card_title = substr(strip_tags($value->description),0,30);
                            return $value;
                          })
                          ->toArray();
                        })
                      ->toArray();

      $success = true;
      $this->set('data',$reflections);
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
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }

      $reflection = $this->UnitReflections->get($id);
      
      $success = true;

      $this->set('data',$reflection);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function add(){
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');

      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      $data = $this->request->data;
      if($data['object_name'] == NULL) {
        $data['object_name'] = 'unit';
        $data['object_identifier'] = $unitId; 
      }
      if(!isset($data['description']) || !$data['description']){
        throw new BadRequestException("Missing description");
      }

      if(!isset($data['reflection_category_id']) || !$data['reflection_category_id']){
          $data['reflection_category_id'] = 1; // setting to default category
      }
      $data['unit_id'] = $unitId;
      $reflections = $this->UnitReflections->newEntity();
      $reflections = $this->UnitReflections->patchEntity($reflections,$data);
      
      if (!$this->UnitReflections->save($reflections)) { 
        throw new Exception("Unit Reflections could not be saved.");
      }

      $success = true;

      $this->set('data',$reflections);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function edit($id = null){
      if (!$this->request->is(['patch', 'post', 'put'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $reflections = $this->UnitReflections->get($id, [
            'contain' => []
      ]);

      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      $data = $this->request->data;

      if($data['object_name'] == NULL) {
        $data['object_name'] = $reflections->object_name;
        $data['object_identifier'] = $reflections->object_identifier; 
      }

      $reflections = $this->UnitReflections->patchEntity($reflections,$data);
      
      if (!$this->UnitReflections->save($reflections)) {  
        throw new Exception("Unit Reflections could not be saved.");
      }

      $success = true;

      $this->set('data',$reflections);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function delete($id = null){
      if (!$this->request->is(['delete'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $courseId = $this->request->getParam('course_id');
      $unitId = $this->request->getParam('unit_id');
      if(!isset($courseId) || !$courseId){
        throw new BadRequestException("Missing Course Id");
      }
      if(!isset($unitId) || !$unitId){
        throw new BadRequestException("Missing Unit id");
      }
      $reflections = $this->UnitReflections->get($id);

      if (!$this->UnitReflections->delete($reflections)) {
          throw new Exception("Resource could not be deleted.");
      } 
        
      $success = true;
      
      $this->set(compact('success'));
      $this->set('_serialize', ['success']);
    }
}
