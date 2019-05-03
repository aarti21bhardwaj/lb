<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Core\Configure;

/**
 * Teachers Controller
 *
 *
 * @method \App\Model\Entity\Teacher[] paginate($object = null, array $settings = [])
 */
class TeachersController extends AppController
{

   public function initialize()
    {
        parent::initialize();
        // $this->Auth->allow(['index']);
    }

    // public function courseSections(){
        
    //     $this->viewBuilder()->layout('student_reports');
    //     $userId = $this->Auth->user('id');
    //     $this->loadModel('Users');
    //     $user = $this->Users->findById($userId)->contain(['Roles'])->first();
    //     $courses = [];
    //     $this->loadModel('Courses');
    //     if($user->role->label == "Teacher"){
    //     $courses = $this->Courses->find()
    //                              ->contain(['Sections.Terms.ReportingPeriods.ReportTemplates'])
    //                              ->matching('CampusCourses.CampusCourseTeachers',function($q)
    //                                         {
    //                                            return $q->where([
    //                                                               'teacher_id' =>$this->Auth->user('id')
    //                                                             ]);
    //                                         }
    //                                        )
    //                              ->toArray();
    //     }

    //     $this->set('courses', $courses);
    //     $this->set('_serialize', ['courses']);
    // }

    // public function sectionStudents($sectionId = null){
    //     $this->loadModel('Sections');
    //     $sections = $this->Sections->find()
    //                                 ->where(['id' => $sectionId])
    //                                 ->contain(['SectionStudents.Students'])
    //                                 ->first();
    //     $courseId = $sections->course_id;
    //     $this->loadModel('ReportTemplates');
    //     $data = $this->ReportTemplates->find()
    //                                 ->contain(['ReportingPeriods.Terms.Sections' => function($q)use($sectionId){
    //                                     return $q->contain(['Courses'])->where(['Sections.id' => $sectionId]);
    //                                 }])
    //                                  ->matching('ReportingPeriods.Terms.Sections' ,function($q) use($sectionId,$courseId){
    //                                     return $q->where(['Sections.id' => $sectionId,'course_id' => $courseId]);
    //                                  })
    //                                  ->first();

    //     $this->loadModel('Scales');
    //     $impactScaleValues = $this->Scales->findById($data->impact_scale)->contain(['ScaleValues'])->first();
    //     $standardScaleValues = $this->Scales->findById($data->academic_scale)->contain(['ScaleValues'])->first();
    //     if($data){

    //         $gradeId = $data->reporting_period->term->sections[0]->course->grade_id;

    //         $this->loadModel('ReportSettings');
    //         $settings = $this->ReportSettings->find()
    //                                          ->where(['report_template_id' => $data->id, 'course_id' => $courseId,'grade_id' => $gradeId])
    //                                          ->first();
    //     }else{
    //         $settings = null;
    //     }


    //     $this->viewBuilder()->layout('section_students');
    //     $this->set('sections', $sections);
    //     $this->set('_serialize', ['sections']);
    // } 

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
      $this->viewBuilder()->layout('ng-ui');
    }

    /**
     * View method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => []
        ]);

        $this->set('teacher', $teacher);
        $this->set('_serialize', ['teacher']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $teacher = $this->Teachers->newEntity();
        if ($this->request->is('post')) {
            $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
        }
        $this->set(compact('teacher'));
        $this->set('_serialize', ['teacher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
        }
        $this->set(compact('teacher'));
        $this->set('_serialize', ['teacher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $teacher = $this->Teachers->get($id);
        if ($this->Teachers->delete($teacher)) {
            $this->Flash->success(__('The teacher has been deleted.'));
        } else {
            $this->Flash->error(__('The teacher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function listStudents(){
        $sectionId = $this->request->query['id'];
        $students = $this->Sections->SectionStudents->find()
                                                    ->where(['section_id' => $sectionId])
                                                    ->contain(['Students'])
                                                    ->all()
                                                    ->extract('student')
                                                    ->toArray();
                $this->set(compact('students'));
        $this->set('_serialize', ['students']);
    }

    public function isAuthorized($user)
    {   
        $session = $this->request->session();
        $superAdminUser = $session->read('superAdminUser');

        if($this->request->params['action'] == 'index'){
            return true;
        }

        if($superAdminUser && ($this->request->params['action'] == 'index' || $this->request->params['action'] == 'view' || $this->request->params['action'] == 'edit' || $this->request->params['action'] == 'add' || $this->request->params['action'] == 'delete')){
          return true;
        }
        
        if (isset($user['role']) && $user['role']->name === 'admin'){
            return true;
        }else{
            return false;
        }

        return parent::isAuthorized($user);
    }
}
