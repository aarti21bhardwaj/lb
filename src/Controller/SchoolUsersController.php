<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SchoolUsers Controller
 *
 * @property \App\Model\Table\SchoolUsersTable $SchoolUsers
 *
 * @method \App\Model\Entity\SchoolUser[] paginate($object = null, array $settings = [])
 */
class SchoolUsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $schoolUsers = $this->SchoolUsers->find()->contain(['Users', 'Schools', 'Legacies'])->all();

        $this->set(compact('schoolUsers'));
        $this->set('_serialize', ['schoolUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id School User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schoolUser = $this->SchoolUsers->get($id, [
            'contain' => ['Users', 'Schools', 'Legacies']
        ]);

        $this->set('schoolUser', $schoolUser);
        $this->set('_serialize', ['schoolUser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schoolUser = $this->SchoolUsers->newEntity();
        if ($this->request->is('post')) {
            $schoolUser = $this->SchoolUsers->patchEntity($schoolUser, $this->request->getData());
            if ($this->SchoolUsers->save($schoolUser)) {
                $this->Flash->success(__('The school user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school user could not be saved. Please, try again.'));
        }
        $users = $this->SchoolUsers->Users->find('list', ['limit' => 200]);
        $schools = $this->SchoolUsers->Schools->find('list', ['limit' => 200]);
        $legacies = $this->SchoolUsers->Legacies->find('list', ['limit' => 200]);
        $this->set(compact('schoolUser', 'users', 'schools', 'legacies'));
        $this->set('_serialize', ['schoolUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id School User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schoolUser = $this->SchoolUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schoolUser = $this->SchoolUsers->patchEntity($schoolUser, $this->request->getData());
            if ($this->SchoolUsers->save($schoolUser)) {
                $this->Flash->success(__('The school user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school user could not be saved. Please, try again.'));
        }
        $users = $this->SchoolUsers->Users->find('list', ['limit' => 200]);
        $schools = $this->SchoolUsers->Schools->find('list', ['limit' => 200]);
        $legacies = $this->SchoolUsers->Legacies->find('list', ['limit' => 200]);
        $this->set(compact('schoolUser', 'users', 'schools', 'legacies'));
        $this->set('_serialize', ['schoolUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id School User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schoolUser = $this->SchoolUsers->get($id);
        if ($this->SchoolUsers->delete($schoolUser)) {
            $this->Flash->success(__('The school user has been deleted.'));
        } else {
            $this->Flash->error(__('The school user could not be deleted. Please, try again.'));
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
