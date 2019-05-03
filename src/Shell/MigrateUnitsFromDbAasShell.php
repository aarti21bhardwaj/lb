<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Http\Client;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\BadRequestException;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use Cake\Collection\Collection;
use Cake\I18n\FrozenTime;

/**
 * MigrateUnitsFromDb shell command.
 */
class MigrateUnitsFromDbAasShell extends Shell
{   

    Private $_templateId = false;

    public function connectWithMigrationDB($sql){

        $host = Configure::read('migrationDB.host');
        $username = Configure::read('migrationDB.username');
        $password = Configure::read('migrationDB.password');
        $database = Configure::read('migrationDB.database');

        ConnectionManager::drop('my_connection');
        $config = [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'persistent' => false,
        'host' => $host,
        'username' => $username,
        'password' => $password,
        'database' => $database,
        'encoding' => 'utf8',
        'timezone' => 'UTC',
        'flags' => [],
        'cacheMetadata' => true,
        'log' => true,
        'quoteIdentifiers' => false,
        'url' => env('DATABASE_URL', null),
        ];
        ConnectionManager::config('my_connection', $config);
        $conn = ConnectionManager::get('my_connection');
        $response = $conn->execute($sql);

        return $response;
    }

    public function migrateUnits($oldUnitId = null, $authorId = null){

        $this->loadModel('Units');
        
        $query = 'Select * from lbf_units as u inner join sis_teacher_details as td on u.unit_author_id = td.sis_teacher_id where u.is_active = 1';

        $oldUnitId = (int) $oldUnitId;
        if($oldUnitId){
            $query = $query.' and u.lbf_units_id = '.$oldUnitId;
        }

        $authorId = (int) $authorId;
        if($authorId){
            $query = $query.' and u.unit_author_id = '.$authorId;
        }

        if(empty($this->_templates)){
            $this->_templates = $this->Units->Templates->find()->combine(function($value){
                return strtolower($value->name);
            }, 'id')->toArray();
        }

        $results = $this->connectWithMigrationDB($query);

        $count = 0;
        $errorCount = 0;
        foreach ($results as $key => $row) {
            
            $this->out('Creating data for unit '.$row['unit_name']);
            $teacherId = false; 
            $teacher = $this->Units->UnitTeachers
                                   ->Teachers
                                   ->find()
                                   ->where([
                                        'first_name' => $row['sis_teacher_first_name'],
                                        'last_name' => $row['sis_teacher_last_name']
                                   ])
                                   ->first();
            if(!$teacher){
                $this->out('Teacher not found on new db. Unit will not be saved.');
                continue;
            }else{
                $teacherId = $teacher->id;
            }


            $startDate = FrozenTime::createFromTimestamp($row['unit_start_date']/1000);
            if($row['unit_end_date']){
                $endDate = FrozenTime::createFromTimestamp($row['unit_end_date']/1000); 
            }else{
                $this->out('End date not found. Setting end date as startDate + 90.');
                $endDate = $startDate->modify('+90 days');

            }

            if(!isset($this->_templates[strtolower($row['template_id'])])){
                $this->out('invalid template, unit will not be saved');
                continue;
            }

            $this->_templateId = $this->_templates[strtolower($row['template_id'])];

            $unit = [
                'name' => $row['unit_name'],
                'description' => $row['unit_description'] ? $row['unit_description'] : $row['unit_name'],
                'start_date' => $startDate < $endDate ? $startDate : $endDate,
                'end_date' => $startDate < $endDate ? $endDate : $startDate,
                'template_id' => $this->_templateId,
                'unit_teachers' => []
            ];

            if(isset($teacherId)){
                $unit['unit_teachers'][] =  [
                                                'is_creator' => true,
                                                'teacher_id' => $teacherId,
                                            ];
                $unit['created_by'] = $teacherId;
            }
            $unit = $this->_addAssociatedData($row['lbf_units_id'], $unit);
            $unit = $this->Units->newEntity($unit);
            $this->out('Enity formed. Trying to save the unit.');
            if(!$this->Units->save($unit)){
                $this->out('Unable to save the unit.');
                print_r($unit);
                $errorCount++;
                continue;
            }

            $this->getUnitResources([$row['lbf_units_id'] => $unit->id], 'unit', $unit->id);
            $this->getUnitReflections($row['lbf_units_id'], $unit->id, 'unit', $unit->id);
            $this->getAssessments($row['lbf_units_id'], $unit->id);

            $count++;
        }

        $this->out($count." units migrated. ".$errorCount." units had errors.");
    }

    private function _addAssociatedData($oldUnitId, $unit){

        $authorId = empty($unit['unit_teachers'])  ? null : $unit['unit_teachers'][0]['teacher_id'];
        $unitTeachers = $this->getUnitTeachers($oldUnitId, $authorId);
        if(!empty($unitTeachers)){
            $unit['unit_teachers'] = array_merge($unit['unit_teachers'], $unitTeachers);
            unset($unitTeachers);
        }

        if(empty($unit['unit_teachers'])){
            unset($unit['unit_teachers']);
        }

        $subjectArea = false;
        $unitCourseData = $this->getUnitCourses($oldUnitId);
        $subjectArea = $unitCourseData['subject_area'];
        if(!empty($unitCourseData['unit_courses'])){
            $unit['unit_courses'] = $unitCourseData['unit_courses'];
            unset($unitCourseData);
        }

        $unitContents = $this->getUnitContents($oldUnitId);
        if(!empty($unitContents)){
            $unit['unit_contents'] = $unitContents;
            unset($unitContents);
        }

        $getUnitSpecificContents = $this->getUnitSpecificContents($oldUnitId, $subjectArea);
        if(!empty($getUnitSpecificContents)){
            $unit['unit_specific_contents'] = $getUnitSpecificContents;
            unset($getUnitSpecificContents);
        }

        $unitStandards = $this->getUnitStandards($oldUnitId);
        if(!empty($unitStandards)){
            $unit['unit_standards'] = $unitStandards;
            unset($unitStandards);
        }

        return $unit;
    }   

    public function getUnitStandards($oldUnitId){
        $this->out('In get unitStandards.');
        $query = "Select Distinct lbf_units_id as unit_id, lbf_standards_id as standard_id  from lbf_standards_units_map where lbf_units_id = ".$oldUnitId;

        $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');

        if(empty($results)){
            $this->out('No standards found for this unit.');
            return [];
        }
        $this->loadModel('OldLbHash');
        $oldStandardIds = (new Collection($results))->extract('standard_id')->toArray(); 
        $this->out('Finding standards on new db.');
        $unitStandards = $this->OldLbHash->find()
                                     ->where(['old_id IN' => $oldStandardIds, 'new_table_name' => 'standards'])
                                     ->map(function($value, $key){
                                        return ['standard_id' => $value->new_id];
                                     })
                                     ->toArray();
        return $unitStandards;
    }

    public function getUnitTeachers($oldUnitId, $authorId){

       $results = $this->connectWithMigrationDB("Select * from lbf_units_team as ut inner join sis_teacher_details as td on ut.sis_teacher_id = td.sis_teacher_id where ut.lbf_units_id = ".$oldUnitId);
       
       $this->loadModel('UnitTeachers');
       $unitTeachers = []; 
       foreach ($results as $key => $row) {
        $this->out('Finding teacher '.$row['sis_teacher_first_name'].' '.$row['sis_teacher_last_name'].' on new db.');
        $teacher = $this->UnitTeachers
                        ->Teachers
                        ->find()
                        ->where([
                            'first_name' => $row['sis_teacher_first_name'],
                            'last_name' => $row['sis_teacher_last_name']
                        ])
                        ->first();
        if(!$teacher){
            $this->out('Teacher not found on new db.');
            continue;
        }

        if($teacher->id == $authorId){
            continue;
        }

        $this->out('Teacher found on new db.');
        $unitTeachers[] = [
            'is_creator' => false,
            'teacher_id' => $teacher->id
        ];

       }

       return $unitTeachers;
    }

    public function getUnitCourses($oldUnitId){

        $results = $this->connectWithMigrationDB("Select * from lbf_units_course as uc inner join lb_course as c on uc.lb_course_id = c.lb_course_id where uc.lbf_units_id = ".$oldUnitId);

        $this->loadModel('BlackBaudHash');
        $unitCourses = [];
        $subjectArea = false;

        foreach ($results as $row) {

            if(!$subjectArea){
                $subjectArea = trim($row['subject_area']);
            }

            if($subjectArea && (int) $row['is_integrated']){
                $subjectArea = trim($row['subject_area']);
            }

            $this->out('Finding Course '.$row['short_name'].' in new Db');
            $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['new_table_name' => 'courses', 'old_id LIKE' => $row['lb_course_code'].'-%'])->all();
            
            if(empty($courseBlackBaudHash)){
                $this->out('Course not found in new db.');
                continue;
            }
            
            foreach($courseBlackBaudHash as $courseHash){
                $unitCourses[] = [
                    'is_primary' => (int) $row['is_integrated'],
                    'course_id' => $courseHash->new_id
                ];
            }
        }

        // if(empty($unitCourses)){
        //     $this->out('No course found for this unit. Associating with course id 1');
        //     $unitCourses[] = [
        //         'is_primary' => true,
        //         'course_id' => 1
        //     ];

        //     $subjectArea = 'World Languages';
        // }

        return ['unit_courses' => $unitCourses, 'subject_area' => $subjectArea];
    }

    public function getUnitContents($oldUnitId){
        $categoryArray = $this->_getContentCategoryArray();

        $this->loadModel('ContentValues');
            
        $unitContents = []; 

        foreach ($categoryArray as $category) {
            $this->out('Finding '.$category['type'].' for this unit.'); 

            $query = 'Select * from '.$category['unit_join_table'].' as uj inner join '.$category['table_name'].' as t on uj.'.$category['join_id'].' = t.'.$category['id_field'].' where uj.is_active = 1 and '.$category['unit_id_field'].' = '.$oldUnitId;

            if($category['old_type']){
                $query = $query." and t.type = '".$category['old_type']."'"; 
            }
            $results = $this->connectWithMigrationDB($query);

            foreach ($results as $row) {
                $contentValue = $this->ContentValues->findByText($row[$category['text_field']])
                                                    ->matching('ContentCategories', function($q) use($category){
                                                        return $q->where(['type' => $category['type']]);
                                                    })
                                                    ->first();
                if(!$contentValue){
                    continue;
                }

                $unitContents[] = [
                    'content_category_id' => $contentValue->content_category_id,
                    'content_value_id' => $contentValue->id
                ];
            }
            
        }
        $this->out('Found '.count($unitContents).' content values for this unit.');
        return $unitContents;
    }

    public function getUnitSpecificContents($oldUnitId, $subjectArea){

        $this->out('In get unit specific contents for this unit.');

        $categoryArray = $this->_getUnitSpecificContentCategoryArray();

        $this->loadModel('UnitSpecificContents');

        $contentCategories = $this->UnitSpecificContents->ContentCategories->find()->indexBy('name')->toArray();
        $unitSpecificContents = [];

        foreach ($categoryArray as $category) {

            if(!isset($contentCategories[$category['name']])){
                $this->out('content category not found on new db. Creating new content category.');
                $contentCategory = [
                    'name' => $category['name'],
                    'meta' => $category['meta'],
                    'type' => $category['type']
                ];
                $contentCategory = $this->UnitSpecificContents->ContentCategories->newEntity($contentCategory);
                $this->UnitSpecificContents->ContentCategories->save($contentCategory);
                $contentCategories[$contentCategory->name] = $contentCategory;
                $this->out("New content category created ".$contentCategory->name);               
            }

            $query = "Select * from ".$category['table_name']." where is_active = 1 and ".$category['unit_id_field']." = ".$oldUnitId;
            if($category['old_type']){
                $query = $query." and pyp_key = '".$category['old_type']."' and code IS NULL";
            }
            $results = $this->connectWithMigrationDB($query);

            foreach ($results as $row) {
                if(in_array(urldecode($row[$category['text_field']]), [null, false, ''])){
                    $this->out("Text fild is empty. Unit specific content will not be saved");
                    continue;
                }
                $unitSpecificContents[] = [
                    'content_category_id' => $contentCategories[$category['name']]->id,
                    'text' => urldecode($row[$category['text_field']])
                ];
            }
        }

        $this->out(count($unitSpecificContents)." unit specific contents found for this unit.");
        return $unitSpecificContents;


    }

    public function getAssessments($oldUnitId, $newUnitId){

        $this->out('In get assessments for this unit.');
        $query = 'Select * from lbf_units_assessment as ua inner join sis_teacher_details as td on ua.sis_teacher_id = td.sis_teacher_id where is_active = 1 and lbf_units_id = '.$oldUnitId;

        $results = $this->connectWithMigrationDB($query);


        $this->loadModel('Assessments');

        $assessments = [];

        foreach ($results as $key => $row) {

            $this->out('Forming data for assessment '.$row['assessment_name']);

            $startDate = FrozenTime::createFromTimestamp($row['start_date']/1000);
            if($row['end_date']){
                $endDate = FrozenTime::createFromTimestamp($row['end_date']/1000); 
            }else{
                // $this->out('End date not found for this assessment. Setting end date as startDate + 30.');
                $endDate = $startDate->modify('+30 days'); 

            }

            if(!isset($row['assessment_name']) || in_array($row['assessment_name'], [null, false, ''])){
                $this->out('Assessment name is empty will be created with name "Untitled"');
                $row['assessment_name'] = "Untitled";
            }

            $assessments[$key] = [
                'unit_id' => $newUnitId,
                'assessment_type_id' => ((int) $row['assessment_type']) == 0 ? 2 : 1,
                'name' => $row['assessment_name'],
                'description' => $row['assessment_name'],
                'start_date' => $startDate < $endDate ? $startDate : $endDate,
                'end_date' => $startDate < $endDate ? $endDate : $startDate,
                'is_accessible' => $row['is_assessable'],
                'old_id' => $row['lbf_units_assessment_id']
            ];

            $teacherId = false; 
            $teacher = $this->Assessments->Units
                                         ->UnitTeachers
                                         ->Teachers
                                         ->find()
                                         ->where([
                                            'first_name' => $row['sis_teacher_first_name'],
                                            'last_name' => $row['sis_teacher_last_name']
                                         ])
                                         ->first();
            if(!$teacher){
                $this->out('Teacher not found on new db. created by will not be saved.');
            }else{
                $assessments[$key]['created_by'] = $teacher->id; 
            }

            $assessments[$key] = $this->_assessmentAssociatedData($row['lbf_units_assessment_id'], $assessments[$key], $row['assessment_description'], $oldUnitId);
        }

        if(empty($assessments)){
            $this->out('No assessments found for this unit.');
            return false;
        }

        $this->out(count($assessments)." assessments found for this unit. Trying to save.");

        $assessments = $this->Assessments->newEntities($assessments);
        if(!$this->Assessments->saveMany($assessments)){
            $this->out('Unable to save assessments. for this units.');
            print_r($assessments);
            return false;
        }

        $assessmentIds = (new Collection($assessments))->combine('old_id', 'id')->toArray();

        $this->out('Assessments saved for this unit. Will get resources & strands of these assessments now.');

        $this->getUnitResources($assessmentIds, 'assessment', $newUnitId);
        // $this->getAssessmentStrands($assessmentIds, $newUnitId);
    }

    private function _assessmentAssociatedData($oldAssessmentId, $assessment, $description, $oldUnitId){
        
        $assessmentImpacts = $this->getAssessmentImpacts($oldAssessmentId);
        if(!empty($assessmentImpacts)){
            $assessment['assessment_impacts'] = $assessmentImpacts;
            unset($assessmentImpacts);
        }

        $assessment['assessment_contents'] = [];
        $assessment['assessment_contents'] = $this->getAssessmentContents($oldAssessmentId, $assessment['unit_id']);

        if($description && !in_array($description, [null, false, ''])){
            $contents = $this->getContentFromDescription($description, $oldUnitId, $assessment['unit_id']);
            if(!empty($contents['assessment_contents'])){
                $assessment['assessment_contents'] = array_merge($assessment['assessment_contents'], $contents['assessment_contents']) ;
            }

            if(!empty($contents['assessment_specific_contents'])){
                $assessment['assessment_specific_contents'] = $contents['assessment_specific_contents'];
            }
        }

        if(empty($assessment['assessment_contents'])){
            unset($assessment['assessment_contents']);
        }  

        $assessmentStandards = $this->getAssessmentStandards($oldAssessmentId);
        if(!empty($assessmentStandards)){
            $assessment['assessment_standards'] = $assessmentStandards;
            unset($assessmentStandards);
        }


        return $assessment;
    }


    public function getAssessmentStandards($oldAssessmentId){
        $this->out('In getAssessmentStandards.');
        $query = "Select Distinct lb_assessment_id as assessment_id, lb_standards_id as standard_id  from lb_assessment_standards where lb_assessment_id = ".$oldAssessmentId;

        $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');

        if(empty($results)){
            $this->out('No standards found for this assessment.');
            return [];
        }
        $this->loadModel('OldLbHash');
        $oldStandardIds = (new Collection($results))->extract('standard_id')->toArray(); 
        $this->out('Finding standards on new db.');
        $assessmentStandards = $this->OldLbHash->find()
                                     ->where(['old_id IN' => $oldStandardIds, 'new_table_name' => 'standards'])
                                     ->map(function($value, $key){
                                        return ['standard_id' => $value->new_id];
                                     })
                                     ->toArray();
        return $assessmentStandards;
    }

    public function getAssessmentImpacts($oldAssessmentId){

        $this->out('In get assessment impacts.');
        $query = "Select ai.*, i.lbf_impact_name as impact_name, i.lbf_impact_type as impact_type, p.lbf_impact_name as parent_name, p.lbf_impact_type as parent_type from lbf_assessment_impacts as ai inner join lbf_impact as i on ai.lbf_impact_id = i.lbf_impact_id left join lbf_impact as p on i.lbf_impact_parent = p.lbf_impact_id where ai.is_active = 1 and lbf_units_assessment_id = ".$oldAssessmentId;

        $results = $this->connectWithMigrationDB($query);
        $assessmentImpacts = [];
        $this->loadModel('AssessmentImpacts');

        foreach ($results as $key => $row) {

            if(!$row['parent_name']){
                $this->out('Ignoring value as it is an impact category in new db.');
                continue;
            }

            $impact = $this->AssessmentImpacts->Impacts
                                              ->find()
                                              ->where([
                                                'Impacts.name' => $row['impact_name'],
                                                'Impacts.impact_type' => $row['impact_type']
                                              ])
                                              ->matching('ImpactCategories', function($q) use($row){
                                                if(!$row['parent_name']){
                                                    return $q;
                                                }
                                                return $q->where([
                                                  'ImpactCategories.name' =>  $row['parent_name'],
                                                  'ImpactCategories.impact_type' => $row['parent_type'] 
                                                ]);
                                              })
                                              ->first();

            if(!$impact){
                $this->out('Impact not found on new db.');
                continue;
            }

            $assessmentImpacts[] = [
                'impact_id' => $impact->id
            ];
        }

        $this->out(count($assessmentImpacts)." assessment impacts found for this assessment.");
        return $assessmentImpacts;
    }

    public function getAssessmentContents($oldAssessmentId, $newUnitId){
        
        $this->out('In get assessment contents. Getting unit specific goals first.');
        $this->loadModel('AssessmentContents');
        $assessmentContents = [];
        $query = "Select * from lbf_assessment_goals_map as agm inner join lbf_units_long_term_goals as ltg on agm.lbf_long_term_goals_id = ltg.lbf_long_term_goals_id where agm.is_active = 1 and lbf_units_assessment_id = ".$oldAssessmentId;
        $results = $this->connectWithMigrationDB($query);

        foreach ($results as $row){
            $this->out('Finding unit specific content on new db.');
            $unitSpecificContent = $this->AssessmentContents->UnitSpecificContents
                                                            ->findByUnitId($newUnitId)
                                                            ->where(['text' => urldecode($row['goal_description'])])
                                                            ->first();
            if(!$unitSpecificContent){
                $this->out('Unit Specific content '.urldecode($row['goal_description']).' not found on new db. Ignoring value.');
                continue;
            }

            $assessmentContents[] = [
                'unit_specific_content_id' => $unitSpecificContent->id,
                'content_category_id' => $unitSpecificContent->content_category_id
            ];
        }

        $query = "Select * from lbf_assessment_goals_map as agm inner join lbf_units_lttg as ug on agm.lbf_units_lttg_id = ug.lbf_units_lttg_id inner join lbf_ltt_goals as ltg on ug.lbf_ltt_goal_id = ltg.lbf_ltt_goals_id where agm.is_active = 1 and lbf_units_assessment_id = ".$oldAssessmentId;
        $results = $this->connectWithMigrationDB($query);

        $this->out('Now Finding common value on new db.');
        foreach ($results as $row){
            $contentValue = $this->AssessmentContents->ContentValues
                                                     ->find()
                                                     ->where(['ContentValues.text' => $row['lbf_ltt_goals_name']])
                                                     ->first();
            if(!$contentValue){
                $this->out('Content value '.$row['lbf_ltt_goals_name'].' not found on new db. Ignoring value.');
                continue;
            }

            $assessmentContents[] = [
                'content_value_id' => $contentValue->id,
                'content_category_id' => $contentValue->content_category_id
            ];
        }

        $this->out(count($assessmentContents)." assessment contents found for this assessment.");
        return $assessmentContents;
    }

    public function getAssessmentStrands($assessmentIds, $newUnitId){
        
        $this->out('In Get assement strands.');

        $this->loadModel('AssessmentStrands');
        $courseStrands = $this->AssessmentStrands->Strands
                                                 ->CourseStrands
                                                 ->find()
                                                 ->matching('Courses.UnitCourses', function($q) use($newUnitId){
                                                    return $q->where(['UnitCourses.unit_id' => $newUnitId]);
                                                 })
                                                 ->toArray();
        $this->out(count($courseStrands)." courseStrands found.");
        $assessmentStrands = [];

        foreach ($assessmentIds as $assessmentId) {
            foreach ($courseStrands as $courseStrand) {
                $assessmentStrands[] = [
                    'assessment_id' => $assessmentId,
                    'strand_id' => $courseStrand->strand_id,
                    'grade_id' => $courseStrand->grade_id,
                    'course_id' => $courseStrand->course_id
                ];                
            }
        }

        $this->out(count($assessmentStrands)." assessment strands formed.");
        if(empty($assessmentStrands)){
            return false;
        }
        $assessmentStrands = $this->AssessmentStrands->newEntities($assessmentStrands);
        if(!$this->AssessmentStrands->saveMany($assessmentStrands)){
            $this->out('assessment strands could not be saved.');
            print_r($assessmentStrands);
            return false;
        }

        $this->out('assessment strands have been saved.');


    }


    /* function to extract associtaed data of assessments stored as json encoded data in assessments table */
    public function getContentFromDescription($description, $oldUnitId, $newUnitId){
        
        $description = json_decode($description, true);
        $this->_commonContentTypes = ['understanding' => [], 'knowledge' => [], 'skills' => [], 'questions' => []];
        $commonContentCategoryArray = $this->_getUnitSpecificContentCategoryArray();
        $this->_specificContentsTypes = [
            'criteria' => [
               'name' => 'Evaluation Criteria',
               'type' => 'evaluation_criteria',
               'meta' => [
                    'heading_1' => 'Evaluation Criteria',
                    'heading_2' => 'Common Evaluation Criteria',
                    'heading_3' => 'Unit Specific Evaluation Criteria'
               ]
            ],
            'evidence' => [
               'name' => 'Assessment Evidence',
               'type' => 'assessment_evidence',
               'meta' => [
                    'heading_1' => 'Assessment Evidence',
                    'heading_2' => 'Common Assessment Evidence',
                    'heading_3' => 'Unit Specific Assessment Evidence'
               ]
            ],

            'comments' => [
               'name' => 'Comments',
               'type' => 'comments',
               'meta' => [
                    'heading_1' => 'Comments',
                    'heading_2' => 'Common Comments',
                    'heading_3' => 'Unit Specific Comments'
               ]
            ]
        ];
        $assessmentContents = ['assessment_contents' => [], 'assessment_specific_contents' => []];

        foreach ($description as $key => $value) {  
            if(isset($this->_commonContentTypes[$key])){
                

                if(!is_array($value)){
                    $value = json_decode($value, true);
                }

                if(empty($value)){
                    unset($description[$key]);
                }

                if(!isset($value[0]['id'])){
                    $this->out('invalid data.');
                    continue;
                }

                $value = (new Collection($value))->extract('id')->toArray(); 
                $value = "(".implode(',' ,$value).")";
                if(empty($this->_commonContentTypes[$key])){
                    foreach ($commonContentCategoryArray as $category){
                        if($category['assessment_join_field'] == $key){
                            $this->_commonContentTypes[$key] = $category;
                            break;    
                        }
                    }
                }
                $contentCategory = $this->getContentCategory($this->_commonContentTypes[$key]);
                $newContents = $this->_getCommonContentFromDescription($value, $contentCategory->id, $this->_commonContentTypes[$key], $oldUnitId, $newUnitId);
                $assessmentContents['assessment_contents'] = array_merge($assessmentContents['assessment_contents'], $newContents);

            }elseif(isset($this->_specificContentsTypes[$key]) && !in_array(urldecode($value), [null, false, ''])){
                $contentCategory = $this->getContentCategory($this->_specificContentsTypes[$key]);
                $assessmentContents['assessment_specific_contents'][] = ['description' => urldecode($value), 'content_category_id' => $contentCategory->id];
            }else{
                unset($description[$key]);
            }
        }
        return $assessmentContents;
    }

    public function getContentCategory($category){
        $this->loadModel('ContentCategories');
        if(!isset($this->_contentCategories) || empty($this->_contentCategories)){
            $this->_contentCategories = $this->ContentCategories->find()->indexBy('name')->toArray();
        }
        if(!isset($this->_contentCategories[$category['name']])){
            $this->out('content category not found on new db. Creating new content category.');
            $contentCategory = [
                'name' => $category['name'],
                'meta' => $category['meta'],
                'type' => $category['type']
            ];
            $contentCategory = $this->ContentCategories->newEntity($contentCategory);
            $this->ContentCategories->save($contentCategory);
            $this->_contentCategories[$contentCategory->name] = $contentCategory;
            $this->out("New content category created ".$contentCategory->name); 
        }
        return $this->_contentCategories[$category['name']];
    }

    private function _getCommonContentFromDescription($oldIds, $contentCategoryId, $category, $oldUnitId, $newUnitId){

        $this->out("In _getCommonContentFromDescription. Finding unit specific contents associated to this assessment.");

        $this->loadModel('UnitSpecificContents');
        $query = "Select * from ".$category['table_name']." where is_active = 1 and ".$category['unit_id_field']." = ".$oldUnitId." and ".$category['id_field']." IN ".$oldIds;

        $results = $this->connectWithMigrationDB($query);
        $assessmentContents = [];

        foreach ($results as $row) {
            $unitSpecificContent = $this->UnitSpecificContents->findByText(urldecode($row[$category['text_field']]))->where(['unit_id' => $newUnitId])->first();
            if(!$unitSpecificContent){
                $this->out('unit specific content not found on new db.');
            }
            $assessmentContents[] = [
                                        'content_category_id' => $contentCategoryId, 
                                        'unit_specific_content_id' => $unitSpecificContent->id
                                    ];
        }
        return $assessmentContents;
    }



    public function getUnitResources($objectIds = [], $objectType = 'unit', $newUnitId){
        $this->out('In get unit resources of '.$objectType);
        $types = [
            'unit' => [
                'id_field' => 'lbf_units_id'
            ],  
            'assessment' => [
                'id_field' => 'lbf_units_assessment_id'
            ]
        ];

        $query = "Select * from lbf_assessment_resources as ar inner join sis_teacher_details as td on ar.sis_teacher_id = td.sis_teacher_id where ".$types[$objectType]['id_field']." IN (".implode(',', array_keys($objectIds)).")";

        $results = $this->connectWithMigrationDB($query);

        $this->loadModel('UnitResources');
        $resourcePath = Configure::read('ImageUpload.uploadPathForUnitResources');

        $unitResources = [];

        foreach ($results as $key => $row) {
        
            $unitResources[$key] = [
                'unit_id' => $newUnitId,
                'object_identifier' => $objectIds[$row[$types[$objectType]['id_field']]],
                'object_name' => $objectType,
                'name' => trim($row['resource_name'])
            ];

            if(in_array($unitResources[$key]['name'], [null, false, ''])){
                $this->out("Resource will be saved with a dummy name as name was not set on old db.");
                $unitResources[$key]['name'] = $objectType." ".$objectIds[$row[$types[$objectType]['id_field']]]; 
            }

            switch ($row['resources_type']) {
                case 'link':
                    $unitResources[$key]['url'] = $row['resources_url'];
                    $unitResources[$key]['resource_type'] = 'link';
                    break;
                case 'text':
                    $unitResources[$key]['description'] = $row['resources_url'];
                    $unitResources[$key]['resource_type'] = 'description';
                    break;
                case 'file':
                    $fileName = explode('assessmentresources/', $row['resources_url'])[1];
                    $unitResources[$key]['file_path'] = $resourcePath;
                    $unitResources[$key]['file_name'] = trim($fileName);
                    $unitResources[$key]['resource_type'] = 'file';
                    break;
            }

            $teacherId = false; 
            $teacher = $this->UnitResources->Units
                                           ->UnitTeachers
                                           ->Teachers
                                           ->find()
                                           ->where([
                                            'first_name' => $row['sis_teacher_first_name'],
                                            'last_name' => $row['sis_teacher_last_name']
                                           ])
                                           ->first();
            if(!$teacher){
                $this->out('Teacher not found on new db. created by will not be saved.');
            }else{
                $unitResources[$key]['created_by'] = $teacher->id; 
            }
        }

        $this->out(count($unitResources)." unit resources found.");
        if(empty($unitResources)){
            return false;
        }
        $unitResources = $this->UnitResources->newEntities($unitResources);
        if(!$this->UnitResources->saveMany($unitResources)){
            $this->out('unit resources could not be saved.');
            print_r($unitResources);
            return false;
        }

        $this->out('unit resources have been saved.');

    }

    public function getUnitReflections($oldObjectId, $objectId, $objectType = 'unit', $newUnitId){

        $this->out('In get unit reflection of '.$objectType);
        $types = [
            'unit' => [
                'id_field' => 'lbf_units_id',
                'table_name' => 'lbf_units_reflections_comments'
            ],  
        ];

        $query = "Select * from ".$types[$objectType]['table_name']." as ur inner join sis_teacher_details as td on ur.teacher_id = td.sis_teacher_id where ur.is_active = 1 and ".$types[$objectType]['id_field']." = ".$oldObjectId;

        $results = $this->connectWithMigrationDB($query);

        $this->loadModel('UnitReflections');

        $unitReflections = [];

        foreach ($results as $key => $row) {
        
            $unitReflections[$key] = [
                'unit_id' => $newUnitId,
                'description' => urldecode($row['comments']),
                'reflection_category_id' => 1,
                'object_identifier' => $objectId,
                'object_name' => $objectType,
            ];

            $teacherId = false; 
            $teacher = $this->UnitReflections->Units
                                             ->UnitTeachers
                                             ->Teachers
                                             ->find()
                                             ->where([
                                                'first_name' => $row['sis_teacher_first_name'],
                                                'last_name' => $row['sis_teacher_last_name']
                                             ])
                                             ->first();
            if(!$teacher){
                $this->out('Teacher not found on new db. created by will not be saved.');
            }else{
                $unitReflections[$key]['created_by'] = $teacher->id; 
            }
        }


        $this->out(count($unitReflections)." unit reflections found.");
        if(empty($unitReflections)){
            return false;
        }
        $unitReflections = $this->UnitReflections->newEntities($unitReflections);
        if(!$this->UnitReflections->saveMany($unitReflections)){
            $this->out('unit reflections could not be saved.');
            print_r($unitReflections);
            return false;
        }

        $this->out('unit reflections have been saved.');
    }

    public function assessmentStandardsReport(){
        $this->out('In assessment standards report.');

        $assessmentIds = "(3047464,5701648,5701649,5701650,5701651,5701652,5701653,5701654,5701655,6357016,6357022,6357064,6357065,5701641,5701642,5701643,6357066,6357067,6357068,6357069,6357070,6357071,6357072,6357073,6357074,6357075,6357076,6357077,6357078,6357200,6357079,6357080,6357081,6357082,6357083,6357084,6357085,6357086,6357087,6357088,6357089,6357090,5701644,5701645,6357091,6357092,6357093,6357094,6357095,6357096,6357097,6357098,6357099,6357100,6357101,6357102,5701646,6357103,6357104,6357105,6357106,6357107,6357108,6357109,6357110,6357111,6357112,6357113,6357199,6357114,6357115,6357116,6357117,6357118,6357119,6357120,6357121,6357122,6357123,6357124,6357157,6357004,6357005,6357006,6357007,6357008,6357009,6357010,6357011,6357012,6357125,6357126,6357127,6357128,6357129,6357130,6357131,6357132,6357133,6357134,6357135,6357136,6357137,6357138,6357139,6357140,6357141,6357142,6357143,6357144,6357145,6357146,6357147,6357148,6357149,6357150,5701647,6357013,6357014,6357015,6357151,6357152,6357153,6357154,6357155,6357156,6357197,6357198)";

        $query = "Select ast.lbf_standard_id as standard_id from lbf_units_assessment as ua inner join lbf_assessment_standards as ast on ua.lbf_units_assessment_id = ast.lbf_units_assessment_id where ua.lbf_units_assessment_id IN ".$assessmentIds;

        $results = $this->connectWithMigrationDB($query);

        $oldStandardIds = [];

        foreach ($results as $row) {
            $oldStandardIds[] = $row['standard_id'];            
        }

        $this->standardsReport(array_unique($oldStandardIds), 'assessmentStandardsReport.csv');
    }

    public function standardsReport($ids = null, $filename = null){

        $this->out('In standards Report');

        $query = "Select la.lbf_learning_area_name as learning_area, s.lbf_standards_id as child_id, s.standards_name as child_name, p.lbf_standards_id as parent_id, p.standards_name as parent_name from lbf_standards as s left join lbf_standards as p on s.lbf_standard_parent = p.lbf_standards_id left join lbf_standards_course as sc on s.lbf_standards_id = sc.lbf_standards_id inner join lbf_learning_areas as la on sc.lbf_learning_areas_id = la.lbf_learning_area_id";

        if($ids){
            $query = $query." where s.lbf_standards_id IN (".implode(',', $ids).")";
        }


        $results = $this->connectWithMigrationDB($query);
        $this->loadModel('Standards');

        if(!$filename){
            $filename = "standardsComparisonReport.csv";
        }

        $file = fopen($filename, "w");
        $data = [];

        fputcsv($file,['Type', 'Old Id', 'Status', 'New Id', 'Learning Areas','Name']);

        
        foreach ($results as $row) {
            
            if(isset($data[$row['child_id']])){
                if(strrpos($data[$row['child_id']][4], $row['learning_area']) === false){
                    $data[$row['child_id']][4] = $data[$row['child_id']][4].' | '.$row['learning_area'];
                }
                continue;   
            }

            if(in_array($row['parent_id'], [0, null])){
                $this->out('Finding strand '.$row['child_name'].' on new db.');
                $strand = $this->Standards->Strands->find()->where(['name' => $row['child_name']])->first();
                if(!$strand){
                    
                    $data[$row['child_id']] = ['Strand', $row['child_id'], 'Not Found', 'N/A', $row['learning_area'], $row['child_name']]; 
                    $this->out('Not found on new DB.');
                    continue;
                }
                $data[$row['child_id']] = ['Strand', $row['child_id'], 'Found', $strand->id, $row['learning_area'], $row['child_name']];
                $this->out('Found on new DB');
            
            }else{

                $this->out('Finding standard '.$row['child_name'].' which belongs to strand '.$row['parent_name'].' on new db.');
                $standard = $this->Standards->find()
                                             ->where(['Standards.name' => $row['child_name']])
                                             ->matching('Strands', function($q) use($row){
                                                return $q->where(['Strands.name' => $row['parent_name']]);
                                             })
                                             ->first();
                if(!$standard){
                    $data[$row['child_id']] = ['Standard', $row['child_id'], 'Not Found', 'N/A', $row['learning_area'], $row['child_name']];
                    $this->out('Not found on new DB.');
                    continue;
                }
                $data[$row['child_id']] = ['Standard', $row['child_id'], 'Found', $standard->id, $row['learning_area'], $row['child_name']];
                $this->out('Found on new DB');
            }
        }

        $this->out('Generating report.');
        
        foreach ($data as $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        $this->out('Report has been generated.');
    }

    private function _getContentCategoryArray(){
        $categoryArray = [
            //Data for Ubd Content.
            [   
               'table_name' => 'lbf_ltt_goals',
               'unit_id_field' => 'lbf_unit_id',
               'id_field' => 'lbf_ltt_goals_id',
               'text_field' => 'lbf_ltt_goals_name',
               'old_type' => false,
               'unit_join_table' => 'lbf_units_lttg',
               'join_id' => 'lbf_ltt_goal_id',
               'type' => 'common_transfer_goals',
            ],
            [
               'table_name' => 'lbf_key_questions',
               'unit_id_field' => 'lbf_unit_id',
               'id_field' => 'lbf_key_questions_id',
               'text_field' => 'question_description',
               'old_type' => false,
               'unit_join_table' => 'lbf_units_key_question',
               'join_id' => 'lbf_key_questions_id',
               'type' => 'essential_questions',
            ],
            [
               'table_name' => 'lbf_key_understandings',
               'unit_id_field' => 'lbf_unit_id',
               'id_field' => 'lbf_key_understanding_id',
               'text_field' => 'understanding_description',
               'old_type' => false,
               'unit_join_table' => 'lbf_units_key_understanding',
               'join_id' => 'lbf_key_understanding_id',
               'type' => 'enduring_understandings',
            ],
            [
               'table_name' => 'lbf_key_skills',
               'unit_id_field' => 'lbf_unit_id',
               'id_field' => 'lbf_key_skill_id',
               'text_field' => 'skill_description',
               'old_type' => false,
               'unit_join_table' => 'lbf_units_key_skills',
               'join_id' => 'lbf_key_skill_id',
               'type' => 'skills',
            ],
            //Data for PYP Content.
            [
               'table_name' => 'lbf_generic_lov',
               'unit_id_field' => 'unit_id',
               'id_field' => 'code',
               'text_field' => 'description',
               'old_type' => 'TDSkill',
               'unit_join_table' => 'pyp_generic_details',
               'join_id' => 'code',
               'type' => 'transdisciplinary_skills',
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'unit_id_field' => 'unit_id',
               'id_field' => 'code',
               'text_field' => 'description',
               'old_type' => 'keyConcept',
               'unit_join_table' => 'pyp_generic_details',
               'join_id' => 'code',
               'type' => 'key_concepts',
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'unit_id_field' => 'unit_id',
               'id_field' => 'code',
               'text_field' => 'description',
               'old_type' => 'relatedConcept',
               'unit_join_table' => 'pyp_generic_details',
               'join_id' => 'code',
               'type' => 'related_concepts',
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'unit_id_field' => 'unit_id',
               'id_field' => 'code',
               'text_field' => 'description',
               'old_type' => 'learnerProfile',
               'unit_join_table' => 'pyp_generic_details',
               'join_id' => 'code',
               'type' => 'learner_profile',
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'unit_id_field' => 'unit_id',
               'id_field' => 'code',
               'text_field' => 'description',
               'old_type' => 'linesOfEnquiry',
               'unit_join_table' => 'pyp_generic_details',
               'join_id' => 'code',
               'type' => 'lines_of_inquiry',
            ],
        ];

        return $categoryArray;
    }

    private function _getUnitSpecificContentCategoryArray(){

        $categoryArray = [
            //For ubd
            [
               'table_name' => 'lbf_units_essential_questions',
               'id_field' => 'lbf_units_essential_questions_id',
               'unit_id_field' => 'lbf_units_id',
               'assessment_join_field' => 'questions',
               'text_field' => 'essential_questions_description',
               'old_type' => false,
               'name' => 'Essential Questions',
               'type' => 'essential_questions',
               'meta' => [
                    'heading_1' => 'Essential Questions',
                    'heading_2' => 'Common Essential Questions',
                    'heading_3' => 'Unit Specific Essential Questions'
                ]
            ],
            [
               'table_name' => 'lbf_units_long_term_goals',
               'id_field' => 'lbf_long_term_goals_id',
               'unit_id_field' => 'lbf_units_id',
               'assessment_join_field' => false,
               'text_field' => 'goal_description',
               'old_type' => false,
               'name' => 'Transfer Goals', 
               'type' => 'common_transfer_goals',
               'meta' => [
                    'heading_1' => 'Transfer Goals',
                    'heading_2' => 'Common Transfer Goals',
                    'heading_3' => 'Unit Specific TRANSFER GOALS',
               ]
            ],
            [
               'table_name' => 'lbf_units_understanding',
               'id_field' => 'lbf_units_understanding_id',
               'unit_id_field' => 'lbf_units_id',
               'assessment_join_field' => 'understanding',
               'text_field' => 'understanding_description',
               'old_type' => false,
               'name' => 'Enduring Understandings',
               'type' => 'enduring_understandings',
               'meta' => [
                    'heading_1' => 'Enduring Understandings',
                    'heading_2' => 'Common Enduring Understandings',
                    'heading_3' => 'Unit Specific Enduring Understandings'
               ]
            ],
            [
               'table_name' => 'lbf_units_knowledge',
               'id_field' => 'lbf_units_knowledge_id',
               'unit_id_field' => 'lbf_units_id',
               'assessment_join_field' => 'knowledge',
               'text_field' => 'knowledge_description',
               'old_type' => false,
               'name' => 'Knowledge',
               'type' => 'knowledge',
               'meta' => [
                    'heading_1' => 'Knowledge',
                    'heading_2' => 'Common Knowledge',
                    'heading_3' => 'Unit Specific Knowledge'
               ]
            ],
            [
               'table_name' => 'lbf_units_skills',
               'id_field' => 'lbf_units_skills_id',
               'unit_id_field' => 'lbf_units_id',
               'assessment_join_field' => 'skills',
               'text_field' => 'skills_description',
               'old_type' => false,
               'name' => 'Skills',
               'type' => 'skills',
               'meta' => [
                    'heading_1' => 'Skills',
                    'heading_2' => 'Common Skills',
                    'heading_3' => 'Unit Specific Skills'

               ]
            ],

            //for pyp

            [
               'table_name' => 'pyp_generic_details',
               'id_field' => 'id',
               'unit_id_field' => 'unit_id',
               'assessment_join_field' => false,
               'text_field' => 'pyp_value',
               'old_type' => 'TDSkill',
               'name' => 'Transdisciplinary Skills',
               'type' => 'transdisciplinary_skills',
               'meta' => [
                    'heading_1' => 'Transdisciplinary Skills',
                    'heading_2' => 'Common Transdisciplinary Skills',
                    'heading_3' => 'Unit Specific Transdisciplinary Skills'

               ]
            ],
            [
               'table_name' => 'pyp_generic_details',
               'id_field' => 'id',
               'unit_id_field' => 'unit_id',
               'assessment_join_field' => false,
               'text_field' => 'pyp_value',
               'old_type' => 'keyConcept',
               'name' => 'Key Concepts',
               'type' => 'key_concepts',
               'meta' => [
                    'heading_1' => 'Key Concepts',
                    'heading_2' => 'Common Key Concepts',
                    'heading_3' => 'Unit Specific Key Concepts'

               ]
            ],
            [
               'table_name' => 'pyp_generic_details',
               'id_field' => 'id',
               'unit_id_field' => 'unit_id',
               'assessment_join_field' => false,
               'text_field' => 'pyp_value',
               'old_type' => 'relatedConceptText',
               'name' => 'Related Concepts',
               'type' => 'related_concepts',
               'meta' => [
                    'heading_1' => 'Related Concepts',
                    'heading_2' => 'Common Related Concepts',
                    'heading_3' => 'Unit Specific Related Concepts'

               ]
            ],
            [
               'table_name' => 'pyp_generic_details',
               'id_field' => 'id',
               'unit_id_field' => 'unit_id',
               'assessment_join_field' => false,
               'text_field' => 'pyp_value',
               'old_type' => 'learnerProfile',
               'name' => 'Learner Profile',
               'type' => 'learner_profile',
               'meta' => [
                    'heading_1' => 'Learner Profile',
                    'heading_2' => 'Common Learner Profile',
                    'heading_3' => 'Unit Specific Learner Profile'

               ]
            ],
            [
               'table_name' => 'pyp_generic_details',
               'id_field' => 'id',
               'unit_id_field' => 'unit_id',
               'assessment_join_field' => false,
               'text_field' => 'pyp_value',
               'old_type' => 'LOIText',
               'name' => 'Lines Of Inquiry',
               'type' => 'lines_of_inquiry',
               'meta' => [
                    'heading_1' => 'Lines Of Inquiry',
                    'heading_2' => 'Common Lines Of Inquiry',
                    'heading_3' => 'Unit Specific Lines Of Inquiry'

               ]
            ],
            [
               'table_name' => 'pyp_generic_details',
               'id_field' => 'id',
               'unit_id_field' => 'unit_id',
               'assessment_join_field' => false,
               'text_field' => 'pyp_value',
               'old_type' => 'centralIdea',
               'name' => 'Central Idea',
               'type' => 'central_idea',
               'meta' => [
                    'heading_1' => 'Central Idea',
                    'heading_2' => 'Common Central Idea',
                    'heading_3' => 'Unit Specific Central Idea'

               ]
            ],
        ];
        return $categoryArray;
    }


    public function fixAssessmentStrands(){
        $this->out('In Assessment strands.');
        $this->loadModel('Assessments');
        $assessmentStrands = [];
        $assessmentStandards = $this->Assessments->AssessmentStandards->find()->contain(['Standards'])->toArray();
        $count = 0;
        foreach ($assessmentStandards as $key => $value) {
            $count++;
            $assessmentStrand = $this->Assessments->AssessmentStrands
                                     ->find()
                                     ->where([
                                                'assessment_id' => $value->assessment_id,
                                                'strand_id' => $value->standard->strand_id
                                            ])
                                     ->first();

            if($assessmentStrand){
                $this->out('Assessment strand already exists. Processed = '.$count);
                continue;
            }

            $assessmentStrand = [
                                    'assessment_id' => $value->assessment_id,
                                    'strand_id' => $value->standard->strand_id
            ];

            $assessmentStrand = $this->Assessments->AssessmentStrands->newEntity($assessmentStrand);
            if(!$this->Assessments->AssessmentStrands->save($assessmentStrand)){
                $this->out('AssessmentStrand could not be saved. Processed = '.$count);
                print_r($assessmentStrand);
                continue;
            }

            $this->out('AssessmentStrand saved. Processed = '.$count);

        }
    }

    public function fixUnitStrands(){
        $this->out('In fix unit strands.');
        $this->loadModel('Units');
        $unitStrands = [];
        $count = 0;
        $unitStandards = $this->Units->UnitStandards->find()->contain(['Standards'])->toArray();
        foreach ($unitStandards as $key => $value) {
            $count++;
            $unitStrand = $this->Units->UnitStrands
                                     ->find()
                                     ->where([
                                                'unit_id' => $value->unit_id,
                                                'strand_id' => $value->standard->strand_id
                                            ])
                                     ->first();

            if($unitStrand){
                $this->out('Unit strand already exists. Processed = '.$count);
                continue;
            }

            $unitStrand = [
                                    'unit_id' => $value->unit_id,
                                    'strand_id' => $value->standard->strand_id
            ];

            $unitStrand = $this->Units->UnitStrands->newEntity($unitStrand);
            if(!$this->Units->UnitStrands->save($unitStrand)){
                $this->out('UnitStrand could not be saved. Processed = '.$count);
                print_r($unitStrand);
                continue;
            }
            
            $this->out('Unit Strand saved. Processed = '.$count);

        }
    }

}   
