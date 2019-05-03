<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Strands Controller
 *
 * @property \App\Model\Table\StrandsTable $Strands
 *
 * @method \App\Model\Entity\Strand[] paginate($object = null, array $settings = [])
 */
class StrandsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $strands = $this->Strands->find()->contain(['LearningAreas'])->all();
        $this->set(compact('strands'));
        $this->set('_serialize', ['strands']);
    }

    /**
     * View method
     *
     * @param string|null $id Strand id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $strand = $this->Strands->get($id, [
            'contain' => ['LearningAreas', 'Standards']
        ]);

        $this->set('strand', $strand);
        $this->set('_serialize', ['strand']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $strand = $this->Strands->newEntity();
        if ($this->request->is('post')) {
            $strand = $this->Strands->patchEntity($strand, $this->request->getData());
            if ($this->Strands->save($strand)) {
                $this->Flash->success(__('The strand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The strand could not be saved. Please, try again.'));
        }
        $learningAreas = $this->Strands->LearningAreas->find('list', ['limit' => 200]);
        // $grades = $this->Strands->Grades->find('list', ['limit' => 200]);
        $this->set(compact('strand', 'learningAreas'));
        $this->set('_serialize', ['strand']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Strand id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $strand = $this->Strands->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $strand = $this->Strands->patchEntity($strand, $this->request->getData());
            if ($this->Strands->save($strand)) {
                $this->Flash->success(__('The strand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The strand could not be saved. Please, try again.'));
        }
        $learningAreas = $this->Strands->LearningAreas->find('list', ['limit' => 200]);
        // $grades = $this->Strands->Grades->find('list', ['limit' => 200]);
        $this->set(compact('strand', 'learningAreas'));
        $this->set('_serialize', ['strand']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Strand id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $strand = $this->Strands->get($id);
        if ($this->Strands->delete($strand)) {
            $this->Flash->success(__('The strand has been deleted.'));
        } else {
            $this->Flash->error(__('The strand could not be deleted. Please, try again.'));
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
