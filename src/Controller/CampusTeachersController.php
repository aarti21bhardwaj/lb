<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CampusTeachers Controller
 *
 * @property \App\Model\Table\CampusTeachersTable $CampusTeachers
 *
 * @method \App\Model\Entity\CampusTeacher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CampusTeachersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $campusTeachers = $this->CampusTeachers->find()
                                                ->contain(['Campuses','Teachers'])
                                                ->all();

        $this->set(compact('campusTeachers'));
    }

    /**
     * View method
     *
     * @param string|null $id Campus Teacher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function view($id = null)
    // {
    //     $campusTeacher = $this->CampusTeachers->get($id, [
    //         'contain' => ['Campuses', 'Teachers']
    //     ]);

    //     $this->set('campusTeacher', $campusTeacher);
    // }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $campusTeacher = $this->CampusTeachers->newEntity();
        if ($this->request->is('post')) {
            //pr($this->request->getData()); //die;
            $campus_id = $this->request->getData('campus_id');
            $saveData  = [];
            foreach ($this->request->getData('teacher_id') as $value) {
                # code...
                $saveData[] = [
                    'campus_id' => $campus_id,
                    'teacher_id' => $value
                ];
            }

            $campusTeacher = $this->CampusTeachers->newEntities($saveData);
//            pr($campusTeacher); die;
            if ($this->CampusTeachers->saveMany($campusTeacher)) {
                $this->Flash->success(__('Campus teachers have been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Campus teachers could not be saved. Please, try again.'));
        }
        $campuses = $this->CampusTeachers->Campuses->find('list', ['limit' => 200]);
        $teachers = $this->CampusTeachers->Teachers->findByRoleId(3)
                                                    ->all()
                                                    ->map(function($value,$key){
                                                      $value->full_name = $value->first_name." ".$value->last_name;
                                                      return $value;
                                                     })
                                                    ->combine('id','full_name')
                                                    ->toArray();
        $this->set(compact('campusTeacher', 'campuses', 'teachers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Campus Teacher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    // public function edit($id = null)
    // {

    //     $campusTeacher = $this->CampusTeachers->get($id, [
    //         'contain' => []
    //     ]);
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $campusTeacher = $this->CampusTeachers->patchEntity($campusTeacher, $this->request->getData());
    //         if ($this->CampusTeachers->save($campusTeacher)) {
    //             $this->Flash->success(__('The campus teacher has been saved.'));

    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('The campus teacher could not be saved. Please, try again.'));
    //     }
    //     $campuses = $this->CampusTeachers->Campuses->find('list', ['limit' => 200]);
    //     $teachers = $this->CampusTeachers->Teachers->find('list', ['limit' => 200]);
    //     $this->set(compact('campusTeacher', 'campuses', 'teachers'));
    // }

    /**
     * Delete method
     *
     * @param string|null $id Campus Teacher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $campusTeacher = $this->CampusTeachers->get($id);
        if ($this->CampusTeachers->delete($campusTeacher)) {
            $this->Flash->success(__('The campus teacher has been deleted.'));
        } else {
            $this->Flash->error(__('The campus teacher could not be deleted. Please, try again.'));
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
