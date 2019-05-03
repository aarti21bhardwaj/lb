<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ScaleValues Controller
 *
 * @property \App\Model\Table\ScaleValuesTable $ScaleValues
 *
 * @method \App\Model\Entity\ScaleValue[] paginate($object = null, array $settings = [])
 */
class ScaleValuesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
	$scaleValues = $this->ScaleValues->find()->contain(['Scales'])->toArray();
        $this->set(compact('scaleValues'));
    }

    /**
     * View method
     *
     * @param string|null $id Scale Value id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $scaleValue = $this->ScaleValues->get($id, [
            'contain' => ['Scales', 'EvaluationImpactScores', 'EvaluationStandardScores']
        ]);

        $this->set('scaleValue', $scaleValue);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $scaleValue = $this->ScaleValues->newEntity();
        if ($this->request->is('post')) {
            $scaleValue = $this->ScaleValues->patchEntity($scaleValue, $this->request->getData());
            if ($this->ScaleValues->save($scaleValue)) {
                $this->Flash->success(__('The scale value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scale value could not be saved. Please, try again.'));
        }
        $scales = $this->ScaleValues->Scales->find('list', ['limit' => 200]);
        $this->set(compact('scaleValue', 'scales'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Scale Value id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $scaleValue = $this->ScaleValues->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $scaleValue = $this->ScaleValues->patchEntity($scaleValue, $this->request->getData());
            if ($this->ScaleValues->save($scaleValue)) {
                $this->Flash->success(__('The scale value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scale value could not be saved. Please, try again.'));
        }
        $scales = $this->ScaleValues->Scales->find('list', ['limit' => 200]);
        $this->set(compact('scaleValue', 'scales'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Scale Value id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $scaleValue = $this->ScaleValues->get($id);
        if ($this->ScaleValues->delete($scaleValue)) {
            $this->Flash->success(__('The scale value has been deleted.'));
        } else {
            $this->Flash->error(__('The scale value could not be deleted. Please, try again.'));
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
