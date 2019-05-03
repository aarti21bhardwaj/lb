<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateTypes Controller
 *
 * @property \App\Model\Table\ReportTemplateTypesTable $ReportTemplateTypes
 *
 * @method \App\Model\Entity\ReportTemplateType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateTypesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $reportTemplateTypes = $this->paginate($this->ReportTemplateTypes);

        $this->set(compact('reportTemplateTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Type id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateType = $this->ReportTemplateTypes->get($id, [
            'contain' => ['ReportTemplatePages']
        ]);

        $this->set('reportTemplateType', $reportTemplateType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateType = $this->ReportTemplateTypes->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateType = $this->ReportTemplateTypes->patchEntity($reportTemplateType, $this->request->getData());
            if ($this->ReportTemplateTypes->save($reportTemplateType)) {
                $this->Flash->success(__('The report template type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template type could not be saved. Please, try again.'));
        }
        $this->set(compact('reportTemplateType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateType = $this->ReportTemplateTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateType = $this->ReportTemplateTypes->patchEntity($reportTemplateType, $this->request->getData());
            if ($this->ReportTemplateTypes->save($reportTemplateType)) {
                $this->Flash->success(__('The report template type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template type could not be saved. Please, try again.'));
        }
        $this->set(compact('reportTemplateType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateType = $this->ReportTemplateTypes->get($id);
        if ($this->ReportTemplateTypes->delete($reportTemplateType)) {
            $this->Flash->success(__('The report template type has been deleted.'));
        } else {
            $this->Flash->error(__('The report template type could not be deleted. Please, try again.'));
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
