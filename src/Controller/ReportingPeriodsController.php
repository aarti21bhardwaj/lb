<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportingPeriods Controller
 *
 * @property \App\Model\Table\ReportingPeriodsTable $ReportingPeriods
 *
 * @method \App\Model\Entity\ReportingPeriod[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportingPeriodsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Terms']
        ];
        $reportingPeriods = $this->paginate($this->ReportingPeriods);

        $this->set(compact('reportingPeriods'));
    }

    /**
     * View method
     *
     * @param string|null $id Reporting Period id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportingPeriod = $this->ReportingPeriods->get($id, [
            'contain' => ['Terms', 'ReportTemplates']
        ]);

        $this->set('reportingPeriod', $reportingPeriod);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($termId = null)
    {
        $reportingPeriod = $this->ReportingPeriods->newEntity();
        $termData = null;
        if($termId){
            $reportingPeriod->term_id = $termId;
            $termData = $this->ReportingPeriods->Terms->findById($termId)->first();
            // pr($reportingPeriodData); die;
        }
        if ($this->request->is('post')) {
            $reportingPeriod = $this->ReportingPeriods->patchEntity($reportingPeriod, $this->request->getData());
            // pr($reportingPeriod); die;
            if ($this->ReportingPeriods->save($reportingPeriod)) {
                $this->Flash->success(__('The reporting period has been saved.'));

                // return $this->redirect(['action' => 'index']);
                 return $this->redirect(['controller'=>'Terms', 'action' => 'view', $reportingPeriod->term_id]);
            }
            $this->Flash->error(__('The reporting period could not be saved. Please, try again.'));
        }
        $terms = $this->ReportingPeriods->Terms->find('list', ['limit' => 200]);
        $this->set(compact('reportingPeriod', 'terms', 'termData'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Reporting Period id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportingPeriod = $this->ReportingPeriods->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportingPeriod = $this->ReportingPeriods->patchEntity($reportingPeriod, $this->request->getData());
            if ($this->ReportingPeriods->save($reportingPeriod)) {
                $this->Flash->success(__('The reporting period has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reporting period could not be saved. Please, try again.'));
        }
        $terms = $this->ReportingPeriods->Terms->find('list', ['limit' => 200]);
        $this->set(compact('reportingPeriod', 'terms'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reporting Period id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportingPeriod = $this->ReportingPeriods->get($id);
        if ($this->ReportingPeriods->delete($reportingPeriod)) {
            $this->Flash->success(__('The reporting period has been deleted.'));
        } else {
            $this->Flash->error(__('The reporting period could not be deleted. Please, try again.'));
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
