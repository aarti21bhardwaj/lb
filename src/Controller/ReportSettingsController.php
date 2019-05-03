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
 * ReportSettings Controller
 *
 * @property \App\Model\Table\ReportSettingsTable $ReportSettings
 *
 * @method \App\Model\Entity\ReportSetting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportSettingsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ReportTemplates', 'Grades', 'Courses']
        ];
        $reportSettings = $this->paginate($this->ReportSettings);

        $this->set(compact('reportSettings'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Setting id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportSetting = $this->ReportSettings->get($id, [
            'contain' => ['ReportTemplates', 'Grades', 'Courses']
        ]);

        $this->set('reportSetting', $reportSetting);
    }

    /**
     * FetchData method
     *
     * @param string|null $courseId Course id.
     * @param string|null $reportTemplateId Report Template id.
     * @param string|null $gradeId Grade id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function fetchData(){

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request is not post');
            
        }
        $data = $this->request->data;
        if(!$data){
            throw new BadRequestException("Request data is missing", 1);    
        }

        $courseId = $data['course_id'];
        $gradeId = $data['grade_id'];
        $reportTemplateId = $data['report_template_id'];

        $reportSetting = $this->ReportSettings->find()
                                               ->where(['report_template_id' => $reportTemplateId, 'course_id' => $courseId, 'grade_id' => $gradeId])
                                               // ->contain(['ReportTemplates.ReportTemplateCourseStrands'])
                                               ->first();
        if(!$reportSetting){
            throw new NotFoundException("No such record found", 1);
        }

        $this->set('response', $reportSetting);
        $this->set('_serialize', ['response']);
    }



    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request is not post');
            
        }
        $data = $this->request->data;
        // pr($data); die;
        if(!$data){
            throw new BadRequestException("Request data is missing", 1);
            
        }

        $reportSettingData = $this->ReportSettings->find()
                                                ->where(['report_template_id'=> $data['report_template_id'] ,'grade_id' => $data['grade_id'], 'course_id' => $data['course_id']])
                                                ->first();
        
        // pr($reportSettingData); die;
        
        if(!$reportSettingData){
            $reportSetting = $this->ReportSettings->newEntity();
            $reportSetting = $this->ReportSettings->patchEntity($reportSetting, $this->request->getData());

            $reportCourseStrand = $this->_saveCourseStrands($reportSetting);

            if($reportSetting->course_status == 1){
                $reports = $this->_saveCourseInReports($reportSetting);
            }
        }else{
            $reportSetting = $this->ReportSettings->patchEntity($reportSettingData, $data);
            // pr($reportSetting); die;
            if($reportSetting->course_status == 0){
                $this->_removeCourseFromReports($reportSetting);
            }else{
                $reports = $this->_saveCourseInReports($reportSetting);   
            }
        }

        if (!$this->ReportSettings->save($reportSetting)) {
             throw new InternalErrorException('Something went wrong');
        }

        if(!empty($reportCourseStrand) && isset($reportCourseStrand)){
            $this->ReportSettings->Courses->ReportTemplateCourseStrands->saveMany($reportCourseStrand);
        }

        if(isset($reports)){
            $this->Reports->save($reports);
        }

        $this->set('response', $reportSetting);
        $this->set('_serialize', ['response']);

    }

    private function _saveCourseStrands($entity){

         $courseStrands = $this->ReportSettings->Courses->CourseStrands->find()
                                                       ->where(['grade_id' => $entity->grade_id, 'course_id' => $entity->course_id])
                                                       ->all()
                                                       ->toArray();

            

            $data = [];
            foreach ($courseStrands as $value) {
                $data[] = [
                            'report_template_id' => $entity->report_template_id,
                            'strand_id' => $value->strand_id,
                            'grade_id' => $entity->grade_id,
                            'course_id' => $entity->course_id
                         ];
            }


            $reportCourseStrand = $this->ReportSettings->Courses->ReportTemplateCourseStrands->newEntities($data);
            return $reportCourseStrand;;
    }

    private function _saveCourseInReports($entity){
        $this->loadModel('Reports');

        $report = $this->Reports->newEntity();
        $reportsData = $this->Reports->find()
                                     ->where(['report_template_id' => $entity->report_template_id, 'grade_id' => $entity->grade_id])
                                     ->order(['sort_order' => 'DESC'])
                                     ->all()
                                     ->toArray();
        
        if(!$reportsData){
            $data = [
                        'report_template_id' => $entity->report_template_id,
                        'grade_id' => $entity->grade_id,
                        'course_id' => $entity->course_id,
                        'sort_order' => 1,
                    ];
        }else{
            $data = [
                        'report_template_id' => $entity->report_template_id,
                        'grade_id' => $entity->grade_id,
                        'course_id' => $entity->course_id,
                        'sort_order' => $reportsData[0]->sort_order + 1,
                    ];
        }
        
        $reports = $this->Reports->patchEntity($report, $data);
        // pr($reports); die;
        return $reports;
    }

    private function _removeCourseFromReports($entity){
        $this->loadModel('Reports');

        $reportsData = $this->Reports->find()
                                     ->where(['report_template_id' => $entity->report_template_id, 'course_id' => $entity->course_id])
                                     ->first();

        if(!$reportsData){
            $this->Flash->error(__('No report set for this course.'));
        }

        if (!$this->Reports->delete($reportsData)) {
            $this->Flash->error(__('The report setting could not be deleted. Please, try again.'));
        } else {
            $this->Flash->success(__('The report setting has been removed for course '.$entity->course_id));
        }

    }

    /**
     * Edit method
     *
     * @param string|null $id Report Setting id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($courseId = null)
    {
        if(!$this->request->is('put')){
            throw new MethodNotAllowedException('Request is not put');
            
        }
        $data = $this->request->data;
        if(!$data){
            throw new BadRequestException("Request data is missing", 1);
            
        }

        $reportSetting = $this->ReportSettings->findByCourseId($courseId)
                                              ->where(['report_template_id' => $data['report_template_id'], 'grade_id' => $data['grade_id']])
                                              ->first();
    
        $reportSetting = $this->ReportSettings->patchEntity($reportSetting, $data);

        if (!$this->ReportSettings->save($reportSetting)) {
             throw new InternalErrorException('Something went wrong');
        }

        $this->set('response', $reportSetting);
        $this->set('_serialize', ['response']);
        
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportSetting = $this->ReportSettings->get($id);
        if ($this->ReportSettings->delete($reportSetting)) {
            $this->Flash->success(__('The report setting has been deleted.'));
        } else {
            $this->Flash->error(__('The report setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function settings($reportTemplateId = null){
        // $reportSetting = $this->ReportSettings->newEntity();
        if(!$reportTemplateId){
            throw new BadRequestException('Missing report_template_id', 1);
            
        }

        $this->loadModel('ReportTemplateGrades');
        $reportTemplateGrades = $this->ReportTemplateGrades->find()
                                             ->contain(['Grades'])
                                             ->where(['report_template_id' => $reportTemplateId])
                                             ->all();

        $gradeIds = $reportTemplateGrades->extract('grade_id')->toArray();
        $gradeIds = array_unique($gradeIds);
        // pr($gradeIds); die;
        $grades = $reportTemplateGrades->extract('grade')
                                       ->map(function($value, $key){
                                            $value->parent_id = NULL;
                                            $value->id = 'g'.$value->id;
                                            $value->text = $value->name;
                                            return $value;
                                      })
                                      ->toArray(); 
        $this->loadModel('Campuses');
        $campus = $this->Campuses->find()->matching('Divisions.Terms.ReportingPeriods.ReportTemplates', function($q) use($reportTemplateId){
            return $q->where(['ReportTemplates.id' => $reportTemplateId]);
        })->first();

        if(!$campus){
            $this->Flash->error('Campus not found.');
            $this->redirect($this->referer());
        }
        // pr($gradeIds); die;
        $this->loadModel('Courses');
        $courses = $this->Courses->find()->where(['grade_id IN' => $gradeIds])
                                         ->matching('Sections.Terms', function($q){
                                            return $q->where(['Terms.is_active' => 1]);
                                         })
                                         ->matching('CampusCourses', function($q) use ($campus){
                                            return $q->where(['CampusCourses.campus_id' => $campus->id]);
                                         })
                                         ->all()
                                         ->indexBy('id')
                                         ->map(function($value, $key) {
                                           $value->parent_id = 'g'.$value->grade_id;
                                           $value->text = $value->name;
                                           return $value;
                                         })
                                         ->toArray();
        $courses = array_values($courses);
        // pr(count(array_unique($courses))); die;
        if(!$courses){
            $this->Flash->error('No courses found in the term belonging to this reporting template');
            $this->redirect($this->referer());
        }
        foreach ($grades as $value) {
            $courses[] = $value;            
        }

        $data = (new Collection($courses))->nest('id','parent_id')
                                          ->reject(function($value, $key){
                                                                return in_array($value->children, [null, false, '', []]);
                                            })
                                          ->toArray();

        $this->loadModel('Grades');
        $grades = $this->Grades->find()->all()->combine('id', 'name')->toArray();
        $this->loadModel('Standards');
        $curriculums = $this->Standards->Strands->LearningAreas->Curriculums->find('list');
        $learningArea = $this->Standards->Strands->LearningAreas->find()->all();
        $learningAreas = $learningArea->combine('id', 'name')->toArray();
        $curriculumLearningAreas = $learningArea->groupBy('curriculum_id')->toArray();
        $this->loadModel('ReportTemplatePages');
        $reportTemplatePages = $this->ReportTemplatePages->find()->where(['report_template_type_id' => 1])
                                                                 ->all()
                                                                 ->combine('id', 'title')
                                                                 ->toArray();

        $this->set('response', $data);
        $this->set('reportTemplateId', $reportTemplateId);
        $this->set(compact('curriculums', 'learningAreas', 'curriculumLearningAreas', 'grades', 'reportTemplatePages'));
        $this->set('_serialize', ['response', 'reportTemplateId']);
    }

    // function get standards and impacts for the specific report templates
    public function standardsAndImpacts(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request method is not get');
        }

        $data = $this->request->data;
        if(!$data){
            throw new BadRequestException("Missing request data", 1);
            
        }

        $courseId = $data['course_id'];
        $reportTemplateId = $data['report_template_id'];
        $gradeId = $data['grade_id'];
        // pr($gradeId); die;

        $this->loadModel('ReportTemplateStrands');
        $reportStrands = $this->ReportTemplateStrands->find()
                                                     ->where(['report_template_id' => $reportTemplateId, 'course_id' => $courseId])
                                                     ->all()
                                                     ->map(function($value, $key){
                                                        $value->newStrandId = 's'.$value->strand_id;
                                                        return $value;
                                                     })
                                                     ->groupBy('newStrandId')
                                                     ->toArray();

        // pr($reportStrands); die;
        $this->loadModel('ReportTemplateStandards');
        $reportStandards = $this->ReportTemplateStandards->find()
                                                         ->where(['report_template_id' => $reportTemplateId, 'course_id' => $courseId])
                                                         ->all()
                                                         ->groupBy('standard_id')
                                                         ->toArray();


        $this->loadModel('ReportTemplateImpacts');
        $reportImpacts = $this->ReportTemplateImpacts->find()
                                                       ->where(['report_template_id' => $reportTemplateId, 'course_id' => $courseId])
                                                       ->all()
                                                       ->groupBy('impact_id')
                                                       ->toArray();

        $this->loadModel('ReportTemplateCourseStrands');
        $courseStrands = $this->ReportTemplateCourseStrands->findByCourseId($courseId)
                                                           ->where(['report_template_id' => $reportTemplateId])
                                                           ->contain(['Strands'])
                                                           ->all()
                                                           ->toArray();
        // pr($courseStrands); die;
$standards = []; 
       if($courseStrands){
 
        $strands = (new Collection($courseStrands))->extract('strand')
                                                   ->map(function($value, $key) use($reportStrands){
                                                        $value->parent_id = NULL;
                                                        $value->id = 's'.$value->id;
                                                        $value->text = $value->name;
                                                        if(isset($reportStrands[$value->id])){
                                                           $value->state = [
                                                                                "checked" => true
                                                                            ]; 
                                                        }
                                                        return $value;
                                                   })
                                                   ->toArray();
        
        // if(!$strands){
        //     throw new BadRequestException('No strands set in this course');
        // }
        $strands = array_unique($strands);

        $strandIds = (new Collection($courseStrands))->extract('strand_id')->toArray();
        // pr($strandIds); die;
        $strandIds = array_unique($strandIds);

        $gradeIds = (new Collection($courseStrands))->extract('grade_id')->toArray();


        $gradeStandardIds = $this->ReportTemplateCourseStrands->Strands->Standards
                                                                     ->StandardGrades
                                                                     ->find()
                                                                     ->where(['grade_id IN' => $gradeIds])
                                                                     ->extract('standard_id')
                                                                     ->toArray();


        $standards = $this->ReportTemplateCourseStrands->Strands->Standards
                                                                ->find()
                                                                ->where(['id IN' => $gradeStandardIds,'strand_id IN' => $strandIds])
                                                                // ->matching('StandardGrades' ,function($q) use($gradeIds){
                                                                //     return $q->where(['StandardGrades.grade_id IN' => $gradeIds]); 
                                                                // })
                                                             ->map(function($value, $key) use($reportStandards){
                                                                if($value->parent_id == NULL) {
                                                                    $value->parent_id = "s".$value->strand_id;//
                                                                }

                                                                if(isset($reportStandards[$value->id])){
                                                                   $value->state = [
                                                                                "checked" => true
                                                                            ]; 
                                                                }

                                                                $value->text = $value->name; 
                                                                return $value;
                                                             })
                                                             ->toArray();

        foreach ($strands as $value) {
            $standards[] = $value;
        }

        $standards = (new Collection($standards))->nest('id','parent_id')
                                                 ->toArray();
}

        $this->loadModel('Impacts');

        $impactGrades = $this->Impacts->GradeImpacts->find()->where(['grade_id' => $gradeId]);
$impactIds = $impactGrades->extract('impact_id')->toArray();
        if(empty($impactIds)){
            $impactsData = [
                                'status' => false,
                                'message' => "No impacts set for this grade"
                           ];
        }else{
            $impactCategoriesData = $this->Impacts->ImpactCategories->find()->all();

            $impactCategoriesIds = $impactCategoriesData->extract('id')->toArray();

            $impactCategories = $impactCategoriesData->map(function($value, $key){
                                                            $value->parent_id = NULL;
                                                            $value->id = 'impact_category'.$value->id;
                                                            $value->text = $value->name;
                                                            $value->a_attr = ["class" => "no_checkbox"];
                                                            return $value;
                                                        })
                                                     ->toArray();
            $impacts = $this->Impacts->find()->where(['impact_category_id IN' => $impactCategoriesIds, 'id IN'=> $impactIds ])
                                             ->map(function($value, $key) use($reportImpacts){
                                                if($value->parent_id == NULL){
                                                    $value->parent_id = 'impact_category'.$value->impact_category_id;
                                                }
                                                if(isset($reportImpacts[$value->id])){
                                                   $value->state = [
                                                                        "checked" => true
                                                                    ];  
                                                }
                                                $value->text = $value->name; 
                                                return $value;
                                             })
                                             ->toArray();


            // if(!$impacts){
            //     throw new NotFoundException("Impacts not found", 1);
                
            // }

            foreach ($impactCategories as $key => $value) {
                $impacts[] = $value;
            }

            $impactsData = (new Collection($impacts))->nest('id', 'parent_id')
                                                     ->reject(function($value, $key){
                                                        return in_array($value->children, [null, false, '', []]);
                                                      })
                                                     ->toArray();
            
            $impactsData = array_values($impactsData);
        }

        // pr($standards);
        // pr($impactsData); die;
        $this->set('standards', $standards);
        $this->set('impacts', $impactsData);
        $this->set('_serialize', ['standards', 'impacts']);

    }

    public function setData(){
        $this->viewBuilder()->layout('default-frame');
        $reportTemplateId = $this->request->query('report_template_id');
        $courseId = $this->request->query('course_id');
        $gradeId = $this->request->query('grade_id');

        $this->loadModel('Grades');
        $grades = $this->Grades->find()->all()->combine('id', 'name')->toArray();

        $this->loadModel('Curriculums');
        $curriculums = $this->Curriculums->find()->all()->combine('id', 'name')->toArray();

        $learningArea = $this->Curriculums->LearningAreas->find()->all();
        $learningAreas = $learningArea->combine('id', 'name')->toArray();

        $curriculumLearningAreas = $learningArea->groupBy('curriculum_id')->toArray();


        $this->set('reportTemplateId', $reportTemplateId);
        $this->set('courseId', $courseId);
        $this->set('gradeId', $gradeId);
        $this->set(compact('curriculums', 'learningAreas', 'curriculumLearningAreas', 'grades'));
        $this->set('_serialize', ['reportTemplateId', 'courseId', 'gradeId']);
    }


     // function to get template settings data 
   public function report(){
    if(!$this->request->is('get')){
        throw new MethodNotAllowedException("Request method is not get", 1);
    }

    $reportTemplateId = $this->request->query('report_template_id');
    $gradeId = trim($this->request->query('grade_id'), 'g');

    $this->loadModel('Campuses');
    $campus = $this->Campuses->find()->matching('Divisions.Terms.ReportingPeriods.ReportTemplates', function($q) use($reportTemplateId){
        return $q->where(['ReportTemplates.id' => $reportTemplateId]);
    })->first();

    if(!$campus){
        $this->Flash->error('Campus not found.');
        $this->redirect($this->referer());
    }
    // pr($gradeIds); die;
    $this->loadModel('Courses');
    $courseIds = $this->Courses->find()->where(['grade_id' => $gradeId])
                                     ->matching('Sections.Terms', function($q){
                                        return $q->where(['Terms.is_active' => 1]);
                                     })
                                     ->matching('CampusCourses', function($q) use ($campus){
                                        return $q->where(['CampusCourses.campus_id' => $campus->id]);
                                     })
                                     ->extract('id')
                                     ->toArray();

    // pr($gradeId); die;
    // $gradeId = $gradeId[0];

    $this->loadModel('ReportPages');
    $reportPages = $this->ReportPages->find()->all()->combine('id', 'title')->toArray();

    $this->loadModel('Reports');
    $coverPages = $this->Reports->find()
                             ->where(['Reports.report_template_id' => $reportTemplateId, 'Reports.grade_id' => $gradeId, 'course_id IS NULL'])
                             ->contain(['Courses','ReportPages'])->toArray();

    $reports = $this->Reports->find()
                             ->where(['Reports.report_template_id' => $reportTemplateId, 'Reports.grade_id' => $gradeId])
                             ->contain(['Courses' => function($q) use($courseIds){
                                return $q->where(['Courses.id IN' => $courseIds]);
                             }, 'ReportPages'])
                             ->matching('Courses', function($q) use($courseIds){
                                return $q->where(['Courses.id IN' => $courseIds]);
                             })->toArray();

    if($coverPages){
        $reports = array_merge($reports, $coverPages);
    }

    $reports = (new Collection($reports))
                             ->map(function($value, $key){
                                if(!empty($value->course)){
                                    $id = 'c'.$value->course->id;
                                    $text = $value->course->name;
                                    $sortOrder = $value->sort_order;
                                    $courseId = $value->course->id;
                                    $objectName = 'course';
                                }
                                if(!empty($value->report_page)){
                                    $id = 'p'.$value->report_page->id;
                                    $text = $value->report_page->title;
                                    $sortOrder = $value->sort_order;
                                    $reportPageId = $value->report_page->id;
                                    $objectName = 'reportPage';
                                }
                                $data = [
                                            'id' => $id,
                                            'text' => $text,
                                            'parent_id' => NULL,
                                            'sort_order' => $sortOrder,
                                            'type' => 'file',
                                            'course_id' => isset($courseId) ? $courseId : NULL,
                                            'report_page_id' => isset($reportPageId) ? $reportPageId : NULL,
                                            'object_name' => $objectName

                                        ];
                                return $data;

                             })                             
                             ->sortBy('sort_order', SORT_ASC)
                             ->toArray();


    // pr($reports); die;
    if(empty($reports)){
        $this->Flash->error(__('Please enable courses for this grade to proceed further.'));

        return $this->redirect(['action' => 'settings', $reportTemplateId]);
    }
// pr($reports); die;
    $this->set('reportTemplateId', $reportTemplateId);
    $this->set('gradeId', $gradeId);
    $this->set('reportPages', $reportPages);
    $this->set('response',array_values($reports));
    $this->set('_serialize', ['response']);

   }
}
