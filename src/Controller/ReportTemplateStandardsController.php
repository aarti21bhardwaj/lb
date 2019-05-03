<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateStandards Controller
 *
 * @property \App\Model\Table\ReportTemplateStandardsTable $ReportTemplateStandards
 *
 * @method \App\Model\Entity\ReportTemplateStandard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateStandardsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Standards']
        ];
        $reportTemplateStandards = $this->paginate($this->ReportTemplateStandards);

        $this->set(compact('reportTemplateStandards'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Standard id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateStandard = $this->ReportTemplateStandards->get($id, [
            'contain' => ['ReportTemplates', 'Standards']
        ]);

        $this->set('reportTemplateStandard', $reportTemplateStandard);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateStandard = $this->ReportTemplateStandards->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateStandard = $this->ReportTemplateStandards->patchEntity($reportTemplateStandard, $this->request->getData());
            if ($this->ReportTemplateStandards->save($reportTemplateStandard)) {
                $this->Flash->success(__('The report template standard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template standard could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateStandards->ReportTemplates->find('list', ['limit' => 200]);
        $standards = $this->ReportTemplateStandards->Standards->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateStandard', 'reportTemplates', 'standards'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Standard id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateStandard = $this->ReportTemplateStandards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateStandard = $this->ReportTemplateStandards->patchEntity($reportTemplateStandard, $this->request->getData());
            if ($this->ReportTemplateStandards->save($reportTemplateStandard)) {
                $this->Flash->success(__('The report template standard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template standard could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateStandards->ReportTemplates->find('list', ['limit' => 200]);
        $standards = $this->ReportTemplateStandards->Standards->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateStandard', 'reportTemplates', 'standards'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Standard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateStandard = $this->ReportTemplateStandards->get($id);
        if ($this->ReportTemplateStandards->delete($reportTemplateStandard)) {
            $this->Flash->success(__('The report template standard has been deleted.'));
        } else {
            $this->Flash->error(__('The report template standard could not be deleted. Please, try again.'));
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
