<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateVariables Controller
 *
 * @property \App\Model\Table\ReportTemplateVariablesTable $ReportTemplateVariables
 *
 * @method \App\Model\Entity\ReportTemplateVariable[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateVariablesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplateTypes']
        ];
        $reportTemplateVariables = $this->paginate($this->ReportTemplateVariables);

        $this->set(compact('reportTemplateVariables'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Variable id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateVariable = $this->ReportTemplateVariables->get($id, [
            'contain' => ['ReportTemplateTypes']
        ]);

        $this->set('reportTemplateVariable', $reportTemplateVariable);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateVariable = $this->ReportTemplateVariables->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateVariable = $this->ReportTemplateVariables->patchEntity($reportTemplateVariable, $this->request->getData());
            if ($this->ReportTemplateVariables->save($reportTemplateVariable)) {
                $this->Flash->success(__('The report template variable has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template variable could not be saved. Please, try again.'));
        }
        $reportTemplateTypes = $this->ReportTemplateVariables->ReportTemplateTypes->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateVariable', 'reportTemplateTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Variable id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateVariable = $this->ReportTemplateVariables->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateVariable = $this->ReportTemplateVariables->patchEntity($reportTemplateVariable, $this->request->getData());
            if ($this->ReportTemplateVariables->save($reportTemplateVariable)) {
                $this->Flash->success(__('The report template variable has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template variable could not be saved. Please, try again.'));
        }
        $reportTemplateTypes = $this->ReportTemplatePages->ReportTemplateTypes->find()
                                                                              ->combine('id','type')
                                                                              ->toArray();
        $this->set(compact('reportTemplateVariable', 'reportTemplateTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Variable id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateVariable = $this->ReportTemplateVariables->get($id);
        if ($this->ReportTemplateVariables->delete($reportTemplateVariable)) {
            $this->Flash->success(__('The report template variable has been deleted.'));
        } else {
            $this->Flash->error(__('The report template variable could not be deleted. Please, try again.'));
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
        }elseif (isset($user['role']) && $user['role']->name === 'school') {
            return true;
        }else{
            return false;
        }

        return parent::isAuthorized($user);
    }
}
