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
/**
 * MigrateFromDb shell command.
 */
class MigrateFromDbIscShell extends Shell
{
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

    public function saveData($modelName, $associated, $data){
      
      $associated = ['associated' => $associated];

      $temp = $this->loadModel($modelName);
      $entity = $this->$modelName->newEntities($data, $associated);
      // pr($entity);die;
      $model = TableRegistry::get($modelName);
      $entities = [];

      $response = $model->connection()->transactional(function () use ($model, $modelName, $associated, $data, $entities){

        $entities = $model->newEntities($data, $associated);
        // print_r($entities);
        foreach ($entities as $entity) {
          if($entity->errors()){
            pr($entity->errors());
            throw new BadRequestException(__('KINDLY_PROVIDE_VALID_DATA'));
          }
        }

        if($model->saveMany($entities, $associated)){

          pr('Data has been saved in '.$modelName.' table and associated '.json_encode($associated).' tables');
          // pr($entities);
          return $entities;
        }else{
          pr($entities);
          throw new Exception(__('ENTITY_ERROR', $modelName.' entity'));
        }

      });
      return $response;
    }

    public function migrateCurriculums(){
        $results = $this->connectWithMigrationDB('select * from lbf_curriculum');
        $curriculums = [];
        foreach ($results as $key => $row){
            
            $curriculum = [
                            'name' => $row['lbf_curriculum_name'],
                            'description' => ($row['description'] ? $row['description'] : $row['lbf_curriculum_name'])
                          ];
            $curriculums[] = $curriculum; 
        }   
        
        print_r($curriculums);
        if(!count($curriculums)){
            $this->out('No curriculums available to save.');
            return false;
        }

        $curriculums = $this->saveData('Curriculums', [], $curriculums);
        if($curriculums){
            $this->out('Curriculums have been saved.');
        }
        return $curriculums;
    }


    public function migrateLearningAreas(){
        
        $results = $this->connectWithMigrationDB('select * from lbf_learning_areas as la join lbf_curriculum as c on la.lbf_curriculum_id = c.lbf_curriculum_id');
        
        $this->loadModel('Curriculums');
        $curriculums = $this->Curriculums->find()->indexBy('name')->toArray();
        if(empty($curriculums)){
            $this->out('No curriculums found');
            return false;
        }

        $learningAreas = [];
        foreach ($results as $key => $row){
            
            
            if(!isset($curriculums[$row['lbf_curriculum_name']])){
                $this->out("Ignoring value as Curriculum was not found.");
                print_r($row);
                continue;
            }

            $learningAreas[] = [
                            
                                    'curriculum_id' => $curriculums[$row['lbf_curriculum_name']]->id,
                                    'name' => $row['lbf_learning_area_name'],
                                    'description' => $row['lb_subject_area'] ? $row['lb_subject_area'] : $row['lbf_learning_area_name'],
                               ]; 
        } 
        
        print_r($learningAreas);
        if(!count($learningAreas)){
            $this->out('No learning areas found');
            return false;
        }

        $learningAreas = $this->saveData('LearningAreas', [], $learningAreas);
        if($learningAreas){
            $this->out('Learning areas have been saved.');
        }
        return $learningAreas;
    }

    public function migrateStrands(){
        $results = $this->connectWithMigrationDB('select * from lbf_standards as s inner join lbf_standards_course as sc on s.lbf_standards_id = sc.lbf_standards_id inner join lbf_learning_areas as la on sc.lbf_learning_areas_id = la.lbf_learning_area_id inner join lbf_curriculum as c on la.lbf_curriculum_id = c.lbf_curriculum_id where hierarchy_level = 1');

        $this->loadModel('Curriculums');
        $learningAreas = $this->Curriculums->find()
                                         ->contain(['LearningAreas'])
                                         ->indexBy('name')
                                         ->map(function($value, $key){
                                            if(!empty($value->learning_areas)){
                                                return (new Collection($value->learning_areas))->indexBy('name')->toArray();
                                            }
                                            return []; 
                                         })
                                         ->toArray();
        if(empty($learningAreas)){
            $this->out('No learningAreas found');
            return false;
        }

        $strands = [];
        $strandsWithMultipleLearningAreas = [];
        $strandsWithoutLearningAreas = [];
        $strandsWithInvalidCodes = [];

        foreach ($results as $key => $row) {
            
            $dataToPrint = [
                            'lbf_standards_id' => $row['lbf_standards_id'],
                            'lbf_standards_code' => $row['lbf_standards_code'],
                            'standards_name' => $row['standards_name']
                        ];

            
            if(!$row['lbf_standards_code'] || in_array($row['lbf_standards_code'], [null, false, '', 'n/a'])){
                $this->out('Ignoring value as strands code is invalid.');
                print_r($dataToPrint); 
                $strandsWithInvalidCodes[] = $row['lbf_standards_id'];
                continue;          
            }

            if(!$row['lbf_learning_area_name'] || !$row['lbf_curriculum_name']){
                $this->out('Ignoring value as associated learning area not found in old db.');
                print_r($dataToPrint);
                if(!in_array($row['lbf_standards_code'], $strandsWithoutLearningAreas)){
                    $strandsWithoutLearningAreas[] = $row['lbf_standards_code'];
                }
                continue;
            }

            if(!isset($learningAreas[$row['lbf_curriculum_name']][$row['lbf_learning_area_name']])){
                $this->out('ignoring value as associated Learning Area not found in new db.');
                print_r($dataToPrint);
                continue;
            }

            if(isset($strands[$row['lbf_standards_code']]) && $learningAreas[$row['lbf_curriculum_name']][$row['lbf_learning_area_name']]->id != $strands[$row['lbf_standards_code']]['learning_area_id']){
                $this->out('Strand with code '.$row['lbf_standards_code'].' is associated with multiple learning areas in old db therefore ignoring value. This strand will not be saved');
                print_r($dataToPrint);
                $strandsWithMultipleLearningAreas[] = $row['lbf_standards_code'];
                continue;
            }

            $strands[$row['lbf_standards_code']] = [
                'name' => $row['standards_name'],
                'description' => $row['standards_name'],
                'code' => $row['lbf_standards_code'],
                'learning_area_id' => $learningAreas[$row['lbf_curriculum_name']][$row['lbf_learning_area_name']]->id
            ];
        }

        if(!empty($strandsWithMultipleLearningAreas)){
            foreach ($strandsWithMultipleLearningAreas as $value) {
                unset($strands[$value]);
            }
        }

        $this->out('Strands with no learning areas');
        print_r($strandsWithoutLearningAreas);

        $this->out('Strands with multiple learning areas');
        print_r($strandsWithMultipleLearningAreas);   

        $this->out('Strands with invalid strand codes.');
        print_r($strandsWithInvalidCodes);        
        // print_r($strands);
        if(!count($strands)){
            $this->out('No strands available to be saved.');
            return false;
        }

        $strands = $this->saveData('Strands', [], $strands);
        if($strands){
            $this->out('Strands have been saved.');
        }

        return $strands;
    }



    public function migrateImpacts(){

        $this->Impacts = $this->loadModel('Impacts'); 
        $impactCategories = $this->_migrateImapactCategories();
        $parents = $this->_migrateLevelOneImpacts($impactCategories);
        // pr($parents);die;
        $anotherLevel = true;

        while($anotherLevel){
            $results = $this->connectWithMigrationDB("Select impact.lbf_impact_id as id, impact.impact_code as code, impact.lbf_impact_name as name, impact.lbf_impact_type as type, impact.school_code as course_grades, parent.lbf_impact_name as parent_name, parent.lbf_impact_type as parent_type, strand.lbf_impact_name as strand_name, strand.lbf_impact_type as strand_type from lbf_impact as impact inner join lbf_impact as parent on impact.lbf_impact_parent = parent.lbf_impact_id inner join lbf_impact as strand on impact.impact_strand = strand.lbf_impact_id where impact.lbf_impact_parent IN (".implode(',', $parents['oldIds']).")");

            
            $completed = [];
            $oldIds = [];
            $futureParents = [];

            foreach ($results as $key => $row) {
                $valueToPrint = [
                    'lbf_impact_id' => $row['id'], 
                    'impact_code' => $row['code'],
                    'lbf_impact_name' => $row['name'],
                    'lbf_impact_type' => $row['type']
                ]; 

                $oldIds[] = $row['id'];

                if(isset($completed[$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']][$row['name']][$row['type']])){
                    $this->out('Duplicate. Ignoring');
                    print_r($valueToPrint);
                    continue;
                }

                $impact = [
                    'name' => $row['name'],
                    'description' => $row['name'],
                    'impact_type' => $row['type'],
                    'impact_category_id' => $impactCategories['completed'][$row['strand_name']][$row['strand_type']],
                    'parent_id' => $parents['completed'][$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']]
                ];

                $impact['grade_impacts'] = $this->_getGradeImpacts($row['course_grades']);
                if(empty($impact['grade_impacts'])){
                    unset($impact['grade_impacts']);
                }

                $this->out('Trying to save new impact.');
                $impact = $this->Impacts->newEntity($impact);

                if(!$this->Impacts->save($impact)){
                    $this->out('Impact could not be saved.');
                    print_r($impact);
                    continue;
                }

                if(!isset($completed[$row['strand_name']])){
                    $completed[$row['strand_name']] = [];
                    $futureParents[$row['strand_name']] = [];
                }

                if(!isset($completed[$row['strand_name']][$row['strand_type']])){
                    $completed[$row['strand_name']][$row['strand_type']] = [];
                    $futureParents[$row['strand_name']][$row['strand_type']] = [];

                }

                if(!isset($completed[$row['strand_name']][$row['strand_type']][$row['parent_name']])){
                    $completed[$row['strand_name']][$row['strand_type']][$row['parent_name']] = [];
                }

                if(!isset($completed[$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']])){
                    $completed[$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']] = [];
                }

                if(!isset($completed[$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']][$row['name']])){
                    $completed[$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']][$row['name']] = [];
                    $completed[$row['strand_name']][$row['strand_type']][$row['name']] = [];
                }

                $completed[$row['strand_name']][$row['strand_type']][$row['parent_name']][$row['parent_type']][$row['name']][$row['type']] = $impact->id;

                $futureParents[$row['strand_name']][$row['strand_type']][$row['name']][$row['type']] = $impact->id;

                $this->out('Impact has been saved.');

            }

            $parents = ['completed' => $futureParents, 'oldIds' => $oldIds];

            if(empty($oldIds) && empty($completed)){
                $anotherLevel = false;
            }
        }

       $this->out('impacts have been migrated.');

    }

    private function _migrateLevelOneImpacts($impactCategories){
        $firstLevel = $this->connectWithMigrationDB("Select impact.lbf_impact_id as id, impact.impact_code as code, impact.lbf_impact_name as name, impact.lbf_impact_type as type, impact.school_code as course_grades, strand.lbf_impact_name as strand_name, strand.lbf_impact_type as strand_type from lbf_impact as impact inner join lbf_impact as strand on impact.lbf_impact_parent = strand.lbf_impact_id  where impact.lbf_impact_parent IN (".implode(',', $impactCategories['oldIds']).")");

        $completed = [];
        $oldIds = [];

        foreach ($firstLevel as $key => $row) {
            $valueToPrint = [
                'lbf_impact_id' => $row['id'], 
                'impact_code' => $row['code'],
                'lbf_impact_name' => $row['name'],
                'lbf_impact_type' => $row['type']
            ]; 

            $oldIds[] = $row['id'];

            if(isset($completed[$row['strand_name']][$row['strand_type']][$row['name']][$row['type']])){
                $this->out('Duplicate. Ignoring');
                print_r($valueToPrint);
                continue;
            }

            $impact = [
                'name' => $row['name'],
                'description' => $row['name'],
                'impact_type' => $row['type'],
                'impact_category_id' => $impactCategories['completed'][$row['strand_name']][$row['strand_type']]
            ];

            $impact['grade_impacts'] = $this->_getGradeImpacts($row['course_grades']);
            if(empty($impact['grade_impacts'])){
                unset($impact['grade_impacts']);
            }

            $this->out('Trying to save new impact.');
            // print_r($impact);
            $impact = $this->Impacts->newEntity($impact);

            if(!$this->Impacts->save($impact)){
                $this->out('Impact could not be saved.');
                print_r($impact);
                continue;
            }

            $this->out('Impact has been saved');

            if(!isset($completed[$row['strand_name']])){
                $completed[$row['strand_name']] = [];
            }

            if(!isset($completed[$row['strand_name']][$row['strand_type']])){
                $completed[$row['strand_name']][$row['strand_type']] = [];
            }

            if(!isset($completed[$row['strand_name']][$row['strand_type']][$row['name']])){
                $completed[$row['strand_name']][$row['strand_type']][$row['name']] = [];
            }

            $completed[$row['strand_name']][$row['strand_type']][$row['name']][$row['type']] = $impact->id;
            $this->out('Impact has been saved.');
            
        }

        return ['completed' => $completed, 'oldIds' => $oldIds];

    }

    private function _migrateImapactCategories(){

        $results = $this->connectWithMigrationDB("Select * from lbf_impact where lbf_impact_parent = 0");

        $this->loadModel('ImpactCategories');
        $completed = [];
        $oldIds = [];
        $impactCategories = [];
        foreach ($results as $key => $row) {
            
            $valueToPrint = [
                'lbf_impact_id' => $row['lbf_impact_id'], 
                'impact_code' => $row['impact_code'],
                'lbf_impact_name' => $row['lbf_impact_name'],
                'lbf_impact_type' => $row['lbf_impact_type']
            ];  

            $oldIds[] = $row['lbf_impact_id'];

            if(isset($completed[$row['lbf_impact_name']][$row['lbf_impact_type']])){
                $this->out('Duplicate. Ignoring');
                print_r($valueToPrint);
                continue;
            }

            $impactCategory = [
                'name' => $row['lbf_impact_name'],
                'description' => $row['lbf_impact_name'],
                'impact_type' => $row['lbf_impact_type']
            ];

            $this->out('Trying to save new imact category.');
            $impactCategory = $this->ImpactCategories->newEntity($impactCategory);

            if(!$this->ImpactCategories->save($impactCategory)){
                $this->out('Impact Category could not be saved.');
                print_r($impactCategory);
                continue;
            }

            if(!isset($completed[$row['lbf_impact_name']])){
                $completed[$row['lbf_impact_name']] = [];
            }

            $completed[$row['lbf_impact_name']][$row['lbf_impact_type']] = $impactCategory->id;
        }

        $this->out('impacts Categories have been migrated');
        return ['completed' => $completed, 'oldIds' => $oldIds];
    }

    private function _getGradeImpacts($courseGrades){
        
        $this->out('In get Grade Impacts');    
        $this->loadModel('GradeImpacts');

        $query  = "Select * from lb_course_grade as cg inner join lb_course as c on c.lb_course_id = cg.lb_course_id where cg.course_grade IN (".$courseGrades.")";

        $results =  $this->connectWithMigrationDB($query);

        $courses = [
            'Health' => [
                'oldName' => 'Health & PE',
                'newNames' => ['PE']
            ],
            'English' => [
                'oldName' => 'English and Humanities',
                'newNames' => ['English', 'Humanities']
            ],
            'Spanish' => [
                'oldName' => 'Spanish A',
                'newNames' => ['Spanish']
            ],
            'Visual' => [
                'oldName' => 'Visual Arts',
                'newNames' => ['Visual Art']
            ]
        ];
        $this->out('Finding course in new DB.');    
        $gradeImpacts = [];
        foreach ($results as $row) {
            $courseNames = [$row['course_name']];
            $firstWord = explode(' ', $row['course_name'])[0]; 
            if(isset($courses[$firstWord])){
                $courseNames = [];
                foreach ($courses[$firstWord]['newNames'] as $key => $value) {
                    $courseNames[] = str_replace($courses[$firstWord]['oldName'], $value, $row['course_name']);
                }
            }
            $gradeIds = $this->GradeImpacts->Grades->Courses->find()
                                                      ->where(['name IN' => $courseNames])
                                                      ->extract('grade_id')
                                                      ->toArray();

            $gradeImpacts = array_merge($gradeImpacts, $gradeIds);
        }

        $gradeImpacts = array_unique($gradeImpacts);

        if(!empty($gradeImpacts)){

            $gradeImpacts = (new Collection($gradeImpacts))->map(function($value, $key){
                return ['grade_id' => $value];
            })->toArray();

        }else{
            $this->out('No course impacts found.');
        }
        return $gradeImpacts;
    }

    private function _getContentCategoryArray(){
        $categoryArray = [
            [
               'table_name' => 'lbf_ltt_goals',
               'id_field' => 'lbf_ltt_goals_id',
               'text_field' => 'lbf_ltt_goals_name',
               'parent_field' => false,
               'name' => 'Common Transfer Goals | ',
               'type' => 'common_transfer_goals',
               'meta' => [
                    'heading_1' => 'What do you want the students to be able to do at the end of this unit?',
                    'heading_2' => 'COMMON TRANSFER GOALS',
                    'heading_3' => 'Unit Specific TRANSFER GOALS',
               ]
            ],
            [
               'table_name' => 'lbf_key_questions',
               'id_field' => 'lbf_key_questions_id',
               'text_field' => 'question_description',
               'parent_field' => false,
               'name' => 'Common Questions | ',
               'type' => 'common_questions',
               'meta' => [
                    'heading_1' => 'What key questions will drive their learning and help them to identify the ways in which they will demonstrate their learning ?',
                    'heading_2' => 'Common Question',
                    'heading_3' => 'Unit Specific Question'

               ]
            ],
            [
               'table_name' => 'lbf_key_understandings',
               'id_field' => 'lbf_key_understanding_id',
               'text_field' => 'understanding_description',
               'parent_field' => false,
               'name' => 'Common Understandings | ',
               'type' => 'common_understandings',
               'meta' => [
                    'heading_1' => 'What key understandings will students need to successfully demonstrate their learning?',
                    'heading_2' => 'Common Understanding',
                    'heading_3' => 'Unit Specific Understanding'

               ]
            ],
            [
               'table_name' => 'lbf_key_skills',
               'id_field' => 'lbf_key_skill_id',
               'text_field' => 'skill_description',
               'parent_field' => 'skill_parent',
               'name' => 'Common Technology Skills | ',
               'type' => 'common_technology_skills',
               'meta' => [
                    'heading_1' => 'What key skills will drive their learning and help them to identify the ways in which they will demonstrate their learning ?',
                    'heading_2' => 'Common Technology Skills & Approaches',
                    'heading_3' => 'Unit Specific Technology Skills & Approaches'

               ]
            ]
        ];

        return $categoryArray;
    }   

    private function _getLearningAreasArray(){
        $learningAreasArray = [
            'Humanities' => ['Humanities'],
            'Math' => ['Math'],
            'The Arts' => ['Performing Arts', 'Visual Arts'],
            'Science' => ['Science'],
            'Computer Science' => ['Computer Science'],
            'Health & Physical Education' => ['PE/Health'],
            'TOK' => ['TOK'],
            'World Languages' => ['German', 'Spanish', 'English'],
            'Learning Studio' => ['Learning Studio'],
            'Global Studies' => ['Global Studies'],
            'Social Studies' => ['Social Studies']
        ];

        return $learningAreasArray;
    }

    public function fixCourseContentCategories(){
        $this->out('inside fixCourseContentCategories');
        $this->loadModel('CourseContentCategories');
        $learningAreas = $this->_getLearningAreasArray();
        $contentCategories = [];

        $courses = $this->CourseContentCategories
                        ->Courses
                        ->find()
                        ->contain(['LearningAreas'])
                        ->toArray();
        $addCount = 0;
        foreach ($courses as $value) {
            $this->out('Processing for course '.$value->name);
            $learningArea = $value->learning_area->name;
            $subjectArea = (new Collection($learningAreas))->filter(function($value,$key) use($learningArea){
                                return in_array($learningArea, $value);
                           })->toArray();
            if(empty($subjectArea)){
                $this->out('Subject Area not found');
                continue;
            }
            $subjectArea = array_keys($subjectArea)[0];

            if(!isset($contentCategories[$subjectArea])){
                $contentCategoryIds = $this->CourseContentCategories->ContentCategories->find()->where(['name LIKE' => '%'.$subjectArea.'%'])->extract('id')->toArray();
                $contentCategories[$subjectArea] = $contentCategoryIds;   
            }

            if(empty($contentCategories[$subjectArea])){
                $this->out('No content categories found for this course');
                continue;
            }

            $duplicateEntries = $this->CourseContentCategories->findByCourseId($value->id)->where(['content_category_id IN' => $contentCategories[$subjectArea]])->extract('content_category_id')->toArray();

            $contentCategoryIds = array_diff($contentCategories[$subjectArea], $duplicateEntries);

            if(empty($contentCategoryIds)){
                $this->out('course Content categories already added.');
                continue;
            }

            $courseContentCategories = (new Collection($contentCategoryIds))
                                       ->map(function($contentCategoryId) use($value){
                                         return ['course_id' => $value->id, 'content_category_id' => $contentCategoryId];
                                       })->toArray();

            $courseContentCategories = $this->CourseContentCategories->newEntities($courseContentCategories);

            if(!$this->CourseContentCategories->saveMany($courseContentCategories)){
                $this->out('course content categories could not be saved for this course.');
                print_r($courseContentCategories);
                continue;
            }

            $this->out('Course Content Categories saved.');
            $addCount = $addCount + count($courseContentCategories);
        }

        $this->out($addCount." CCC added to the system.");
    }


    public function migrateContentValues(){
        
        $categoryArray = $this->_getContentCategoryArray();
        $this->ContentValues = $this->loadModel('ContentValues');
        $contentCategories = [];

        $this->out('Migrating content values');
        foreach ($categoryArray as $key => $category) {
            
            if($category['parent_field']){
              $contentCategories = $this->_treeContentValues($contentCategories, $category);
            }else{
              $contentCategories = $this->_linearContentValues($contentCategories, $category);
            }
        }
        $this->out('Content values have been migrated.');
        $this->createCourseContentCategories();
    }

    public function createCourseContentCategories(){
        $learningAreasArray = $this->_getLearningAreasArray();
        $this->loadModel('ContentCategories');
        $contentCategories = $this->ContentCategories->find()->all();
        $this->out('Creating course content categories.');
        $courseContentCategories = [];
        foreach ($contentCategories as $category) {
            $this->out('Content category '.$category->name);
            $learningAreas = $learningAreasArray[trim($category->meta['subject_area'])];
            $courses = $this->ContentCategories
                            ->CourseContentCategories
                            ->Courses
                            ->find()
                            ->contain(['LearningAreas'])
                            ->matching('LearningAreas', function($q) use($learningAreas){
                                return $q->where(['LearningAreas.name IN' => $learningAreas]);
                            })
                            ->all()
                            ->toArray();

            if(empty($courses)){
                $this->out('No course found for this content category.');
            }

            foreach ($courses as $course) {
                $courseContentCategories[] = [
                    'content_category_id' => $category->id,
                    'course_id' => $course->id
                ];
            }
        }   

        if(!count($courseContentCategories)){
            $this->out('No courseContentCategories available to be saved');
            return false;
        }

        $courseContentCategories = $this->saveData('CourseContentCategories', [], $courseContentCategories);
        if($courseContentCategories){
            $this->out('courseContentCategories have been created');
        }

        return $courseContentCategories;
           
    }

    private function _linearContentValues($contentCategories, $category){

        $this->out('in _linearContentValues for '.$category['type']);

        $results = $this->connectWithMigrationDB("Select * from ".$category['table_name']);
        $contentValues = [];

        foreach ($results as $row) {
            $this->out('Finding content category on new db.');
            $row['subject_area'] = trim($row['subject_area']);
            if(!isset($contentCategories[$category['name'].$row['subject_area']])){
                $this->out('Content Category not found. Creating new');
                $contentCategory = [
                    'name' => $category['name'].$row['subject_area'],
                    'type' => $category['type'],
                    'meta' => $category['meta']
                ];
                $contentCategory['meta']['subject_area'] = $row['subject_area'];
                $contentCategory = $this->ContentValues->ContentCategories->newEntity($contentCategory);
                if(!$this->ContentValues->ContentCategories->save($contentCategory)){
                    $this->out('Content category could not be created. Ignoring value.');
                    print_r($contentCategory);
                    continue;
                }
                $contentCategories[$contentCategory->name] = $contentCategory->id; 

            }

            $contentValues[] = [
                'content_category_id' => $contentCategory->id,
                'text' => $row[$category['text_field']]
            ];
        }

        if(!count($contentValues)){
            $this->out('No content values available to be saved for '.$category['type']);
            return false;
        }

        $contentValues = $this->saveData('ContentValues', [], $contentValues);
        if($contentValues){
            $this->out('Content values have been saved for '.$category['type']);
        }

        return $contentCategories;

    }

    private function _treeContentValues($contentCategories, $category){

        $this->out('in _treeContentValues for '.$category['type']);
        $childrenAvailable = true;
        $parents = [];


        while($childrenAvailable){
            
            if(empty($parents)){
                $results = $this->connectWithMigrationDB("Select * from ".$category['table_name']." where ".$category['parent_field']."= 0");
            }else{
                $results = $this->connectWithMigrationDB("Select * from ".$category['table_name']." where ".$category['parent_field']." IN (".implode(',', array_keys($parents)).")");
            }

            $futureParents = [];

            foreach ($results as $row) {
                $row['subject_area'] = trim($row['subject_area']);
                if(!isset($contentCategories[$category['name'].$row['subject_area']])){
                    $this->out('Content Category not found. Creating new');
                    $contentCategory = [
                        'name' => $category['name'].$row['subject_area'],
                        'type' => $category['type'],
                        'meta' => $category['meta']
                    ];

                    $contentCategory['meta']['subject_area'] = $row['subject_area'];
                    $contentCategory = $this->ContentValues->ContentCategories->newEntity($contentCategory);
                    if(!$this->ContentValues->ContentCategories->save($contentCategory)){
                        $this->out('Content category could not be created. Ignoring value.');
                        print_r($contentCategory);
                        continue;
                    }
                    $contentCategories[$contentCategory->name] = $contentCategory->id; 
                }

                $contentValue = [
                    'content_category_id' => $contentCategory->id,
                    'text' => $row[$category['text_field']],
                    'is_selectable' => $row['is_selectable'],
                    'parent_id' => $row[$category['parent_field']] == 0 ? null : $parents[$row[$category['parent_field']]]
                ];

                $this->out('trying to save content value.');
                $contentValue = $this->ContentValues->newEntity($contentValue);
                if(!$this->ContentValues->save($contentValue)){
                    $this->out('Content value could not be created. Ignoring value.');
                    print_r($contentValue);
                    continue;
                }

                $futureParents[$row[$category['id_field']]] = $contentValue->id;
            }

            if(empty($futureParents)){
                $childrenAvailable = false;
            }

            $parents = $futureParents;
        }

        $this->out('Content values have been saved for '.$category['type']);
        return $contentCategories;
    }
}
