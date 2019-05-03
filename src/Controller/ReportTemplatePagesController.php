<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;

/**
 * ReportTemplatePages Controller
 *
 * @property \App\Model\Table\ReportTemplatePagesTable $ReportTemplatePages
 *
 * @method \App\Model\Entity\ReportTemplatePage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplatePagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $reportTemplatePages = $this->ReportTemplatePages->find()
                                                         ->contain(['ReportTemplateTypes'])
                                                         ->all()
                                                         ->toArray();

        $this->set(compact('reportTemplatePages'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Page id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplatePage = $this->ReportTemplatePages->get($id, [
            'contain' => ['ReportTemplateTypes']
        ]);

        $this->set('reportTemplatePage', $reportTemplatePage);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplatePage = $this->ReportTemplatePages->newEntity();
        if ($this->request->is('post')) {
            $reportTemplatePage = $this->ReportTemplatePages->patchEntity($reportTemplatePage, $this->request->getData());
            if ($this->ReportTemplatePages->save($reportTemplatePage)) {
                $this->Flash->success(__('The report template page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template page could not be saved. Please, try again.'));
        }
        $reportTemplateTypes = $this->ReportTemplatePages->ReportTemplateTypes->find()
                                                                              ->combine('id','type')
                                                                              ->toArray();
        $this->loadModel('ReportTemplateVariables');
        $reportTemplateVariables = $this->ReportTemplateVariables->find()->all()
                                                                         ->map(function($value,$key){
                                                                                $value->identifier = '{{'.$value->identifier.'}}';
                                                                                return $value;
                                                                            })
                                                                          ->groupBy('report_template_type_id')
                                                                          ->map(function($value,$key){
                                                                                return (new Collection($value))->combine('identifier', 'name')->toArray();
                                                                            })
                                                                          ->toArray();

        $this->set(compact('reportTemplatePage', 'reportTemplateTypes', 'reportTemplateVariables'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Page id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplatePage = $this->ReportTemplatePages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplatePage = $this->ReportTemplatePages->patchEntity($reportTemplatePage, $this->request->getData());
            if ($this->ReportTemplatePages->save($reportTemplatePage)) {
                $this->Flash->success(__('The report template page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template page could not be saved. Please, try again.'));
        }
        $reportTemplateTypes = $this->ReportTemplatePages->ReportTemplateTypes->find()
                                                                              ->combine('id','type')
                                                                              ->toArray();

        $this->loadModel('ReportTemplateVariables');
        $reportTemplateVariables = $this->ReportTemplateVariables->find()->all()
                                                                         ->map(function($value,$key){
                                                                                $value->identifier = '{{'.$value->identifier.'}}';
                                                                                return $value;
                                                                            })
                                                                          ->groupBy('report_template_type_id')
                                                                          ->map(function($value,$key){
                                                                                return (new Collection($value))->combine('identifier', 'name')->toArray();
                                                                            })
                                                                          ->toArray();
        
        $this->set(compact('reportTemplatePage', 'reportTemplateTypes', 'reportTemplateVariables'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Page id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplatePage = $this->ReportTemplatePages->get($id);
        if ($this->ReportTemplatePages->delete($reportTemplatePage)) {
            $this->Flash->success(__('The report template page has been deleted.'));
        } else {
            $this->Flash->error(__('The report template page could not be deleted. Please, try again.'));
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
