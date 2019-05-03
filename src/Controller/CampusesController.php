<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Campuses Controller
 *
 * @property \App\Model\Table\CampusesTable $Campuses
 *
 * @method \App\Model\Entity\Campus[] paginate($object = null, array $settings = [])
 */
class CampusesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $campuses = $this->Campuses->find()->contain(['Schools'])->all();

        $this->set(compact('campuses'));
        $this->set('_serialize', ['campuses']);
    }

    /**
     * View method
     *
     * @param string|null $id Campus id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $campus = $this->Campuses->get($id, [
            'contain' => ['Schools']
        ]);

        $this->set('campus', $campus);
        $this->set('_serialize', ['campus']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $campus = $this->Campuses->newEntity();
        if ($this->request->is('post')) {
            $campus = $this->Campuses->patchEntity($campus, $this->request->getData());
            if ($this->Campuses->save($campus)) {
                $this->Flash->success(__('The campus has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The campus could not be saved. Please, try again.'));
        }
        $schools = $this->Campuses->Schools->find('list', ['limit' => 200]);
        $this->set(compact('campus', 'schools'));
        $this->set('_serialize', ['campus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Campus id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $campus = $this->Campuses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $campus = $this->Campuses->patchEntity($campus, $this->request->getData());
            if ($this->Campuses->save($campus)) {
                $this->Flash->success(__('The campus has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The campus could not be saved. Please, try again.'));
        }
        $schools = $this->Campuses->Schools->find('list', ['limit' => 200]);
        $this->set(compact('campus', 'schools'));
        $this->set('_serialize', ['campus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Campus id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $campus = $this->Campuses->get($id);
        if ($this->Campuses->delete($campus)) {
            $this->Flash->success(__('The campus has been deleted.'));
        } else {
            $this->Flash->error(__('The campus could not be deleted. Please, try again.'));
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
