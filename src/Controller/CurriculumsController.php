<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Curriculums Controller
 *
 * @property \App\Model\Table\CurriculumsTable $Curriculums
 *
 * @method \App\Model\Entity\Curriculum[] paginate($object = null, array $settings = [])
 */
class CurriculumsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $curriculums = $this->Curriculums->find()->all();
        $this->set(compact('curriculums'));
        $this->set('_serialize', ['curriculums']);
    }

    /**
     * View method
     *
     * @param string|null $id Curriculum id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $curriculum = $this->Curriculums->get($id, [
            'contain' => ['LearningAreas']
        ]);

        $this->set('curriculum', $curriculum);
        $this->set('_serialize', ['curriculum']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $curriculum = $this->Curriculums->newEntity();
        if ($this->request->is('post')) {
            $curriculum = $this->Curriculums->patchEntity($curriculum, $this->request->getData());
            if ($this->Curriculums->save($curriculum)) {
                $this->Flash->success(__('The curriculum has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The curriculum could not be saved. Please, try again.'));
        }
        $this->set(compact('curriculum'));
        $this->set('_serialize', ['curriculum']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Curriculum id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $curriculum = $this->Curriculums->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $curriculum = $this->Curriculums->patchEntity($curriculum, $this->request->getData());
            if ($this->Curriculums->save($curriculum)) {
                $this->Flash->success(__('The curriculum has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The curriculum could not be saved. Please, try again.'));
        }
        $this->set(compact('curriculum'));
        $this->set('_serialize', ['curriculum']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Curriculum id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $curriculum = $this->Curriculums->get($id);
        if ($this->Curriculums->delete($curriculum)) {
            $this->Flash->success(__('The curriculum has been deleted.'));
        } else {
            $this->Flash->error(__('The curriculum could not be deleted. Please, try again.'));
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
