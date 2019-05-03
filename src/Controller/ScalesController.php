<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Scales Controller
 *
 * @property \App\Model\Table\ScalesTable $Scales
 *
 * @method \App\Model\Entity\Scale[] paginate($object = null, array $settings = [])
 */
class ScalesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $scales = $this->paginate($this->Scales);

        $this->set(compact('scales'));
    }

    /**
     * View method
     *
     * @param string|null $id Scale id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $scale = $this->Scales->get($id, [
            'contain' => ['Evaluations', 'ScaleValues']
        ]);

        $this->set('scale', $scale);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $scale = $this->Scales->newEntity();
        if ($this->request->is('post')) {
            $scale = $this->Scales->patchEntity($scale, $this->request->getData());
            if ($this->Scales->save($scale)) {
                $this->Flash->success(__('The scale has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scale could not be saved. Please, try again.'));
        }
        $this->set(compact('scale'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Scale id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $scale = $this->Scales->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $scale = $this->Scales->patchEntity($scale, $this->request->getData());
            if ($this->Scales->save($scale)) {
                $this->Flash->success(__('The scale has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scale could not be saved. Please, try again.'));
        }
        $this->set(compact('scale'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Scale id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $scale = $this->Scales->get($id);
        if ($this->Scales->delete($scale)) {
            $this->Flash->success(__('The scale has been deleted.'));
        } else {
            $this->Flash->error(__('The scale could not be deleted. Please, try again.'));
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
