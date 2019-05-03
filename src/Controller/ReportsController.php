<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 *
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Grades', 'ReportPages', 'Courses']
        ];
        $reports = $this->paginate($this->Reports);

        $this->set(compact('reports'));
    }

    /**
     * View method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => ['ReportTemplates', 'Grades', 'ReportPages', 'Courses']
        ]);

        $this->set('report', $report);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $report = $this->Reports->newEntity();
        if ($this->request->is('post')) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            if ($this->Reports->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->Reports->ReportTemplates->find('list', ['limit' => 200]);
        $grades = $this->Reports->Grades->find('list', ['limit' => 200]);
        $reportPages = $this->Reports->ReportPages->find('list', ['limit' => 200]);
        $courses = $this->Reports->Courses->find('list', ['limit' => 200]);
        $this->set(compact('report', 'reportTemplates', 'grades', 'reportPages', 'courses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            if ($this->Reports->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->Reports->ReportTemplates->find('list', ['limit' => 200]);
        $grades = $this->Reports->Grades->find('list', ['limit' => 200]);
        $reportPages = $this->Reports->ReportPages->find('list', ['limit' => 200]);
        $courses = $this->Reports->Courses->find('list', ['limit' => 200]);
        $this->set(compact('report', 'reportTemplates', 'grades', 'reportPages', 'courses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $report = $this->Reports->get($id);
        if ($this->Reports->delete($report)) {
            $this->Flash->success(__('The report has been deleted.'));
        } else {
            $this->Flash->error(__('The report could not be deleted. Please, try again.'));
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

    public function addReports($reportTemplateId = null){
        if(!$this->request->is('post')){
            throw new BadRequestException('Request is not post');
        }

        $reqData = $this->request->getData();
        $this->loadModel('CronJobs');
        $data = [
                    'shell_name' => 'Pdf', 
                    'method_name' => 'generateLinksForPdf',
                    'status' => 1,
                    'in_process' => 0,
                    'meta' => [
                                'report_template_id' => $reportTemplateId,
                                'email' => $reqData['email'],
                                // 'password' => $reqData['password'] 
                              ]
                ];

        $cronJobs = $this->CronJobs->newEntity();
        $cronJobs = $this->CronJobs->patchEntity($cronJobs, $data);
        // pr($cronJobs); die;
        if(!$this->CronJobs->save($cronJobs)){
            throw new InternalErrorException('Something went wrong in saving');
        }
        
        // $this->Flash->success(__('We have recieved your request and will send you a link to download the report on the email you have provided'));

        // return $this->redirect(['controller' => 'ReportTemplates', 'action' => 'studentReports', $reportTemplateId]);
        $this->set('data', $cronJobs);

        $this->set('_serialize', ['data']);
    }
}
