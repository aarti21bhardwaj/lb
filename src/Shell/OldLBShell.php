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
use Cake\Http\Client;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\FrozenTime;

/**
 * OldLB shell command.
 */
class OldLBShell extends Shell
{
    Private $_syncReport = [];
    Private $_grades = ["PK" => 1, "K" => 2, "1" => 3, "2" => 4, "3" => 5, "4" => 6, "5" => 7, "6" => 8, "7" => 9, "8" => 10, "9" => 11, "10" => 12, "11" => 13, "12" => 14];

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
   

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out('In Main');
        $this->uploadCurriculums();
        $this->uploadLearningAreas();
        $this->uploadStrands();
        $this->uploadStandards();
        $this->out($this->_syncReport);
        $this->out('Shell Process Completed.');
    }
    // Method to upload Curriculums.
    public function uploadCurriculums(){
        $this->out("In update curriculums");
        $this->loadModel('Curriculums');
        $this->loadModel('OldLbHash');
        $data = $this->getCsvData('Curriculums', 'LB_Standards');
        
        if(!$data){
            return false;
        }
        $hashEntry = [];// To prepare data for hashEntry.
        $hashCount = 0; // To count number of hash entries made.
        $addCount = 0; // To count number of entities saved in Curriculums Table.
        
        foreach ($data as $row) {
            
            $this->out('Processing Curriculum - '.$row['lbf_curriculum_name']);
            $reqData = [
                
                'name' => $row['lbf_curriculum_name'],
                'description' =>$row['lbf_curriculum_name']
                
            ];    
            //To check if the current Curriculum exits in the database.
            $curriculum = $this->Curriculums->findByName($row['lbf_curriculum_name'])->first();
            if(!$curriculum){
                $this->out('Creating new Curriculum '.$row['lbf_curriculum_name']);
                $curriculum =  $this->Curriculums->newEntity();
                $addCount++;
                $curriculum= $this->Curriculums->patchEntity($curriculum, $reqData);    
            
                if(!$this->Curriculums->save($curriculum)){
                    $this->out('Curriculums could not be saved.');
                    continue;
                }
                $this->out('Curriculum has been added.');
            }
            
            $this->out('Processing Hash entries.');

            $oldLbHash=$this->OldLbHash->findByOldId($row['lbf_curriculum_id'].'-'.$row['lbf_curriculum_name'])
                                       ->where(['new_table_name' => 'curriculums'])
                                       ->first();
            
            if(!$oldLbHash){
                $hashEntry = [
                'old_table_name' => 'lb_standards',
                'old_id' => $row['lbf_curriculum_id'].'-'.$row['lbf_curriculum_name'],
                'new_id' => $curriculum->id,
                'new_table_name' => 'curriculums'
                ];
                $newEntity=$this->OldLbHash->newEntity();
                $hashEntry=$this->OldLbHash->patchEntity($newEntity,$hashEntry);
                
                if(!$this->OldLbHash->save($hashEntry)){
                    $this->out("Hash Entry  not saved in database");
                }
                $this->out("Hash Entry saved in database");
                $hashCount++;
            }
        }
        $message = $addCount." Curriculums have been added to the system and ".$hashCount." hash entries have been added";
        $this->out($message); 
        $this->_syncReport[] = $message;
    }
    // Method to upload Learning Areas for respective Curriculums.
    public function uploadLearningAreas(){

        $this->out("In uploadLearningAreas");
        $this->loadModel('LearningAreas');
        $this->loadModel('OldLbHash');
        $data = $this->getCsvData('LearningAreas', 'LB_Standards');
        
        if(!$data){
            return false;
        }
        $hashCount = 0;
        $addCount = 0;
        
        foreach ($data as $row) {

            $this->out("Processing Learning Area".$row['lbf_learning_area_name']);
            $curriculumOldLbHash=$this->OldLbHash->findByOldId($row['lbf_curriculum_id'].'-'.$row['lbf_curriculum_name'])->where(['new_table_name'=>'curriculums'])->first();

            if(!$curriculumOldLbHash){
                $this->out('Curriculum Not found ignoring learning area.');
                continue;
            }

            $this->out('Curriculum found');

            $learningAreaData = [
                    'curriculum_id'=>$curriculumOldLbHash->new_id,
                    'name'=>$row['lbf_learning_area_name'],
                    'description'=>$row['lbf_learning_area_name']
            ];

            $learningArea=$this->LearningAreas->findByName($row['lbf_learning_area_name'])->where(['curriculum_id' => $curriculumOldLbHash->new_id])->first();

            if(!$learningArea){
                $this->out('Learning does not exist. New Entry will be made.');
                $learningArea=$this->LearningAreas->newEntity();
                $learningArea=$this->LearningAreas->patchEntity($learningArea,$learningAreaData);
                
                if(!$this->LearningAreas->save($learningArea)){
                    $this->out('Learning Area: '.$row['lbf_learning_area_name'].' could not be saved');
                    continue;
                }
                $addCount++;
                $this->out("Learning Area has been saved.");
            }else{
                $this->out('Learning area alreaddy exists.');
            }

            $this->out('Checking hash entry');
            $oldLbHash=$this->OldLbHash->findByOldId($row['lbf_learning_area_id'].'-'.$row['lbf_learning_area_name'])->where(['new_table_name'=>'learning_areas'])->first();

            if(!$oldLbHash){
                $this->out('Hash entry not found. Creating new');
                $hashEntry=[
                    'old_id'=>$row['lbf_learning_area_id'].'-'.$row['lbf_learning_area_name'],
                    'new_id'=>$learningArea->id,
                    'old_table_name'=>'lb_standards',
                    'new_table_name'=>'learning_areas'
                ];
                $newEntity=$this->OldLbHash->newEntity();
                $hashEntry=$this->OldLbHash->patchEntity($newEntity,$hashEntry);
                
                if(!$this->OldLbHash->save($hashEntry)){
                    $this->out("Hash Entry  not saved in database");
                }
                $hashCount++;
            }else{
                $this->out('Hash entry already present.');
            }
            
        }
        $message = $addCount." learning areas were added to the system and ".$hashCount." has entries added.";
        $this->_syncReport[] = $message;
        $this->out($message);
    }


    // Method to upload Strands for respective Learning Areas.
    public function uploadStrands(){
        $this->out("In uploadStrands");
        $this->loadModel('Strands');
        $this->loadModel('OldLbHash');
        $data = $this->getCsvData('Strands', 'LB_Standards');
        
        $strand=[];
        $addCount = 0;
        
        $parentIds = array_unique((new Collection($data))->extract('parent_id')->toArray());
        $childrenIds = array_unique((new Collection($data))->extract('child_id')->toArray());
        $strandIds = array_diff($parentIds, $childrenIds);
        
        $data = (new Collection($data))->indexBy('parent_id')->toArray();
        
        $learningAreaIds = $this->OldLbHash->find()->where(['new_table_name'=>'learning_areas'])->combine('old_id','new_id')->toArray();

        foreach ($data as $row) {
            // To check the existense of the current Strand in the Strands Table.
            if(!in_array($row['parent_id'], $strandIds)){
                $this->out("Not a strand. Ignoring Value.");
                continue;
            }

            $this->out("Processing strand - ".$row['parent_standard']);
            $strandOldLbHash = $this->OldLbHash->findByOldId($row['parent_id'])->where(['new_table_name'=>'strands'])->first();
            
            if($strandOldLbHash){
                $this->out("Strand Already Exists. Ignoring Value.");
                continue;
            }
            
            $learningAreaOldId = $row['lbf_learning_area_id'];
            $learningAreaName = $row['lbf_learning_area_name'];

            if(!isset($learningAreaIds[$row['lbf_learning_area_id'].'-'.$row['lbf_learning_area_name']])){
                $this->out('Learning area not found');
                continue;
            }


            $learningAreaId = $learningAreaIds[$row['lbf_learning_area_id'].'-'.$row['lbf_learning_area_name']];

            $strandData = [
                'name'=>$row['parent_standard'],
                'description'=>$row['parent_standard'],
                'learning_area_id'=>$learningAreaId,
                'code'=> substr($row['parent_standard'], 0, 3)
            ];

            $strand=$this->Strands->newEntity();
            $strand=$this->Strands->patchEntity($strand,$strandData);
            
            if(!$this->Strands->save($strand)){
                $this->out('Strand could not be saved.');
                print_r($strand);
                continue;
            }

            $this->out('Strand has been saved.');
            $addCount++;
            $hashEntry = [
                'old_id'=>$row['parent_id'],
                'new_id'=>$strand->id,
                'old_table_name'=>'lb_standards',
                'new_table_name'=>'strands'
            ];

            $hashEntry=$this->OldLbHash->newEntity($hashEntry);            

            if(!$this->OldLbHash->save($hashEntry)){
                $this->out("Hash Entry could not be saved in database");
            }
            $this->out('Hash Entry has been added to the system.');
        }
        $message = $addCount." Strands were added to the system";
        $this->_syncReport[] = $message;
        $this->out($message);
    }
    // Method to upload Standards for respective Strands.
    public function uploadStandards(){
        $this->out("In uploadStrands");
        $this->loadModel('Standards');
        $this->loadModel('StandardGrades');
        $this->loadModel('OldLbHash');
        $this->loadModel('Strands');
        $data = $this->getCsvData('Strands', 'LB_Standards');
        if(!$data){
            return false;
        }
        $addCount = 0;
        $strandIds=$this->OldLbHash->find()->where(['new_table_name'=>'strands'])->combine('old_id', 'new_id')->toArray();
        $processedStandards = [];

        do{
            $orphanedStandards = [];
            $count=0;
            $totalCount = count($data);

            foreach($data as $row){
                $count++;
                $this->out('Count : '.$count." of ".$totalCount);
                $standardOldLbHash=$this->OldLbHash->findByOldId($row['child_id'])->where(['new_table_name'=>'standards'])->combine('old_id','new_id')->first();

                if($standardOldLbHash){
                    $this->out('Standard Already Exists, Ignoring value.');
                    continue;
                }

                if(isset($strandIds[$row['parent_id']])){
                    $this->out('1st level strand.');
                    $strandId=$strandIds[$row['parent_id']];
                    $parentId=null;
                }elseif(isset($processedStandards[$row['parent_id']])){
                    $this->out('Has a parent parent standard.');
                    $strandId = $processedStandards[$row['parent_id']]['strand_id'];
                    $parentId = $processedStandards[$row['parent_id']]['new_id'];
                }else{
                    $this->out('Is orphaned');
                    $orphanedStandards[] = $row;
                    continue;
                }

                $standardText = mb_convert_encoding($row['child_standard'], 'utf-8');
                $standardData=[
                    'name'=>  $standardText,
                    'description'=> $standardText,
                    'parent_id'=>$parentId,
                    'strand_id'=>$strandId,
                    'code'=> $row['child_id']
                ];
                
                $grades =explode(',', $row['course_grade']);
                $grades = array_map('trim', $grades);
                if(!empty($grades)){
                    $standardData['standard_grades'] = [];
                    foreach ($grades as $grade) {
                        $standardData['standard_grades'][] = [
                            'grade_id'=>$this->_grades[$grade]
                        ];
                        
                    }
                }
                $standard=$this->Standards->newEntity();
                $standard=$this->Standards->patchEntity($standard,$standardData);
                
                if(!$this->Standards->save($standard)){
                    $this->out('Standard could not be saved');
                    continue;
                }

                $processedStandards[$row['child_id']] = [
                    'strand_id' => $standard->strand_id,
                    'new_id' => $standard->id,
                ];

                $this->out('Standard has been saved.');

                $addCount++;
                $this->out('Standard '.$standard['name'].'has been be saved');
                
                $hashEntry=[
                    'old_id'=>$row['child_id'],
                    'new_id'=> $standard->id,
                    'new_table_name'=>'standards',
                    'old_table_name'=>'lb_standards'
                ];
                $hashEntry=$this->OldLbHash->newEntity($hashEntry);
                    
                if(!$this->OldLbHash->save($hashEntry)){
                    $this->out("Hash Entry could not saved in database");
                }
                $this->out("Hash Entry has been saved in database");
            }

            $data = $orphanedStandards;

           pr('Orphaned Standards'); pr(count($orphanedStandards));
        }while(!empty($orphanedStandards));
        
        $message = $addCount." Standards were added to the system";
        $this->_syncReport[] = $message;
        $this->out($message);
    
    }
   
    //Method to read CSV file data and returning it as array of arrays.
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
  
}
