<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportTemplateStudentComments Controller
 *
 * @property \App\Model\Table\ReportTemplateStudentCommentsTable $ReportTemplateStudentComments
 *
 * @method \App\Model\Entity\ReportTemplateStudentComment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateStudentCommentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Students', 'Teachers']
        ];
        $reportTemplateStudentComments = $this->paginate($this->ReportTemplateStudentComments);

        $this->set(compact('reportTemplateStudentComments'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Student Comment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateStudentComment = $this->ReportTemplateStudentComments->get($id, [
            'contain' => ['ReportTemplates', 'Students', 'Teachers']
        ]);

        $this->set('reportTemplateStudentComment', $reportTemplateStudentComment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportTemplateStudentComment = $this->ReportTemplateStudentComments->newEntity();
        if ($this->request->is('post')) {
            $reportTemplateStudentComment = $this->ReportTemplateStudentComments->patchEntity($reportTemplateStudentComment, $this->request->getData());
            if ($this->ReportTemplateStudentComments->save($reportTemplateStudentComment)) {
                $this->Flash->success(__('The report template student comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template student comment could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateStudentComments->ReportTemplates->find('list', ['limit' => 200]);
        $students = $this->ReportTemplateStudentComments->Students->find('list', ['limit' => 200]);
        $teachers = $this->ReportTemplateStudentComments->Teachers->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateStudentComment', 'reportTemplates', 'students', 'teachers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Student Comment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateStudentComment = $this->ReportTemplateStudentComments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateStudentComment = $this->ReportTemplateStudentComments->patchEntity($reportTemplateStudentComment, $this->request->getData());
            if ($this->ReportTemplateStudentComments->save($reportTemplateStudentComment)) {
                $this->Flash->success(__('The report template student comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template student comment could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateStudentComments->ReportTemplates->find('list', ['limit' => 200]);
        $students = $this->ReportTemplateStudentComments->Students->find('list', ['limit' => 200]);
        $teachers = $this->ReportTemplateStudentComments->Teachers->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateStudentComment', 'reportTemplates', 'students', 'teachers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Student Comment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateStudentComment = $this->ReportTemplateStudentComments->get($id);
        if ($this->ReportTemplateStudentComments->delete($reportTemplateStudentComment)) {
            $this->Flash->success(__('The report template student comment has been deleted.'));
        } else {
            $this->Flash->error(__('The report template student comment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
