<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Log\Log;

class PdfController extends AppController
{

    public function initialize(){

        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->Auth->allow(['tubdTemplate','pypTemplate','transferTemplate','ubdTemplate','report']);

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    public function tubdTemplate($unitId = null)
    {

        $this->viewBuilder()->layout('unit');
        
        // $this->loadModel('Schools');
        // $school = $this->Schools->find()
        //                          ->matching('Campuses.Courses.Units',function($q) use($unitId){
        //                             return $q->where(['unit_id' => $unitId]);
        //                          })
        //                          ->first();

        $this->loadModel('Units');
        $unitData = $this->Units->findById($unitId)
                                ->contain(['UnitStandards'])
                                ->first();


        $unitStandardIds = [];                        
        if(!empty($unitData->unit_standards)){
            $unitStandardIds = (new Collection($unitData->unit_standards))->extract('standard_id')->toArray();
            $unit = $this->Units->findById($unitId)
            ->contain(['UnitStrands.Strands.Standards' => function($q) use($unitStandardIds){
                return $q->where(['Standards.id IN' => $unitStandardIds]);
            },'UnitImpacts.Impacts.ImpactCategories','UnitCourses.Courses.Sections.SectionTeachers.Teachers','UnitResources','UnitReflections.Users','UnitReflections.ReflectionCategories','Assessments' => function($q) {
                return $q->contain(['Standards','Strands','AssessmentImpacts.Impacts.ImpactCategories','AssessmentSpecificContents']);
            }])
            ->first();

        }else{
            $unit = $this->Units->findById($unitId)
            ->contain(['UnitStrands.Strands','UnitImpacts.Impacts.ImpactCategories','UnitCourses.Courses.Sections.SectionTeachers.Teachers','UnitResources','UnitReflections.Users','UnitReflections.ReflectionCategories','Assessments' => function($q) {
                return $q->contain(['Standards','Strands','AssessmentImpacts.Impacts.ImpactCategories']);
            }])
            ->first();
        }

        if($unit){

            // if(Configure::read('CakePdf')){
            //     $this->RequestHandler->renderAs($this, 'pdf', ['attachment' => 'abc']);
            //     $this->viewBuilder()->layout('unit')->options([
            //         'pdfConfig' => [
                        
            //             'filename' => $unit->name.'_'.$unitId,
            //         ]
            //     ]);
            // }

            $teachers = [];
            $courses = [];
            if(!empty($unit->unit_courses)){

                $courses = (new Collection($unit->unit_courses))->extract('course.name')->toArray();

                $teachers = (new Collection($unit->unit_courses))->extract('course.sections.{*}.section_teachers.{*}.teacher.full_name')->toArray();

                $teachers = array_unique($teachers);
            }

            if(!empty($unit->assessments)){
                $assessment_impact_categories = [];
                foreach ($unit->assessments as $assessment) {
                    $impact_categories =  (new Collection($assessment->assessment_impacts))->groupBy('impact.impact_category.name')->toArray();
                    $assessment->assessment_impacts = $impact_categories;
                }

                $unit->assessments = (new Collection($unit->assessments))->map(function($assessment, $key){

                    $standards = (new Collection($assessment->standards))->groupBy('strand_id')->toArray(); 
                    $strands = (new Collection($assessment->strands))->map(function($strand, $key) use($standards){
                        $strand->standards = [];
                        if(isset($standards[$strand->id])){
                            $strand->standards = $standards[$strand->id];
                        }
                        return $strand;
                    })->toArray();

                    $assessment->strands = (new Collection($strands))->indexBy('name')
                    ->map(function($value, $key){

                        $value = (new Collection($value->standards))->extract('name')->toArray();
                        return $value;
                    })
                    ->toArray();
                    
                    unset($assessment->standards);
                    return $assessment;
                })->toArray();
            }

            if(!empty($unit->unit_impacts)){
                $impact_categories = (new Collection($unit->unit_impacts))->groupBy('impact.impact_category.name')->toArray();
                $unit->unit_impacts = $impact_categories;
            }

            $unitStrands = [];
            if(!empty($unit->unit_strands)){
                $unitStrands = (new Collection($unit->unit_strands))->indexBy('strand.name')->map(function($value,$key){
                    if(!empty($value->strand->standards)){
                        $value = (new Collection($value->strand->standards))->extract('name')->toArray();
                    }
                    return $value;
                })->toArray();

                $unit->unit_strands = $unitStrands;
            }

            $this->set('data',$unit);
            $this->set('courses',$courses);
            $this->set('teachers',$teachers);
            $this->set('_serialize', ['users','data','courses','teachers']);
        }
    }

    public function ubdTemplate($unitId = null)
    {
        $this->viewBuilder()->layout('unit');
        // $this->loadModel('Schools');
        // $school = $this->Schools->find()
        //                          ->matching('Campuses.Courses.Units',function($q) use($unitId){
        //                             return $q->where(['unit_id' => $unitId]);
        //                          })
        //                          ->first();
        
        $this->loadModel('Units');
        $unitData = $this->Units->findById($unitId)
                                ->contain(['UnitStandards'])
                                ->first();


        $unitStandardIds = [];                        
        if(!empty($unitData->unit_standards)){
            $unitStandardIds = (new Collection($unitData->unit_standards))->extract('standard_id')->toArray();
            $unit = $this->Units->findById($unitId)
            ->contain(['UnitSpecificContents.ContentCategories','UnitStrands.Strands.Standards' => function($q) use($unitStandardIds){
                return $q->where(['Standards.id IN' => $unitStandardIds]);
            },'UnitImpacts.Impacts.ImpactCategories','UnitCourses.Courses.Sections.SectionTeachers.Teachers','UnitResources','UnitReflections.Users','UnitReflections.ReflectionCategories','Assessments.Standards','Assessments.Strands','Assessments.AssessmentImpacts.Impacts.ImpactCategories','Assessments.AssessmentContents.ContentValues','Assessments.AssessmentContents.ContentCategories','Assessments.AssessmentSpecificContents.ContentCategories'])
            ->first();

        }else{
            $unit = $this->Units->findById($unitId)
            ->contain(['UnitSpecificContents.ContentCategories','UnitStrands.Strands','UnitImpacts.Impacts.ImpactCategories','UnitCourses.Courses.Sections.SectionTeachers.Teachers','UnitResources','UnitReflections.Users','UnitReflections.ReflectionCategories','Assessments.Standards','Assessments.Strands','Assessments.AssessmentImpacts.Impacts.ImpactCategories','Assessments.AssessmentContents.ContentValues','Assessments.AssessmentContents.ContentCategories','Assessments.AssessmentSpecificContents.ContentCategories'])
            ->first();
        }

        if($unit){

            if(Configure::read('CakePdf')){
                $this->RequestHandler->renderAs($this, 'pdf', ['attachment' => 'abc']);
                $this->viewBuilder()->layout('unit')->options([
                    'pdfConfig' => [
                        
                        'filename' => $unit->name.'_'.$unitId.'.pdf'
                    ]
                ]);
            }

            $teachers = [];
            $courses = [];
            if(!empty($unit->unit_courses)){

                $courses = (new Collection($unit->unit_courses))->extract('course.name')->toArray();

                $teachers = (new Collection($unit->unit_courses))->extract('course.sections.{*}.section_teachers.{*}.teacher.full_name')->toArray();

                $teachers = array_unique($teachers);

            }  

            if(!empty($unit->assessments)){
                $assessment_impact_categories = [];
                foreach ($unit->assessments as $assessment) {
                    $impact_categories =  (new Collection($assessment->assessment_impacts))->groupBy('impact.impact_category.name')->toArray();
                    $assessment->assessment_impacts = $impact_categories;
                }
                
                $unit->assessments = (new Collection($unit->assessments))->map(function($assessment, $key){

                    $standards = (new Collection($assessment->standards))->groupBy('strand_id')->toArray(); 
                    $strands = (new Collection($assessment->strands))->map(function($strand, $key) use($standards){
                        $strand->standards = [];
                        if(isset($standards[$strand->id])){
                            $strand->standards = $standards[$strand->id];
                        }
                        return $strand;
                    })->toArray();

                    $assessment->strands = (new Collection($strands))->indexBy('name')
                    ->map(function($value, $key){

                        $value = (new Collection($value->standards))->extract('name')->toArray();
                        return $value;
                    })
                    ->toArray();
                    
                    unset($assessment->standards);
                    return $assessment;
                })->toArray();
            }

            if(!empty($unit->unit_impacts)){
                $impact_categories = (new Collection($unit->unit_impacts))->groupBy('impact.impact_category.name')->toArray();
                $unit->unit_impacts = $impact_categories;
            }

            $unitStrands = [];
            if(!empty($unit->unit_strands)){
                $unitStrands = (new Collection($unit->unit_strands))->indexBy('strand.name')->map(function($value,$key){
                    if(!empty($value->strand->standards)){
                        $value = (new Collection($value->strand->standards))->extract('name')->toArray();
                    }
                    return $value;
                })->toArray();

                $unit->unit_strands = $unitStrands;
            }

            $this->set('courses',$courses);
            $this->set('teachers',$teachers);
        }
        $this->set('data',$unit);
        $this->set('_serialize', ['users','data','courses','teachers']);
    }

    public function transferTemplate($unitId = null)
    {
        $this->viewBuilder()->layout('unit');
        // $this->loadModel('Schools');
        // $school = $this->Schools->find()
        //                          ->matching('Campuses.Courses.Units',function($q) use($unitId){
        //                             return $q->where(['unit_id' => $unitId]);
        //                          })
        //                          ->first();
        
        // $users = $this->Auth->user();

        $this->loadModel('Units');
        $unit = $this->Units->findById($unitId)
        ->contain(['UnitSpecificContents.ContentCategories','UnitContents' => function($q){
            return $q->contain(['ContentCategories','ContentValues']);
        },'UnitCourses.Courses.Sections.SectionTeachers.Teachers','UnitResources','UnitReflections.Users','Assessments.Standards','Assessments.Strands','Assessments.AssessmentImpacts.Impacts.ImpactCategories','Assessments.AssessmentContents.ContentValues','Assessments.AssessmentContents.ContentCategories','Assessments.AssessmentSpecificContents.ContentCategories'])
        ->first();

        if($unit){

            if(Configure::read('CakePdf')){
                $this->RequestHandler->renderAs($this, 'pdf', ['attachment' => 'abc']);
                $this->viewBuilder()->layout('unit')->options([
                    'pdfConfig' => [
                        
                        'filename' => $unit->name.'_'.$unitId.'.pdf'
                    ]
                ]);
            }

            $teachers = [];
            $courses = [];
            if(!empty($unit->unit_courses)){

                $courses = (new Collection($unit->unit_courses))->extract('course.name')->toArray();

                $teachers = (new Collection($unit->unit_courses))->extract('course.sections.{*}.section_teachers.{*}.teacher.full_name')->toArray();

                $teachers = array_unique($teachers);

            }

            if(!empty($unit->assessments)){
                $assessment_impact_categories = [];
                foreach ($unit->assessments as $assessment) {
                    $impact_categories =  (new Collection($assessment->assessment_impacts))->groupBy('impact.impact_category.name')->toArray();
                    $assessment->assessment_impacts = $impact_categories;
                }
                
                $unit->assessments = (new Collection($unit->assessments))->map(function($assessment, $key){

                    $standards = (new Collection($assessment->standards))->groupBy('strand_id')->toArray(); 
                    $strands = (new Collection($assessment->strands))->map(function($strand, $key) use($standards){
                        $strand->standards = [];
                        if(isset($standards[$strand->id])){
                            $strand->standards = $standards[$strand->id];
                        }
                        return $strand;
                    })->toArray();

                    $assessment->strands = (new Collection($strands))->indexBy('name')
                    ->map(function($value, $key){

                        $value = (new Collection($value->standards))->extract('name')->toArray();
                        return $value;
                    })
                    ->toArray();
                    
                    unset($assessment->standards);
                    return $assessment;
                })->toArray();
            }

            if(!empty($unit->unit_impacts)){
                $impact_categories = (new Collection($unit->unit_impacts))->groupBy('impact.impact_category.name')->toArray();
                $unit->unit_impacts = $impact_categories;
            }

            
            // $this->set('schoolLogo',$schoolLogo);
            $this->set('courses',$courses);
            $this->set('teachers',$teachers);
        }
        $this->set('data',$unit);
        $this->set('_serialize', ['users','data','courses','teachers']);
    }

    public function pypTemplate($unitId = null)
    {

        $this->viewBuilder()->layout('unit');
        $users = $this->Auth->user();

        $this->loadModel('Units');
        $unit = $this->Units->findById($unitId)
        ->contain(['TransDisciplinaryThemes','UnitSpecificContents.ContentCategories','UnitContents' => function($q){
            return $q->contain(['ContentCategories','ContentValues']);
        },'UnitCourses.Courses.Sections.Teachers','UnitResources','UnitReflections.ReflectionSubcategories','Assessments.AssessmentSubtypes','Assessments.AssessmentTypes','Assessments.AssessmentSpecificContents.ContentCategories'])
        ->first();
        

        if($unit){    
            if(Configure::read('CakePdf')){
                $this->RequestHandler->renderAs($this, 'pdf', ['attachment' => 'abc']);
                $this->viewBuilder()->layout('unit')->options([
                    'pdfConfig' => [
                        
                        'filename' => $unit->name.'_'.$unitId.'.pdf'
                    ]
                ]);
            }
            
            $this->loadModel('Schools');
            $schools = $this->Schools->find()
            ->matching('Campuses.Courses.Units',function($q) use($unitId){
                return $q->where(['unit_id' => $unitId]);
            })
            ->all()
            ->toArray();
            
            
            $grades = null;
            $schoolName = null;
            if(!empty($schools)){
                $schoolName = (new Collection($schools))->extract('name')->toArray();
                $schoolName = array_unique($schoolName);
                $schoolName = h(implode(', ',$schoolName));

                $grades = (new Collection($schools))->extract('_matchingData.Courses.grade_id')->toArray();
                $grades = array_unique($grades);
                $this->loadModel('Grades');
                $unitGrades = $this->Grades->find()->where(['id IN' => $grades])->all()->toArray();
                if(!empty($unitGrades)){
                    $grades = (new Collection($unitGrades))->extract('name')->toArray();
                    $grades = h(implode(', ',$grades));
                }
            }

            $unitReflections = [];
            if(!empty($unit->unit_reflections)){
                $unitReflections = (new Collection($unit->unit_reflections))->groupBy('reflection_subcategory_id')->map(function($value,$key){
                    $value = (new Collection($value))->extract('description')->toArray();
                    return $value;
                })->toArray();
            }

            $team = null;
            if(!empty($unit->unit_courses)){

                $team = (new Collection($unit->unit_courses))->map(function($unitCourse, $key){
                    $teacher = (new Collection($unitCourse->course->sections))->map(function($section, $newKey){
                        $teacherName = ucwords($section->teacher->first_name).' '.ucwords($section->teacher->last_name); 
                        return ($teacherName);
                    })->toArray();
                    return $teacher;
                })->toArray();

                $team = array_unique($team[0]);
                $team = implode(', ',$team);
            }

            $t1 = strtotime($unit->start_date);
            $t2 = strtotime($unit->end_date);
            
            $differenceInSeconds = $t2 - $t1;
            $differenceInHours = $differenceInSeconds / 3600;
            
            $assessmentEvidenceDescription = [];
            $assessmentSubtypes = [];
            if(!empty($unit->assessments)){

                $assessmentSubtypes = (new Collection($unit->assessments))->filter(function($value,$key){
                    return $value->assessment_type_id == 4;
                })->groupBy('assessment_subtype.name')->toArray();

                $assessmentTypes = (new Collection($unit->assessments))->groupBy('assessment_type.name')->toArray();
                
                $assessmentSpecificText = [];
                foreach ($unit->assessments as $assessment) {

                    if(!empty($assessment->assessment_specific_contents)){
                        foreach ($assessment->assessment_specific_contents as $assessmentSpecificContent){
                            if($assessmentSpecificContent->content_category->type == "assessment_evidence"){
                                $assessmentSpecificText[] = $assessmentSpecificContent->description;
                            }    
                        }    
                    }
                }
            }

            $this->set('assessmentTypes',$assessmentTypes);
            $this->set('unitReflections',$unitReflections);
            $this->set('assessmentSpecificText',$assessmentSpecificText);
            $this->set('assessmentSubtypes',$assessmentSubtypes);
            $this->set('assessmentEvidenceDescription',$assessmentEvidenceDescription);
            $this->set('proposedHours',$differenceInHours);
            $this->set('grades',$grades);
            $this->set('schoolName',$schoolName);
            $this->set('users',$users);
        }
        $this->set('data',$unit);
        $this->set('_serialize', ['users','data']);

    }


    // report pdf

    public function report($studentId, $reportTemplateId, $useCakePdf = 1){
        $this->viewBuilder()->layout('unit');
        $this->loadModel('Sections');
        $this->loadModel('ReportTemplatePages');
        $this->loadModel('Users');
        $this->loadModel('Reports');
        $this->loadModel('ReportTemplates');
        $this->loadModel('Grades'); 
        $this->imgFlag = 0;
        $student = $this->Sections->SectionStudents->Students->findById($studentId)->first();

        $duplicateCourses = [666 => 256, 667 => 257, 668 => 669, 634 => 238, 633 => 635, 660 => 661, 663 => 662, 664 => 665];

        $reportTemplate = $this->ReportTemplates->findById($reportTemplateId)
                                                ->contain(['ReportSettings','ReportingPeriods.Terms.AcademicYears'])
                                                ->first();

        
        $studentAcademicYear = $reportTemplate->reporting_period->term->academic_year->name;
        $studentTerm = $reportTemplate->reporting_period->term->name;
        $studentTerm = str_replace(' ', '_', $studentTerm);

        try {
           $studentGradeId =  $this->Users->getStudentGrade($student->legacy_id);
           $grade = $this->Grades->findById($studentGradeId)->first();
          } catch (\Exception $e) {
            //Handle Exception Here
          }
        if(isset($grade)){
            $gradeName = $grade->name;
        }else{
            $gradeName = 'Unknown';
        }

        $footerHash = [
                    'student_name' => $student->full_name,
                    'student_legacy_id' => $student->legacy_id
                  ];

         
         if(Configure::read('CakePdf') && $useCakePdf){
            $reportTemplateFile = Configure::read('reportTemplateConfig');
            if(isset($reportTemplateFile[$reportTemplateId])){
                if(isset($reportTemplateFile[$reportTemplateId]['footer-right'])){
                    $reportTemplateFile[$reportTemplateId]['footer-right'] = $this->_substitute($reportTemplateFile[$reportTemplateId]['footer-right'], $footerHash);
                }

                if(isset($reportTemplateFile[$reportTemplateId]['footer-center'])){
                    $reportTemplateFile[$reportTemplateId]['footer-center'] = $this->_substitute($reportTemplateFile[$reportTemplateId]['footer-center'], $footerHash);
                }

                if(isset($reportTemplateFile[$reportTemplateId]['footer-left'])){
                    $reportTemplateFile[$reportTemplateId]['footer-left'] = $this->_substitute($reportTemplateFile[$reportTemplateId]['footer-left'], $footerHash);
                }

                Configure::write('CakePdf.engine.options', $reportTemplateFile[$reportTemplateId]);
                
            }
            $this->viewBuilder()->layout('unit')->options([
                 'pdfConfig' => [
                    
                    // 'filename' => 'reports'.$studentId.'.pdf',
                    'filename' => $gradeName.'_'.$studentAcademicYear.'_'.$studentTerm.'_'.$student->last_name.'_'.$student->first_name.'.pdf',
                 ]
            ]);
            $this->RequestHandler->renderAs($this, 'pdf', ['attachment' => 'abc']);
        }

        $reportSettingsWithCourses = new Collection($reportTemplate->report_settings);
        $selectedCourses = $reportSettingsWithCourses->extract('course_id')->toArray();

        $sectionStudent = $this->Sections->SectionStudents->find()->where(['student_id' => $studentId])
                                                        ->contain(['Students','Sections' => function($q) use($selectedCourses, $reportTemplate){
                                                            return $q->where([
                                                                        'term_id' => $reportTemplate->reporting_period->term_id,
                                                                        'course_id IN' => $selectedCourses,
                                                                ])->contain(['Courses', 'SectionTeachers']);
                                                        }])
                                                        ->all();


        $courseSection = $sectionStudent->extract('section')->combine('id', 'course_id')->toArray();

        $sectionTeacher = $sectionStudent->extract('section.section_teachers')->map(function($value, $key){
                                                                                 return current($value);
                                                                               })
                                                                             ->combine('section_id', 'teacher_id')
                                                                             ->toArray();

        $studentCourseIds = $sectionStudent->extract('section.course_id')->toArray();

        $sectionStudentCourseTeacher = $sectionStudent->map(function($value, $key) use($sectionTeacher, $courseSection){
                                                        unset($value->section);
                                                        $value->teacher_id = $sectionTeacher[$value->section_id];
                                                        $value->course_id = $courseSection[$value->section_id];
                                                        return $value;
                                                      })
                                                     ->combine('course_id', 'teacher_id')
                                                     ->toArray(); 

        $enableCourseReflection = $this->ReportTemplates->ReportSettings->findByReportTemplateId($reportTemplateId)
                                                                        ->where(['OR' => [
                                                                                            'show_student_reflection' => 1,
                                                                                            'show_teacher_reflection' => 1,
                                                                                            'show_special_services' => 1
                                                                                         ]
                                                                                ])
                                                                        ->andWhere(['course_id IN' => $studentCourseIds])
                                                                        ->first();

        if(isset($enableCourseReflection)){
            $homeTeacherId = $sectionStudentCourseTeacher[$enableCourseReflection->course_id];
            $homeTeacher = $this->Users->find()->where(['id' => $homeTeacherId])->first();
            $homeTeacherName = $homeTeacher->full_name;   
            
            $this->loadModel('ReportTemplateStudentComments');
            $studentTeacherReflection = $this->ReportTemplateStudentComments->findByReportTemplateId($reportTemplateId)
                                                                            ->where(['student_id' => $studentId])
                                                                            ->first();

            if($studentTeacherReflection){
                $studentReflection = $studentTeacherReflection->student_comment;
                $teacherReflection = $studentTeacherReflection->teacher_comment;
            }
            // pr($studentReflection); 
            // pr($teacherReflection); die;

            $this->loadModel('ReportTemplateStudentServices');
            $studentServices = $this->ReportTemplateStudentServices->find()->where(['student_id' => $studentId])
                                                                           ->contain(['SpecialServiceTypes'])
                                                                           ->extract('special_service_type.name')
                                                                           ->toArray();
            
            $noneStudentService = array_search('none', $studentServices);
            if($noneStudentService){
                unset($studentServices[$noneStudentService]);
            }

            $studentServices = implode(',', $studentServices);

        }else{
            $homeTeacherName = '-';
        }
                                                  

        try {
            $teacherAttendance = [];
            $studentAttendanceData = $this->Users->getStudentAttendance($student->legacy_id, $reportTemplate->reporting_period->term_id, $studentCourseIds);

            foreach ($studentAttendanceData as $value) {
                $teacherAttendance[$value['course_id']] = [
                                                            'absence' => $value['absence'],
                                                            'tardy' => $value['tardy']
                                                          ]; 
            }

          } catch (\Exception $e) {
            //Handle Exception Here
          }



        $this->loadModel('ReportSettings');
        $reportTemplatePageIds = $this->ReportSettings->findByReportTemplateId($reportTemplateId)
                                                      ->all()
                                                      ->extract('report_template_page_id')
                                                      ->toArray();

        $reportTemplatePageIds = array_unique($reportTemplatePageIds);              
        
        $reports = $this->Reports->findByReportTemplateId($reportTemplateId)
                                 ->where(['OR' => ['Reports.course_id IN' => $studentCourseIds, 'Reports.course_id IS NULL']])
                                 ->contain(['ReportPages', 'Courses' => function($q) use($studentId, $reportTemplatePageIds, $reportTemplateId){
                                    return $q->contain(['ReportTemplatePages' => function($q) use($reportTemplatePageIds){
                                        return $q->where(['ReportTemplatePages.id IN' => $reportTemplatePageIds]);
                                    }, 'ReportTemplateCourseScores'=> function($q) use($studentId, $reportTemplateId){
                                        return $q->where(['student_id' => $studentId, 'report_template_id' => $reportTemplateId])->contain(['ScaleValues']);
                                    }, 'ReportTemplateStrands'=> function($q) use($reportTemplateId){
                                        return $q->where(['report_template_id' => $reportTemplateId]);
                                    },'ReportTemplateStandards'=> function($q) use($reportTemplateId){
                                        return $q->where(['report_template_id' => $reportTemplateId]);
                                    },'ReportTemplateImpacts'=> function($q) use($studentId, $reportTemplateId){
                                        return $q->where(['report_template_id' => $reportTemplateId]);
                                    }]);
                                }])
                                 ->order(['sort_order' => 'ASC'])    
                                 ->toArray();

       // pr($reports); die;
        if(!$reports){
            throw new NotFoundException("Reports not found", 1);
            
        }

        $hash = [];
        $generalPage = [];
        $courseData = [];
        foreach ($reports as $key => $value) {
            if(!empty($value->report_page)){
                $dob = strtotime($student->dob);
              $studentReplaceData = [   
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'student_id' => $student->legacy_id,
                    'student_image' => $student->image_url,
                    'grade' => isset($grade) ? $grade->name : "Unknown",
                    'academic_year' => $studentAcademicYear,
                    // 'student_reflection' => isset($studentReflection) ? $studentReflection : '',
                    // 'teacher_reflection' => isset($teacherReflection) ? $teacherReflection : '',
                    'services' => isset($studentServices) ? $studentServices : '',
                    'dob' => date('Y-m-d', $dob),
                    'home_teacher' => $homeTeacherName,
		    'absences'=> !empty($teacherAttendance) ? (new Collection($teacherAttendance))->max('absence')['absence'] : 0
              ];

            $generalPage['r'.$value->report_page->id] = json_decode($this->_substitute($value->report_page, $studentReplaceData));
            $generalPage['r'.$value->report_page->id] = json_decode(json_encode($generalPage['r'.$value->report_page->id]), True);
          }
          else{
              if(!empty($value->course)){
                $reportTemplatePage = $value->course->report_template_pages[0];
              // pr($reportTemplatePage); 
              unset($reportTemplatePage->_joinData);
              unset($value->course->report_template_pages[0]);
            
              Log::write('debug', "Course Id".$value->course_id);
              $teacherId = $this->Sections->findByCourseId($value->course_id)
                                          ->where(['term_id'=> $reportTemplate->reporting_period->term_id])
                                          ->matching('SectionStudents', function($q) use ($studentId){
                                                return $q->where(['student_id' => $studentId]);
                                           })
                                          ->first();

              if(!$teacherId){
                    if(!empty($value->course->report_template_course_scores)){  
                        $teacherId = $value->course->report_template_course_scores[0]->created_by;
                        $userRole = $this->Users->findById($teacherId)->contain(['Roles'])->first();
                        if($userRole->role->name == 'admin'){
                            $teacherId = $sectionStudentCourseTeacher[$value->course->id];    
                        }
                    }else{
                        $teacherId = $sectionStudentCourseTeacher[$value->course->id];
                    }
              }else{
                   $teacherId = $teacherId->teacher_id; 
              }

              $teacherData = $this->Users->findById($teacherId)->first();
              Log::write('debug', $teacherData);
              if(count($value->course->report_template_course_scores) > 0){
                 $courseComment = !empty($value->course->report_template_course_scores[0]->comment) ? $value->course->report_template_course_scores[0]->comment : 'No Comment';
              }else{
                 $courseComment =  'No Comment';
              }

              Log::write('debug',"Course Comments".$courseComment);
            // pr($value->course->id); die;
            // pr($teacherAttendance[$value->course->id]); die;
              if(isset($teacherAttendance[$value->course->id])){
                    $absence = $teacherAttendance[$value->course->id]['absence'];
                    $tardy = $teacherAttendance[$value->course->id]['tardy'];
               }else{
                    $absence = 0;
                    $tardy = 0;
               }

               $hash = [   
                'course_id' => $value->course->id,
                'course_name' => $value->course->name,
                'course_comment' => $courseComment,
                'teacher_name' => $teacherData->first_name.' '.$teacherData->last_name,
                'teacher_image' => $teacherData->image_url,
                'student_reflection' => isset($studentReflection) ? $studentReflection : 'No Student Reflection',
                'teacher_reflection' => isset($teacherReflection) ? $teacherReflection : 'No Teacher Reflection',
                'absences' => $absence,
                'tardies' => $tardy,
                ];
                Log::write("debug", $hash);

                $courseData['c'.$value->course->id] =  $this->fixJson($this->_substitute($reportTemplatePage, $hash));

                $reportTemplatePageIds = $this->ReportTemplatePages->find()
                                                                  ->where(['report_template_type_id IN' => [2, 4]])
                                                                  ->combine('id', function($entity){
                                                                    if($entity->report_template_type_id == 2){
                                                                        return '{{strand_template = '.$entity->id.'}}';
                                                                    }
                                                                    return '{{impact_template = '.$entity->id.'}}';
                                                                  })
                                                                  ->toArray();

                foreach ($reportTemplatePageIds as $key => $reportTemplatePageId) {
                    if(strpos($courseData['c'.$value->course->id]['body'], $reportTemplatePageId) !== false){
                        if(strpos($reportTemplatePageId, 'strand')){
                            $strandBody = '';

                            if(count($value->course->report_template_strands) > 0){ $i=0;
                                foreach ($value->course->report_template_strands as $reportTemplateStrand) {
                                    $strandBody = $strandBody.str_replace('}}', ', strand_id = '.$reportTemplateStrand->strand_id.'}}' , $reportTemplatePageId);
                                $i++;
                                   if($i ==6 ) {
                                       $strandBody = $strandBody.'<div style="height:150px;width:10px"></div>';
                                   }                                
                        }
                            
                            $courseData['c'.$value->course->id]['body'] = str_replace($reportTemplatePageId,$strandBody,$courseData['c'.$value->course->id]['body']);
                             // $courseData['c'.$value->course->id]['body'] = str_replace($reportTemplatePageId,'No reporting for Approaches to Learning',$courseData['c'.$value->course->id]['body']);
             
                            $courseData['c'.$value->course->id]['body'] = $this->_replaceStrandData($value->course->id,$courseData['c'.$value->course->id]['body'], count($value->course->report_template_strands), $studentId, $reportTemplateId, $value->course->report_template_standards);
                            } else {
$courseData['c'.$value->course->id]['body'] = str_replace($reportTemplatePageId,'No reporting for Learning Goals',$courseData['c'.$value->course->id]['body']);

}
                        }
                        if(strpos($reportTemplatePageId, 'impact')){
                            $impactBody = '';
                            if(count($value->course->report_template_impacts) > 0){
                                foreach ($value->course->report_template_impacts as $reportTemplateImpact) {
                                    $impactBody = $impactBody.str_replace('}}', ', impact_id = '.$reportTemplateImpact->impact_id.'}}' , $reportTemplatePageId);
                                }
                            
                            $courseData['c'.$value->course->id]['body'] = str_replace($reportTemplatePageId,$impactBody,$courseData['c'.$value->course->id]['body']);
                            $courseData['c'.$value->course->id]['body'] = $this->_replaceImpactData($value->course->id,$courseData['c'.$value->course->id]['body'], count($value->course->report_template_impacts), $studentId, $reportTemplateId);
			} else {
$courseData['c'.$value->course->id]['body'] = str_replace($reportTemplatePageId,'No reporting for Approaches to Learning',$courseData['c'.$value->course->id]['body']);
}  
                      }
                    }
                }
                // if($this->imgFlag != 0){
                //     $courseData['c'.$value->course->id]['body'] =  str_replace ("style='margin-left:-23px; width: 530px; height: 50px;'" , "style='margin-left:-23px; width: 300px; height: 25px;'", $courseData['c'.$value->course->id]['body']);
                // }
              }
              
            }
            
        }
        $data = array_merge($generalPage, $courseData);
        $data = array_values((new Collection($data))->toArray());
        $this->set('reports',$data);
        $this->set('_serialize', ['reports']);
}

private function _replaceStrandData($courseId,$courseBody, $strandCount, $studentId, $reportTemplateId, $courseTemplateStandards){
    if(!$strandCount){
        return $courseBody;
    }
    $matches = [];
    preg_match_all('/{{strand_template\s=\s\d+,\sstrand_id\s=\s\d+}}/', $courseBody, $matches, PREG_OFFSET_CAPTURE);
    $this->loadModel('ReportTemplateStrands');
    $count = 0;
    if(!empty($matches[0])){
        foreach ($matches[0] as $value) {
            $string = trim($value[0],"{{}}");
            $string = explode(',', $string);
            $strandTemplateId = explode('=', $string[0]);
            $strandTemplateId = $strandTemplateId[1];
            $strandId = explode('=', $string[1]);
            $strandId = $strandId[1];
            $strandTemplate = $this->ReportTemplatePages->find()
                                                        ->where(['id' => $strandTemplateId])
                                                        ->first();

            $strandData = $this->ReportTemplateStrands->find()
                                                      ->contain(['Strands', 'ReportTemplateStrandScores' => function($q) use($studentId){
                                                        return $q->where(['ReportTemplateStrandScores.student_id' => $studentId])->contain(['ScaleValues']);
                                                      }])
                                                      ->where(['strand_id' => $strandId, 'course_id' => $courseId, 'report_template_id' => $reportTemplateId])
                                                      ->first();

            if(!empty($strandData->report_template_strand_scores)){
                $body = $strandTemplate->body;

                $count++;

                $strandBodyContent = [
                                        'strand_name' => $strandData->strand->name,
                                        'strand_description' => $strandData->strand->description,
                                        'strand_comment' => isset($strandData->report_template_strand_scores[0]->comment) ? $strandData->report_template_strand_scores[0]->comment : '',
                                        'strand_scale_name' => $strandData->report_template_strand_scores[0]->scale_value->name,
                                        'strand_scale_value_image' => $strandData->report_template_strand_scores[0]->scale_value->image_url
                                     ];

                $body = $this->_substitute($body, $strandBodyContent);
                if($count > 7){
                    $this->imgFlag = 1;
                }
                if(strpos($body, 'Insufficient Evidence')){     
                    $body = preg_replace('#<img[^>]*>#i', '', $body);
                }

                if(strpos($body, 'Not Assessed')){
                    $body = '';
                }

                $standardReportTemplate = $this->ReportTemplatePages->find()
                                                                    ->where(['report_template_type_id' => 3])
                                                                    ->combine('id', function($entity){
                                                                        return '{{standard_template = '.$entity->id.'}}';
                                                                    })
                                                                    ->toArray();
               
                //standard template  
                foreach ($standardReportTemplate as $standardTemplateId) {
                    if(strpos($body, $standardTemplateId) !== false){
                        if(strpos($standardTemplateId, 'standard')){
                            $standardBody = '';
                            if(count($courseTemplateStandards) > 0){
                                $standardIds = (new Collection($courseTemplateStandards))->extract('standard_id')->toArray();

                                $strandStandards = $this->ReportTemplateStrands->Strands->Standards
                                                                                        ->find()
                                                                                        ->where(['id IN' => $standardIds,'strand_id' => $strandId])
                                                                                        ->toArray();
                                foreach ($strandStandards as $reportTemplateStandard) {
                                    $standardBody = $standardBody.str_replace('}}', ', standard_id = '.$reportTemplateStandard->id.'}}' , $standardTemplateId);
                                }
                            }
                            if(!empty($strandStandards)){
                                    $body = str_replace($standardTemplateId,$standardBody,$body);
                                    $body = $this->_replaceStandardData($courseId, $body, count($courseTemplateStandards), $studentId, $reportTemplateId);
                            }else{
                                $body = str_replace($standardTemplateId, '', $body);
                            }
                        }
                    }
                }

            }else{
                $body = '';
            }
            $courseBody = str_replace($value[0], $body, $courseBody);
            
        }
        if(!$count){
           $courseBody =  substr_replace($courseBody,"No reporting for Learning Goals",$matches[0][0][1],0);
        }
        return $courseBody;
    }
}

private function _replaceStandardData($courseId, $strandBody, $standardCount, $studentId, $reportTemplateId){
    if(!$standardCount){
        return;
    }
    $matches = [];
    preg_match_all('/{{standard_template\s=\s\d+,\sstandard_id\s=\s\d+}}/', $strandBody, $matches, PREG_OFFSET_CAPTURE);
    $this->loadModel('ReportTemplateStandards');
    $count = 0;
    // pr($courseBody);die;
    if(!empty($matches[0])){
        foreach ($matches[0] as $value) {
            $string = trim($value[0],"{{}}");
            $string = explode(',', $string);
            $standardTemplateId = explode('=', $string[0]);
            $standardTemplateId = $standardTemplateId[1];
            $standardId = explode('=', $string[1]);
            $standardId = $standardId[1];

            $standardTemplate = $this->ReportTemplatePages->find()
                                                          ->where(['id' => $standardTemplateId])
                                                          ->first();

            $standardData = $this->ReportTemplateStandards->find()
                                                          ->where(['standard_id' => $standardId, 'course_id' => $courseId, 'report_template_id' => $reportTemplateId])
                                                          ->contain(['Standards', 'ReportTemplateStandardScores' => function($q) use($studentId){
                                                                return $q->where(['ReportTemplateStandardScores.student_id' => $studentId])->contain(['ScaleValues']);
                                                            }])
                                                          ->first();

            if(!empty($standardData ->report_template_standard_scores)){
                $body = $standardTemplate->body;
                // str_replace($value[0], $standardTemplate->body, $courseBody);
                $count++;
                $standardBodyContent = [
                                        'standard_name' => $standardData->standard->name,
                                        'standard_scale_name' => $standardData->report_template_standard_scores[0]->scale_value->name,
                                        'standard_scale_value_image' => $standardData->report_template_standard_scores[0]->scale_value->image_url
                                     ];

                $body = $this->_substitute($body, $standardBodyContent);

                if($count > 7){
                    $this->imgFlag = 1;
                }
                if(strpos($body, 'Insufficient Evidence')){     
                    $body = preg_replace('#<img[^>]*>#i', '', $body);
                }

                if(strpos($body, 'Not Assessed')){
                    $body = '';
                }


            }else{

                $body = '';
            }

            $strandBody = str_replace($value[0], $body, $strandBody);

        }
        if(!$count){
           $strandBody =  substr_replace($strandBody,"",$matches[0][0][1],0);
        }
        return $strandBody;
    }
}

private function _replaceImpactData($courseId,$courseBody, $impactCount, $studentId, $reportTemplateId){
    if(!$impactCount){
        return $courseBody;
    }
    $matches = [];
    preg_match_all('/{{impact_template\s=\s\d+,\simpact_id\s=\s\d+}}/', $courseBody, $matches, PREG_OFFSET_CAPTURE);
    $this->loadModel('ReportTemplateImpacts');
    $count = 0;
    // pr($courseBody);die;
    if(!empty($matches[0])){
        foreach ($matches[0] as $value) {
            $string = trim($value[0],"{{}}");
            $string = explode(',', $string);
            $impactTemplateId = explode('=', $string[0]);
            $impactTemplateId = $impactTemplateId[1];
            $impactId = explode('=', $string[1]);
            $impactId = $impactId[1];
            $impactTemplate = $this->ReportTemplatePages->find()
                                                        ->where(['id' => $impactTemplateId])
                                                        ->first();

            $impactData = $this->ReportTemplateImpacts->find()
                                                      ->contain(['Impacts', 'ReportTemplateImpactScores' => function($q) use($studentId){
                                                        return $q->where(['ReportTemplateImpactScores.student_id' => $studentId])->contain(['ScaleValues']);
                                                      }])
                                                      ->where(['impact_id' => $impactId, 'course_id' => $courseId, 'report_template_id' => $reportTemplateId])
                                                      ->first();


            if(!empty($impactData->report_template_impact_scores)){
                $body = $impactTemplate->body;
                // str_replace($value[0], $impactTemplate->body, $courseBody);
                $count = 1;
                $impactBodyContent = [
                                        'impact_name' => $impactData->impact->name,
                                        'impact_comment' => $impactData->report_template_impact_scores[0]->comment,
                                        'impact_scale_name' => $impactData->report_template_impact_scores[0]->scale_value->name,
                                        'impact_scale_value_image' => $impactData->report_template_impact_scores[0]->scale_value->image_url
                                     ];

                $body = $this->_substitute($body, $impactBodyContent);

                if($count > 7){
                    $this->imgFlag = 1;
                }

                if(strpos($body, 'Insufficient Evidence')){     
                    $body = preg_replace('#<img[^>]*>#i', '', $body);
                }

                if(strpos($body, 'Not Assessed')){
                    $body = '';
                }


            }else{

                $body = '';
            }
            $courseBody = str_replace($value[0], $body, $courseBody);   
        }
        if(!$count){
           $courseBody =  substr_replace($courseBody,"No reporting for Approaches to Learning",$matches[0][0][1],0);
        }
        return $courseBody;
    }
}

public function fixJson($string, $assoc=true, $fixNames=true){
  
  $string = str_replace(PHP_EOL, ' ', $string);
  $string = preg_replace('/[\r\n]+/', "\n", $string);
  $string = preg_replace('/[ \t]+/', ' ', $string);
  $string = json_decode($string);
  return json_decode(json_encode($string), True);
}

private function _substitute($content, $hash){
    // pr($hash);
      //write substitute logic
    if(isset($content->body)){
        $content->body = str_replace('"', "'", $content->body);
    }
   
    foreach ($hash as $key => $value) {
    
        if(!is_array($value)){
            $value = trim($value);
            $value = str_replace('"', '\"', $value);
            $placeholder = sprintf('{{%s}}', $key);
            // if($placeholder=="{{".$key."}}"){
                        // $content = str_replace($placeholder, $value, $content);
            if(strpos($placeholder, 'strand_scale_value_image') || strpos($placeholder, 'standard_scale_value_image') || strpos($placeholder, 'impact_scale_value_image') || strpos($placeholder, 'student_image') || strpos($placeholder, 'teacher_image')){
                if(strpos($placeholder, 'strand_scale_value_image') || strpos($placeholder, 'standard_scale_value_image') || strpos($placeholder, 'impact_scale_value_image')){
                    $content = str_replace($placeholder, "<img src = '".$value."' style='margin-left:-23px; width: 530px; height: 50px;'>", $content);
                }else if(strpos($placeholder, 'student_image')){
                    
                    $content = str_replace($placeholder, "<img src = '".$value."' style='width: 130px; height: 170px;'>", $content);                    
                }else{
                    $content = str_replace($placeholder, "<img src = '".$value."' style='width: 90px; height: auto;'>", $content);
                }
            }
            // else if(strpos($placeholder, 'student_reflection') && !in_array($value, [null, false, ''])){
            //         $content = str_replace($placeholder, "<h2><strong>Student Reflection</strong></h2><p style='font-size: 18px; background-color: #e4f2f1 !important; padding: 7px 0 11px 8px;'>".$value."</p>", $content);
            // }else if(strpos($placeholder, 'teacher_reflection') && !in_array($value, [null, false, ''])){

            //         $content = str_replace($placeholder, "<h2><strong>Teacher General Comment</strong></h2><p style='font-size: 18px; background-color: #e4f2f1 !important; padding: 7px 0 11px 8px;'>".$value."</p>", $content);
            // }
            else if(strpos($placeholder, 'services')){
                    if(!in_array($value, [null, false, ''])){
                        $content = str_replace($placeholder, "<span style='font-size: 16px;'>&nbsp;".$value."</span>", $content);

                    }else{
                        $content = str_replace($placeholder, " ", $content);
                        if(strpos($content, 'Supported received from:')){
                          $content =  str_replace('Supported received from:', '', $content);
                        }
                        
                    }

            }else{
                  $content = str_replace($placeholder, $value, $content);
            }
          // }
      }
  }
  return $content;
}

}

