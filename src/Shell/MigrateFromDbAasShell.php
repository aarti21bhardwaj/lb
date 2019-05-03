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
class MigrateFromDbAasShell extends Shell
{

    Private $_divisionsMap = ['MOS-ES' => 1, 'MOS-MS' => 2, 'MOS-HS' => 3];
    Private $_divisionGrades = [];
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

    public function migrateStandards(){
        $results = $this->connectWithMigrationDB('select s.lbf_standard_parent, s.lbf_strands_id, s.lbf_standards_id, s.hierarchy_level, s.standards_name,
        s.lbf_standards_code, la.lbf_learning_area_name, s.is_selectable, sc.course_grade from lbf_learning_areas as la inner join lbf_standards_course as sc on la.lbf_learning_area_id = sc.lbf_learning_areas_id inner join lbf_standards as s on sc.lbf_standards_id = s.lbf_standards_id where s.hierarchy_level');

        $results = (new Collection($results))
                      ->groupBy('lbf_learning_areas_id')
                      ->map(function($value, $key){
                        $value = (new Collection($value))
                                ->groupBy('hierarchy_level')
                                ->map(function($standards){
                                    $standards = (new Collection($standards))->indexBy('lbf_standards_id')->toArray();
                                    return $standards;
                                })
                                ->toArray();
                        return $value;
                       })
                       ->toArray();

        $this->Standards = $this->loadModel('Standards');
        $this->OldLbHash = $this->loadModel('OldLbHash');
        $this->_learningAreas = $this->Standards->Strands->LearningAreas->find()->combine('name', 'id')->toArray();
        
        if(empty($this->_learningAreas)){
            $this->out('No learningAreas found');
            return false;
        }

        foreach ($results as $oldLearningAreaId => $standardsByHierarchy){
            foreach ($standardsByHierarchy as $hierarchyLevel => $standards) {
                if($hierarchyLevel == 1){
                    //Strand will be saved here
                    $this->_extractStrands($standards);
                }elseif($hierarchyLevel == 2) {
                    $this->_extractStandards($standards, 'Strands');
                }else{
                    $this->_extractStandards($standards, 'Standards');
                }
            }
        }
    }

    private function _extractStrands($strands){
        $oldLbHash = [];
        foreach ($strands as $key => $row) {
            $this->out('Creating data for strand');
            $strand = [
                'name' => $row['standards_name'],
                'description' => $row['standards_name'],
                'code' => in_array($row['lbf_standards_code'], [null, false, ""]) ? 'n/a' : $row['lbf_standards_code'],
                'learning_area_id' => $this->_learningAreas[trim($row['lbf_learning_area_name'])]
            ];
            $strand = $this->Standards->Strands->newEntity($strand);
            if(!$this->Standards->Strands->save($strand)){
                print_r($strand);
                continue;
            }
            $this->out('strand has been saved. Creating data for old lb hash.');
            $oldLbHash[] = [
                'old_table_name' => 'lbf_standards',
                'new_table_name' => 'strands',
                'old_id' => $row['lbf_standards_id'],
                'new_id' => $strand->id
            ];
        }
        $this->out('Saving Old lb hash data.');
        $oldLbHash = $this->OldLbHash->newEntities($oldLbHash);
        if(!$this->OldLbHash->saveMany($oldLbHash)){
            $this->out('old lb hash could not be saved');
            print_r($oldLbHash);
        }
    }

    private function _extractStandards($standards, $parent){
        $oldLbHash = [];
        
        foreach ($standards as $key => $row) {
            $this->out('Creating data for standard');
            $standard = [
                'name' => $row['standards_name'],
                'description' => $row['standards_name'],
                'code' => in_array($row['lbf_standards_code'], [null, false, ""]) ? 'n/a' : $row['lbf_standards_code'],
                'is_selectable' => $row['is_selectable'],
                'parent_id' => null
            ];
            
            $this->out('Finding Strand on new_db');
            $strandOldLbHashEntry = $this->OldLbHash->findByOldId($row['lbf_strands_id'])->where(['new_table_name' == 'strands'])->last();
            $standard['strand_id'] = $strandOldLbHashEntry['new_id'];

            if($parent == 'Standards'){
                $this->out('Is not a root level standard .Finding parent_standard on new_db');
                $parentOldLbHashEntry = $this->OldLbHash->findByOldId($row['lbf_standard_parent'])->where(['new_table_name' == 'standards'])->last();
                $standard['parent_id'] = $parentOldLbHashEntry['new_id'];
            }

            $standard = $this->Standards->newEntity($standard);
            if(!$this->Standards->save($standard)){
                print_r($standard);
                continue;
            }
            $this->out('standard has been saved. Creating data for old lb hash.');
            $oldLbHash[] = [
                'old_table_name' => 'lbf_standards',
                'new_table_name' => 'standards',
                'old_id' => $row['lbf_standards_id'],
                'new_id' => $standard->id
            ];
        }

        $this->out('Saving Old lb hash data.');
        $oldLbHash = $this->OldLbHash->newEntities($oldLbHash);
        $oldLbHash = $this->OldLbHash->saveMany($oldLbHash);
        
    }

    public function migrateImpacts(){

        $this->Impacts = $this->loadModel('Impacts'); 
        $impactCategories = $this->_migrateImapactCategories();
        $parents = $this->_migrateLevelOneImpacts($impactCategories);
        $anotherLevel = true;

        while($anotherLevel){
            $results = $this->connectWithMigrationDB("Select impact.lbf_impact_id as id, impact.impact_code as code, impact.lbf_impact_name as name, impact.lbf_impact_type as type, impact.school_code as school_code, parent.lbf_impact_name as parent_name, parent.lbf_impact_type as parent_type, strand.lbf_impact_name as strand_name, strand.lbf_impact_type as strand_type from lbf_impact as impact inner join lbf_impact as parent on impact.lbf_impact_parent = parent.lbf_impact_id inner join lbf_impact as strand on impact.impact_strand = strand.lbf_impact_id where impact.lbf_impact_parent IN (".implode(',', $parents['oldIds']).")");

            
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

                $impact['grade_impacts'] = $this->_getGradeImpacts($row['school_code']);
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
        $firstLevel = $this->connectWithMigrationDB("Select impact.lbf_impact_id as id, impact.impact_code as code, impact.lbf_impact_name as name, impact.lbf_impact_type as type, impact.school_code as school_code, strand.lbf_impact_name as strand_name, strand.lbf_impact_type as strand_type from lbf_impact as impact inner join lbf_impact as strand on impact.lbf_impact_parent = strand.lbf_impact_id  where impact.lbf_impact_parent IN (".implode(',', $impactCategories['oldIds']).")");

        $completed = [];
        $oldIds = [];

        $this->out("In migrate level one impacts.");
        foreach ($firstLevel as $key => $row) {
            $valueToPrint = [
                'lbf_impact_id' => $row['id'], 
                'impact_code' => $row['code'],
                'lbf_impact_name' => $row['name'],
                'lbf_impact_type' => $row['type']
            ]; 

            $oldIds[] = $row['id'];
        
            $this->out("Checking for duplicate entries.");
            if(isset($completed[$row['strand_name']][$row['strand_type']][$row['name']][$row['type']])){
                $this->out('Duplicate. Ignoring');
                print_r($valueToPrint);
                continue;
            }
            $this->out("Forming data.");
            $impact = [
                'name' => $row['name'],
                'description' => $row['name'],
                'impact_type' => $row['type'],
                'impact_category_id' => $impactCategories['completed'][$row['strand_name']][$row['strand_type']]
            ];

            $impact['grade_impacts'] = $this->_getGradeImpacts($row['school_code']);
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

    private function _getGradeImpacts($schoolCode = false){
        
        $this->out('In get Grade Impacts');    
        $this->loadModel('DivisionGrades');
        if(!isset($this->_divisionsMap[$schoolCode])){
            $this->out("Invalid School Code. Impact grades will not be saved.");
            return [];
        }

        $divisonId = $this->_divisionsMap[$schoolCode];
        if(empty($this->_divisionGrades)){
           $this->_divisionGrades = $this->DivisionGrades->find()->groupBy('division_id')->map(function($value, $key){
                $value = (new Collection($value))->extract('grade_id')->toArray();
                return $value;
            })->toArray();
        }

        $gradeIds = $this->_divisionGrades[$divisonId];

        $gradeImpacts = (new Collection($gradeIds))->map(function($value, $key){
            return ['grade_id' => $value];
        })->toArray();

        return $gradeImpacts;
    }

    private function _getContentCategoryArray(){
        $categoryArray = [
            //Data for Ubd Content.
            [   
               'table_name' => 'lbf_ltt_goals',
               'id_field' => 'lbf_ltt_goals_id',
               'text_field' => 'lbf_ltt_goals_name',
               'description_field' => false,
               'old_type' => false,
               'parent_field' => false,
               'name' => 'Transfer Goals',
               'type' => 'common_transfer_goals',
               'meta' => [
                    'heading_1' => 'Transfer Goals',
                    'heading_2' => 'Common Transfer Goals',
                    'heading_3' => 'Unit Specific Transfer Goals',
               ]
            ],
            [   
               'table_name' => 'lbf_ltt_goals',
               'id_field' => 'lbf_ltt_goals_id',
               'text_field' => 'lbf_ltt_goals_name',
               'description_field' => false,
               'old_type' => false,
               'parent_field' => false,
               'name' => 'Transfer Goals',
               'type' => 'common_transfer_goals',
               'meta' => [
                    'heading_1' => 'Transfer Goals',
                    'heading_2' => 'Common Transfer Goals',
                    'heading_3' => 'Unit Specific Transfer Goals',
               ]
            ],
            [
               'table_name' => 'lbf_key_questions',
               'id_field' => 'lbf_key_questions_id',
               'text_field' => 'question_description',
               'description_field' => false,
               'old_type' => false,
               'parent_field' => false,
               'name' => 'Essential Questions',
               'type' => 'essential_questions',
               'meta' => [
                    'heading_1' => 'Essential Questions',
                    'heading_2' => 'Common Essential Questions',
                    'heading_3' => 'Unit Specific Essential Questions'

               ]
            ],
            [
               'table_name' => 'lbf_key_understandings',
               'id_field' => 'lbf_key_understanding_id',
               'text_field' => 'understanding_description',
               'description_field' => false,
               'old_type' => false,
               'parent_field' => false,
               'name' => 'Enduring Understandings',
               'type' => 'enduring_understandings',
               'meta' => [
                    'heading_1' => 'Enduring Understandings',
                    'heading_2' => 'Common Enduring Understandings',
                    'heading_3' => 'Unit Specific Enduring Understandings'

               ]
            ],
            [
               'table_name' => 'lbf_key_skills',
               'id_field' => 'lbf_key_skill_id',
               'text_field' => 'skill_description',
               'description_field' => false,
               'old_type' => false,
               'parent_field' => 'skill_parent',
               'name' => 'Skills',
               'type' => 'skills',
               'meta' => [
                    'heading_1' => 'Skills',
                    'heading_2' => 'Common Skills',
                    'heading_3' => 'Unit Specific Skills'

               ]
            ],
            //Data for PYP Content.
            [
               'table_name' => 'lbf_generic_lov',
               'id_field' => 'lbf_lov_id',
               'text_field' => 'description',
               'description_field' => 'value',
               'old_type' => 'TDSkill',
               'parent_field' => 'lbf_parent_id',
               'name' => 'Transdisciplinary Skills',
               'type' => 'transdisciplinary_skills',
               'meta' => [
                    'heading_1' => 'Transdisciplinary Skills',
                    'heading_2' => 'Common Transdisciplinary Skills',
                    'heading_3' => 'Unit Specific Transdisciplinary Skills'

               ]
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'id_field' => 'lbf_lov_id',
               'text_field' => 'description',
               'description_field' => 'value',
               'old_type' => 'keyConcept',
               'parent_field' => false,
               'name' => 'Key Concepts',
               'type' => 'key_concepts',
               'meta' => [
                    'heading_1' => 'Key Concepts',
                    'heading_2' => 'Common Key Concepts',
                    'heading_3' => 'Unit Specific Key Concepts'

               ]
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'id_field' => 'lbf_lov_id',
               'text_field' => 'description',
               'description_field' => 'value',
               'old_type' => 'relatedConcept',
               'parent_field' => false,
               'name' => 'Related Concepts',
               'type' => 'related_concepts',
               'meta' => [
                    'heading_1' => 'Related Concepts',
                    'heading_2' => 'Common Related Concepts',
                    'heading_3' => 'Unit Specific Related Concepts'

               ]
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'id_field' => 'lbf_lov_id',
               'text_field' => 'description',
               'description_field' => 'value',
               'old_type' => 'learnerProfile',
               'parent_field' => false,
               'name' => 'Learner Profile',
               'type' => 'learner_profile',
               'meta' => [
                    'heading_1' => 'Learner Profile',
                    'heading_2' => 'Common Learner Profile',
                    'heading_3' => 'Unit Specific Learner Profile'

               ]
            ],
            [
               'table_name' => 'lbf_generic_lov',
               'id_field' => 'lbf_lov_id',
               'text_field' => 'description',
               'description_field' => 'value',
               'old_type' => 'linesOfEnquiry',
               'parent_field' => false,
               'name' => 'Lines Of Inquiry',
               'type' => 'lines_of_inquiry',
               'meta' => [
                    'heading_1' => 'Lines Of Inquiry',
                    'heading_2' => 'Common Lines Of Inquiry',
                    'heading_3' => 'Unit Specific Lines Of Inquiry'

               ]
            ],
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
        // $this->createCourseContentCategories();
    }

    //Needs to be run after the courses have been migrated
    public function createCourseContentCategories(){
        $learningAreasArray = $this->_getLearningAreasArray();
        $this->loadModel('ContentCategories');
        $contentCategories = $this->ContentCategories->find()->all();
        $this->out('Creating course content categories.');
        $courseContentCategories = [];
        foreach ($contentCategories as $category) {
            $this->out('Content category '.$category->name);
            $query = $this->ContentCategories
                            ->CourseContentCategories
                            ->Courses
                            ->find();

            if(isset($category->meta['subject_area'])){
                $learningAreas = $learningAreasArray[trim($category->meta['subject_area'])];
                $query = $query->contain(['LearningAreas'])
                               ->matching('LearningAreas', function($q) use($learningAreas){
                                    return $q->where(['LearningAreas.name IN' => $learningAreas]);
                               });
            }
            $courses = $query->toArray();

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
        $query = "Select * from ".$category['table_name'];
        if($category['old_type']){
            $query = $query." where type = '".$category['old_type']."'";
        }
        $results = $this->connectWithMigrationDB($query);
        $contentValues = [];

        foreach ($results as $row) {
            $this->out('Finding content category on new db.');
            $categoryName = $category['name'];
            if(isset($row['subject_area'])){
                $row['subject_area'] = trim($row['subject_area']);
                $categoryName = $category['name']." | ".$row['subject_area'];
            }
            if(!isset($contentCategories[$categoryName])){
                $this->out('Content Category not found. Creating new');
                $contentCategory = [
                    'name' => $categoryName,
                    'type' => $category['type'],
                    'meta' => $category['meta']
                ];
                if(isset($row['subject_area'])){
                    $contentCategory['meta']['subject_area'] = $row['subject_area'];
                }
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
                'text' => $row[$category['text_field']],
                'description' => $category['description_field'] ? $row[$category['description_field']] : null
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
        $addCount = 0;

        while($childrenAvailable){
            
            if(empty($parents)){
                $query = "Select * from ".$category['table_name']." where ".$category['parent_field']."= 0";
                
                if($category['old_type']){
                    $query = $query." and type = '".$category['old_type']."'";
                }
                $results = $this->connectWithMigrationDB($query);
            }else{
                $query = "Select * from ".$category['table_name']." where ".$category['parent_field']." IN (".implode(',', array_keys($parents)).")";
                
                if($category['old_type']){
                    $query = $query." and type = '".$category['old_type']."'";
                }
                $results = $this->connectWithMigrationDB($query);
            }

            $futureParents = [];

            foreach ($results as $row) {
                $categoryName = $category['name'];
                if(isset($row['subject_area'])){
                    $row['subject_area'] = trim($row['subject_area']);
                    $categoryName = $category['name']." | ".$row['subject_area'];
                }
                if(!isset($contentCategories[$categoryName])){
                    $this->out('Content Category not found. Creating new');
                    $contentCategory = [
                        'name' => $categoryName,
                        'type' => $category['type'],
                        'meta' => $category['meta']
                    ];
                    if(isset($row['subject_area'])){
                        $contentCategory['meta']['subject_area'] = $row['subject_area'];
                    }
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
                    'parent_id' => $row[$category['parent_field']] == 0 ? null : $parents[$row[$category['parent_field']]],
                    'description' => $category['description_field'] ? $row[$category['description_field']] : null
                ];

                $this->out('trying to save content value.');
                $contentValue = $this->ContentValues->newEntity($contentValue);
                if(!$this->ContentValues->save($contentValue)){
                    $this->out('Content value could not be created. Ignoring value.');
                    print_r($contentValue);
                    continue;
                }
                $addCount++;
                $futureParents[$row[$category['id_field']]] = $contentValue->id;
            }

            if(empty($futureParents)){
                $childrenAvailable = false;
            }

            $parents = $futureParents;
        }

        if(!$addCount){
            $this->out('No Content value available to be saved for '.$category['type']);
        }else{
            $this->out('Content values have been saved for '.$category['type']);
        }
        return $contentCategories;
    }

/*    public function createCourseStrands(){
        $this->out('Inside create Course strands');
        $query = "SELECT * FROM edubvz_1111_data.lbf_standards_course sc inner join lb_course c on sc.lb_course_id = c.lb_course_id where lbf_standards_id in (Select lbf_standards_id from lbf_standards where hierarchy_level = 1);";
        $courseList = [];
        $courseStrands = [];
        $results = $this->connectWithMigrationDB($query);
        $this->loadModel('BlackBaudHash');
        $this->loadModel('OldLbHash');
        $this->loadModel('CourseStrands');

        foreach($results as $key => $row){
print_r($row); die;        
   $this->out('Finding course '.$row['course_name'].' on new db.');
            $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['old_id LIKE' => '%'.$row['short_name'].'%', 'new_table_name' => 'courses'])->all();
            if(empty($courseBlackBaudHash)){
                $this->out('Course not found on new db. Ignoring value.');
                continue;
            }
            $this->out('Finding strand on new db.');
            $strandOldLbHash = $this->OldLbHash->find()->where(['old_id' => $row['lbf_standards_id'], 'new_table_name' => 'strands'])->first();
            if(!$strandOldLbHash){
                $this->out('Strand not found on new db. Ignoring Value');
                continue;
            }
            $processedCourses = [];
            foreach ($courseBlackBaudHash as $courseHash) {
                if(isset($processedCourses[$courseHash->new_id])){
                    $this->out('Course already processed for this strand.');
                    continue;
                }

                if(!isset($courseList[$courseHash->new_id])){
                    $course = $this->CourseStrands->Courses->findById($courseHash->new_id)->first();
                    $courseList[$course->id] = $course;
                }

                $courseStrands['c'.$courseHash->new_id.'s'.$strandOldLbHash->new_id.'g'.$courseList[$courseHash->new_id]->grade_id] = [
                    'course_id' => $courseHash->new_id,
                    'strand_id' => $strandOldLbHash->new_id,
                    'grade_id' => $courseList[$courseHash->new_id]->grade_id
                ]; 
            }
        }

        $courseStrands = $this->CourseStrands->newEntities($courseStrands);
        if(!$this->CourseStrands->saveMany($courseStrands)){
            $this->out('Course strands could not be saved.');
            print_r($courseStrands);
            return;
        }
        $this->out('Course strands have been saved.');
    }*/


	public function createCourseStrands(){
        $this->out('Inside create Course strands');
        $query = "SELECT * FROM edubvz_1111_data.lbf_standards_course sc inner join lb_course c on sc.lb_course_id = c.lb_course_id where lbf_standards_id in (Select lbf_standards_id from lbf_standards where hierarchy_level = 1);";
        $courseList = [];
        $courseStrands = [];
        $results = $this->connectWithMigrationDB($query);
        $this->loadModel('BlackBaudHash');
        $this->loadModel('OldLbHash');
        $this->loadModel('CourseStrands');

        foreach($results as $key => $row){
            $this->out('Finding course '.$row['course_name'].' on new db.');
            $courseBlackBaudHash = $this->CourseStrands->Courses->find()->where(['name LIKE' => '%'.$row['course_name'].'%'])->all();
            if(empty($courseBlackBaudHash)){
                $this->out('Course not found on new db. Ignoring value.');
                continue;
            }
            $this->out('Finding strand on new db.');
            $strandOldLbHash = $this->OldLbHash->find()->where(['old_id' => $row['lbf_standards_id'], 'new_table_name' => 'strands'])->first();
            if(!$strandOldLbHash){
                $this->out('Strand not found on new db. Ignoring Value');
                continue;
            }
            $processedCourses = [];
            foreach ($courseBlackBaudHash as $courseHash) {
                if(isset($processedCourses[$courseHash->id])){
                    $this->out('Course already processed for this strand.');
                    continue;
                }

                if(!isset($courseList[$courseHash->id])){
                    $course = $courseHash;
                    $courseList[$course->id] = $course;
                }

                $courseStrands['c'.$courseHash->id.'s'.$strandOldLbHash->new_id.'g'.$courseList[$courseHash->id]->grade_id] = [
                    'course_id' => $courseHash->id,
                    'strand_id' => $strandOldLbHash->new_id,
                    'grade_id' => $courseList[$courseHash->id]->grade_id
                ]; 
            }
        }

        $courseStrands = $this->CourseStrands->newEntities($courseStrands);
        if(!$this->CourseStrands->saveMany($courseStrands)){
            $this->out('Course strands could not be saved.');
            print_r($courseStrands);
            return;
        }
        $this->out('Course strands have been saved.');
    }

    // function for creating standard grades
    public function createStandardGrades(){
        $this->out('Inside create Standard Grades.');
        $query = "SELECT * FROM edubvz_1111_data.lbf_standards_course sc inner join lb_course c on sc.lb_course_id = c.lb_course_id where lbf_standards_id in (Select lbf_standards_id from lbf_standards where hierarchy_level != 1);";
        $courseList = [];
        $standardGrades = [];
        $results = $this->connectWithMigrationDB($query);
        $this->loadModel('BlackBaudHash');
        $this->loadModel('StandardGrades');
        $this->loadModel('Courses');
        $this->loadModel('OldLbHash');

        foreach($results as $key => $row){
            $this->out('Finding course '.$row['course_name'].' on new db.');
            $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['old_id LIKE' => '%'.$row['short_name'].'%', 'new_table_name' => 'courses'])->all();
            if(empty($courseBlackBaudHash)){
                $this->out('Course not found on new db. Ignoring value.');
                continue;
            }

            $this->out('Finding standard on new db.');
            $standardOldLbHash = $this->OldLbHash->find()->where(['old_id' => $row['lbf_standards_id'], 'new_table_name' => 'standards'])->first();
            if(!$standardOldLbHash){
                $this->out('Standard not found on new db. Ignoring Value');
                continue;
            }
            $processedCourses = [];
            foreach ($courseBlackBaudHash as $courseHash) {
                if(isset($processedCourses[$courseHash->new_id])){
                    $this->out('Course already processed for this standard.');
                    continue;
                }

                if(!isset($courseList[$courseHash->new_id])){
                    $course = $this->Courses->findById($courseHash->new_id)->first();
                    $courseList[$course->id] = $course;
                }

                $standardGrades['s'.$standardOldLbHash->new_id.'g'.$courseList[$courseHash->new_id]->grade_id] = [
                    'standard_id' => $standardOldLbHash->new_id,
                    'grade_id' => $courseList[$courseHash->new_id]->grade_id
                ];

            }
        }

        $standardGrades = $this->StandardGrades->newEntities($standardGrades);
        if(!$this->StandardGrades->saveMany($standardGrades)){
            $this->out('StandardGrades could not be saved.');
            print_r($standardGrades);
            return;
        }
        $this->out('StandardGrades have been saved.');
    }

}
