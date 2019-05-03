<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Collection\Collection;
use Cake\Utility\Text;
use Cake\Log\Log;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use Faker\Factory as Faker;
use Cake\Network\Exception\BadRequestException;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
/**
 * CustomUpload shell command.
 */
class CustomUploadShell extends Shell
{

    public function getCsvData($tableName, $fileName){
        
        while(in_array($fileName, [null, false, ""])){
          
          $this->out('Filename not entered please try again.');
          $fileName = $this->in("Please enter the file name without the extension for ".$tableName);
        }

        $fileName = $fileName.'.csv';
        if (!file_exists($fileName) ) {
            
            $this->out('File not found.');
            return false;
        }        
        ini_set('auto_detect_line_endings', true);
        $fp = fopen($fileName,'r');
        $data = array();
        while (($row = fgetcsv($fp)) !== FALSE) {
          if(str_replace(" ", "", implode('', $row)) == ''){
            continue;
          }
          $data[] = $row;

        }
        $headings = $data[0];
        foreach ($headings as $key => $value) {
            $headings[$key] = strtolower(Text::slug($value, '_'));
        }
        unset($data[0]);

        $tempData = []; 
        foreach ($data as $key => $value) {

            $value = (new Collection($value))->map(function($val, $key){
                return trim($val);
            })->toArray();
          
            $tempData[] = array_combine($headings, $value);
        }
        if(empty($tempData)){
            
            $this->out('No Data found in the uploaded file.');
            return false;
        }

        return $tempData;
    }

    public function uploadStrandsForMiddleSchool($reportTemplateId, $reportTemplatePageId){

        $this->out('Inside uploadStrandsForMiddleSchool. Creating data for new curriculum.');
        $this->loadModel('Curriculums');

        if(!$reportTemplateId || in_array($reportTemplateId, [null, false, ''])){
            $this->out('Report Template Id is required.');
            die;
        }

        $curriculum = [
            'name' => 'Middle School Standards',
            'description' => 'Middle School Standards',
            'learning_areas' => [
                ['name' => 'Mathematics', 'description' => 'Mathematics'],
                ['name' => 'Visual Arts', 'description' => 'Visual Arts'],
                ['name' => 'Humanities', 'description' => 'Humanities'],
                ['name' => 'Science', 'description' => 'Science'],
                ['name' => 'Health', 'description' => 'Health'],
                ['name' => 'Physical Education', 'description' => 'Physical'],
                ['name' => 'Drama', 'description' => 'Education Drama'],
                ['name' => 'Choir', 'description' => 'Choir'],
                ['name' => 'Band', 'description' => 'Band'],
                ['name' => 'Strings', 'description' => 'Strings'],
                ['name' => 'World Language', 'description' => 'World Language']
            ]
        ];
        $curriculum = $this->Curriculums->newEntity($curriculum);
        $this->Curriculums->save($curriculum);

        $learningAreas = (new Collection($curriculum['learning_areas']))->combine('name', 'id')->toArray();

        $results = $this->getCsvData('Strands', 'middleSchoolstrands');
        $processedCourses = [];
        $coursesNotFound = [];
        $this->loadModel('Strands');
        $this->loadModel('Courses');
        foreach ($results as $key => $row) {
           
           $courseName = $row['course_name'];
           $this->out('Finding courses having name '.$courseName);
           $courses = $this->Courses->find()->where(['name LIKE' => '%'.$courseName.'%']);
           if(empty($courses->toArray())){
            $coursesNotFound[] = $courseName;
           }

           if(isset($row['strand']) && !in_array($row['strand'], [null, false, ''])){
               $strandData = [
                'name' => $row['strand'],
                'description' => $row['strand'],
                'learning_area_id' => $learningAreas[$row['subject']],
                'code' => substr($row['strand'], 0, 2),
               ];

               $this->out('Finding strand - '.$strandData['name']);
               $strand = $this->Strands->find()->where($strandData)->first();
               if(!$strand){
                $this->out('Strand not found creating new.');
                $strand = $this->Strands->newEntity($strandData);
                if(!$this->Strands->save($strand)){
                    $this->out('Strand Could not be saved.');
                    print_r($strand);
                    continue;
                }
               }    
                if(!in_array($courseName, $coursesNotFound)){
                   $this->out('Creating Data for course strands.');
                   $courseStrandsData = $courses->map(function($value, $key) use ($strand){
                                            return [
                                                    'course_id' => $value->id, 
                                                    'grade_id' => $value->grade_id,
                                                    'strand_id' => $strand->id
                                                   ];
                                        })
                                        ->toArray();

                    // $this->out('Saving course strands.');
                    // $courseStrands = $this->Courses->CourseStrands->newEntities($courseStrandsData);
                    // if(!$this->Courses->CourseStrands->saveMany($courseStrands)){
                    //     $this->out('Course Strands Coould not be saved.');
                    //     print_r($courseStrands);
                    //     continue;
                    // }
                }
            }
            
           if(isset($strandData)){
               

                $this->out('Creating Data for report template course strands and report template strands.');
                $reportTemplateCourseStrands = $reportTemplateStrands = 
                (new Collection($courseStrandsData))->map(function($value, $key) use ($reportTemplateId){
                    $value['report_template_id'] = $reportTemplateId;
                    return $value;
                })->toArray();

                $this->out('Saving report Template course strands.');
                $reportTemplateCourseStrands = $this->Strands->ReportTemplateCourseStrands->newEntities($reportTemplateCourseStrands);
                
                if(!$this->Strands->ReportTemplateCourseStrands->saveMany($reportTemplateCourseStrands)){
                    $this->out('reportTemplateCourseStrands Could not be saved.');
                    print_r($reportTemplateCourseStrands);
                }

                $this->out('Saving report Template strands.');
                $reportTemplateStrands = $this->Strands->ReportTemplateStrands->newEntities($reportTemplateStrands);
                if(!$this->Strands->ReportTemplateStrands->saveMany($reportTemplateStrands)){
                    $this->out('reportTemplateStrands Could not be saved.');
                    print_r($reportTemplateStrands);
                }
           }
           
            if(in_array($courseName, $processedCourses)){
                $this->out('Course Already Processed for report template');
                continue;
            }
            

            if(in_array($courseName, $coursesNotFound)){
                $this->out('Strand has been saved. Rest of the data will not be formed as course was not found.');
                continue;
            }



            $reportSettings = $courses->map(function($value, $key) use ($reportTemplateId, $reportTemplatePageId){

                $value = [
                            'course_id' => $value->id, 
                            'grade_id' => $value->grade_id,
                ];
                $value['report_template_id'] = $reportTemplateId;
                $value['course_status'] = 1;
                $value['strand_status'] = 1;
                $value['standard_status'] = 0;
                $value['impact_status'] = 1;
                $value['impact_comment_status'] = 1;
                $value['course_comment_status'] = 1;
                $value['strand_comment_status'] = 1;
                $value['report_template_page_id'] = $reportTemplatePageId;
                return $value;
            })->toArray();

            $this->out('Saving report Template settings.');
            $reportSettings = $this->Courses->ReportSettings->newEntities($reportSettings);
            if(!$this->Courses->ReportSettings->saveMany($reportSettings)){
                $this->out('reportSettings Coould not be saved.');
                print_r($reportSettings);
                continue;
            }

            $this->_createApproachesToLearning($reportTemplateId, $courses);
            $processedCourses[] = $courseName;
        }

        $this->out('Script has finished and courses not found on db are:');
        print_r(array_unique($coursesNotFound));
    }

    private function _createApproachesToLearning($reportTemplateId, $courses){
        
        if(empty($this->_impactIds)){
            $this->_createImpacts();
        }
        $this->out('Inside _createApproachesToLearning');
        $this->loadModel('ReportTemplateImpacts');
        $reportTemplateImpacts  = [];

        foreach ($courses as $key => $course) {
            foreach ($this->_impactIds as $impactId) {
                $data = [
                  'report_template_id' => $reportTemplateId,
                  'course_id' => $course->id,
                  'grade_id' => $course->grade_id,
                  'impact_id' => $impactId
                ];

                $reportTemplateImpact = $this->ReportTemplateImpacts->find()->where($data)->first();
                if(!$reportTemplateImpact){
                    $this->out('Creating new report template impact.');
                    $reportTemplateImpact = $this->ReportTemplateImpacts->newEntity($data);
                    if(!$this->ReportTemplateImpacts->save($reportTemplateImpact)){
                        $this->out('Report template impacts could not be saved.');
                        print_r($reportTemplateImpact);
                        continue;
                    }
                    $this->out('Report template  Impact saved.');
                } 

                $gradeImpactData = ['grade_id' => $course->grade_id, 'impact_id' => $impactId];
                $gradeImpact = $this->ReportTemplateImpacts->Impacts->GradeImpacts->find()->where($gradeImpactData)->first();
                if(!$gradeImpact){
                    $this->out('Creating new grade impact.');
                    $gradeImpact = $this->ReportTemplateImpacts->Impacts->GradeImpacts->newEntity($gradeImpactData);
                    if(!$this->ReportTemplateImpacts->Impacts->GradeImpacts->save($gradeImpact)){
                        $this->out('Grade impacts could not be saved.');
                        print_r($gradeImpact);
                        continue;
                    }
                    $this->out('Grade impact saved.');
                }
            }            
        }
    }

    private function _createImpacts(){
        $this->out('Inside create impacts');
        $this->loadModel('ImpactCategories');
        $impactCategory = [
            'name' => 'Approaches To Learning',
            'description' => 'Approaches To Learning',
            'impacts' => [
                [
                    'name' => 'Self-Motivated Learning', 
                    'description' => 'Works independently and seeks assistance when required. Reflects on his/her own learning and takes appropriate steps to improve.', 
                    'is_selectable' => 1
                ],
                [
                    'name' => 'Managing Assignments',
                    'description' => 'Completes assignments on time, including homework and music practice.',
                    'is_selectable' => 1
                ],
                [
                    'name' => 'Preparedness for Class',
                    'description' => 'Is punctual, ready and prepared to begin each class with the correct materials.',
                    'is_selectable' => 1
                ],
                [
                    'name' => 'Engagement in Class',
                    'description' => 'Pays attention, follows directions and uses time productively in class. Works well with others in the class. Contributes appropriately to all class activities.',
                    'is_selectable' => 1
                ],
                [
                    'name' => 'Social Responsibility',
                    'description' => 'Respects class and school rules.',
                    'is_selectable' => 1
                ]

            ]
        ];

        $impactCategory = $this->ImpactCategories->newEntity($impactCategory);
        if(!$this->ImpactCategories->save($impactCategory)){
            $this->out('Impacts could not be saved.');
            print_r($impactCategory);
        }
        $this->_impactIds = (new Collection($impactCategory->impacts))->extract('id');
    }   

public function updateReports($reportTemplateId, $reportPageId){
        $this->loadModel('Reports');
        $this->out('Inside updateReports');
        $reportSettings = $this->Reports->ReportTemplates->ReportSettings->find()->where(['report_template_id' => $reportTemplateId])->all();
        foreach ($reportSettings as $key => $value) {
            $data = [
                'course_id' => $value->course_id,
                'report_template_id' => $value->report_template_id,
                'grade_id' => $value->grade_id
            ];
            $this->out('Checking if report already exists.');
            $report = $this->Reports->find()->where($data)->first();
            if($report){
                $this->out("Report already exists.");
                continue;
            }
            $data['sort_order'] = 1;
            
            $this->out("Report not found new report will be created. Setting the sort order.");
            $report = $this->Reports
                           ->find()
                           ->where([
                                    'report_template_id' => $value->report_template_id,
                                    'grade_id' => $value->grade_id
                                ])
                           ->last();
            if($report){
                $data['sort_order'] = $report->sort_order + 1;
            }else{
                unset($data['course_id']);
                $data['report_page_id'] = $reportPageId;

            }
            $this->out('Trying to save report.');
            $report = $this->Reports->newEntity($data);
            if(!$this->Reports->save($report)){
                $this->out('Reprt could not be saved.');
                print_r($report);
            }
        }

    }
public function test(){
$email = new Email('aasMail');
try {
$email
->from(['ms.office@aas.ru' => 'noah.bohnen@aas.ru'])
->replyTo('noah.bohnen@aas.ru')
    ->to('james.kukreja@twinspark.co')
    ->subject('About 1')
//->domain('learningboard-v2.aas.ru')    
->send('My message');

$this->out('aftersed');
} catch ( \Exception $e) {
print_r($e);
die('in error');
}
}

public function duplicateReportTemplate(){

        $this->out('In duplicate report Template id 1 as report template id 2.');
        $queries = [
            "Insert into report_pages (id, title, body, created, modified) (Select '2' as id, 'MS Sem2 Cover Page' as title, body, created, modified from report_pages where id = 1);",

            "Delete from reports where report_template_id = 2;",

            "Insert into reports (report_template_id, grade_id, report_page_id, course_id, sort_order, created, modified) (Select '2' as report_template_id, grade_id, IF(report_page_id,2, NULL) as report_page_id, IF(course_id,course_id, NULL) as course_id, sort_order, created, modified from reports where report_template_id = 1);",

            "Insert into report_template_pages (id, report_template_type_id, title, body, created, modified) (Select id+3,report_template_type_id, Replace(title, 'MSQ3', 'MSSem2') as title, body, created, modified from report_template_pages where id in (4,5,6));",

            "Delete from report_settings where report_template_id = 2;",

            "Insert into report_settings (report_template_id,grade_id,course_id,course_status,course_comment_status,strand_status,strand_comment_status,standard_status,standard_comment_status,impact_status,impact_comment_status,created,modified,report_template_page_id,course_scale_status,show_teacher_reflection,show_student_reflection,show_special_services) (select '2' as report_template_id,grade_id,course_id,course_status,course_comment_status,strand_status,strand_comment_status,standard_status,standard_comment_status,impact_status,impact_comment_status,created,modified,report_template_page_id + 3 as report_template_page_id,course_scale_status,show_teacher_reflection,show_student_reflection,show_special_services from report_settings where report_template_id = 1);",

            "Delete from report_template_course_strands where report_template_id = 2;",

            "Insert into report_template_course_strands (report_template_id, course_id, grade_id, strand_id, created, modified) (Select '2' as report_template_id, course_id, grade_id, strand_id, created, modified from report_template_course_strands where report_template_id = 1);",

            "Delete from report_template_impacts where report_template_id = 2;",

            "Insert into report_template_impacts (report_template_id, course_id, impact_id, created, modified, grade_id) (Select '2' as report_template_id, course_id, impact_id, created, modified, grade_id from report_template_impacts where report_template_id = 1);",

            "Delete from report_template_strands where report_template_id = 2;",

            "Insert into report_template_strands (report_template_id, course_id, strand_id, created, modified, grade_id) (Select '2' as report_template_id, course_id, strand_id, created, modified, grade_id from report_template_strands where report_template_id = 1);"
        ];

        foreach($queries as $query){
            $this->out('Executing Query : '.$query);
            $results = $this->connectWithMigrationDB($query);
            print_r($results);
        }
        $this->out('all queries have been executed. Report template has been duplicated.');
    }

 public function connectWithMigrationDB($query){

        $conn = ConnectionManager::get('default');
        $response = $conn->execute($query);

        return $response;
    }

public function uploadStandardsForES($reportTemplateId, $reportTemplatePageId, $reportPageId){

        $this->out('Inside uploadStandardsForES. Creating data for new curriculum.');
        $this->loadModel('Curriculums');
        $this->loadModel('ReportTemplates');
        $this->loadModel('Courses');

        if(!$reportTemplateId || in_array($reportTemplateId, [null, false, ''])){
            $this->out('Report Template Id is required.');
            die;
        }

        $reportTemplateGrades = $this->ReportTemplates->ReportTemplateGrades->findByReportTemplateId($reportTemplateId)->extract('grade_id')->toArray();

        $coursesMap = [
            'Literacy' => ['Literacy', 'Language and Literacy'],
            'PE' => ['PE'], 
            'Music' => ['Art and Music', 'Band', 'Choir', 'Music', 'Strings'], 
            'Art' => ['Art and Music', 'Art'], 
            'WL' => ['French', 'Russian', 'Spanish'], 
            'Math' => ['Mathematics', 'Mathematics and Cognition']
        ];

        $curriculumData = [
            'name' => 'Elementary School Reporting Standards',
            'description' => 'Elementary School Reporting Standards',
            'learning_areas' => [
                ['name' => 'Literacy', 'description' => 'Literacy'],
                ['name' => 'PE', 'description' => 'PE'],
                ['name' => 'Music', 'description' => 'Music'],
                ['name' => 'Art', 'description' => 'Art'],
                ['name' => 'WL', 'description' => 'WL'],
                ['name' => 'Math', 'description' => 'Math']
            ]
        ];
        $grades = $this->Courses->Grades->find()->combine('name', 'id')->toArray();

        $curriculum = $this->Curriculums->findByName('Elementary School Reporting Standards')->contain(['LearningAreas'])->first();
        if(!$curriculum){
            $curriculum = $this->Curriculums->newEntity($curriculumData);
            $this->Curriculums->save($curriculum);
        }
        $learningAreas = (new Collection($curriculum['learning_areas']))->combine('name', 'id')->toArray();

        $results = $this->getCsvData('Standards', 'es_standards');
        $processedCourses = [];
        $processedCourseGradeStrands = [];
        $coursesNotFound = [];
        $this->loadModel('Strands');
        $this->loadModel('Courses');
        foreach ($results as $key => $row) {
           if(!in_array($grades[$row['grade']], $reportTemplateGrades)){
            $this->out('Grade Does not belong to Report Template ignoring value.');
            continue;
           }           
           $courseNames = $coursesMap[$row['subject_area']];

           foreach ($courseNames as $courseName) {
               $this->out('Finding courses having name '.$courseName);
               $courses = $this->Courses->find()->where(['name LIKE' => $courseName.'%', 'grade_id' => $grades[$row['grade']]]);
               if(empty($courses->toArray())){
                $coursesNotFound[] = $courseName.$grades[$row['grade']];
               }
            
               if(isset($row['reporting_area']) && !in_array($row['reporting_area'], [null, false, ''])){
                   $strandData = [
                    'name' => $row['reporting_area'],
                    'description' => $row['reporting_area'],
                    'learning_area_id' => $learningAreas[$row['subject_area']],
                    'code' => substr($row['reporting_area'], 0, 2),
                   ];

                   $this->out('Finding strand - '.$strandData['name']);
                   $strand = $this->Strands->find()->where($strandData)->first();
                   if(!$strand){
                    $this->out('Strand not found creating new.');
                    $strand = $this->Strands->newEntity($strandData);
                    if(!$this->Strands->save($strand)){
                        $this->out('Strand Could not be saved.');
                        print_r($strand);
                        continue;
                    }
                   }

                   $standardData = [
                    'name' => $row['content_standard'],
                    'description' => $row['content_standard'],
                    'strand_id' => $strand->id,
                    'code' => substr($row['reporting_area'], 0, 2).'-'.substr($row['content_standard'], 0, 2),
                    'is_selectable' => 1
                   ]; 

                   
                   
                   if(!in_array($courseName.$grades[$row['grade']], $coursesNotFound)){
                        
                        if(!in_array($courseName.$grades[$row['grade']].$strand->id, $processedCourseGradeStrands)){
                            $this->out('Creating Data for course strands.');
                            $courseStrandsData = $courses->map(function($value, $key) use ($strand){
                                                    return [
                                                            'course_id' => $value->id, 
                                                            'grade_id' => $value->grade_id,
                                                            'strand_id' => $strand->id
                                                           ];
                                                })
                                                ->toArray();
                            $this->out('Creating Data for report template course strands and report template strands.');
                            $reportTemplateCourseStrands = $reportTemplateStrands = 
                            (new Collection($courseStrandsData))->map(function($value, $key) use ($reportTemplateId){
                                $value['report_template_id'] = $reportTemplateId;
                                return $value;
                            })->toArray();

                            $this->out('Saving report Template course strands.');
                            $reportTemplateCourseStrands = $this->Strands->ReportTemplateCourseStrands->newEntities($reportTemplateCourseStrands);
                            
                            if(!$this->Strands->ReportTemplateCourseStrands->saveMany($reportTemplateCourseStrands)){
                                $this->out('reportTemplateCourseStrands Could not be saved.');
                                print_r($reportTemplateCourseStrands);
                            }

                            $this->out('Saving report Template strands.');
                            $reportTemplateStrands = $this->Strands->ReportTemplateStrands->newEntities($reportTemplateStrands);
                            if(!$this->Strands->ReportTemplateStrands->saveMany($reportTemplateStrands)){
                                $this->out('reportTemplateStrands Could not be saved.');
                                print_r($reportTemplateStrands);
                            }
                            $processedCourseGradeStrands[] = $courseName.$grades[$row['grade']].$strand->id;
                        }else{
                            $this->out('Course Already processed for this strand.');
                        }
                    }else{

                        $this->out('Strand has been saved. Rest of the data will not be formed as course was not found.');
                        continue;
                    }

                    $standard = $this->Strands->Standards->find()->where($standardData)->first();
                    if(!$standard){
                        $this->out('Standard not found creating new.');
                        $standard = $this->Strands->Standards->newEntity($standardData);
                        if(!$this->Strands->Standards->save($standard)){
                            $this->out('Standard Could not be saved.');
                            print_r($standard);
                            continue;
                        }
                    }   

                    $this->out('Creating Standard Grade');
                    $standardGrade = $this->Strands->Standards->StandardGrades->newEntity(['standard_id' => $standard->id, 'grade_id' => $grades[$row['grade']]]);
                    if(!$this->Strands->Standards->StandardGrades->save($standardGrade)){
                        $this->out('standardGrade Could not be saved.');
                        print_r($standardGrade);
                        continue;
                    }

                    if(!in_array($courseName.$grades[$row['grade']], $coursesNotFound)){
                        $this->out('Creating data for report template standards');
                        $reportTemplateStandards =(new Collection($courses))->map(function($value, $key) use ($reportTemplateId, $standard){
                            $value = [
                                'course_id' => $value->id,
                                'grade_id' => $value->grade_id,
                                'report_template_id' => $reportTemplateId,
                                'standard_id' => $standard->id
                            ];
                            return $value;
                        })->toArray();
                        $reportTemplateStandards = $this->Strands->Standards->ReportTemplateStandards->newEntities($reportTemplateStandards);
                        if(!$this->Strands->Standards->ReportTemplateStandards->saveMany($reportTemplateStandards)){
                            $this->out('reportTemplateStandards Could not be saved.');
                            print_r($reportTemplateStandards);
                        }
                    }
                    
                }
                
               
                if(in_array($courseName.$grades[$row['grade']], $processedCourses)){
                    $this->out('Course Already Processed for report template. Report setting has already been added.');
                    continue;
                }


                $reportSettings = $courses->map(function($value, $key) use ($reportTemplateId, $reportTemplatePageId){

                    $value = [
                                'course_id' => $value->id, 
                                'grade_id' => $value->grade_id,
                    ];
                    $value['report_template_id'] = $reportTemplateId;
                    $value['course_status'] = 1;
                    $value['strand_status'] = 1;
                    $value['standard_status'] = 1;
                    $value['impact_status'] = 0;
                    $value['impact_comment_status'] = 0;
                    $value['course_comment_status'] = 1;
                    $value['strand_comment_status'] = 1;
                    $value['report_template_page_id'] = $reportTemplatePageId;
                    return $value;
                })->toArray();

                $this->out('Saving report Template settings.');
                $reportSettings = $this->Courses->ReportSettings->newEntities($reportSettings);
                if(!$this->Courses->ReportSettings->saveMany($reportSettings)){
                    $this->out('reportSettings Could not be saved.');
                    print_r($reportSettings);
                    continue;
                }

                $processedCourses[] = $courseName.$grades[$row['grade']];
            }


           }

            $this->updateReports($reportTemplateId, $reportPageId);
            $this->out('Script has finished and courses not found on db are:');
            print_r(array_unique($coursesNotFound));

    }
	

	public function uploadStandardsForESUoi($campusId, $reportTemplateId, $reportTemplatePageId, $reportPageId){

        $this->out('Inside uploadStandardsForES. Creating data for new curriculum.');
        $this->loadModel('Curriculums');
        $this->loadModel('ReportTemplates');
        $this->loadModel('Courses');

        if(!$reportTemplateId || in_array($reportTemplateId, [null, false, ''])){
            $this->out('Report Template Id is required.');
            die;
        }

        if(!$campusId || in_array($campusId, [null, false, ''])){
            $this->out('campusId is required.');
            die;
        }

        $reportTemplateGrades = $this->ReportTemplates->ReportTemplateGrades->findByReportTemplateId($reportTemplateId)->extract('grade_id')->toArray();

        $grades = $this->Courses->Grades->find()->combine('name', 'id')->toArray();

        $curriculum = $this->Curriculums->findByName('Elementary School Reporting Standards')->first();

        $learningArea =  $this->Curriculums->LearningAreas->findByName('UOI')->matching('Curriculums', function($q){
            return $q->where(['Curriculums.name' => 'Elementary School Reporting Standards']);
        })->first();
        if(!$learningArea){
            $this->out('Learning Area not found. Creating New.');
            $learningArea = $this->Curriculums->LearningAreas->newEntity(['name' => 'UOI', 'description' => 'UOI', 'curriculum_id' => $curriculum->id]);
            if(!$this->Curriculums->LearningAreas->save($learningArea)){
                $this->out('Learning Area Could not be saved.');
                print_r($learningArea);
                return;
            }
        }

        $results = $this->getCsvData('Standards', 'pyp_standards');
        $processedCourses = [];
        $processedCourseGradeStrands = [];
        $coursesNotFound = [];
        $standardTypes = ['uoi_line_of_inquiry_1', 'uoi_line_of_inquiry_2', 'uoi_line_of_inquiry_3', 'uoi_line_of_inquiry_4'];

        $this->loadModel('Strands');
        foreach ($results as $key => $row) {
           if(!isset($grades[$row['grade']])){
            $this->out('Invalid Grade');
            continue;
           }
                      
           if(!in_array($grades[$row['grade']], $reportTemplateGrades)){
            $this->out('Grade Does not belong to Report Template ignoring value.');
            continue;
           }

           $courseName = $row['course'];

           $this->out('Finding courses having name '.$courseName);
           $courses = $this->Courses->find()->where(['name LIKE' => $courseName.'%', 'grade_id' => $grades[$row['grade']]]);
           if(empty($courses->toArray())){
            $coursesNotFound[] = $courseName.$grades[$row['grade']];
           }
        
           if(isset($row['uoi_theme_title']) && !in_array($row['uoi_theme_title'], [null, false, ''])){
               $strandData = [
                'name' => $row['uoi_theme_title'],
                'description' => $row['uoi_central_idea'],
                'learning_area_id' => $learningArea->id,
                'code' => substr($row['uoi_theme_title'], 0, 2),
               ];

               
                $this->out('creating new Strand.');
                $strand = $this->Strands->newEntity($strandData);
                if(!$this->Strands->save($strand)){
                    $this->out('Strand Could not be saved.');
                    print_r($strand);
                    continue;
                }

                $standardData = [];

                foreach($standardTypes as $standardType){
                    if(isset($row[$standardType]) && !in_array($row[$standardType], [null, false, ''])){

                        $standardData[] = [
                            'name' => $row[$standardType],
                            'description' => $row[$standardType],
                            'strand_id' => $strand->id,
                            'code' => substr($row['uoi_theme_title'], 0, 2).'-'.substr($row[$standardType], 0, 2),
                            'is_selectable' => 1,
                            'standard_grades' => [['grade_id' => $grades[$row['grade']]]] 
                        ]; 
                        
                    }
                }

               if(!in_array($courseName.$grades[$row['grade']], $coursesNotFound)){
                    
                    $this->out('Creating Data for course strands.');
                    $courseStrandsData = $courses->map(function($value, $key) use ($strand){
                                            return [
                                                    'course_id' => $value->id, 
                                                    'grade_id' => $value->grade_id,
                                                    'strand_id' => $strand->id
                                                   ];
                                        })
                                        ->toArray();
                    $this->out('Creating Data for report template course strands and report template strands.');
                    $reportTemplateCourseStrands = $reportTemplateStrands = 
                    (new Collection($courseStrandsData))->map(function($value, $key) use ($reportTemplateId){
                        $value['report_template_id'] = $reportTemplateId;
                        return $value;
                    })->toArray();

                    $this->out('Saving report Template course strands.');
                    $reportTemplateCourseStrands = $this->Strands->ReportTemplateCourseStrands->newEntities($reportTemplateCourseStrands);
                    
                    if(!$this->Strands->ReportTemplateCourseStrands->saveMany($reportTemplateCourseStrands)){
                        $this->out('reportTemplateCourseStrands Could not be saved.');
                        print_r($reportTemplateCourseStrands);
                    }

                    $this->out('Saving report Template strands.');
                    $reportTemplateStrands = $this->Strands->ReportTemplateStrands->newEntities($reportTemplateStrands);
                    if(!$this->Strands->ReportTemplateStrands->saveMany($reportTemplateStrands)){
                        $this->out('reportTemplateStrands Could not be saved.');
                        print_r($reportTemplateStrands);
                    }
                    $processedCourseGradeStrands[] = $courseName.$grades[$row['grade']].$strand->id;

                }else{

                    $this->out('Strand has been saved. Rest of the data will not be formed as course was not found.');
                    continue;
                }

                if(!empty($standardData)){
                    $this->out('creating new Standards');
                    $standards = $this->Strands->Standards->newEntities($standardData);
                    if(!$this->Strands->Standards->saveMany($standards)){
                        $this->out('Standards Could not be saved.');
                        print_r($standard);
                        continue;
                    }
                    if(!in_array($courseName.$grades[$row['grade']], $coursesNotFound)){
                        $this->out('Creating data for report template standards');
                        $reportTemplateStandards = [];
                        foreach($standards as $standard){
                            foreach($courses as $course){

                                $reportTemplateStandards[] = [
                                    'course_id' => $course->id,
                                    'grade_id' => $course->grade_id,
                                    'report_template_id' => $reportTemplateId,
                                    'standard_id' => $standard->id
                                ];
                            }
                        }

                        $reportTemplateStandards = $this->Strands->Standards->ReportTemplateStandards->newEntities($reportTemplateStandards);
                        if(!$this->Strands->Standards->ReportTemplateStandards->saveMany($reportTemplateStandards)){
                            $this->out('reportTemplateStandards Could not be saved.');
                            print_r($reportTemplateStandards);
                        }
                    }
                }   
            }
            
           
            if(in_array($courseName.$grades[$row['grade']], $processedCourses)){
                $this->out('Course Already Processed for report template. Report setting has already been added.');
                continue;
            }


            $reportSettings = $courses->map(function($value, $key) use ($reportTemplateId, $reportTemplatePageId){

                $value = [
                            'course_id' => $value->id, 
                            'grade_id' => $value->grade_id,
                ];
                $value['report_template_id'] = $reportTemplateId;
                $value['course_status'] = 1;
                $value['strand_status'] = 1;
                $value['standard_status'] = 1;
                $value['impact_status'] = 0;
                $value['impact_comment_status'] = 0;
                $value['course_comment_status'] = 1;
                $value['strand_comment_status'] = 1;
                $value['report_template_page_id'] = $reportTemplatePageId;
                return $value;
            })->toArray();

            $this->out('Saving report Template settings.');
            $reportSettings = $this->Courses->ReportSettings->newEntities($reportSettings);
            if(!$this->Courses->ReportSettings->saveMany($reportSettings)){
                $this->out('reportSettings Could not be saved.');
                print_r($reportSettings);
                continue;
            }

            $processedCourses[] = $courseName.$grades[$row['grade']];

       }

        $this->updateReports($reportTemplateId, $reportPageId);
        $this->out('Script has finished and courses not found on db are:');
        print_r(array_unique($coursesNotFound));

    }
	
    public function removeDuplicateReportImpacts($reportTemplateId){

        $this->loadModel('ReportTemplateImpacts');

        $reportTemplateImpacts = $this->ReportTemplateImpacts->findByReportTemplateId($reportTemplateId)->all()->toArray();
        $processed = [];
        $duplicates = [];
        foreach($reportTemplateImpacts as $key1 => $value1) {
            foreach($reportTemplateImpacts as $key2 => $value2) {
                if (!in_array($value2->id, $processed) && !in_array($value1->id, $processed) && $value1->id != $value2->id && $value1->grade_id == $value2->grade_id && $value1->impact_id == $value2->impact_id && $value1->course_id == $value2->course_id && $value1->report_template_id == $value2->report_template_id) {
                    $processed[] = $value1->id;
                    $processed[] = $value2->id;
                    $duplicates[] = $value1->id;
                } 
            }    
        }
        $this->out(count($duplicates));
        if(!empty($duplicates)){
            $this->out('Deleting Duplicates');
		$this->ReportTemplateImpacts->deleteAll(['id IN' => $duplicates]);
            $this->ReportTemplateImpacts->ReportTemplateImpactScores->deleteAll(['report_template_impact_id IN' => $duplicates]);
        }

        $this->out('Done!!!');
    }

    //1st argument should be model name and folowing arguments should be Columns group which is distinct across table
    public function deleteDuplicates(){
        $args = func_get_args();
        if(count($args) <= 0){
            $this->out('Arguments are required.');
        }

        if(count($args) == 1){
            $this->out('Columns group which is distinct across table is required.');
        }
        $tableName = $args[0];
        unset($args[0]); 
        $columns = array_values($args);
        $columnsLength = count($columns);
        $this->out('Getting entities.');
        $this->Model = $this->loadModel($tableName);
        $entities = $this->Model->find()->toArray();
        $processed = [];
        $duplicates = [];
        // pr(count($entities));die;

        foreach($entities as $entity1){
            $this->out('New Entity1');
            foreach($entities as $entity2){
                
                if($entity1->id == $entity2->id){
                    $this->out('Same ids not comparing.');
                    continue;
                }
                if(in_array($entity1->id, $processed) || in_array($entity2->id, $processed)){
                    continue;
                }

                foreach($columns as $key => $column){
                    if($entity1[$column] != $entity2[$column]){
                        break;
                    }
                    if($key == $columnsLength-1 && $entity1[$column] == $entity2[$column]){
                        $processed[] = $entity1->id;
                        $processed[] = $entity2->id;
                        $duplicates[] = $entity1->id;
                        $this->out('Duplicate found');
                    }
                }
            }            
        }

        $this->out(count($duplicates)."duplicates found in ".$tableName);
        if(!empty($duplicates)){
            $this->out('Deleting Duplicates');
            $this->Model->deleteAll(['id IN' => $duplicates]);
        }

        $this->out('Done!!!');
    }
    public function addStrandsAndStandardsforUOI(){
        $this->out('Inside uploadStandardsForES. Creating data for new curriculum.');
        $this->loadModel('Curriculums');
        $this->loadModel('ReportTemplates');
        $this->loadModel('Courses');
        $this->loadModel('Strands');

        $this->out('Deleting Report template strands and standards');
        $this->ReportTemplates->ReportTemplateStandards->deleteAll(['course_id IN' => [815, 816], 'report_template_id' => 4]);
        $this->ReportTemplates->ReportTemplateStrands->deleteAll(['course_id IN' => [815, 816], 'report_template_id' => 4]); 
        $this->ReportTemplates->ReportTemplateCourseStrands->deleteAll(['course_id IN' => [815, 816], 'report_template_id' => 4]); 
        $standardTypes = ['uoi_line_of_inquiry_1', 'uoi_line_of_inquiry_2', 'uoi_line_of_inquiry_3', 'uoi_line_of_inquiry_4'];

        $results = $this->getCsvData('Standards', 'uoi_integration');
        foreach($results as $row){
            $strandData = [
            'name' => $row['uoi_theme_title'],
            'description' => $row['uoi_central_idea'],
            'learning_area_id' => 129,
            'code' => substr($row['uoi_theme_title'], 0, 2),
           ];

           
            $this->out('creating new Strand.');
            $strand = $this->Strands->newEntity($strandData);
            if(!$this->Strands->save($strand)){
                $this->out('Strand Could not be saved.');
                print_r($strand);
                continue;
            }

            $reportTemplateStrandData = [
                'course_id' => $row['course_id'],
                'report_template_id' => 4,
                'grade_id' => $row['grade_id'],
                'strand_id' => $strand->id
            ];

            $this->out('creating new reportTemplateStrands.');
            $reportTemplateStrand = $this->ReportTemplates->ReportTemplateStrands->newEntity($reportTemplateStrandData);
            if(!$this->ReportTemplates->ReportTemplateStrands->save($reportTemplateStrand)){
                $this->out('reportTemplateStrand Could not be saved.');
                print_r($reportTemplateStrand);
            }

            $this->out('creating new reportTemplateCourseStrands.');
            $reportTemplateCourseStrand = $this->ReportTemplates->ReportTemplateCourseStrands->newEntity($reportTemplateStrandData);
            if(!$this->ReportTemplates->ReportTemplateCourseStrands->save($reportTemplateCourseStrand)){
                $this->out('reportTemplateCourseStrand Could not be saved.');
                print_r($reportTemplateCourseStrand);
            }

            $standardData = [];
            foreach($standardTypes as $standardType){
                if(isset($row[$standardType]) && !in_array($row[$standardType], [null, false, ''])){

                    $standardData[] = [
                        'name' => $row[$standardType],
                        'description' => $row[$standardType],
                        'strand_id' => $strand->id,
                        'code' => substr($row['uoi_theme_title'], 0, 2).'-'.substr($row[$standardType], 0, 2),
                        'is_selectable' => 1,
                        'standard_grades' => [['grade_id' => $row['grade_id']]] 
                    ];
                }
            }

            if(!empty($standardData)){
                $this->out('creating new Standards');
                $standards = $this->Strands->Standards->newEntities($standardData);
                if(!$this->Strands->Standards->saveMany($standards)){
                    $this->out('Standards Could not be saved.');
                    print_r($standard);
                    continue;
                }
                $this->out('Creating data for report template standards');
                $reportTemplateStandards = [];
                foreach($standards as $standard){

                    $reportTemplateStandards[] = [
                        'course_id' => $row['course_id'],
                        'grade_id' => $row['grade_id'],
                        'report_template_id' => 4,
                        'standard_id' => $standard->id
                    ];
                }

                $reportTemplateStandards = $this->Strands->Standards->ReportTemplateStandards->newEntities($reportTemplateStandards);
                if(!$this->Strands->Standards->ReportTemplateStandards->saveMany($reportTemplateStandards)){
                    $this->out('reportTemplateStandards Could not be saved.');
                    print_r($reportTemplateStandards);
                }
            }
        }

        $this->out('done!!!!');

    }

    public function fixComments($type =  null){
        
        $types = [
            'strands' => [
                'table_name' => "ReportTemplateStrandScores",
                'file_name' => 'strand_scores',
            ],
            'courses' => [
                'table_name' => "ReportTemplateCourseScores",
                'file_name' => 'course_scores',


            ]
        ];

        if(!$type || !isset($types[$type])){
            $this->out(' Valid Type is required.');
            return;
        }

        $this->out('In fix Comments for '.$type);

        $type= $types[$type];
        $table = $this->loadModel($type['table_name']);
        $results = $this->getCsvData('scores', $type['file_name']);
        $totalCount = count($results);
        $count=0;


        foreach($results as $row){
            $count++;
            $this->out('Count: '.$count." of ".$totalCount);

            if($type['table_name'] == 'ReportTemplateStrandScores'){
                $conditions = [
                    'report_template_strand_id' => $row['report_template_strand_id'],
                    'student_id' => $row['student_id']
                ];

            }else{
                $conditions = [
                    'report_template_id' => $row['report_template_id'],
                    'student_id' => $row['student_id'],
                    'course_id' => $row['course_id'],
                ];
            }

            if(in_array($conditions['student_id'], [null, false, ''])){
                $this->out('Empty Row.');
                continue;

            }
            print_r($conditions);

            $entity = $table->find()->where($conditions)->first();


            if(!$entity){
                $this->out("Entity to be updated not found.");
                continue;
            }


            $data = [
                'comment' => $row['comment']
            ];
            pr($data);
            $entity = $table->patchEntity($entity, $data);
            print_r($entity);
            if(!$table->save($entity, $data)){
                $this->out('entity could not be saved.');
                print_r($entity);
                continue;
            }
            $this->out('entity has been saved.');
        }
        $this->out('Done!!!');
    }
	
     public function removeOrphanedUnits(){
        $this->out('In removeOrphanedUnits');
        $data = $this->getCsvData('UnitCourses', 'UnitsWithNoCourses');
        $this->loadModel('UnitCourses');
        $this->loadModel('Units');
        $this->loadModel('Courses');
        $orphanUnitsCount=0;
        $unitCourseCount=0;
        // pr($data);die;
        foreach($data as $row){
            $unit=$this->Units->findById($row['id'])->first();
            if(!$unit){
                $this->out('Unit Does not exist');
                continue;
            }
            if(!$row['learningboard_course_ids'] || in_array($row['learningboard_course_ids'], [null, false, ''])){
                $orphanUnitsCount++;
                $this->out('Deleting Unit: '.$row['name']);
                if(!$this->Units->delete($unit)){
                    $this->out('Orphan unit Could not be deleted');
                }
                $this->out('Unit: '.$row['name'].' with unit id '.$row['id'].' deleted successfully');
                continue;
            }

            $course=$this->Courses->findById($row['learningboard_course_ids'])->toArray();
            
            if(!$course){
                $this->out('Course not found');
                continue;
            }

            $unitCourse=[
                'unit_id'=>$row['id'],
                'course_id'=>$row['learningboard_course_ids'],
                'is_primary'=>1                
            ];

            $unitCourse=$this->UnitCourses->newEntity($unitCourse);
            pr($unitCourse);
            if(!$this->UnitCourses->save($unitCourse)){
                $this->out('Unit: '.$row['name'].' could not be saved');
                continue;
            }
            
            $this->out('Unit Course '.$row['name'].' Saved.');
            $unitCourseCount++;

        }
        $this->out('Orphan Units Deleted: '.$orphanUnitsCount);
        $this->out('Unit Courses Added: '.$unitCourseCount);
    }

   public function fixAssessmentStrands(){
        // pr($entity); die;
            $this->loadModel('Units');
            $this->loadModel('UnitStrands');

            $unitIds = $this->Units->find()->select(['id'])->extract('id')->toArray();
            foreach ($unitIds as $unitId) {
                
                $unitStrandIds = $this->UnitStrands->find()->where(['unit_id' => $unitId])
                                            ->extract('strand_id')->toArray();

                $unitStrandIds = array_unique($unitStrandIds);

                $assessments = $this->UnitStrands->Units->Assessments
                                          ->findByUnitId($unitId)
                                          ->contain(['AssessmentStrands'])
                                          ->toArray();

                foreach ($assessments as $key => $value) {
                    $assessmentId = $value->id;
                    $assessmentStrandIds = [];
                    if(!empty($value->assessment_strands)){
                        $assessmentStrandIds = (new Collection($value->assessment_strands))->extract('strand_id')->toArray();
                    }

                    $assessmentStrandIds = array_unique($assessmentStrandIds);

                    $strandDiff = array_diff($unitStrandIds, $assessmentStrandIds);

                    if(empty($strandDiff)){
                        continue;
                    }

                    $assessmentStrands = (new Collection($strandDiff))->map(function($val, $key) use($assessmentId){
                        return [
                            'assessment_id' => $assessmentId,
                            'strand_id' => $val
                        ];

                    })->toArray();

                    $assessmentStrands = $this->UnitStrands->Units->Assessments->AssessmentStrands->newEntities($assessmentStrands);

                    if(!$this->UnitStrands->Units->Assessments->AssessmentStrands->saveMany($assessmentStrands)){
                        Log::write('debug', "AssessmentStrands coulld not be saved");
                        Log::write('debug', $assessmentStrands);
                    }

                }            
            }

    }


   /* public function removeOrphanedUnits(){
        $this->out('In removeOrphanedUnits');
        $data = $this->getCsvData('UnitCourses', 'UnitsWithNoCourses');
        $this->loadModel('UnitCourses');
        $this->loadModel('Units');
        $this->loadModel('Courses');
        $orphanUnitsCount=0;
        $unitCourseCount=0;
        // pr($data);die;
        foreach($data as $row){
            $unit=$this->Units->findById($row['id'])->toArray();
            if(!$unit){
                $this->out('Unit Does not exist');
                continue;
            }
            if(!$row['learningboard_course_ids'] || in_array($row['learningboard_course_ids'], [null, false, ''])){
                $orphanUnitsCount++;
                $this->out('Deleting Unit: '.$row['name']);
                if(!$this->Units->delete($unit)){
                    $this->out('Orphan unit Could not be deleted');
                }
                $this->out('Unit: '.$row['name'].' with unit id '.$row['id'].' deleted successfully');
                continue;
            }

            $course=$this->Courses->findById($row['learningboard_course_ids'])->toArray();
            
            if(!$course){
                $this->out('Course not found');
                continue;
            }

            $unitCourse=[
                'unit_id'=>$row['id'],
                'course_id'=>$row['learningboard_course_ids'],
                'is_primary'=>1                
            ];

            $unitCourse=$this->UnitCourses->newEntity($unitCourse);
            pr($unitCourse);
            if(!$this->UnitCourses->save($unitCourse)){
                $this->out('Unit: '.$row['name'].' could not be saved');
                continue;
            }
            
            $this->out('Unit Course '.$row['name'].' Saved.');
            $unitCourseCount++;

        }
        $this->out('Orphan Units Deleted: '.$orphanUnitsCount);
        $this->out('Unit Courses Added: '.$unitCourseCount);
    }

    public function fixAssessmentStrands(){
        // pr($entity); die;
            $this->loadModel('Units');
            $this->loadModel('UnitStrands');

            $unitIds = $this->Units->find()->select(['id'])->extract('id')->toArray();
            foreach ($unitIds as $unitId) {
                
                $unitStrandIds = $this->UnitStrands->find()->where(['unit_id' => $unitId])
                                            ->extract('strand_id')->toArray();

                $unitStrandIds = array_unique($unitStrandIds);

                $assessments = $this->UnitStrands->Units->Assessments
                                          ->findByUnitId($unitId)
                                          ->contain(['AssessmentStrands'])
                                          ->toArray();

                foreach ($assessments as $key => $value) {
                    $assessmentId = $value->id;
                    $assessmentStrandIds = [];
                    if(!empty($value->assessment_strands)){
                        $assessmentStrandIds = (new Collection($value->assessment_strands))->extract('strand_id')->toArray();
                    }

                    $assessmentStrandIds = array_unique($assessmentStrandIds);

                    $strandDiff = array_diff($unitStrandIds, $assessmentStrandIds);

                    if(empty($strandDiff)){
                        continue;
                    }

                    $assessmentStrands = (new Collection($strandDiff))->map(function($val, $key) use($assessmentId){
                        return [
                            'assessment_id' => $assessmentId,
                            'strand_id' => $val
                        ];

                    })->toArray();

                    $assessmentStrands = $this->UnitStrands->Units->Assessments->AssessmentStrands->newEntities($assessmentStrands);

                    if(!$this->UnitStrands->Units->Assessments->AssessmentStrands->saveMany($assessmentStrands)){
                        Log::write('debug', "AssessmentStrands coulld not be saved");
                        Log::write('debug', $assessmentStrands);
                    }

                }            
            }

    }*/

     public function uploadFrameworks($filepath){
      $this->out('Inside uploadFrameowrks');
      $this->loadModel('ImpactCategories');
      $data = $this->getCsvData('ImpactCategories',$filepath);
      if(!$data){
        return false;
      }
      $impactCategory=[];
      $processedCategories =[];
      $data = (new Collection($data))->groupBy('parent')->map(function($value, $key){
        $impactCategoryData = [
            'name'=> $value[0]['parent'],
            'description'=> $value[0]['parent'],
            'teacher_evidence_selectable_impacts' => [[]]
        ];
        $impactCategoryData['impacts'] = (new Collection($value))->map(function($v, $k){
            return [
                'name'=>$v['child'],
                'description'=>$v['child'],
            ];
        })->toArray();
        return $impactCategoryData;
      })->toArray();
      $impactCategories =$this->ImpactCategories->newEntities($data);
      if(!$this->ImpactCategories->saveMany($impactCategories)){
            $this->out('Impact Categories could not be saved.');    
        }
        $this->out('Impact Categories have been saved.');
    }

}
