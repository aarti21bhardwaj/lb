<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * ReportTemplateCourseStrands Controller
 *
 * @property \App\Model\Table\ReportTemplateCourseStrandsTable $ReportTemplateCourseStrands
 *
 * @method \App\Model\Entity\ReportTemplateCourseStrand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplateCourseStrandsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Courses', 'Grades', 'Strands']
        ];
        $reportTemplateCourseStrands = $this->paginate($this->ReportTemplateCourseStrands);

        $this->set(compact('reportTemplateCourseStrands'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template Course Strand id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplateCourseStrand = $this->ReportTemplateCourseStrands->get($id, [
            'contain' => ['ReportTemplates', 'Courses', 'Grades', 'Strands']
        ]);

        $this->set('reportTemplateCourseStrand', $reportTemplateCourseStrand);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if(!$data){
                throw new BadRequestException("Missing request data", 1);
                
            }

            if(!isset($data['report_template_id']) && empty($data['report_template_id'])){
                throw new BadRequestException("Missing report template id", 1);
                
            }

            if(!isset($data['course_id']) && empty($data['course_id'])){
                throw new BadRequestException("Missing course id", 1);
                
            }

            if(!isset($data['grade_id']) && empty($data['grade_id'])){
                throw new BadRequestException("Missing grade id", 1);
                
            }

            if(!isset($data['learning_area_id']) && empty($data['learning_area_id'])){
                throw new BadRequestException("Missing learning area id", 1);
                
            }

        
            $this->loadModel('Strands');
            $strands = $this->Strands->find()->where(['learning_area_id' => $data['learning_area_id']])
                                             ->all()
                                             ->toArray();
            // pr($strands); die;
            if(!$strands){
                throw new BadRequestException('No strands set for this learning area and grade, Please set them first');
            }

            $reportStrandData = [];
            foreach ($strands as $key => $value) {
                $reportStrandData[] = [
                                          'report_template_id' => $data['report_template_id'],
                                          'strand_id' => $value->id,
                                          'grade_id' => $data['grade_id'],
                                          'course_id' => $data['course_id']

                                      ];
            }

                                             
            $reportTemplateCourseStrand = $this->ReportTemplateCourseStrands->newEntities($reportStrandData);

            if (!$this->ReportTemplateCourseStrands->saveMany($reportTemplateCourseStrand)) {
                throw new BadRequestException('Something went wrong in saving');
            }

        }
        $this->set('response', $data);
        $this->set('data', $reportTemplateCourseStrand);
        $this->set('_serialize', ['response', 'data']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template Course Strand id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplateCourseStrand = $this->ReportTemplateCourseStrands->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplateCourseStrand = $this->ReportTemplateCourseStrands->patchEntity($reportTemplateCourseStrand, $this->request->getData());
            if ($this->ReportTemplateCourseStrands->save($reportTemplateCourseStrand)) {
                $this->Flash->success(__('The report template course strand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template course strand could not be saved. Please, try again.'));
        }
        $reportTemplates = $this->ReportTemplateCourseStrands->ReportTemplates->find('list', ['limit' => 200]);
        $courses = $this->ReportTemplateCourseStrands->Courses->find('list', ['limit' => 200]);
        $grades = $this->ReportTemplateCourseStrands->Grades->find('list', ['limit' => 200]);
        $strands = $this->ReportTemplateCourseStrands->Strands->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplateCourseStrand', 'reportTemplates', 'courses', 'grades', 'strands'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template Course Strand id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplateCourseStrand = $this->ReportTemplateCourseStrands->get($id);
        if ($this->ReportTemplateCourseStrands->delete($reportTemplateCourseStrand)) {
            $this->Flash->success(__('The report template course strand has been deleted.'));
        } else {
            $this->Flash->error(__('The report template course strand could not be deleted. Please, try again.'));
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
