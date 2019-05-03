<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Email\Email;
use mikehaertl\wkhtmlto\Pdf;


/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class TeachersController extends ApiController
{

    public function sendEmail($studentId,$reportTemplateId){
      
      if(!$this->request->is('get')){
          throw new MethodNotAllowedException("Method not allowed", 1); 
      }
      if(!isset($studentId) || !$studentId){
        throw new BadRequestException("Missing Student Id");
      }
      if(!isset($reportTemplateId) || !$reportTemplateId){
        throw new BadRequestException("Missing Report Template Id");
      }

      $this->loadModel('Users');
      $student = $this->Users->findById($studentId)->contain(['StudentGuardians.Guardians'])->first();
      
      $this->loadModel('ReportTemplateEmailSettings');
      $this->loadModel('ReportTemplates');

      $emailSettings = $this->ReportTemplateEmailSettings->findByReportTemplateId($reportTemplateId)->first();
      $emailBody = $emailSettings->body;
      $sendBy = $emailSettings->sender_email;

      $pdfName = 'Report'.$student->id;

      sleep(1);
      $url = Router::url('/pdf/report/'.$studentId.'/'.$reportTemplateId.'/0', true);
      
      $footerHash = [
                    'student_name' => $student->full_name,
                    'student_legacy_id' => $student->legacy_id
      ];
      $reportTemplateConfig = Configure::read('reportTemplateConfig');
      if(isset($reportTemplateConfig[$reportTemplateId])){
          $reportTemplateConfig = $reportTemplateConfig[$reportTemplateId];

          if(isset($reportTemplateConfig['footer-right'])){
              $reportTemplateConfig['footer-right'] = $this->ReportTemplates->substitute($reportTemplateConfig['footer-right'], $footerHash);
          }
          if(isset($reportTemplateConfig['footer-center'])){
              $reportTemplateConfig['footer-center'] = $this->ReportTemplates->substitute($reportTemplateConfig['footer-center'], $footerHash);
          }

          if(isset($reportTemplateConfig['footer-left'])){
              $reportTemplateConfig['footer-left'] = $this->ReportTemplates->substitute($reportTemplateConfig['footer-left'], $footerHash);
          }

          $reportTemplateConfig = (new Collection($reportTemplateConfig))->map(function($settingValue, $settingName){
            if(in_array($settingName, ['footer-right', 'footer-left', 'footer-center'])){
   
		return "--".$settingName." '".$settingValue."'";
            }
            return "--".$settingName." ".$settingValue;

          })->toArray();
          $reportTemplateConfig = implode(' ',$reportTemplateConfig);
      }else{
        $reportTemplateConfig = "";
      }

Log::write('debug', "wkhtmltopdf ".$reportTemplateConfig." --print-media-type ".$url." pdfs/".$pdfName.".pdf 2>&1");
      $shellOutput = shell_exec("wkhtmltopdf ".$reportTemplateConfig." --print-media-type ".$url." pdfs/".$pdfName.".pdf 2>&1");
Log::write('debug', "wkhtmlto pdf output");
Log::write('debug', $shellOutput);
      $email = new Email('default');
      $defaultConfig = Email::config('default');
      $defaultEmail = $defaultConfig['from']; 
       if($emailSettings->live_mode == 0){
           $sendTo = $emailSettings->test_receiver_email;
           $email->from([$defaultEmail => $sendBy])
		->replyTo($sendBy)
                 ->to($sendTo)
                 ->subject('Report')
                 ->attachments("pdfs/".$pdfName.".pdf")
		->send($emailBody);      
 }else{
     if(!empty($student->student_guardians)){
       $sendTo = $student->student_guardians[0]->guardian->email;
$flag = 0;
	foreach($student->student_guardians as $guardian){

	if($guardian->guardian->email) {
		$flag = 1;
		$email->addTo($guardian->guardian->email); 
		}
}
if(!$flag){
$email->to($emailSettings->test_receiver_email);
}
                $email->from([$defaultEmail => $sendBy])
                ->replyTo($sendBy)   
		->subject('Report')
                   ->attachments("pdfs/".$pdfName.".pdf")
                     ->send($emailBody); 
           }else{
             $sendTo = $emailSettings->test_receiver_email;
                $email->from([$defaultEmail => $sendBy])
		->to($sendTo)
                ->replyTo($sendBy)   
		->subject('Report')
                   ->attachments("pdfs/".$pdfName.".pdf")
     	           ->send($emailBody);      
     }
       }
      /*$sendTo = $emailSettings->test_receiver_email;
  $email->from($sendBy)
            ->to($sendTo)
            ->subject('Report')
            ->attachments("pdfs/".$pdfName.".pdf")
            ->send($emailBody);
*/
      $success = true;
      $this->set('success', $success);
      $this->set('_serialize', ['success']);
    }

    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
      if(!$this->request->is('get')){
        throw new MethodNotAllowedException("Method not allowed", 1);
        
      }
      $this->loadModel('Users');
      $teachers = $this->Users->find()->where(['role_id' => 3])->all()
                                                               ->map(function($value, $key){
                                                                  $value->first_name = $value->first_name.' '.$value->last_name;
                                                                  return $value;
                                                               })
                                                               ->toArray();

      $success = true;

      $this->set('data',$teachers);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
      
    }

    public function getSectionStudents($sectionId = null){
      if(!$this->request->is('get')){
        throw new MethodNotAllowedException("Method not allowed", 1); 
      }
      if(!isset($sectionId) || !$sectionId){
        throw new BadRequestException("Missing Section Id");
      }
      $this->loadModel('Sections');
      $section = $this->Sections->findById($sectionId)
                              ->contain(['Courses'])
                              ->first();
      
      if(!$section->course){
        throw new NotFoundException(__('Course has not been found for this section.'));
      }
      $courseId = $section->course->id;

      $this->loadModel('ReportTemplates');
      $reportTemplates = $this->ReportTemplates->find()
                                    ->contain(['ReportingPeriods.Terms.Sections' => function($x)use($sectionId){
                                                  return $x->where(['Sections.id' => $sectionId ]);
                                    }])
				->matching('ReportingPeriods.Terms.Sections', function($q) use ($sectionId){
					return $q->where(['Sections.id' => $sectionId ]);
				})
				->matching("ReportTemplateGrades", function($q) use ($section){
          				return $q->where(['ReportTemplateGrades.grade_id' => $section->course->grade_id]);
        			})
                                    ->first();
      if(!$reportTemplates){
        throw new NotFoundException(__('Report Template not found.'));
      }

      if($reportTemplates->reporting_period->term->is_active != 1){
        throw new BadRequestException("Term is not active for this Report Template.");
      }

      $reportTemplateId = $reportTemplates->id;

      $this->loadModel('Sections');
      $sectionsStudents = $this->Sections->find()
                                         ->where(['Sections.id' => $sectionId])
                                         ->contain(['Teachers','SectionStudents.Students.ReportTemplateCourseScores' =>  function($q) use($reportTemplateId, $courseId){
                                              return $q->where(['course_id' => $courseId,'report_template_id' => $reportTemplateId]);
                                         }, 'Courses'])
                                         ->first();

      if(!empty($sectionsStudents->section_students)){
        $sectionsStudents->section_students = (new Collection($sectionsStudents->section_students))->sortBy('student.last_name',SORT_ASC,SORT_STRING)->map(function($value, $key){
                          $value->full_name = $value->student->full_name;
                          $value->is_completed = null;

                          if(!empty($value->student->report_template_course_scores) && isset($value->student->report_template_course_scores[0]->is_completed)){
                            $value->is_completed = $value->student->report_template_course_scores[0]->is_completed;
                          }
                          return $value;
                        })
                
                ->toArray();

        $sectionsStudents->section_students = array_values($sectionsStudents->section_students);
      
      }

      if(!$sectionsStudents){
        throw new NotFoundException(__('Sections not found for this section id .'));
      }

      $this->set('sectionsStudents', $sectionsStudents);
      $this->set('_serialize', ['sectionsStudents']);

    }

     public function studentReportSettings($sectionId = null, $studentId = null){
        if(!$this->request->is('get')){
          throw new MethodNotAllowedException("Method not allowed", 1); 
        }

        if(!isset($sectionId) || !$sectionId){
          throw new BadRequestException("Missing Section Id");
        }

        if(!isset($studentId) || !$studentId){
          throw new BadRequestException("Missing Student Id");
        }


        $this->loadModel('Sections');
        $section = $this->Sections->findById($sectionId)
                                ->contain(['Courses'])
                                ->first();
        
        $courseId = $section->course->id;
        $gradeId = $section->course->grade_id;
        $courseLink = [];
        $this->loadModel('Users');
        $student = $this->Users->findById($studentId)->first();

        $this->loadModel('ReportTemplates');
$reportTemplates = $this->ReportTemplates->find()
                                    ->contain(['ReportingPeriods.Terms.Sections' => function($x)use($sectionId){
                                                  return $x->where(['Sections.id' => $sectionId ]);
                                    }])
                                ->matching('ReportingPeriods.Terms.Sections', function($q) use ($sectionId){
        return $q->where(['Sections.id' => $sectionId ]);
})
->matching('ReportTemplateGrades', function($q) use($gradeId){
                                                      return $q->where(['grade_id' => $gradeId]);
                                                    })
                                    ->first(); 
Log::write('debug', "Report Template -----------");
Log::write('debug', $reportTemplates);       
if(!$reportTemplates){
          throw new NotFoundException(__('Report Template not found.'));
        }

        if($reportTemplates->reporting_period->term->is_active != 1){
          throw new BadRequestException("Term is not active for this Report Template.");
        }

        $this->loadModel('Scales');
        $impactScale = $this->Scales->findById($reportTemplates->impact_scale)
                                          ->contain(['ScaleValues'])
                                          ->first();

        $academicScale = $this->Scales->findById($reportTemplates->academic_scale)
                                            ->contain(['ScaleValues'])
                                            ->first();

        $this->loadModel('ReportSettings');
        $settings = $this->ReportSettings->find()
                                      ->where(['ReportSettings.grade_id' => $gradeId, 'report_template_id' => $reportTemplates->id, 'course_id' => $courseId,'course_status' => 1])
                                      ->contain(['Courses','ReportTemplates'=> function($q) use($studentId, $courseId){
                                        return $q->contain(['ReportTemplateCourseScores' => function($q) use($studentId, $courseId){
                                          return $q->where(['student_id' => $studentId, 'course_id' => $courseId]);
                                        },'ReportTemplateStrands' => function ($q) use($studentId, $courseId){
                                          return $q->where(['course_id' => $courseId])->contain(['Strands','ReportTemplateStrandScores' => function($q) use($studentId){
                                            return $q->where(['student_id' => $studentId]);
                                          }]);
                                        },'ReportTemplateStandards' => function($q) use($studentId, $courseId){
                                            return $q->where(['course_id' => $courseId])->contain(['Standards','ReportTemplateStandardScores' => function($q) use($studentId){
                                            return $q->where(['student_id' => $studentId]);
                                          }]);
                                        },'ReportTemplateImpacts' => function($q) use($studentId, $courseId){
                                          return $q->where(['course_id' => $courseId])->contain(['Impacts','ReportTemplateImpactScores' => function($q) use($studentId){
                                            return $q->where(['student_id' => $studentId]);
                                          }]);
                                        }]);
                                      }])
                                      ->first();

        $reportTemplateStrands = [];
        $reportTemplateStandards = [];
        $reportTemplateImpacts = [];
        $standards = [];
        if($settings){

          if($settings->strand_status == 1){

              $reportTemplateStrands = (new Collection($settings->report_template->report_template_strands))->indexBy('strand_id')->toArray();

              $reportTemplateStandards = $settings->report_template->report_template_standards;

              $leftOvers = [];
              
              foreach ($reportTemplateStandards as $key => $value) {

                if(isset($reportTemplateStrands[$value->standard->strand_id])){
                  if(isset($reportTemplateStrands[$value->standard->strand_id]['report_standards'])){
                    $reportTemplateStrands[$value->standard->strand_id]['report_standards'][] = $value;
                  }else{
                    $reportTemplateStrands[$value->standard->strand_id]['report_standards'] = [];
                    $reportTemplateStrands[$value->standard->strand_id]['report_standards'][] = $value;
                  }
                }else{
                  $leftOvers[] = $value;
                }
              }

              $strands = [];

              if(!empty($leftOvers)){
                $strandIds = (new Collection($leftOvers))->extract('standard.strand_id')->toArray();
                
                $this->loadModel('Strands');
                $strands = $this->Strands->find()->where(['id IN' => $strandIds])->all()->indexBy('id')->toArray();

                foreach ($strands as $key => $strand) {
                  unset($strands[$key]);
                  $strands[$key]['strand'] = $strand;
                }

                foreach ($leftOvers as $key => $value) {

                  if(isset($strands[$value->standard->strand_id]['report_standards'])){
                    $strands[$value->standard->strand_id]['report_standards'][] = $value;
                    $strands[$value->standard->strand_id]['is_accessible'] = false;
                  }else{
                    $strands[$value->standard->strand_id]['report_standards'] = [];
                    $strands[$value->standard->strand_id]['report_standards'][] = $value;
                    $strands[$value->standard->strand_id]['is_accessible'] = false;
                  }
                }
              }
              $standards = array_merge($reportTemplateStrands,$strands);
          }

          if($settings->impact_status == 1){
            if(!empty($settings->report_template->report_template_impacts)){
              $reportTemplateImpacts = $settings->report_template->report_template_impacts;
            }
          }
         
        }

        unset($settings['report_template']['report_template_impacts']);
        unset($settings['report_template']['report_template_standards']);
        unset($settings['report_template']['report_template_strands']);

        $this->loadModel('SpecialServiceTypes');
        $specialServices = $this->SpecialServiceTypes->find()->all()->toArray();
        
        $this->set('student', $student);
        $this->set('reportTemplateSettings', $settings);
        $this->set('reportTemplateStrands', $standards);
        $this->set('impactScale', $impactScale);
        $this->set('academicScale', $academicScale);
        $this->set('reportTemplateImpacts', $reportTemplateImpacts);
        $this->set('service_types', $specialServices);
        $this->set('course_link', $courseLink);

        $this->set('_serialize', ['student','reportTemplateSettings','reportTemplateStrands','reportTemplateImpacts','impactScale','academicScale', 'service_types', 'course_link']);

    } 

 public function saveReportFeedback(){

    if(!$this->request->is(['post'])){
      throw new MethodNotAllowedException(__('BAD_REQUEST'));
    }
    $data = $this->request->data;

    if(!isset($data['student_id']) || !$data['student_id']){
      throw new BadRequestException("Missing Student id");
    }
  
    $studentId = $data['student_id'];
    
    $connection = ConnectionManager::get('default');
    $response = $connection->transactional(function () use ($data,$studentId){

      $reportTemplateCourseScores = [];
      if(!empty($data['report_template_course_score'])){
        // pr($data['report_template_course_score']['report_tempate_id']);die;
        if(!isset($data['report_template_course_score']['report_tempate_id']) || !$data['report_template_course_score']['report_tempate_id']){
          throw new BadRequestException("Missing Report Template id");
        }
        if(!isset($data['report_template_course_score']['course_id']) || !$data['report_template_course_score']['course_id']){
          throw new BadRequestException("Missing Course id");
        }
        
        $courseId = $data['report_template_course_score']['course_id'];
        $reportTemplateId = $data['report_template_course_score']['report_tempate_id'];
        // pr($reportTemplateId);die;
        $this->loadModel('ReportTemplateCourseScores');

        $data['report_template_course_score']['student_id'] = $studentId;
        // $data['report_template_course_score']['is_completed'] = 0;
        if(!isset($data['report_template_course_score']['is_completed'])){
          throw new NotFoundException("Parameter is_completed is missing.");
        }

        if(!$data['report_template_course_score']['is_completed']){
          $is_completed = $this->ReportTemplateCourseScores->find()
                                                    ->where([
                                                          'report_template_id' => $reportTemplateId,
                                                          'course_id' => $courseId,
                                                          'student_id' => $studentId   
                                                        ])
                                                    ->first();
            if($is_completed && $is_completed->is_completed){
              $data['report_template_course_score']['is_completed'] = 1;
            }
        }

        $delete = $this->ReportTemplateCourseScores->deleteAll([
                                                                  'report_template_id' => $reportTemplateId,
                                                                  'course_id' => $courseId,
                                                                  'student_id' => $studentId   
                                                                ]);          
        
        $data['report_template_course_score']['report_template_id'] = $reportTemplateId;
        $reportTemplateCourseScores = $this->ReportTemplateCourseScores->newEntity();
        $reportTemplateCourseScores = $this->ReportTemplateCourseScores->patchEntity($reportTemplateCourseScores, $data['report_template_course_score']);

        if (!$this->ReportTemplateCourseScores->save($reportTemplateCourseScores)) {   
          Log::write('error',$reportTemplateCourseScores);
          throw new Exception("ReportTemplateCourseScores could not be saved.");
        }
        Log::write('debug',$reportTemplateCourseScores);
      }

      $reportTemplateStandardScores = [];
      if(!empty($data['report_template_standard_scores'])){
        $this->loadModel('ReportTemplateStandardScores');
        $reportTemplateStandardIds = (new Collection($data['report_template_standard_scores']))->extract('report_template_standard_id')->toArray();
        
        $reportTemplateStandardIds = array_unique($reportTemplateStandardIds);
        
        $delete = $this->ReportTemplateStandardScores->deleteAll([
                                                                    'student_id' => $studentId,
                                                                    'report_template_standard_id IN' => $reportTemplateStandardIds
                                                                  ]);          
        

        $reportTemplateStandardScoreData = (new Collection($data['report_template_standard_scores']))->map(function($value, $key) use($studentId){
                                              $value['student_id'] = $studentId;
                                              return $value;
                                          })
                                          ->toArray();

        $data['report_template_standard_scores'] = $reportTemplateStandardScoreData;

        $reportTemplateStandardScores = $this->ReportTemplateStandardScores->newEntities($data['report_template_standard_scores']);
        
        $reportTemplateStandardScores = $this->ReportTemplateStandardScores->patchEntities($reportTemplateStandardScores, $data['report_template_standard_scores']);

        if (!$this->ReportTemplateStandardScores->saveMany($reportTemplateStandardScores)) {   
          Log::write('error',$reportTemplateStandardScores);
          throw new Exception("ReportTemplateStandardScores could not be saved.");
        }

        Log::write('debug',$reportTemplateStandardScores);
      }

      $reportTemplateStrandScores = [];
      if(!empty($data['report_template_strand_scores'])){
        $this->loadModel('ReportTemplateStrandScores');
        $reportTemplateStrandIds = (new Collection($data['report_template_strand_scores']))->extract('report_template_strand_id')->toArray();
        
        $reportTemplateStrandIds = array_unique($reportTemplateStrandIds);
        
        $delete = $this->ReportTemplateStrandScores->deleteAll([
                                                                    'student_id' => $studentId,
                                                                    'report_template_strand_id IN' => $reportTemplateStrandIds
                                                                  ]);          
        

        $reportTemplateStrandScoreData = (new Collection($data['report_template_strand_scores']))->map(function($value, $key) use($studentId){
                                              $value['student_id'] = $studentId;
                                              return $value;
                                          })
                                          ->toArray();

        $data['report_template_strand_scores'] = $reportTemplateStrandScoreData;
        
        $reportTemplateStrandScores = $this->ReportTemplateStrandScores->newEntities($data['report_template_strand_scores']);
        
        $reportTemplateStrandScores = $this->ReportTemplateStrandScores->patchEntities($reportTemplateStrandScores, $data['report_template_strand_scores']);
        
        if (!$this->ReportTemplateStrandScores->saveMany($reportTemplateStrandScores)) {   
          Log::write('error',$reportTemplateStrandScores);
          throw new Exception("ReportTemplateStrandScores could not be saved.");
        }

        Log::write('debug',$reportTemplateStrandScores);
      }

      $reportTemplateImpactScores = [];
      if(!empty($data['report_template_impact_scores'])){
        $this->loadModel('ReportTemplateImpactScores');
        $reportTemplateImpactIds = (new Collection($data['report_template_impact_scores']))->extract('report_template_impact_id')->toArray();
        
        $reportTemplateImpactIds = array_unique($reportTemplateImpactIds);
        
        $delete = $this->ReportTemplateImpactScores->deleteAll([
                                                                    'student_id' => $studentId,
                                                                    'report_template_impact_id IN' => $reportTemplateImpactIds
                                                                  ]);          
        

        $reportTemplateImpactScoreData = (new Collection($data['report_template_impact_scores']))->map(function($value, $key) use($studentId){
                                              $value['student_id'] = $studentId;
                                              return $value;
                                          })
                                          ->toArray();

        $data['report_template_impact_scores'] = $reportTemplateImpactScoreData;
        
        $reportTemplateImpactScores = $this->ReportTemplateImpactScores->newEntities($data['report_template_impact_scores']);
        
        $reportTemplateImpactScores = $this->ReportTemplateImpactScores->patchEntities($reportTemplateImpactScores, $data['report_template_impact_scores']);
        
        if (!$this->ReportTemplateImpactScores->saveMany($reportTemplateImpactScores)) {   
          Log::write('error',$reportTemplateImpactScores);
          throw new Exception("ReportTemplateImpactScores could not be saved.");
        }

        Log::write('debug',$reportTemplateImpactScores);
      }

      $success = true;

      $this->set('status',$success);
      $this->set('reportTemplateCourseScores',$reportTemplateCourseScores);
      $this->set('reportTemplateImpactScores',$reportTemplateImpactScores);
      $this->set('reportTemplateStrandScores',$reportTemplateStrandScores);
      $this->set('reportTemplateStandardScores',$reportTemplateStandardScores);
      $this->set('_serialize', ['status','reportTemplateCourseScores','reportTemplateStandardScores','reportTemplateStrandScores','reportTemplateImpactScores']);
    
    });
  }

  public function getCourseLinks($sectionId=null, $studentId=null){
    if(!$this->request->is('get')){
          throw new MethodNotAllowedException("Method not allowed", 1); 
        }

    if(!isset($sectionId) || !$sectionId){
          throw new BadRequestException("Missing Section Id");
      }

    if(!isset($studentId) || !$studentId){
          throw new BadRequestException("Missing Student Id");
        }

    $this->loadModel('Sections');
    $section = $this->Sections->findById($sectionId)
                              ->contain(['Courses'])
                              ->first();
        
    $gradeId = $section->course->grade_id;
    $this->loadModel('ReportTemplates');
   
    $reportTemplates = $this->ReportTemplates->find()
                                             ->contain(['ReportingPeriods.Terms.Sections' => function($q) use ($sectionId){
                                                 return $q->where(['Sections.id' => $sectionId ]);
                                              },'ReportSettings'])
                                             ->matching('ReportingPeriods.Terms.Sections', function($q) use ($sectionId){
                                                 return $q->where(['Sections.id' => $sectionId ]);
                                              })
                                             ->matching('ReportTemplateGrades', function($q) use($gradeId){
                                                      return $q->where(['grade_id' => $gradeId]);
                                              })
                                             ->first();
          
    $courseLink = [];
    $user = $this->Auth->user();
    if($user['role']->label == 'Admin' || $user['role']->label == 'School Admin'){
        $reportSettingsWithCourses = new Collection($reportTemplates->report_settings);
        unset($reportTemplates->report_settings);
        $selectedCourses = $reportSettingsWithCourses->extract('course_id')->toArray();
        $selectedCourses = array_unique($selectedCourses);
        $sectionCourses = $this->Sections->Courses->find()
                                                  ->where(['Courses.id IN' => $selectedCourses])
                                                  ->indexBy('id')
                                                  ->toArray();
        // pr($sectionCourses); die;
        $sectionData = $this->Sections->find()
                                      ->matching('SectionStudents.Students' ,function($q) use($studentId){
                                                                                      return $q->where(['Students.is_active' => 1, 'Students.id' => $studentId]);
                                                   })
                                      ->contain(['Courses'])
                                      ->where([
                                                    'Sections.term_id' => $reportTemplates->reporting_period->term_id,
                                                    'Sections.course_id IN' => $selectedCourses,
                                                 ])
                                      ->all();

        if(!empty($sectionData->toArray())){
            $sectionIds = $sectionData->extract('id')->toArray();

            $this->loadModel('SectionStudents');
            $sectionStudents = $this->SectionStudents->findByStudentId($studentId)
                                                     ->where(['section_id IN' => $sectionIds])
                                                     ->groupBy('section_id')
                                                     ->toArray();

            $sectionData = $sectionData->map(function ($value,$key) use($sectionCourses, $sectionStudents){
                                                        $value->course = $sectionCourses[$value->course_id];
                                                        $value->section_students = $sectionStudents[$value->id];
                                                        return $value;
                                                  });
            

            $sectionArray = $sectionData->combine('id','course')
                                        ->toArray();
            // pr($sectionArray); die;
            foreach ($sectionArray as $key => $value) {
              $url = Router::url('/', true);
              $courseLink[] = [
                                'name' => $value->name,
                                'link' => $url.'teachers#/reports/section/'.$key.'/'.$studentId

                              ];
            }

          }

        }
        // pr($courseLink); die;

      $this->set('course_link', $courseLink);

      $this->set('_serialize', ['course_link']);

   }
}
