<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ContentCategories Controller
 *
 * @property \App\Model\Table\ContentCategoriesTable $ContentCategories
 *
 * @method \App\Model\Entity\ContentCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        // $contentCategories = $this->paginate($this->ContentCategories);
        $contentCategories = $this->ContentCategories->find()->all()->toArray();
        $this->set(compact('contentCategories'));
    }

    /**
     * View method
     *
     * @param string|null $id Content Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contentCategory = $this->ContentCategories->get($id, [
            'contain' => ['ContentValues', 'CourseContentCategories.Courses', 'UnitContents.Units', 'UnitSpecificContents.Units']
        ]);
        // pr($contentCategory); die;

        $this->set('contentCategory', $contentCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contentCategory = $this->ContentCategories->newEntity();
        if ($this->request->is('post')) {
            $contentCategory = $this->ContentCategories->patchEntity($contentCategory, $this->request->getData());
            if ($this->ContentCategories->save($contentCategory)) {
                $this->Flash->success(__('The content category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The content category could not be saved. Please, try again.'));
        }
        $this->set(compact('contentCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Content Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contentCategory = $this->ContentCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contentCategory = $this->ContentCategories->patchEntity($contentCategory, $this->request->getData());
            if ($this->ContentCategories->save($contentCategory)) {
                $this->Flash->success(__('The content category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The content category could not be saved. Please, try again.'));
        }
        $this->set(compact('contentCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Content Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contentCategory = $this->ContentCategories->get($id);
        if ($this->ContentCategories->delete($contentCategory)) {
            $this->Flash->success(__('The content category has been deleted.'));
        } else {
            $this->Flash->error(__('The content category could not be deleted. Please, try again.'));
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
