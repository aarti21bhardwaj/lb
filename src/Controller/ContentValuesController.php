<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ContentValues Controller
 *
 * @property \App\Model\Table\ContentValuesTable $ContentValues
 *
 * @method \App\Model\Entity\ContentValue[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentValuesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
       
        $contentValues = $this->ContentValues->find()->contain(['ContentCategories', 'ParentContentValues'])->all()->toArray();

        $this->set(compact('contentValues'));
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
        $contentValue = $this->ContentValues->get($id, [
            'contain' => ['ContentCategories', 'ParentContentValues', 'ChildContentValues', 'UnitContents']
        ]);

        $this->set('contentValue', $contentValue);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contentValue = $this->ContentValues->newEntity();
        if ($this->request->is('post')) {
            $contentValue = $this->ContentValues->patchEntity($contentValue, $this->request->getData());
            if ($this->ContentValues->save($contentValue)) {
                $this->Flash->success(__('The content value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The content value could not be saved. Please, try again.'));
        }
        $contentCategories = $this->ContentValues->ContentCategories->find('list', ['limit' => 200]);
        $parentContentValues = $this->ContentValues->ParentContentValues->find('list', ['limit' => 200]);
        $this->set(compact('contentValue', 'contentCategories', 'parentContentValues'));
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
        $contentValue = $this->ContentValues->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contentValue = $this->ContentValues->patchEntity($contentValue, $this->request->getData());
            if ($this->ContentValues->save($contentValue)) {
                $this->Flash->success(__('The content value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The content value could not be saved. Please, try again.'));
        }
        $contentCategories = $this->ContentValues->ContentCategories->find('list', ['limit' => 200]);
        $parentContentValues = $this->ContentValues->ParentContentValues->find('list', ['limit' => 200]);
        $this->set(compact('contentValue', 'contentCategories', 'parentContentValues'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Content Value id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contentValue = $this->ContentValues->get($id);
        if ($this->ContentValues->delete($contentValue)) {
            $this->Flash->success(__('The content value has been deleted.'));
        } else {
            $this->Flash->error(__('The content value could not be deleted. Please, try again.'));
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
