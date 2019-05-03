<?php
namespace App\Shell;

use Cake\Console\Shell;

use Cake\Collection\Collection;
use Cake\Utility\Text;
use Cake\Log\Log;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Faker\Factory as Faker;
use Cake\Network\Exception\BadRequestException;
use Cake\Datasource\ConnectionManager;

/**
 * GreenFieldSchoolSync shell command.
 */
class GreenFieldSchoolSyncShell extends Shell
{
    Private $_school = false;
    Private $_schoolId = false;
    Private $_syncReport=[];
    Private $_grades = ["JK" => 1, "SK" => 2, "1" => 3, "2" => 4, "3" => 5, "4" => 6, "5" => 7,"6" => 8, "7" => 9, "8" => 10, "9" => 11, "10" => 12, "11" => 13, "12" => 14];
    
    public function setGrades()
    {
        $this->out('In setGrades');
        $this->loadModel('Grades');

        $addCount=0;
        $updateCount=0;
        $newGrade=false;
        
        foreach($this->_grades as $grade=>$id){
            $tableValue=$this->Grades->findById($id)->first();
            if($tableValue['name']!=$grade){
                $tableValue['name']=$grade;
                $addCount++;
                $newGrade=true;
            }else{
                $updateCount++;
                $newGrade=false;
            }
            if($this->Grades->save($tableValue)){
                if($newGrade){
                    $this->out('Grade '.$grade.' has been saved');
                }else{
                    $this->out('Grade '.$grade.' has been updated');
                }
            }else{
                $this->out('Could not save Grade '.$grade);
            }
            
        }
        $this->out('Grades Added: '.$addCount.', Grades Updated: '.$updateCount);
    }
    
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main($schoolId)
    {
        $startTime = FrozenTime::now(); 
        $this->out('In Power school sync data shell.');
        $this->Schools = $this->loadModel('Schools');
        $this->GreenFieldSchoolHash = $this->loadModel('GreenFieldSchoolHash');
        if(in_array($schoolId, [null, false, ''])){
            $this->out('School id is required');
            return false;
        }

        $this->_schoolId = $schoolId;   

        $this->_getSchool();
        $this->syncCampuses();
        $this->syncAcademicYears();
        $this->syncDivisions();
        $this->syncCourses(); 

       
        //$this->syncSections();
        //$this->syncUsers('teacher');
        //$this->syncUsers('student');

        $endTime = FrozenTime::now();
        $totalTime = ($endTime->toUnixString() - $startTime->toUnixString())/60;
        $this->out('Sync has been completed. Total Time Taken = '.number_format((float)$totalTime, 2, '.', '')." minutes");
        print_r($this->_syncReport);

    }

    public function syncCampuses(){
        $this->out("In sync Campuses");
        $this->loadModel('Campuses');

        $data = $this->getCsvData('Campuses', 'SDG/Schools_Campuses');
        if(!$data){
            return false;
        }
        
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;

        foreach ($data as $row) {
            
            $greenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['school_id'])->where(['new_table_name' =>'campuses'])->first();
            $reqData = [
                    'school_id'=>$this->_schoolId,
                    'name' => $row['school_name']
                    
                ];
            if($greenFieldSchoolHash){
                $this->out('Campus already exists on new db. Updating Value.');

                $campus = $this->Campuses->find()->where(['id' => $greenFieldSchoolHash->new_id])->first();
               
               
               
                    $campus= $this->Campuses->patchEntity($campus, $reqData);    
                    $updateCount++;
               
                
            }else{
                
                
                 $campus =  $this->Campuses->newEntity();
            }
            $campus=$this->Campuses->patchEntity($campus, $reqData); 
            if(!$this->Campuses->save($campus)){
                $this->out('Campuses could not be saved.');
                continue;
                }else{
                    $this->out('New Campus '.$campus->name.'has been saved successfully');
                }
// $newID = $this->Campuses->findByName($row['school_name'])->->extract('id')->toArray();
            if(!$greenFieldSchoolHash){
                
                    $hashEntries[] = [
                        'old_table_name' => 'Schools_Campuses',
                        'old_id' => $row['school_id'],
                        'new_id' => $campus->id,
                        'new_table_name' => 'campuses'
                    ];
                
            }
        }
        $this->_saveHashEntries($hashEntries);

        $message = $addCount." Campuses were added to the system and ".$updateCount." have been updated.";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function syncAcademicYears(){
        
        $this->out("In sync Academic years");
        $this->loadModel('AcademicYears');
        $data = $this->getCsvData('AcademicYears', 'SDG/Academic Years');
        if(!$data){
            return false;
        }
        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];
        foreach ($data as $row) {
            $reqData = [
                'name' => $row['name'],
                'start_date' => new FrozenDate($row['start_date']),
                'end_date' =>  new FrozenDate($row['end_date']),
                'school_id' => $this->_schoolId
            ];
            $greenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['name'])->where(['new_table_name' => 'academic_years'])->first();
            if($greenFieldSchoolHash){
                $this->out('Academic Year already exists on new db. Updating Value.');
                $academicYear = $this->AcademicYears->findById($greenFieldSchoolHash->new_id)->first();
                $updateCount++;
            }else{
                $this->out('Academic does not exists, new Academic will be created.');
                $academicYear =  $this->AcademicYears->newEntity();
                $addCount++;
            }
            $academicYear = $this->Schools->AcademicYears->patchEntity($academicYear, $reqData);
            if(!$this->AcademicYears->save($academicYear)){
                $this->out('Academic Year could not be saved.');
                $this->out($academicYear);
                continue;
            }
            $this->out('Acedemic Year has been saved.');
            if(!$greenFieldSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => 'Academic Years',
                    'old_id' => $row['name'],
                    'new_id' => $academicYear->id,
                    'new_table_name' => 'academic_years'
                ];
            }
            $this->_saveHashEntries($hashEntries);
        }  
        $message = $addCount." academic years have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function syncDivisions(){
        $this->out('Inside sync Divisions');
        $this->loadModel('Divisions');
        $this->loadModel('Campuses');
        $this->loadModel('DivisionGrades');

        $data = $this->getCsvData('Divisions', 'SDG/Divisions');
        if(!$data){
            return false;
        }
        $campuses = $this->Campuses->find()->where(['school_id' => $this->_schoolId])->combine('name', 'id')->toArray(); 
     
        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];
        $divisionGrades= [];
        $elementary = [1,2,3,4,5,6,7];
        $middleSchool = [8,9,10];
        $highSchool = [11,12,13,14];    
        foreach ($data as $row) {
         
            $reqData = [
                'name' => $row['division'],
                'school_id' => $this->_schoolId,
                'campus_id' => $campuses[$row['school_name']],
              
            ];
          
            $this->out('finding Division '.$row['division'].' on new db.');
            $greenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['school_id'].$row['division'])
                                                      ->where(['new_table_name' => 'divisions'])
                                                      ->first();
           
            if($greenFieldSchoolHash){
                $this->out('Division already exists, new Division will not be created.');
                $division = $this->Divisions->findById($greenFieldSchoolHash->new_id)->first();
                $updateCount++;
           
            }else{
                $this->out('Division does not exists, new Division will be created.');
                $reqData['template_id'] = 1;
                $division = $this->Divisions->newEntity();
                $addCount++;
            }

            $division = $this->Divisions->patchEntity($division, $reqData);
            if(!$this->Divisions->save($division)){
                $this->out('Division could not be saved.');
                print_r($division);
                continue;
            }

            $this->out('Division has been saved. Creating data for Green Field School hash entry.');
            
            if(!$greenFieldSchoolHash){
                if($division->name=='Elementary'){
                    $grades=$elementary;
                }elseif ($division->name=='Middle School') {
                    $grades=$middleSchool;
                }elseif ($division->name='High School') {
                    $grades=$highSchool;
                }
                for($i=0;$i<count($grades);$i++){
                    $divisionGrades[]=[
                        'division_id'=>$division->id,
                        'grade_id'=>$grades[$i]
                    ];
                    }
                    $divisionGrades=$this->DivisionGrades->newEntities($divisionGrades);
                    if(!$this->DivisionGrades->saveMany($divisionGrades)){
                    $this->out("Grades not saved.");
                }
                
                $hashEntries[] = [
                    'old_table_name' => 'Divisions',
                    'old_id' => $row['school_id'].$row['division'],
                    'new_id' => $division->id,
                    'new_table_name' => 'divisions'
                ];
            }
        }

        $this->_saveHashEntries($hashEntries);
        $message = $addCount." divisions were added to the system and ".$updateCount." have been updated.";
        $this->_syncReport[] = $message;
        $this->out($message);
    }


    public function syncCourses(){
        $this->out("In sync courses");
        $this->loadModel('Courses');
        $this->loadModel('Grades');

        $this->loadModel('GreenFieldSchoolHash');
        $this->loadModel('Campuses');
        $this->loadModel('Divisions');
        $this->loadModel('DivisionGrades');
        $data = $this->getCsvData('Courses', 'SDG/Courses');
        
        $gradeIds = $this->Campuses->find()
                                 ->contain(['Divisions.DivisionGrades'])
                                 ->map(function($value, $key){
                                    $gradeIds = (new Collection($value->divisions))->extract('division_grades.{*}.grade_id')->toArray();
                                    return ['name' => $value->name, 'grade_ids' => $gradeIds];
                                 })
                                 ->combine('name', 'grade_ids')
                                 ->toArray();
        

        if(!$data){
            return false;
        }

        $this->out('Getting Courses');


        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];
        
        foreach ($data as $row) {
                $this->out('Found course '.$row['course_name']);                
                
                foreach($gradeIds[$row['school_name']] as $gradeId){

                    $this->out('Creating Course for Grade:'.$gradeId);
                    $greenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['lb_course_id'].$gradeId)->where(['new_table_name' => 'courses'])->first();
                    
                    $data = [
                        'name' => $row['course_name'],
                        'description' => $row['course_name'],
                        'grade_id' => $gradeId
                    ];
                    
                    if($greenFieldSchoolHash)
                        {
                        $this->out('Course already exists on new db. Updating Value.');
                        $course = $this->Courses->find()->where(['id ' => $greenFieldSchoolHash->new_id])->first();
                        $updateCount++;
                    
                        }
                    else{

                        $data['learning_area_id'] = 1;
                        $course =  $this->Courses->newEntity();
                        $addCount++;
                        }


                    $course = $this->Courses->patchEntity($course, $data);    

                    if(!$this->Courses->save($course)){
                        $this->out('Course could not be saved.');
                        print_r($course);
                        continue;
                    }


                    $this->out('Course has been saved.');
                    
                    if(!$greenFieldSchoolHash){
                      $hashEntries[] = [
                          'old_table_name' => 'Courses',
                          'old_id' => $row['lb_course_id'].$gradeId,
                          'new_id' => $course->id,
                          'new_table_name' => 'courses'
                      ];
                    }
                    }
            }  


        $this->_saveHashEntries($hashEntries);
        $message = $addCount." courses have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
        }


    public function syncUsers($role){
        $this->out('Inside sync Users for role '.$role);
        $this->loadModel('Users');
        $this->loadModel('CampusTeachers');
        $roles = [
          'teacher' => [
            'old_table' => 'teacher',
            'role_id' => 3,
            'first_name' => 'individual_firstname',
            'middle_name' => false,
            'last_name' => 'individual_lastname',
            'email' => 'function_email',
            'dob' => false,
            'legacy_id' => 'individual_personid',
            'gender'=> 'individual_gender',
            'is_active' => false

          ],
          'student' => [
            'old_table' => 'Students',
            'role_id' => 4,
            'first_name' => 'student_first_name',
            'middle_name' => 'student_middle_name',
            'last_name' => 'student_last_name',
            'email' => 'student_email',
            'dob' => 'student_dob',
            'legacy_id' => 'student_id',
            'gender'=> 'student_gender',
            'is_active' =>  'student_spec_ed_status'
          ]
        ]; 

        $roleData = $roles[$role];
        $ignoredValues=[];
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $campusTeacherData =[];

        $data = $this->getCsvData('Users', 'SDG/'.$roleData['old_table']);
        if(!$data){
          return false;
        }
        $this->out('Got users. Data will be formed and users will be saved.');
        
        foreach ($data as $row) {
            $reqData = [
                'first_name' => $row[$roleData['first_name']],
                'last_name' => $row[$roleData['last_name']],
                'school_id' => $this->_schoolId,
                'email' => preg_replace('/\s+/', '', $row[$roleData['email']]),
                'legacy_id' => $row[$roleData['legacy_id']],
                'role_id' => $roleData['role_id'],
                'gender' => $row[$roleData['gender']]
            ];
            $_email=explode("@", $reqData['email'],2);
            if(!$_email[0]){
                $this->out('Email is invalid. creating dummy email');
                $reqData['email']=$role.".".$row[$roleData['legacy_id']].$reqData['email'];
                $this->out($reqData['email']);
            }

            if($roleData['dob']){
                $reqData['dob'] = $row[$roleData['dob']];
            }
            if($roleData['middle_name']){
                $reqData['middle_name'] = $row[$roleData['middle_name']];
            }
            if($roleData['is_active']){
                if($row[$roleData['is_active']]==='y'||$row[$roleData['is_active']]==='Y'){
                  $reqData['is_active'] = true;
                }
                else{
                  $reqData['is_active'] = false;
                }
            }

            $this->out('Data has been formed. Checking if user already exists');
            $greenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row[$roleData['legacy_id']])->where(['new_table_name' => 'users', 'old_table_name' => $roleData['old_table']])->first();
       
            if($greenFieldSchoolHash){
                $this->out('User already exists on new db. Value will be updated.');
                $user = $this->Users->findById($greenFieldSchoolHash->new_id)->first();
                $updateCount++;
            }else{
                $duplicateUser = $this->Users->find()
                                             ->where(['first_name'=> $reqData['first_name'],
                                                      'last_name'=> $reqData['last_name'],
                                                      'email'=> $reqData['email'] ])
                                             ->first();
                if($duplicateUser){
                    $user = $this->Users->findById($duplicateUser->id)->first();
                }else{
                    $duplicateEmail = $this->Users->find()->where(['email'=> $reqData['email']])->first();
                    if($duplicateEmail ){
                        $this->out('Email is duplicate. creating dummy email');
                        $this->out("old email:".$reqData['email']);
                        $reqData['email']=$role.".".$row[$roleData['legacy_id']]."@greenfield.k12.wi.us";
                        $this->out("new email:".$reqData['email']);
                    }
                    $this->out('Its a new user. User will be added to the system.');
                    $user = $this->Users->newEntity();
                    $reqData['password']='123456789';
                    $addCount++;
                }
            }

            $user = $this->Users->patchEntity($user, $reqData);
            if(!$this->Users->save($user)){
              $this->out('User could not be saved.');
              print_r($user);
              continue;
            }

            $this->out('User has been saved.');
            if(!$greenFieldSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => $roleData['old_table'],
                    'old_id' => $row[$roleData['legacy_id']],
                    'new_id' => $user->id,
                    'new_table_name' => 'users'
                ];
            }
            $this->_saveHashEntries($hashEntries);

            if($role==='teacher'){
                $campus = $this->GreenFieldSchoolHash->findByOldId($row['schoolemployment_schoolnumber'])->where(['new_table_name' =>'campuses'])->first();
                if($campus){
                    $campusTeacherData = [
                        'campus_id' => $campus->new_id,
                        'teacher_id' =>  $user->id
                    ];
                    $duplicate = $this->CampusTeachers->find()->where([$campusTeacherData])->first();
                    if($duplicate){
                        $this->out('duplicate CampusTeachers Entry. Ignoring value.');
                    }
                    else{
                        $campusTeacher = $this->CampusTeachers->newEntity();
                        $campusTeacher = $this->CampusTeachers->patchEntity($campusTeacher, $campusTeacherData);
                        if(!$this->CampusTeachers->save($campusTeacher)){
                            $this->out('campusTeacher could not be saved.');
                            print_r($campusTeacher);
                            continue;
                        }
                        $this->out('campusTeacher has been be saved.');
                    }
                }else{
                    $this->out('campus is not found or invalid .');
                    $ignoredValues[] = [
                        'old_table_name' => $roleData['old_table'],
                        'old_id' => $row[$roleData['legacy_id']],
                        'new_id' => $user->id
                    ];
                }
            }
        }
        $message = $addCount." users have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
        print_r($ignoredValues);
    }
    
    

    public function syncSections(){
        $this->out("In sync Sections");
        $this->loadModel('Sections');

        $data = $this->getCsvData('Sections', 'SDG/Sections');
        if(!$data){
            return false;
        }
       
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;

        $courseIds=$this->GreenFieldSchoolHash->find()->where(['new_table_name'=>'courses'])->combine('old_id','new_id')->toArray();
        $teacherIds=$this->GreenFieldSchoolHash->find()->where(['new_table_name'=>'users','old_table_name'=>'teacher'])->combine('old_id','new_id')->toArray();
       
        foreach ($data as $row) {
           
            $greenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['sectioninfo_sectionid'])->where(['new_table_name' =>'sections'])->first();
            $reqData = [
                'name'=>$row['sectioninfo_sectionnumber'],
                'course_id'=>$row['courseinfo_coursenumber'],
                'teacher_id' => $row['sectioninfo_teacherpersonid'],
                'term_id'=>$row['term_termid']
            ];
            if($greenFieldSchoolHash){
                $this->out('Section already exists on new db. Updating Value.');
                $section = $this->Sections->find()->where(['id' => $greenFieldSchoolHash->new_id])->first();
                $updateCount++;
            }else{
                $section =  $this->Sections->newEntity();
                $addCount++;
            }
            $section=$this->Sections->patchEntity($section, $reqData);
            if(!$this->Sections->save($section)){
                $this->out('Section could not be saved.');
                continue;
            }

            $this->out('New Section '.$section->name.'has been saved successfully');
            if(!$greenFieldSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => 'Sections',
                    'old_id' => $row['sectioninfo_sectionid'],
                    'new_id' => $section->id,
                    'new_table_name' => 'Sections'
                ];
               
            }
            $this->_saveHashEntries($hashEntries);
        }
       
        $message = $addCount." Sections were added to the system and ".$updateCount." have been updated.";
        $this->_syncReport[] = $message;
        $this->out($message);
    }
    public function syncSectionStudents(){
        
        $this->out("In sync Section Students");
        $this->loadModel('SectionStudents');
        $data = $this->getCsvData('SectionStudents', 'SDG/Section Students');
        if(!$data){
            return false;
        }

        $addCount = 0;
        $updateCount = 0;
        
        foreach ($data as $row) {
            $sectionGreenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['sectionschedule.sectionid'])->where(['new_table_name' => 'sections'])->first();
            $studentGreenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['student.studentNumber'])->where(['new_table_name' => 'users','old_table_name'=>'Students'])->first();
            if(!$sectionGreenFieldSchoolHash){
                $this->out('Section not found on new db. Ignoring value.');
                continue;
            }elseif(!$studentGreenFieldSchoolHash){
                $this->out('Student not found on new db. Ignoring value.');
                continue;
            }else{
                $reqData = [
                  'section_id' => $sectionGreenFieldSchoolHash->new_id,
                  'student_id' => $studentGreenFieldSchoolHash->new_id
                ];

                $duplicate = $this->SectionStudents->find()->where([$reqData])->first();
                if($duplicate){
                    $this->out('duplicate SectionStudents Entry. Ignoring value.');
                    $updateCount++;
                }else{
                    $sectionStudent =  $this->SectionStudents->newEntity();
                    $sectionStudent = $this->SectionStudents->patchEntity($sectionStudent, $reqData);
                    if(!$this->SectionStudents->save($sectionStudent)){
                        $this->out('SectionStudent could not be saved.');
                        $this->out($sectionStudent);
                        continue;
                    }
                    $addCount++;
                    $this->out('SectionStudent has been saved.');
                }
            }
        }  
        $message = $addCount." SectionStudent have been added to the system and ".$updateCount." Duplicate";
        $this->_syncReport[] = $message;
        $this->out($message);
    } 

    public function syncSectionTeachers(){
        
        $this->out("In sync Section Teachers ");
        $this->loadModel('SectionTeachers');
        $data = $this->getCsvData('SectionTeachers', 'SDG/Section Teachers');
        if(!$data){
          return false;
        }
        $addCount = 0;
        $updateCount = 0;
        
        foreach ($data as $row) {
            $sectionGreenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['sectioninfo_sectionid'])->where(['new_table_name' => 'sections'])->first();
            $teacherGreenFieldSchoolHash = $this->GreenFieldSchoolHash->findByOldId($row['staffhistory_personid'])->where(['new_table_name' => 'users','old_table_name'=>'teacher'])->first();
            if(!$sectionGreenFieldSchoolHash){
                $this->out('Section not found on new db. Ignoring value.');
                continue;
            }elseif(!$teacherGreenFieldSchoolHash){
                $this->out('Teacher not found on new db. Ignoring value.');
                continue;
            }else{
                $reqData = [
                    'section_id' => $sectionGreenFieldSchoolHash->new_id,
                    'student_id' => $teacherGreenFieldSchoolHash->new_id
                ];

                $duplicate = $this->SectionTeachers->find()->where([$reqData])->first();
                if($duplicate){
                    $this->out('duplicate SectionTeacher Entry. Ignoring value.');
                    $updateCount++;
                }else{
                    $teacherStudent =  $this->SectionTeachers->newEntity();
                    $teacherStudent = $this->SectionTeachers->patchEntity($teacherStudent, $reqData);
                    if(!$this->SectionTeachers->save($teacherStudent)){
                      $this->out('Section Teacher could not be saved.');
                      $this->out($teacherStudent);
                      continue;
                    }
                    $addCount++;
                    $this->out('Section Teacher has been saved.');
                }
            }
        }  
        $message = $addCount." Section Teacher have been added to the system and ".$updateCount." Duplicate";
        $this->_syncReport[] = $message;
        $this->out($message);
    } 
     
    private function _getSchool(){
        $this->_school = $this->Schools->findById($this->_schoolId)->contain(['Campuses.Divisions', 'AcademicYears'])->first();
        if(!$this->_school){
            $this->out("School not found.");
            die;
        }
    }
   

    private function _saveHashEntries($hashEntries){
        
        if(empty($hashEntries)){
            $this->out('no hash entries to be saved');
            return false;
        }

        $hashEntries = $this->GreenFieldSchoolHash->newEntities($hashEntries);
        if(!$this->GreenFieldSchoolHash->saveMany($hashEntries)){
            $this->out("Hash entries could not be saved");
            $this->out($hashEntries);
            return false;
        }
        
        $this->out("Hash entries have been saved");
        // $divisions[
        //     'school_id' => $this->_schoolId,
        // ]
    }
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
