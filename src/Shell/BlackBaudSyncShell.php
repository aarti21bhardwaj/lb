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
use Faker\Factory as Faker;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;


/**
 * BlackBaudSync shell command.
 */
class BlackBaudSyncShell extends Shell
{
    public function connectWithMigrationDB($query){

        $conn = ConnectionManager::get('mssql');
        $response = $conn->execute($query);

        return $response;
    }

    Private $_school = false;
    Private $_schoolId = false;
    Private $_grades = ["PK" => 1, "K" => 2, "01" => 3, "02" => 4, "03" => 5, "04" => 6, "05" => 7, "06" => 8, "07" => 9, "08" => 10, "09" => 11, "10" => 12, "11" => 13, "12" => 14];
    Private $_divisionTemplates = ['ES' => 3, 'MS' => 1, 'HS' => 1];

    Private $_syncReport = [];


    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main($schoolId, $oldAcademicYearId)
    {  

      $startTime = FrozenTime::now(); 
      $this->out('In BlackBaud sync data shell.');
      $this->Schools = $this->loadModel('Schools');
      $this->BlackBaudHash = $this->loadModel('BlackBaudHash');
      if(in_array($schoolId, [null, false, ''])){
          $this->out('School id is required');
          return false;
      }

      $this->_schoolId = $schoolId;   

      $this->_getSchool();
      $this->syncCampuses();
      //$this->syncDivisions();
      //$this->syncDivisionGrades();
      $this->syncAcademicYears();
      $this->syncTerms();
      $this->syncUsers('teacher');
      $this->syncUsers('student');
      $this->syncCourses();
      $this->syncSections($oldAcademicYearId);
      $this->fixSections($oldAcademicYearId);
      $this->syncSectionStudents();
      $this->fixSectionStudents($schoolId);
      $this->syncGuardians();
      $this->deleteEmptySections();
      $endTime = FrozenTime::now();
      $totalTime = ($endTime->toUnixString() - $startTime->toUnixString())/60;
      $this->out('Sync has been completed. Total Time Taken = '.number_format((float)$totalTime, 2, '.', '')." minutes");
      print_r($this->_syncReport);
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

        $hashEntries = $this->BlackBaudHash->newEntities($hashEntries);
        if(!$this->BlackBaudHash->saveMany($hashEntries)){
            $this->out("Hash entries could not be saved");
            $this->out($hashEntries);
            return false;
        }
        $this->out("Hash entries have been saved");
    }

    public function syncCampuses(){
      $this->out('Inside sync Campuses');
      $this->loadModel('Campuses');

      $query = "Select * from dbo.vLB_Schools";
      $this->out('Getting Campuses');
      $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');
      $results = (new Collection($results))->extract('CITY')->toArray();
      $results = array_unique($results);

      $addCount = 0;
      
      foreach ($results as $row) {
        $data = [
          'name' => $row,
          'school_id' => $this->_schoolId,
        ];
        $this->out('finding campus '.$row.' on new db.');
        $campus = $this->Campuses->find()->where($data)->first();
        if($campus){
          $this->out('Campus already exists, new campus will not be created.');
          continue;
        }
        $this->out('Campus does not exists, new campus will be created.');
        $campus = $this->Campuses->newEntity($data);
        if(!$this->Campuses->save($campus)){
          $this->out('Campus could not be saved.');
          print_r($campus);
        }
        $addCount++;
        $this->out('Campus has been saved.');
      }

      $message = $addCount." campuses were added to the system.";
      $this->_syncReport[] = $message;
      $this->out($message);
    }

    public function syncDivisions(){
      $this->out('Inside sync Divisions');
      $this->loadModel('Divisions');

      $query = "Select * from dbo.vLB_Schools";
      $this->out('Getting Divisons');
      $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');

      $campuses = $this->Campuses->find()->where(['school_id' => $this->_schoolId])->combine('name', 'id')->toArray();
      $addCount = 0;
      $updateCount = 0;
      $hashEntries = [];
            
      foreach ($results as $row) {
        
        $data = [
          'name' => $row['DESCRIPTION'],
          'school_id' => $this->_schoolId,
          'campus_id' => $campuses[$row['CITY']],
          'template_id' => 1
        ];

        if(strpos($row['SCHOOLID'], 'ES')){
          $data['template_id'] = 3;
        }

        $this->out('finding Division '.$row['DESCRIPTION'].' on new db.');
        $blackBaudHash = $this->BlackBaudHash->findByOldId($row['SCHOOLSID'])
                                                  ->where(['old_table_name' => 'dbo.vLB_Schools'])
                                                  ->first();
        
        if($blackBaudHash){
          $this->out('Division already exists, new Division will not be created.');
          $division = $this->Divisions->findById($blackBaudHash->new_id)->first();
          $updateCount++;
        
        }else{
          $this->out('Division does not exists, new Division will be created.');
          $division = $this->Divisions->newEntity();
          $addCount++;
        }

        $division = $this->Divisions->patchEntity($division, $data);
        if(!$this->Divisions->save($division)){
          $this->out('Division could not be saved.');
          print_r($division);
        }

        $this->out('Division has been saved. Creating data for blackbaud hash entry.');
        if(!$blackBaudHash){
            $hashEntries[] = [
                'old_table_name' => 'dbo.vLB_Schools',
                'old_id' => $row['SCHOOLSID'],
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

    public function syncDivisionGrades(){
      $this->out('Inside sync DivisionGrades');
      $this->loadModel('DivisionGrades');

      $query = "Select * from dbo.vLB_GradeLevels";
      $this->out('Getting DivisonGrades');
      $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');
      $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
      $addCount = 0;
      $updateCount = 0;
      $hashEntries = [];
            
      foreach ($results as $row) {
        
        if(!isset($this->_grades[$row['GradeLevel']])){
          $this->out('Grade not present in grade map. Finding grade on new db.');
          $grade = $this->DivisionGrades->Grades->findByName($row['GradeLevel'])->first();
          if(!$grade){
            $this->out('Grade not found creating new.');
            $grade = $this->DivisionGrades->Grades->newEntity(['name' => $row['GradeLevel'], 'sort_order' => $row['GL_TABLESEQUENCE']]);
            $this->DivisionGrades->Grades->save($grade);
            $this->_grades[$row['GradeLevel']] = $grade->id;
            $expression = new QueryExpression('sort_order = sort_order + 1');
            $this->DivisionGrades->Grades->updateAll([$expression], ['id !=' => $grade->id, 'sort_order >=' => $grade->sort_order]);
          }
          $this->_grades[$row['GradeLevel']] = $grade->id;
        }

        $data = [
          'grade_id' => $this->_grades[$row['GradeLevel']],
          'division_id' => $divisions[$row['SCHOOL_ID_FK']]
        ];

        $this->out('finding Division Grade '.$row['GradeLevel'].' for division id '.$data['division_id'].' on new db.');
        $blackBaudHash = $this->BlackBaudHash->findByOldId($row['GL_GradeLevel_PK'])
                                                  ->where(['old_table_name' => 'dbo.vLB_GradeLevels'])
                                                  ->first();
        
        if($blackBaudHash){
          $this->out('Division Grade already exists, new Division will not be created.');
          $divisionGrade = $this->DivisionGrades->findById($blackBaudHash->new_id)->first();
          $updateCount++;
        
        }else{
          $this->out('Division does not exists, new Division will be created.');
          $divisionGrade = $this->DivisionGrades->newEntity();
          $addCount++;
        }

        $divisionGrade = $this->DivisionGrades->patchEntity($divisionGrade, $data);
        if(!$this->DivisionGrades->save($divisionGrade)){
          $this->out('Division grade could not be saved.');
          print_r($divisionGrade);
        }

        $this->out('Division Grade has been saved. Creating data for blackbaud hash entry.');
        if(!$blackBaudHash){
            $hashEntries[] = [
                'old_table_name' => 'dbo.vLB_GradeLevels',
                'old_id' => $row['GL_GradeLevel_PK'],
                'new_id' => $divisionGrade->id,
                'new_table_name' => 'division_grades'
            ];
        }
      }

      $this->_saveHashEntries($hashEntries);
      $message = $addCount." division grades were added to the system and ".$updateCount." have been updated.";
      $this->_syncReport[] = $message;
      $this->out($message);
    }

     public function syncAcademicYears(){
        
        $this->out("In sync Academic years");
        
        $this->loadModel('AcademicYears');

        $query = "SELECT * from dbo.vLB_AcademicYearsExtended where year(CAL_YearAcademic_STARTDATE) >= 2016";
        $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');
        $results = (new Collection($results))->indexBy('CAL_Year_FK')->toArray();
        $this->out('Getting Academic Years greater than or equal 2016-2017');
        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];

        foreach ($results as $row) {

            $this->out('Found year '.$row['CAL_Year']);

            $data = [
                'name' => implode('_', explode('-', $row['CAL_Year'])),
                'start_date' => new FrozenDate($row['CAL_YearAcademic_STARTDATE']),
                'end_date' =>  new FrozenDate($row['CAL_YearAcademic_ENDDATE']),
                'school_id' => $this->_schoolId,
            ];

            $blackBaudHash = $this->BlackBaudHash->findByOldId($row['CAL_Year_FK'])->where(['new_table_name' => 'academic_years'])->first();

            if($blackBaudHash){
            
                $this->out('Academic Year already exists on new db. Updating Value.');
                $academicYear = $this->Schools->AcademicYears->findById($blackBaudHash->new_id)->first();
                $updateCount++;
            
            }else{
                
                $duplicateEntry = $this->Schools->AcademicYears->find()->where($data)->first();

                if($duplicateEntry){
                    $this->out('duplicate Entry. Ignoring value. adding to hash table.');
                    $hashEntries[] = [
                        'old_table_name' => 'dbo.vLB_AcademicYearsExtended',
                        'old_id' => $row['CAL_Year_FK'],
                        'new_id' => $duplicateEntry->id,
                        'new_table_name' => 'academic_years'
                    ];
                    continue;
                }
                
                $academicYear =  $this->Schools->AcademicYears->newEntity();
                $addCount++;
            }

            $academicYear = $this->Schools->AcademicYears->patchEntity($academicYear, $data);
            if(!$this->Schools->AcademicYears->save($academicYear)){
                $this->out('Academic Year could not be saved.');
                $this->out($academicYear);
                continue;
            }

            $this->out('Acedemic Year has been saved.');
            if(!$blackBaudHash){
                $hashEntries[] = [
                    'old_table_name' => 'dbo.vLB_AcademicYearsExtended',
                    'old_id' => $row['CAL_Year_FK'],
                    'new_id' => $academicYear->id,
                    'new_table_name' => 'academic_years'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $message = $addCount." academic years have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function syncTerms(){
        
        $this->out("In sync terms");
        $this->loadModel('Terms');
        
        $query = "SELECT * from dbo.vLB_SchoolsTerms where year(STARTDATE) >= 2016";
        $results = $this->connectWithMigrationDB($query)->fetchAll('assoc');
        $this->out('Getting terms having start date greater than or equal 2016-2017');

        $academicYears = $this->BlackBaudHash->findByNewTableName('academic_years')->combine('old_id', 'new_id')->toArray();
        $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
        
        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];

        foreach ($results as $row) {

            $this->out('Found term '.$row['Term']." for ".$row['AcademicYear']);
            if(!isset($academicYears[$row['YEARID']])){
              $this->out('Invalid year, ignoring value.');
              continue;
            }

            $data = [
                'name' => $row['Term'],
                'start_date' => new FrozenDate($row['STARTDATE']),
                'end_date' =>  new FrozenDate($row['ENDDATE']),
                'academic_year_id' => $academicYears[$row['YEARID']],
                'division_id' => $divisions[$row['SCHOOLSID']]
            ];

            $blackBaudHash = $this->BlackBaudHash->findByOldId($row['CAL_Term_PK'].$row['SCHOOLSID'].$row['YEARID'])->where(['new_table_name' => 'terms'])->first();

            if($blackBaudHash){
                $this->out('Term already exists on new db. Value will be updated.');
                $term = $this->Terms->findById($blackBaudHash->new_id)->first();
                $updateCount++;
                
            }else{
                $term = $this->Terms->newEntity();
                $addCount++;
            }

            $term = $this->Terms->patchEntity($term, $data);

            if(!$this->Terms->save($term)){
                $this->out('Term could not be saved.');
                $this->out($term);
                continue;
            }

            $this->out('Term has been saved.');
            if(!$blackBaudHash){
                $hashEntries[] = [
                    'old_table_name' => 'dbo.vLB_AcademicYearsExtended',
                    'old_id' => $row['CAL_Term_PK'].$row['SCHOOLSID'].$row['YEARID'],
                    'new_id' => $term->id,
                    'new_table_name' => 'terms'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $message = $addCount." terms have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function syncUsers($role){
      $this->out('Inside sync Users for role '.$role);
      $this->loadModel('Users');
      $roles = [
        'teacher' => [
          'old_table' => 'dbo.vLB_Teachers',
          'role_id' => 3,
          'fields' => '[Record ID] as id, [Last name] as last_name, [First name] as first_name, [Online user ID] as online_user_id, [ISCURRENTTEACHER] as is_active, [TERMINATED], [GENDER] as gender, [Leadership], [Primary email] as email',
          'id_field' => 'id',
          'first_name_field' => 'first_name',
          'middle_name_field' => false,
          'last_name_field' => 'last_name',
          'gender_field' => 'gender',
          'dob_field' => false,
          'email_field' => 'email',
          'legacy_id_field' => 'id',
          'join' => false,
          'windows_ad_id_field' => 'online_user_id',
          'conditions' => "dbo.vLB_Teachers.TERMINATED = 0 and dbo.vLB_Teachers.[ISCURRENTTEACHER] = '-1'"
        ],
        'student' => [
          'old_table' => 'dbo.vLB_StudentsEnrolled',
          'role_id' => 4,
          'fields' => '[BIRTHDATE], [First_name] sis_student_first_name, dbo.vLB_StudentsEnrolled.[Gender] sis_student_gender, [Last_name]sis_student_last_name, dbo.vLB_StudentsEnrolled.[MIDDLENAME] sis_student_middle_name, [Student_ID]',
          'id_field' => 'Student_ID',
          'first_name_field' => 'sis_student_first_name',
          'middle_name_field' => 'sis_student_middle_name',
          'last_name_field' => 'sis_student_last_name',
          'gender_field' => 'sis_student_gender',
          'dob_field' => 'BIRTHDATE',
          'email_field' => false,
          'join' => ' left join [dbo].[EA7RECORDS] on [USERDEFINEDID]=[Student_ID]',
          'legacy_id_field' => 'Student_ID',
          'windows_ad_id_field' => false,
          'conditions' => "dbo.vLB_StudentsEnrolled.Status = 'Enrolled'"
        ]
      ]; 

      $roleData = $roles[$role];

      $query = "Select ".$roleData['fields']." from ".$roleData['old_table'];
      if($roleData['join']){
        $query = $query.$roleData['join'];
      }
      $query = $query.' where '.$roleData['conditions'];
      $this->out('Getting users');
      $results = $this->connectWithMigrationDB($query);
      
      
      $hashEntries = [];
      $updateCount = 0;
      $addCount = 0;
      $faker = Faker::create();
      $this->out('Got users. Data will be formed and users will be saved.');
      foreach ($results as $row) {
        $this->out('Forming data for '.$row[$roleData['first_name_field']]." ".$row[$roleData['last_name_field']]);
        $data = [
                'first_name' => $row[$roleData['first_name_field']],
                'last_name' => $row[$roleData['last_name_field']],
                'school_id' => $this->_schoolId,
        ];

        if($roleData['dob_field'] && !in_array($row[$roleData['dob_field']], [null, false, ''])){
          $data['dob'] = $row[$roleData['dob_field']];
        }

        if($roleData['role_id'] == 3 && $roleData['gender_field'] && !in_array($row[$roleData['gender_field']], [null, false, ''])){
          $data['gender'] = $row[$roleData['gender_field']] == 1 ? 'Male' : 'Female';
        }

        if($roleData['role_id'] == 4 && $roleData['gender_field'] && !in_array($row[$roleData['gender_field']], [null, false, ''])){
          $data['gender'] = $row[$roleData['gender_field']];
        }

        if($roleData['windows_ad_id_field'] && !in_array($row[$roleData['windows_ad_id_field']], [null, false, ''])){
          $data['windows_ad_id'] = $row[$roleData['windows_ad_id_field']];
        }

        if($roleData['legacy_id_field'] && !in_array($row[$roleData['legacy_id_field']], [null, false, ''])){
          $data['legacy_id'] = $row[$roleData['legacy_id_field']];
        }

        if($roleData['middle_name_field'] && !in_array($row[$roleData['middle_name_field']], [false, null, ''])){
          $data['middle_name'] = $row[$roleData['middle_name_field']];
        }

        $this->out('Data has been formed. Checking if user already exists');
        $blackBaudHash = $this->BlackBaudHash->findByOldId($row[$roleData['id_field']])->where(['new_table_name' => 'users', 'old_table_name' => $roleData['old_table']])->first();
              
        if($blackBaudHash){
            
          if($roleData['email_field'] && !in_array($row[$roleData['email_field']], [false, null, '', 'n/a'])){
            $data['email'] = preg_replace('/\s+/', '', $row[$roleData['email_field']]);
          }

          $this->out('User already exists on new db. Value will be updated.');
          $user = $this->Users->findById($blackBaudHash->new_id)->first();
          $updateCount++;
        
        }else{
          $this->out('Its a new user. User will be added to the system.');
          if($roleData['email_field']){
              $data['email'] = preg_replace('/\s+/', '', $row[$roleData['email_field']]);
          }else{
              $this->out('Email Field not present user will be created with a dummy email.');
              $data['email'] = $role.".".$row[$roleData['id_field']]."@aas.ru";
              $data['email'] = str_replace('-','', (strtolower($data['email'])));
          }

          if($roleData['email_field'] && in_array($row[$roleData['email_field']], [false, null, '', 'n/a'])){
              $this->out('No Email found. Creating user with dummy email for user having name '.$data['first_name'].' '.$data['last_name']);
              $data['email'] = $role.".".$row[$roleData['id_field']]."@aas.ru";
              $data['email'] = str_replace('-','', (strtolower($data['email'])));
          }

          $data['role_id'] = $roleData['role_id'];

            
          $duplicate = $this->Users->find()->where([$data])->orWhere(['email' => $data['email']])->first();
          if($duplicate){
              $this->out('duplicate Entry. Ignoring value. adding to hash table.');
              $hashEntries[] = [
                  'old_table_name' => $roleData['old_table'],
                  'old_id' => $row[$roleData['id_field']],
                  'new_id' => $duplicate->id,
                  'new_table_name' => 'users'
              ];
              continue;
          }
            
          $data['password'] = '123456789';
          $user = $this->Users->newEntity();
          $addCount++;
        }

        $user = $this->Users->patchEntity($user, $data);
        if(!$this->Users->save($user)){
            $this->out('User could not be saved.');
            print_r($user);
            continue;
        }

        $this->out('User has been saved.');
        if(!$blackBaudHash){
            $hashEntries[] = [
                'old_table_name' => $roleData['old_table'],
                'old_id' => $row[$roleData['id_field']],
                'new_id' => $user->id,
                'new_table_name' => 'users'
            ];
        }
       }

       $this->_saveHashEntries($hashEntries);
       $message = $addCount." users have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function syncCourses(){
        $this->out("In sync courses");
        $this->loadModel('Courses');
        $this->loadModel('Divisions');

        $query = "SELECT * from dbo.vLB_CoursesGradelevels_20182019 c inner join dbo.VLB_Schools s on c.School = s.Description";
        $results = $this->connectWithMigrationDB($query);
        $this->out('Getting Courses');

        $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
        $divisionCampuses = $this->Divisions->find()->combine('id', 'campus_id')->toArray();

        $courses = $this->BlackBaudHash->find()->where(['new_table_name' => 'courses'])->combine('old_id', 'new_id')->toArray();

        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];
        $count = 0;
        $totalCount = count($results);
       
        foreach ($results as $row) {

            $count++;
            $this->out('Count : '.$count.' of '.$totalCount);

            $this->out('Found course '.$row['Course name']);
            $blackBaudHash = $this->BlackBaudHash->findByOldId($row['EA7COURSESID'].'-'.$row['Grade levels allowed'])->where(['new_table_name' => 'courses'])->first();
            
            $data = [
                'name' => $row['Course name'],
                'description' => $row['Course name'],
                'grade_id' => $this->_grades[$row['Grade levels allowed']]
            ];
            
            if($blackBaudHash){
                
                $this->out('Course already exists on new db. Updating Value.');
                $course = $this->Courses->find()->where(['id ' => $blackBaudHash->new_id])->first();
                $divisionId = $divisions[$row['SCHOOLSID']];
                $campusCourseData = ['course_id' => $course->id, 'campus_id' => $divisionCampuses[$divisionId]];
                $campusCourse = $this->Courses->CampusCourses->find()->where($campusCourseData)->first();
                if(!$campusCourse){
                  $campusCourse = $this->Courses->CampusCourses->newEntity($campusCourseData);
                  if(!$this->Courses->CampusCourses->save($campusCourse)){
                    $this->out('Campus Course could not be saved.');
                    print_r($course);
                  }else{
                    $this->out('Campus Course has been added.');
                  }
                }
                $updateCount++;
            
            }else{

                $divisionId = $divisions[$row['SCHOOLSID']];
                $data['learning_area_id'] = 1;
                $data['campus_courses'] = [['campus_id' => $divisionCampuses[$divisionId]]]; 
                $addCount++;
                $course =  $this->Courses->newEntity();
            }

            $course = $this->Courses->patchEntity($course, $data);    

            if(!$this->Courses->save($course)){
                $this->out('Course could not be saved.');
                print_r($course);
                continue;
            }

            $this->out('Course has been saved.');
            if(isset($courses[$row['EA7COURSESID'].'-'.$row['Grade levels allowed']])){
               unset($courses[$row['EA7COURSESID'].'-'.$row['Grade levels allowed']]);
            }
            $this->addCourseContentCategories($course);
            if(!$blackBaudHash){
              $hashEntries[] = [
                  'old_table_name' => 'dbo.vLB_CoursesGradelevels_20172018',
                  'old_id' => $row['EA7COURSESID'].'-'.$row['Grade levels allowed'],
                  'new_id' => $course->id,
                  'new_table_name' => 'courses'
              ];
            }
        }

        $this->_saveHashEntries($hashEntries);
        print_r($courses);
        $this->out('Extra courses count = '.count($courses));
        $message = $addCount." courses have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function addCourseContentCategories($course){
       
        $this->out('In course content categories.');
        $this->loadModel('CourseContentCategories');
        $contentCategoryIds = $this->CourseContentCategories->ContentCategories->find()->extract('id')->toArray();
        if(empty($contentCategoryIds)){
            $this->out('No content categories found on db associations will not be made.');
            return;
        }
        $courseContentCategories = [];
        $this->out('Searching for course id '.$course->course_id);
        $categories = $this->CourseContentCategories->findByCourseId($course->course_id)->toArray();
        if(!empty($categories)){
            $this->out("Entries already found. New Entries will not be made.");
            return;
        }

        foreach ($contentCategoryIds as $contentCategoryId) {
            $courseContentCategory = $this->CourseContentCategories->find()->where(['course_id' => $course->id, 'content_category_id' => $contentCategoryId])->first();
            if(!$courseContentCategory){
              $courseContentCategories[] = ['course_id' => $course->id, 'content_category_id' => $contentCategoryId];
            }
        }

        $courseContentCategories = $this->CourseContentCategories->newEntities($courseContentCategories);
        if(!$this->CourseContentCategories->saveMany($courseContentCategories)){
            $this->out("Course content categories could not be saved.");
            return;
        }

        $this->out("Course content categories have been saved.");
    }

    public function syncSections($oldAcademicYearId){
        $this->out("In sync Sections");
        $this->loadModel('Sections');
        $query = "SELECT DISTINCT sct.[Instructor ID], sct.[SCHOOLSID], sct.[Student Grade] ,sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] = ".$oldAcademicYearId;
        
        $results = $this->connectWithMigrationDB($query);
        $this->out('Getting Sections');
        $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
        $divisionCampuses = $this->Sections->Terms->Divisions->find()->combine('id', 'campus_id')->toArray();


        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $count = 0;
        $totalCount = count($results);
        $primaryTeacherCount = 0;
        $this->out('Processing sections.');
        foreach ($results as $row) {
            $count++;
            $this->out("Count : ".$count." of ".$totalCount);
            $this->out('Processing section'.$row['CLASSSECTION']);

            $data = [
                'name' => $row['CLASSSECTION'],
            ];

            $this->out('Finding Course having id '.$row['COURSEID']);
            
            $oldCourseId = $row['COURSEID'].'-'.$row['Student Grade'];
            $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['new_table_name' => 'courses', 'old_id' => $oldCourseId])->first();
            
            if(!$courseBlackBaudHash){
                 $oldCourseId = $row['COURSEID'];
                 $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['new_table_name' => 'courses', 'old_id Like' => '%'.$row['COURSEID'].'%'])->first();
            }

            if(!$courseBlackBaudHash){
                $this->out('Course not found on new db. Ignoring value.');
                continue;
            }

            $data['course_id'] = $courseBlackBaudHash->new_id;

            $this->out('Finding Term having id on old db '.$row['CAL_Term_FK'].$row['SCHOOLSID']);
            
            $termBlackBaudHash = $this->BlackBaudHash->findByOldId($row['CAL_Term_FK'].$row['SCHOOLSID'].$row['YEARID'])->where(['new_table_name' => 'terms'])->first();
            
            if(!$termBlackBaudHash){
                $this->out('Term not found on new db. Ignoring Value.');
                continue;
            }
            $data['term_id'] = $termBlackBaudHash->new_id;

            $this->out('Finding Teacher');
            $teacherBlackBaudHash = $this->BlackBaudHash->findByOldId($row['Instructor ID'])->where(['old_table_name' => 'dbo.vLB_Teachers'])->first();

            if(!$teacherBlackBaudHash){
                $this->out('Teacher not found on new db. Ignoring Value.');
                continue;
            }
            
            $sectionTeacher = false;

            $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$oldCourseId;
            $blackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();

            if($blackBaudHash){
            
                $this->out('Section already exists on new db. Updating Value.');
                $section = $this->Sections->findById($blackBaudHash->new_id)->contain(['SectionTeachers', 'SectionStudents'])->first();

                if($section->teacher_id != $teacherBlackBaudHash->new_id){
                  
                  $this->out("Section teacher is not equal to current teacher. Confirming if section teacher is still the primary teacher.");
                  $oldPrimaryTeacher = $this->BlackBaudHash->findByNewId($section->teacher_id)->where(['old_table_name' => 'dbo.vLB_Teachers'])->first();

                  $confirmPrimaryTeacherQuery = "SELECT DISTINCT sct.[Instructor ID], sct.[SCHOOLSID], sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] =".$row['YEARID']." and sct.[Instructor ID] = '".$oldPrimaryTeacher->old_id."' and sct.[CLASSSECTION] = '".$row['CLASSSECTION']."' and sct.[CAL_Term_FK] = '".$row['CAL_Term_FK']."' and sct.[SCHOOLSID] = '".$row['SCHOOLSID']."' and sct.[EA7COURSESID] Like '%".$row['COURSEID']."%'";

                  $confirmPrimaryTeacher = $this->connectWithMigrationDB($confirmPrimaryTeacherQuery)->fetch('assoc');

                  if(empty($confirmPrimaryTeacher) || !$confirmPrimaryTeacher){
                    $this->out('Primary Teacher has changed. Making the changes on LB.');
                    $data['teacher_id'] =  $teacherBlackBaudHash->new_id;
                    $this->Sections->SectionTeachers->deleteAll(['section_id'=> $section->id, 'teacher_id' => $section->teacher_id]);
                    $primaryTeacherCount++;
                  }else{
                    $this->out('Primary Teacher has not changed.');
                  }

                }

                if(!empty($section->section_teachers)){
                  $teacherIds = (new Collection($section->section_teachers))->extract('teacher_id')->toArray();
                  if(!in_array($teacherBlackBaudHash->new_id, $teacherIds)){
                    $sectionTeacher = [
                      'section_id' => $section->id,
                      'teacher_id' => $teacherBlackBaudHash->new_id
                    ];
                  }
                }

                $updateCount++;

            }else{
                

                $data['teacher_id'] = $teacherBlackBaudHash->new_id;
                $data['section_teachers'] =  [['teacher_id' => $teacherBlackBaudHash->new_id]];
                $section = $this->Sections->newEntity();
                $addCount++;
            }

            $section = $this->Sections->patchEntity($section, $data);
            
            if(!$this->Sections->save($section)){
                $this->out('Section could not be saved.');
                print_r($section);
                continue;
            }

            if($sectionTeacher){
              $this->out('Saving Section Teacher');
              $sectionTeacher = $this->Sections->SectionTeachers->newEntity($sectionTeacher);
              if(!$this->Sections->SectionTeachers->save($sectionTeacher)){
                $this->out('section teacher could not be saved.');
                print_r($sectionTeacher);
              }
            }

            $this->out('Section has been saved.');
            if(!$blackBaudHash){
                $hashEntries = [[
                    'old_table_name' => 'dbo.vLB_StudentCoursesTerms_20172018',
                    'old_id' => $oldSectionId,
                    'new_id' => $section->id,
                    'new_table_name' => 'sections'
                ]];
                $this->_saveHashEntries($hashEntries);
            }
            $this->out('Checking CampusCourseTeacher');
            $this->checkCampusCourseTeacher($teacherBlackBaudHash->new_id, $courseBlackBaudHash->new_id, $divisionCampuses[$divisions[$row['SCHOOLSID']]]);
        }  

        $message = $addCount." sections have been added to the system, ".$updateCount." have been updated and ".$primaryTeacherCount." primary teachers updated.";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function checkCampusCourseTeacher($teacherId, $courseId, $campusId){
      $this->out('In check CampusTeachers and and campus course teachers');
      $this->loadModel('Campuses');
      $this->out('Teacher Id: '.$teacherId." course id: ".$courseId." campus Id: ".$campusId);
      $campusCourse = $this->Campuses->CampusCourses->find()->where(['campus_id' => $campusId, 'course_id' => $courseId])->first();
      $campusTeacherData = ['campus_id' => $campusId, 'teacher_id' => $teacherId];
      $this->out('Finding campus Teacher');
      $campusTeacher = $this->Campuses
                            ->CampusTeachers->find()
                            ->where($campusTeacherData)
                            ->first();

      $campusCourseTeacher = true;
      if($campusCourse){
        $this->out('Campus Course found');
        $campusCourseTeacherData = ['campus_course_id' => $campusCourse->id, 'teacher_id' => $teacherId];
        $this->out('Finding campus Course Teacher');
        $campusCourseTeacher = $this->Campuses->CampusCourses
                                    ->CampusCourseTeachers->find()
                                    ->where($campusCourseTeacherData)
                                    ->first();
      }

      if(!$campusTeacher){
        $this->out('Campus Teacher not found. Creating New');
        $campusTeacher = $this->Campuses->CampusTeachers->newEntity($campusTeacherData);
        if(!$this->Campuses->CampusTeachers->save($campusTeacher)){
          $this->out('campus teacher could not be saved.');
          print_r($campusTeacher);
        }
      }

      if(!$campusCourse){
       $this->out('campus course not found');
       return; 
      }

       if(!$campusCourseTeacher){
        $this->out('Campus Course Teacher not found. Creating New');
        $campusCourseTeacher = $this->Campuses->CampusCourses->CampusCourseTeachers->newEntity($campusCourseTeacherData);
        if(!$this->Campuses->CampusCourses->CampusCourseTeachers->save($campusCourseTeacher)){
          $this->out('campus course teacher could not be saved.');
          print_r($campusCourseTeacher);
        }
      }
    }

    public function test(){
      $this->out('in test');
    // $query = "SELECT Status from dbo.vLB_StudentsEnrolled";
      $query = "SELECT DISTINCT sct.[Instructor ID], sct.[SCHOOLSID], sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] =2387 and sct.[Instructor ID] = 'FAC-108784'";

      $query = "SELECT DISTINCT sct.[Instructor ID], sct.[SCHOOLSID], sct.[Student Grade] ,sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] = 2387 and sct.[Instructor ID] = 'FAC-108784'";
      
      $query = "Select * from vLB_StudentCoursesTerms_20182019";


        
      $results = $this->connectWithMigrationDB($query);
      foreach($results as $result){
        print_r($result);
      }


        print_r(count($results)); die;
    }

    public function syncSectionStudents(){
      $this->out("In sync Section Students");
        $this->loadModel('SectionStudents');

        $query = 'SELECT DISTINCT sct.[Student ID], sct.[SCHOOLSID], sct.[Student Grade] ,sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and sct.[WITHDRAWNDATE] IS NULL'
        ;
        $results = $this->connectWithMigrationDB($query);

        $this->out('Getting Section Students');

        $addCount = 0;
        $processedSectionIds = [];
        $count = 0;
        $totalCount = count($results);
        $this->out('Processing section Students.');
        foreach ($results as $row) {
            $count++;
            $this->out("Count : ".$count." of ".$totalCount);
            $this->out('Processing a section Student');

            $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$row['COURSEID'].'-'.$row['Student Grade'];
            
            if(!isset($processedSectionIds[$oldSectionId])) {

            $sectionBlackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();

              if(!$sectionBlackBaudHash){
                $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$row['COURSEID'];
                $sectionBlackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();
              }

              if(!$sectionBlackBaudHash){
                $this->out('Section not found on new db. Ignoring value.');
                continue;
              }

              $processedSectionIds[$oldSectionId] = $sectionBlackBaudHash->new_id;            
            }

            $newSectionId = $processedSectionIds[$oldSectionId];

            $studentBlackBaudHash = $this->BlackBaudHash->findByOldId($row['Student ID'])->where(['old_table_name' => 'dbo.vLB_StudentsEnrolled'])->first();

            if(!$studentBlackBaudHash){
                $this->out('Student not found on new db. Ignoring value.');
                continue;
            }

            $sectionStudent =  $this->SectionStudents
                                ->findByStudentId($studentBlackBaudHash->new_id)
                                ->where([
                                  'section_id' => $newSectionId,
                                ])
                                ->first();

            if($sectionStudent){
                $this->out('Student and section already exist.');
                continue;
            }

            $data = [
              'student_id' => $studentBlackBaudHash->new_id,
              'section_id' => $newSectionId
            ];
            $sectionStudent = $this->SectionStudents->newEntity($data);
            if(!$this->SectionStudents->save($sectionStudent)) {
                $this->out('Error in saving sectionstudent');
                print_r($sectionStudent); 
                continue;  
            }

            $addCount++;

            $this->out('SectionStudent saved');
        }  

        $message = $addCount." sections have been added to the system.";
        $this->_syncReport[] = $message;
        $this->out($message);
    }

    public function syncGuardians(){
      $this->out("In sync Guardians");
        $this->loadModel('Users');

        $query = "SELECT * from [dbo].[vLB_ParentsStudentsEnrolled]";
        $results = $this->connectWithMigrationDB($query);
        $this->out('Getting Guardians');

        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];
        
       
        foreach ($results as $row) {

            $this->out('Found Guardians. '.$row['ParentFullname']);

            $studentBlackBaudHash = $this->BlackBaudHash->findByOldId($row['StudentID'])->where(['old_table_name' => 'dbo.vLB_StudentsEnrolled'])->first();

            if(!$studentBlackBaudHash){
              $this->out('Student not found on new DB. Ignoring Value.');
              continue;
            }

            
            $blackBaudHash = $this->BlackBaudHash->findByOldId($row['ParentID'])->where(['old_table_name' => 'dbo.vLB_ParentsStudentsEnrolled'])->first();
            
            $data = [
                'first_name' => $row['ParentFirst'],
                'last_name' => $row['ParentLast'],
                'email' => $row['ParentPrimaryEmail'],
                'school_id' => $this->_schoolId,
            ];
            
            if($blackBaudHash){

                if(in_array($data['email'], [null, false, ''])){
                  unset($data['email']);
                }
            
                $this->out('User already exists. Value will be updated.');
                $user = $this->Users->find()->where(['id ' => $blackBaudHash->new_id])->first();
                $updateCount++;
            
            }else{

                if(in_array($data['email'], [null, false, ''])){
                  $this->out('Email Field not present user will be created with a dummy email.');
                  $data['email'] = "guardian.".$row['ParentID']."@aas.ru";
                  $data['email'] = str_replace('-','', (strtolower($data['email'])));
                }
                
                $duplicate = $this->Users->find()->where($data)->first();

                if($duplicate){
                  $this->out('Guardian has already been added, hash entry will be created and association will be made.');
                  $hashEntries[] = [
                      'old_table_name' => 'dbo.vLB_ParentsStudentsEnrolled',
                      'old_id' => $row['ParentID'],
                      'new_id' => $duplicate->id,
                      'new_table_name' => 'users'
                  ];

                  $studentGuardian = [
                    'student_id' => $studentBlackBaudHash->new_id,
                    'guardian_id' => $duplicate->id,
                    'relationship_type' => $row['RelationReciprocal']
                  ];

                  $this->out('Trying to save student guardian.');
                  $studentGuardian = $this->Users->StudentGuardians->newEntity($studentGuardian);
                  if(!$this->Users->StudentGuardians->save($studentGuardian)){
                    $this->out('Student Guardian cannot be saved.');
                    print_r($studentGuardian);
                  }
                  continue;
                }

                $this->out('Guardian does not exist, creating new.');

                $data['password'] = '123456789';
                $data['role_id'] = 5;
                $data['legacy_id'] = $row['ParentID'];
                $data['gender'] = $row['ParentGender'];
                $data['windows_ad_id'] = $row['ParentLogin'];

                $user =  $this->Users->newEntity();
                $addCount++;
            }

            $user = $this->Users->patchEntity($user, $data);    

            if(!$this->Users->save($user)){
                $this->out('Guardian could not be saved.');
                print_r($user);
                continue;
            }

            if(!$blackBaudHash && !$duplicate){
              $studentGuardian = ['guardian_id' => $user->id, 'student_id'=> $studentBlackBaudHash->new_id, 'relationship_type' => $row['RelationReciprocal']];
              $studentGuardian = $this->Users->StudentGuardians->newEntity($studentGuardian);
              if(!$this->Users->StudentGuardians->save($studentGuardian)){
                $this->out('Student Guardian could not be saved.');
                print_r($studentGuardian);
              }
              
            }

            $this->out('Guardian has been saved.');
            if(!$blackBaudHash){
              $hashEntries[] = [
                  'old_table_name' => 'dbo.vLB_ParentsStudentsEnrolled',
                  'old_id' => $row['ParentID'],
                  'new_id' => $user->id,
                  'new_table_name' => 'users'
              ];
            }
        }

        $this->_saveHashEntries($hashEntries);
        $message = $addCount." users have been added to the system and ".$updateCount." have been updated";
        $this->_syncReport[] = $message;
        $this->out($message);
    }


   public function fixSectionStudents($schoolId){
        $this->out("In fixSectionStudents");
        $this->loadModel('SectionStudents');
        $this->Schools = $this->loadModel('Schools');
        $this->BlackBaudHash = $this->loadModel('BlackBaudHash');
        if(in_array($schoolId, [null, false, ''])){
            $this->out('School id is required');
            return false;
        }

        $this->_schoolId = $schoolId;  
        $query = 'SELECT DISTINCT sct.[Student ID], sct.[SCHOOLSID], sct.[Student Grade], sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and sct.[WITHDRAWNDATE] IS NOT NULL'
        ;
        $results = $this->connectWithMigrationDB($query);


        $this->out('Getting Section Students');

        $addCount = 0;
        $processedSectionIds = [];
        $this->out('Processing section Students.');
        foreach ($results as $row) {

            $this->out('Processing a section Student');

            $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$row['COURSEID'].'-'.$row['Student Grade'];
            
            if(!isset($processedSectionIds[$oldSectionId])) {

            $sectionBlackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();

              if(!$sectionBlackBaudHash){
                $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$row['COURSEID'];
                $sectionBlackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();
              }

              if(!$sectionBlackBaudHash){
                $this->out('Section not found on new db. Ignoring value.');
                continue;
              }

              $processedSectionIds[$oldSectionId] = $sectionBlackBaudHash->new_id;            
            }

            $newSectionId = $processedSectionIds[$oldSectionId];

            $studentBlackBaudHash = $this->BlackBaudHash->findByOldId($row['Student ID'])->where(['old_table_name' => 'dbo.vLB_StudentsEnrolled'])->first();

            if(!$studentBlackBaudHash){
                $this->out('Student not found on new db. Ignoring value.');
                continue;
            }

            $sectionStudent =  $this->SectionStudents
                                ->findByStudentId($studentBlackBaudHash->new_id)
                                ->where([
                                  'section_id' => $newSectionId,
                                ])
                                ->first();

            if($sectionStudent){
                $this->out('Found Section student. Deleting Section student.');
                $this->SectionStudents->delete($sectionStudent);
		                $addCount++;

            }
        }  
	$this->out($addCount." entries deleted.");
    }
    public function fixSections($oldAcademicYearId){
        $this->out("In fixSections");
        $this->loadModel('Sections');
        $this->loadModel('BlackBaudHash');
        $query = "SELECT DISTINCT sct.[Instructor ID], sct.[SCHOOLSID], sct.[Student Grade] ,sct.[EA7COURSESID] as COURSEID, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo. vLB_StudentCoursesTerms_20182019 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] = ".$oldAcademicYearId;
        
        $results = $this->connectWithMigrationDB($query);
        $this->out('Getting Sections');
        $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
        $divisionCampuses = $this->Sections->Terms->Divisions->find()->combine('id', 'campus_id')->toArray();


        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $count =0 ;
        $totalCount = count($results);
        $academicYearHash = $this->BlackBaudHash->findByOldId($oldAcademicYearId)->where(['new_table_name' => 'academic_years'])->first();

        if(!$academicYearHash){
          $this->out('Academic year not found. Cannot continue');
          return;
        }

        $activeSectionIds = $this->Sections
                            ->find()
                            ->matching('Terms.AcademicYears', function($q) use ($academicYearHash){
                              return $q->where(['AcademicYears.id' => $academicYearHash->new_id]);
                            })
                            ->extract('id')
                            ->toArray();

        $sections = $this->BlackBaudHash->find()->where(['new_table_name' => 'sections', 'new_id IN' => $activeSectionIds])->combine('old_id', 'new_id')->toArray();
        
        $this->out('Processing sections.');
        foreach ($results as $row) {
            $count++;
            $this->out('Count: '.$count." of ".$totalCount);
            $this->out('Processing section'.$row['CLASSSECTION']);

            $data = [
                'name' => $row['CLASSSECTION'],
            ];

            $this->out('Finding Course having id '.$row['COURSEID']);
            
            $oldCourseId = $row['COURSEID'].'-'.$row['Student Grade'];
            $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['new_table_name' => 'courses', 'old_id' => $oldCourseId])->first();
            
            if(!$courseBlackBaudHash){
                 $oldCourseId = $row['COURSEID'];
                 $courseBlackBaudHash = $this->BlackBaudHash->find()->where(['new_table_name' => 'courses', 'old_id Like' => '%'.$row['COURSEID'].'%'])->first();
            }

            if(!$courseBlackBaudHash){
                $this->out('Course not found on new db. Ignoring value.');
                continue;
            }

            $data['course_id'] = $courseBlackBaudHash->new_id;

            $this->out('Finding Term having id on old db '.$row['CAL_Term_FK'].$row['SCHOOLSID']);
            
            $termBlackBaudHash = $this->BlackBaudHash->findByOldId($row['CAL_Term_FK'].$row['SCHOOLSID'].$row['YEARID'])->where(['new_table_name' => 'terms'])->first();
            
            if(!$termBlackBaudHash){
                $this->out('Term not found on new db. Ignoring Value.');
                continue;
            }
            $data['term_id'] = $termBlackBaudHash->new_id;

            $this->out('Finding Teacher');
            $teacherBlackBaudHash = $this->BlackBaudHash->findByOldId($row['Instructor ID'])->where(['old_table_name' => 'dbo.vLB_Teachers'])->first();

            if(!$teacherBlackBaudHash){
                $this->out('Teacher not found on new db. Ignoring Value.');
                continue;
            }
            
            $sectionTeacher = false;

            $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$oldCourseId;
            $blackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();

            if($blackBaudHash){
              print_r('Found Section');
              unset($sections[$blackBaudHash->old_id]);

            }
            
        }  

        $sections = array_values($sections);
        $this->out('Found '.count($sections)." extra secctions. Deleting Sections and associations");
        $this->Sections->deleteAll(['id IN' => $sections]);
        $this->Sections->SectionStudents->deleteAll(['section_id IN' => $sections]);
        $this->Sections->SectionTeachers->deleteAll(['section_id IN' => $sections]);
        $this->BlackBaudHash->deleteAll(['new_id IN' => $sections, 'new_table_name' => 'sections']);

        $message = " sections have been deleted";
        $this->out($message);
    }

    public function deleteEmptySections(){
      $this->loadModel('Sections');
      $this->loadModel('BlackBaudHash');
      

      $this->out("In deleteEmptySections. Getting Sections");
      $sections = $this->Sections->find()->contain('SectionStudents')->toArray();
      $total = count($sections);
      $count = 0;
      $deleteCount =0;
      foreach($sections as $section){
        $count++;
        $this->out('Count: '.$count.' of '.$total);
        if(empty($section->section_students)){
          $this->out('Found Empty Section. Deleting section.');
          $deleteCount++;
          $this->Sections->deleteAll(['id' => $section->id]);
          $this->Sections->SectionStudents->deleteAll(['section_id IN' => $section->id]);
          $this->Sections->SectionTeachers->deleteAll(['section_id IN' => $section->id]);
          $this->BlackBaudHash->deleteAll(['new_id' => $section->id, 'new_table_name' => 'sections']);
        }
      }

      $message = $deleteCount." Sections deleted.";
      $this->_syncReport[] = $message;
      $this->out($message);
    }
}?>