<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateImpacts Controller
 *
 * @property \App\Model\Table\ReportTemplateImpactsTable $ReportTemplateImpacts
 *
 * @method \App\Model\Entity\ReportTemplateImpact[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateImpactsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Impacts']
        ];
        $reportTemplateImpacts = $this->paginate($this->ReportTemplateImpacts);

        $this->set(compact('reportTemplateImpacts'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Impact id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateImpact = $this->ReportTemplateImpacts->get($id, [
            'contain' => ['ReportTemplates', 'Impacts']
        ]);

        $this->set('reportTemplateImpact', $reportTemplateImpact);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateImpact = $this->ReportTemplateImpacts->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateImpact = $this->ReportTemplateImpacts->patchEntity($reportTemplateImpact, $this->request->getData());
            if ($this->ReportTemplateImpacts->save($reportTemplateImpact)) {
                $this->Flash->success(__('The report template impact has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template impact could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateImpacts->ReportTemplates->find('list', ['limit' => 200]);
        $impacts = $this->ReportTemplateImpacts->Impacts->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateImpact', 'reportTemplates', 'impacts'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Impact id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateImpact = $this->ReportTemplateImpacts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateImpact = $this->ReportTemplateImpacts->patchEntity($reportTemplateImpact, $this->request->getData());
            if ($this->ReportTemplateImpacts->save($reportTemplateImpact)) {
                $this->Flash->success(__('The report template impact has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template impact could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateImpacts->ReportTemplates->find('list', ['limit' => 200]);
        $impacts = $this->ReportTemplateImpacts->Impacts->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateImpact', 'reportTemplates', 'impacts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Impact id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateImpact = $this->ReportTemplateImpacts->get($id);
        if ($this->ReportTemplateImpacts->delete($reportTemplateImpact)) {
            $this->Flash->success(__('The report template impact has been deleted.'));
        } else {
            $this->Flash->error(__('The report template impact could not be deleted. Please, try again.'));
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
