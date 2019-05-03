<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LearningAreas Controller
 *
 * @property \App\Model\Table\LearningAreasTable $LearningAreas
 *
 * @method \App\Model\Entity\LearningArea[] paginate($object = null, array $settings = [])
 */
class LearningAreasController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $learningAreas = $this->LearningAreas->find()->contain(['Curriculums'])->all();

        $this->set(compact('learningAreas'));
        $this->set('_serialize', ['learningAreas']);
    }

    /**
     * View method
     *
     * @param string|null $id Learning Area id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $learningArea = $this->LearningAreas->get($id, [
            'contain' => ['Curriculums', 'Courses', 'Strands']
        ]);

        $this->set('learningArea', $learningArea);
        $this->set('_serialize', ['learningArea']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $learningArea = $this->LearningAreas->newEntity();
        if ($this->request->is('post')) {
            $learningArea = $this->LearningAreas->patchEntity($learningArea, $this->request->getData());
            if ($this->LearningAreas->save($learningArea)) {
                $this->Flash->success(__('The learning area has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The learning area could not be saved. Please, try again.'));
        }
        $curriculums = $this->LearningAreas->Curriculums->find('list', ['limit' => 200]);
        $this->set(compact('learningArea', 'curriculums'));
        $this->set('_serialize', ['learningArea']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Learning Area id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $learningArea = $this->LearningAreas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $learningArea = $this->LearningAreas->patchEntity($learningArea, $this->request->getData());
            if ($this->LearningAreas->save($learningArea)) {
                $this->Flash->success(__('The learning area has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The learning area could not be saved. Please, try again.'));
        }
        $curriculums = $this->LearningAreas->Curriculums->find('list', ['limit' => 200]);
        $this->set(compact('learningArea', 'curriculums'));
        $this->set('_serialize', ['learningArea']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Learning Area id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $learningArea = $this->LearningAreas->get($id);
        if ($this->LearningAreas->delete($learningArea)) {
            $this->Flash->success(__('The learning area has been deleted.'));
        } else {
            $this->Flash->error(__('The learning area could not be deleted. Please, try again.'));
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
