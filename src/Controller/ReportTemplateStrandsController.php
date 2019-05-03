<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateStrands Controller
 *
 * @property \App\Model\Table\ReportTemplateStrandsTable $ReportTemplateStrands
 *
 * @method \App\Model\Entity\ReportTemplateStrand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateStrandsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Strands']
        ];
        $reportTemplateStrands = $this->paginate($this->ReportTemplateStrands);

        $this->set(compact('reportTemplateStrands'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Strand id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateStrand = $this->ReportTemplateStrands->get($id, [
            'contain' => ['ReportTemplates', 'Strands']
        ]);

        $this->set('reportTemplateStrand', $reportTemplateStrand);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateStrand = $this->ReportTemplateStrands->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateStrand = $this->ReportTemplateStrands->patchEntity($reportTemplateStrand, $this->request->getData());
            if ($this->ReportTemplateStrands->save($reportTemplateStrand)) {
                $this->Flash->success(__('The report template strand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template strand could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateStrands->ReportTemplates->find('list', ['limit' => 200]);
        $strands = $this->ReportTemplateStrands->Strands->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateStrand', 'reportTemplates', 'strands'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Strand id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateStrand = $this->ReportTemplateStrands->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateStrand = $this->ReportTemplateStrands->patchEntity($reportTemplateStrand, $this->request->getData());
            if ($this->ReportTemplateStrands->save($reportTemplateStrand)) {
                $this->Flash->success(__('The report template strand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template strand could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateStrands->ReportTemplates->find('list', ['limit' => 200]);
        $strands = $this->ReportTemplateStrands->Strands->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateStrand', 'reportTemplates', 'strands'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Strand id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateStrand = $this->ReportTemplateStrands->get($id);
        if ($this->ReportTemplateStrands->delete($reportTemplateStrand)) {
            $this->Flash->success(__('The report template strand has been deleted.'));
        } else {
            $this->Flash->error(__('The report template strand could not be deleted. Please, try again.'));
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
