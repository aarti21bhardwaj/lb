<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class StandardsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $standards = $this->Standards->find()->contain(['Strands', 'ParentStandards'])->all();
        
        $this->set(compact('standards'));
        $this->set('_serialize', ['standards']);
    }

    public function standardsByStrands(){

        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException('This request is not post');
        }
        $data = $this->request->data;

        $strandsData = $this->Standards->Strands->find()
                                                ->where(['learning_area_id' => $data['learning_area_id']])
                                                ->all();

        $strandIds = $strandsData->extract('id')->toArray();
                                            
        $strands = $strandsData->map(function($value, $key){
                                $value->parent_id = NULL;
                                    $value->id = 's'.$value->id;
                                    $value->text = $value->name;
                                    return $value;
                                })
                                ->toArray();



        $standards = $this->Standards->find()->where(['strand_id IN' => $strandIds])
                                             ->all()
                                             ->map(function($value, $key) {
                                                if($value->parent_id == NULL) {
                                                    $value->parent_id = "s".$value->strand_id;//
                                                }
                                                $value->text = $value->name; 
                                                return $value;
                                             })
                                             ->toArray(); 
        foreach ($strands as $value) {
            $standards[] = $value;            
                                                         # code...
        }

        $new = (new Collection($standards))->nest('id','parent_id')->toArray();                            
        
        if(!$standards){
            $data =array();
            $data['data']['message']='No standards set for this strands';
        }else{
            $data =array();
            $data['data']=$new;
        }

        $this->set('response', $data);
        $this->set('_serialize', ['response']);
    }

    /**
     * View method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $standard = $this->Standards->get($id, [
            'contain' => ['Strands', 'ParentStandards', 'ChildStandards']
        ]);

        $this->set('standard', $standard);
        $this->set('_serialize', ['standard']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $standard = $this->Standards->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if(!isset($data['name']) && empty($data['name'])){
                throw new BadRequestException("Missing field name in standards");
            }

            if(!isset($data['code']) && empty($data['code'])){
                throw new BadRequestException("Missing field code in standards");
            }

            $standard = $this->Standards->patchEntity($standard, $data);
            // pr($standard); die;
            if ($this->Standards->save($standard)) {
                $this->Flash->success(__('The standard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The standard could not be saved. Please, try again.'));
        }
        $this->loadModel('Grades');
        $strands = $this->Standards->Strands->find('list', ['limit' => 200]);
        $grades = $this->Grades->find()->all()->combine('id', 'name')->toArray();
        // pr($grades); die;
        $parentStandards = $this->Standards->ParentStandards->find('list', ['limit' => 200]);
        
        $curriculums = $this->Standards->Strands->LearningAreas->Curriculums->find('list');
        $learningArea = $this->Standards->Strands->LearningAreas->find()->all();
        $learningAreas = $learningArea->combine('id', 'name')->toArray();
        $curriculumLearningAreas = $learningArea->groupBy('curriculum_id')->toArray();

        $this->set(compact('standard', 'strands','parentStandards', 'grades','curriculums', 'learningAreas', 'curriculumLearningAreas'));
        $this->set('_serialize', ['standard']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $standard = $this->Standards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $standard = $this->Standards->patchEntity($standard, $this->request->getData());
            if ($this->Standards->save($standard)) {
                $this->Flash->success(__('The standard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The standard could not be saved. Please, try again.'));
        }
        $strands = $this->Standards->Strands->find('list', ['limit' => 200]);
        $grades = $this->Standards->Grades->find('list', ['limit' => 200]);
        $parentStandards = $this->Standards->ParentStandards->find('list', ['limit' => 200]);
        $this->set(compact('standard', 'strands', 'parentStandards'));
        $this->set('_serialize', ['standard']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $standard = $this->Standards->get($id);
        if ($this->Standards->delete($standard)) {
            $this->Flash->success(__('The standard has been deleted.'));
        } else {
            $this->Flash->error(__('The standard could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $session = $this->request->session();
        $superAdminUser = $session->read('superAdminUser');

        if($superAdminUser && ($this->request->params['action'] == 'index' || $this->request->params['action'] == 'view' || $this->request->params['action'] == 'edit' || $this->request->params['action'] == 'add' || $this->request->params['action'] == 'delete')){
          return true;
        }
        
        if (isset($user['role']) && $user['role']->name === 'admin'){
            return true;
        }else{
            return false;
        }

        return parent::isAuthorized($user);
    }
}
