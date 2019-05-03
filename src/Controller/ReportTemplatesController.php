<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;

/**
 * ReportTemplates Controller
 *
 * @property \App\Model\Table\ReportTemplatesTable $ReportTemplates
 *
 * @method \App\Model\Entity\ReportTemplate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportTemplatesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    private $sectionStudentsArray;

    public function configureEmailSettings($reportTemplateId = null){

        $this->loadModel('ReportTemplateEmailSettings');
        
        $emailSettings = $this->ReportTemplateEmailSettings->findByReportTemplateId($reportTemplateId)->first();
        if(!$emailSettings){
            $emailSettings = $this->ReportTemplateEmailSettings->newEntity();
        }        
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->data['report_template_id'] = $reportTemplateId;
            $emailSettings = $this->ReportTemplateEmailSettings->patchEntity($emailSettings,$this->request->data);

            if ($this->ReportTemplateEmailSettings->save($emailSettings)) {
                $this->Flash->success(__('Email has been configured successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Email Settings could not be saved. Please, try again.'));

        }
        $this->set(compact('emailSettings'));
    }

    public function sendEmails($id = null){
        
        $reportTemplate = $this->ReportTemplates->findById($id)
                                                ->contain(['ReportSettings','ReportingPeriods.Terms','ReportTemplateEmailSettings'])
                                                ->first();
        $students = [];
        if(!empty($reportTemplate->report_settings)){

            $reportSettingsWithCourses = new Collection($reportTemplate->report_settings);
            $selectedCourses = $reportSettingsWithCourses->extract('course_id')->toArray();

            $this->loadModel('Sections');
            // $sections = $this->Sections->find()
            //                               ->contain(['SectionStudents.Students.StudentGuardians.Guardians'])
            //                               ->where([
            //                                         'term_id' => $reportTemplate->reporting_period->term_id,
            //                                       ])
            //                               ->all()
            //                               ->toArray();

            $sections = $this->Sections->find()
                                        ->where([
                                                'term_id' => $reportTemplate->reporting_period->term_id,
						'course_id IN' => $selectedCourses
                                              ])
                                        ->contain(['SectionStudents.Students' => function($q){
                                            return $q->where(['is_active' => 1])
                                                     ->contain(['StudentGuardians.Guardians']);
                                          }])
                                        ->all()
                                        ->toArray();
            
            $students = (new Collection($sections))->extract('section_students.{*}.student.id')->toArray();
	        $students = array_values(array_unique($students));
        }

        $this->set(compact('students','reportTemplate'));
        $this->set('_serialize', ['students','reportTemplate']);

    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportingPeriods']
        ];
        $reportTemplates = $this->paginate($this->ReportTemplates);
        $this->loadModel('Scales');
        $scales = $this->Scales->find()->all()->combine('id', 'name')->toArray();

        $this->set('scales', $scales);
        $this->set(compact('reportTemplates'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Template id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportTemplate = $this->ReportTemplates->get($id, [
            'contain' => ['ReportingPeriods', 'ReportSettings', 'ReportTemplateGrades', 'Reports']
        ]);
        $this->loadModel('Scales');
        $scales = $this->Scales->find()->all()->combine('id', 'name')->toArray();

        $this->set('scale', $scales);
        $this->set('reportTemplate', $reportTemplate);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        if ($this->request->is('post')) {
            
            $reqData = $this->request->getData();
            foreach ($reqData['grade_id'] as $gradeId) {
                $reqData['report_template_grades'][] = ['grade_id' => $gradeId];
            }
            $reportTemplate = $this->ReportTemplates->newEntity($reqData, ['associated' => ['ReportTemplateGrades']]);
            // $reportTemplate = $this->ReportTemplates->patchEntity($reportTemplate, $this->request->getData());
            if ($this->ReportTemplates->save($reportTemplate)) {
                $this->Flash->success(__('The report template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template could not be saved. Please, try again.'));
        }
        $this->loadModel('Scales');
        $scales = $this->Scales->find()->all()->combine('id', 'name')->toArray();
        $grades = $this->ReportTemplates->ReportTemplateGrades->Grades->find('list', ['limit' => 200]);
        $reportingPeriods = $this->ReportTemplates->ReportingPeriods->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplate', 'reportingPeriods', 'scales', 'grades'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Template id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportTemplate = $this->ReportTemplates->get($id, [
            'contain' => ['ReportTemplateGrades']
        ]);
        
        $reportTemplateGradeIds = [];
        foreach ($reportTemplate->report_template_grades as $value) {
            # code...
            $reportTemplateGradeIds[] = $value->grade_id;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportTemplate = $this->ReportTemplates->patchEntity($reportTemplate, $this->request->getData());
            if ($this->ReportTemplates->save($reportTemplate)) {
                $this->Flash->success(__('The report template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report template could not be saved. Please, try again.'));
        }
        $reportingPeriods = $this->ReportTemplates->ReportingPeriods->find('list', ['limit' => 200]);
        $this->loadModel('Scales');
        $scales = $this->Scales->find()->all()->combine('id', 'name')->toArray();
        $grades = $this->ReportTemplates->ReportTemplateGrades->Grades->find('list', ['limit' => 200]);
        $this->set(compact('reportTemplate', 'reportingPeriods', 'reportTemplateGradeIds', 'grades'));
        $this->set('scales', $scales);
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Template id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportTemplate = $this->ReportTemplates->get($id);
        if ($this->ReportTemplates->delete($reportTemplate)) {
            $this->Flash->success(__('The report template has been deleted.'));
        } else {
            $this->Flash->error(__('The report template could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function studentReports($id = null){
        // pr('here'); die;
        $this->loadModel('Users');
        
        $this->loadModel('Campuses');
        $campus = $this->Campuses->find()->matching('Divisions.Terms.ReportingPeriods.ReportTemplates', function($q) use($id){
            return $q->where(['ReportTemplates.id' => $id]);
        })->first();

        if(!$campus){
            $this->Flash->error('Campus not found.');
            $this->redirect($this->referer());
        }

        $reportTemplate = $this->ReportTemplates->findById($id)
                                                ->contain(['ReportSettings.Courses.CampusCourses' => function($q) use($campus){
                                                  return $q->where(['CampusCourses.campus_id' => $campus->id]);
                                                },'ReportingPeriods.Terms','ReportTemplateGrades'])
                                                ->matching('ReportSettings.Courses.CampusCourses', function($q) use($campus){
                                                  return $q->where(['CampusCourses.campus_id' => $campus->id]);
                                                })
                                                ->first();

        // pr($reportTemplate);die;
        $data = [];
        $studentCourses = [];
        $studentGuardian = [];
        if(!empty($reportTemplate)){

            $reportSettingsWithCourses = new Collection($reportTemplate->report_settings);
            $selectedCourses = $reportSettingsWithCourses->extract('course_id')->toArray();
            // pr($selectedCourses); die;

            $this->loadModel('Sections');

            $sectionCourses = $this->Sections->Courses->find()
                                                      ->where(['Courses.id IN' => $selectedCourses])
                                                      ->indexBy('id')
                                                      ->toArray();
            $sectionData = $this->Sections->find()
                                          ->contain(['SectionStudents.Students' => function($q){
                                                                                      return $q->where(['Students.is_active' => 1])
                                                                                       ->contain(['StudentGuardians']);
                                                   }, 'Courses'])
                                          ->where([
                                                    'Sections.term_id' => $reportTemplate->reporting_period->term_id,
                                                    'Sections.course_id IN' => $selectedCourses,
                                                 ])
                                          ->all();

          if(!empty($sectionData->toArray())){
            // pr($sectionData->toArray()); die;
            $sectionIds = $sectionData->extract('id')->toArray();

            $this->loadModel('SectionStudents');
            $sectionStudents = $this->SectionStudents->find()
                                                     ->where(['section_id IN' => $sectionIds])
                                                     ->contain(['Students' =>  function($q){
                                                                return $q->where(['Students.is_active' => 1])
                                                                         ->contain(['StudentGuardians']);
                                                            }])
                                                     ->groupBy('section_id')
                                                     ->toArray();


            $sectionData = $sectionData->map(function ($value,$key) use($sectionCourses, $sectionStudents){
                                                        // $value->course = $sectionCourses[$value->course_id];
                                                        if(isset($sectionStudents[$value->id])){
                                                          $value->section_students = $sectionStudents[$value->id];
                                                        }else{
                                                          $value->section_students = [];
                                                        }
                                                        return $value;
                                                  });
            $sectionArray = $sectionData
                            ->combine('id','course')
                            //->groupBy('student_id')
                            ->toArray();
            $studentReportData = $this->ReportTemplates->ReportTemplateCourseScores
                                                       ->find()
                                                       ->where(['report_template_id' => $id])
                                                       ->all()
                                                       ->groupBy('student_id')
                                                       ->map(function($value, $key){
                                                         return (new Collection($value))->combine('course_id', 'is_completed')->toArray();
                                                       })
                                                       ->toArray();

            $sectionStudentsArray = $sectionData->extract('section_students.{*}')
                                                ->sortBy('student.last_name',SORT_ASC,SORT_STRING)
                                                ->map(function ($value,$key) use($sectionArray){
                                                    $value->course_id = $sectionArray[$value->section_id]->id;
                                                    $value->course = $sectionArray[$value->section_id]->toArray();
                                                    return $value;
                                                })
                                                ->groupBy('student_id')
                                                ->map(function($value, $key) use($studentReportData){
                                                    return (new Collection($value))->map(function($value, $key)use($studentReportData){
                                                        $value->course['is_completed'] = null;
                                                        if(isset($studentReportData[$value->student_id][$value->course_id])){
                                                           $value->course['is_completed'] = $studentReportData[$value->student_id][$value->course_id];
                                                        }
							return $value;

                                                    })->toArray();
                                                })
                                                ->toArray();

            // pr($sectionStudentsArray);die;
            $this->sectionStudentsArray = $sectionStudentsArray;
            // pr($this->sectionStudentsArray); die;
            unset($sectionStudentsArray);

            foreach ($this->sectionStudentsArray as $key => $reportData){
                foreach ($reportData as $value) {
                    // pr($value); 
                     $studentCourses[$key][] = [
                                            'id' => $value->course['id'],
                                            'name' => $value->course['name'],
                                            // 'section_id' => $this->findSectionFromCourseAndStudent(
                                            //     $value->course->id,
                                            //     $value->student_id
                                            //     ),
                                            'section_id' => $value->section_id,
                                            'status' => $value->course['is_completed']
                                       ];


                }
    //            $this->sectionStudentsArray = null; // freeing memory

                if(!empty($value->student->student_guardians)){
                    // pr($value->student->student_guardians); continue;
                    foreach ($value->student->student_guardians as $guardian){
                        $userGuardian = $this->Users->find()->where(['id' => $guardian->guardian_id])->first();
                        $studentGuardian[$key][] = [
                                                    'id' => $userGuardian->id,
                                                    'first_name' => $userGuardian->first_name,
                                                    'last_name' => $userGuardian->last_name,
                                                    'name' => $userGuardian->first_name.' '.$userGuardian->last_name,
                                                    'email' => $userGuardian->email
                                                  ];
                    }
                }else{
                    $studentGuardian[$key][] = [];
                }
                
                $data[] = [
                            'id' => $value->student_id,
                            'first_name' => $value->student->first_name,
                            'last_name' => $value->student->last_name,
                            'name' => $value->student->full_name,
                            'guardian' => $studentGuardian[$value->student_id],
                            'courses' => array_values($studentCourses[$value->student_id])

                          ];
                }
            }                                     

        }

        $this->set('reportTemplate', $reportTemplate);
        $this->set('studentReports', $data);
        $this->set('reportTemplateId', $id);
    }

    private function findSectionFromCourseAndStudent($course_id,$student_id)
    {
        foreach ($this->sectionStudentsArray[$student_id] as $key => $value) {            
            if($value->course_id == $course_id) {

            return $value->section_id;
            }
            return false;
        }
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
