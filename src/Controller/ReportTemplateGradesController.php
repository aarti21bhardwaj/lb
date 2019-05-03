<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateGrades Controller
 *
 * @property \App\Model\Table\ReportTemplateGradesTable $ReportTemplateGrades
 *
 * @method \App\Model\Entity\ReportTemplateGrade[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateGradesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Grades']
        ];
        $reportTemplateGrades = $this->paginate($this->ReportTemplateGrades);

        $this->set(compact('reportTemplateGrades'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Grade id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateGrade = $this->ReportTemplateGrades->get($id, [
            'contain' => ['ReportTemplates', 'Grades']
        ]);

        $this->set('reportTemplateGrade', $reportTemplateGrade);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateGrade = $this->ReportTemplateGrades->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateGrade = $this->ReportTemplateGrades->patchEntity($reportTemplateGrade, $this->request->getData());
            if ($this->ReportTemplateGrades->save($reportTemplateGrade)) {
                $this->Flash->success(__('The report template grade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template grade could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateGrades->ReportTemplates->find('list', ['limit' => 200]);
        $grades = $this->ReportTemplateGrades->Grades->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateGrade', 'reportTemplates', 'grades'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Grade id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateGrade = $this->ReportTemplateGrades->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateGrade = $this->ReportTemplateGrades->patchEntity($reportTemplateGrade, $this->request->getData());
            if ($this->ReportTemplateGrades->save($reportTemplateGrade)) {
                $this->Flash->success(__('The report template grade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template grade could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateGrades->ReportTemplates->find('list', ['limit' => 200]);
        $grades = $this->ReportTemplateGrades->Grades->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateGrade', 'reportTemplates', 'grades'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Grade id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateGrade = $this->ReportTemplateGrades->get($id);
        if ($this->ReportTemplateGrades->delete($reportTemplateGrade)) {
            $this->Flash->success(__('The report template grade has been deleted.'));
        } else {
            $this->Flash->error(__('The report template grade could not be deleted. Please, try again.'));
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
