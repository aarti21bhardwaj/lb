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


/**
 * PowerSchoolSync shell command.
 */
class PowerSchoolSyncShell extends Shell
{
    Private $_school = false;
    Private $_schoolId = false;
    Private $_divisions = [
                            "2" => 1,
                            "3" => 2,
                            "4" => 3
                         ];

    Private $_grades = ["-2" => 1, "-1" => 1, "0" => 2, "1" => 3, "2" => 4, "3" => 5, "4" => 6, "5" => 7, "6" => 8, "7" => 9, "8" => 10, "9" => 11, "10" => 12, "11" => 13, "12" => 14 ];

    public function main($schoolId, $terms = false)
    {
        
        $this->out('In Power school sync data shell.');
        $this->Schools = $this->loadModel('Schools');
        $this->PowerSchoolHash = $this->loadModel('PowerSchoolHash');
        if(in_array($schoolId, [null, false, ''])){
            $this->out('School id is required');
            return false;
        }

        $this->_schoolId = $schoolId;   

        $this->_getSchool();

        if(count($this->_school->campuses) > 1){
            $this->out('More than one campus found for this school. Sync will only be done for the first campus.');
        }

        $this->createTempTables();
        $this->insertTempData();
        if($terms){

            $this->syncAcademicYears();
            $this->syncTerms();
        }
        $this->syncUsers('teacher');
        $this->syncUsers('student');
        $this->syncStudentEmails();
        $this->syncCourses();
        $this->syncSections();
        $this->syncSectionStudents();
        $this->syncSectionTeachers();
        $this->createCampusCourseTeachers();
        $this->dropTempTables();
    }

    public function createTempTables(){

        $this->out("Creating Temp Tables");

        $this->out("Creating ps_courses");
        $query1 = 'CREATE TABLE `ps_courses` ( `course_id` VARCHAR(255) NOT NULL ,  `course_name` VARCHAR(255) NULL , PRIMARY KEY  (`course_id`)) ENGINE = InnoDB';
        $this->out("Creating ps_sections");
        $query2 = 'CREATE TABLE `ps_sections` ( `section_id` VARCHAR(255) NOT NULL ,  `course_id` VARCHAR(255) NOT NULL , PRIMARY KEY  (`section_id`)) ENGINE = InnoDB';
        $this->out("Creating ps_cc");
        $query3 = 'CREATE TABLE `ps_cc` ( `id` VARCHAR(255) NOT NULL ,  `section_id` VARCHAR(255) NULL , `student_id` VARCHAR(255) NULL, PRIMARY KEY  (`id`)) ENGINE = InnoDB';
        $this->out("Creating ps_students");
        $query4 = 'CREATE TABLE `ps_students` ( `student_id` VARCHAR(255) NOT NULL ,  `grade_level` VARCHAR(255) NULL , `student_ccid` VARCHAR(255) NULL, PRIMARY KEY  (`student_id`)) ENGINE = InnoDB';
        $this->out("Creating ps_teachers");
        $query5 = 'CREATE TABLE `ps_teachers` ( `teacher_id` VARCHAR(255) NOT NULL,  `user_id` VARCHAR(255) NOT NULL, PRIMARY KEY  (`teacher_id`)) ENGINE = InnoDB';
        $connection = ConnectionManager::get('default');
        $results = $connection->execute($query1);
        $results = $connection->execute($query2);
        $results = $connection->execute($query3);
        $results = $connection->execute($query4);
        $results = $connection->execute($query5);

    }

    public function insertTempData() {
        $this->out('Inside insertTempData. Getting data from csv Files.');
        $courses = $this->getCsvData('Courses', 'powerSchool/LB_Courses');
        $sections = $this->getCsvData('Sections', 'powerSchool/LB_Sections');
        $cc = $this->getCsvData('CC', 'powerSchool/LB_CC');
        $students = $this->getCsvData('Students', 'powerSchool/LB_Students');
        $teachers = $this->getCsvData('Teachers', 'powerSchool/LB_Teachers');
        $connection = ConnectionManager::get('default');

        $this->out('Inserting Data in ps_courses');
        foreach ($courses as $row) {

            $connection->insert('ps_courses',[
                'course_id' => $row['courses_course_number'],
                'course_name' => $row['courses_course_name']
            ]);
            # code...
        }
        
        $this->out('Inserting Data in ps_sections');
        foreach ($sections as $row) {
            $connection->insert('ps_sections',[
                'section_id' => $row['sections_dcid'],
                'course_id' => $row['sections_course_number']
            ]);
            # code...
        }

        $this->out('Inserting Data in ps_students');
        foreach ($students as $row) {
            if(!isset($this->_grades[$row['students_grade_level']])){
                $this->out('invalid grade');
                continue;
            }
            $connection->insert('ps_students',[
                'student_id' => $row['students_dcid'],
                'grade_level' => $row['students_grade_level'],
                'student_ccid' => $row['students_id']
            ]);
            # code...
        }
        $this->out('Inserting Data in ps_teachers');
        foreach ($teachers as $row) {
            if(!isset($row['teachers_users_dcid'])){
                continue;
            }
            $connection->insert('ps_teachers',[
                'teacher_id' => $row['teachers_dcid'],
                'user_id' => $row['teachers_users_dcid']
            ]);
        }

        $this->out('Inserting Data in ps_cc');
        foreach ($cc as $row) {
            $connection->insert('ps_cc',[
                'id' => $row['cc_dcid'],
                'section_id' => $row['cc_sectionid'],
                'student_id' => $row['cc_studentid'],
            ]);
            # code...
        }


    }

    public function dropTempTables(){
        $this->out('Inside dropTempTables');
        $query = 'DROP TABLE `ps_courses`, `ps_sections`, `ps_cc`, `ps_students`, `ps_teachers`;';
        $connection = ConnectionManager::get('default');
        $results = $connection->execute($query);
        $this->out('Temp tables have been droped.');
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

        $hashEntries = $this->PowerSchoolHash->newEntities($hashEntries);
        if(!$this->PowerSchoolHash->saveMany($hashEntries)){
            $this->out("Hash entries could not be saved");
            $this->out($hashEntries);
            return false;
        }
        $this->out("Hash entries have been saved");
    }

    public function syncAcademicYears(){
        
        $this->out("In sync Academic years");
        
        $data = $this->getCsvData('Terms', 'powerSchool/LB_Terms');
        if(!$data){
            return false;
        }
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $this->out('Processing academicYears greater than or equal 2016-2017');
        foreach ($data as $row) {

            if(strrpos($row['terms_name'], 'Semester') !== false || strrpos($row['terms_name'], 'Quarter') !== false || $row['terms_yearid'] < 26){
                continue;
            }

            $this->out('Found year '.$row['terms_name']);

            $data = [
                'name' => implode('_', explode('-', $row['terms_name'])),
                'start_date' => new FrozenDate($row['terms_firstday']),
                'end_date' =>  new FrozenDate($row['terms_lastday']),
                'school_id' => $this->_schoolId,
            ];

            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row['terms_dcid'])->where(['new_table_name' => 'academic_years'])->first();

            if($powerSchoolHash){
            
                $this->out('Academic Year already exists on new db. Updating Value.');
                $academicYear = $this->Schools->AcademicYears->findById($powerSchoolHash->new_id)->first();
                $updateCount++;
            
            }else{
                
                $duplicateEntry = $this->Schools->AcademicYears->find()->where($data)->first();

                if($duplicateEntry){
                    $this->out('duplicate Entry. Ignoring value. adding to hash table.');
                    $hashEntries[] = [
                        'old_table_name' => 'terms',
                        'old_id' => $row['terms_dcid'],
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
                $this->out('Year could not be saved.');
                $this->out($academicYear);
                continue;
            }

            $this->out('Year has been saved.');
            if($academicYear->isNew()){
                $hashEntries[] = [
                    'old_table_name' => 'terms',
                    'old_id' => $row['terms_dcid'],
                    'new_id' => $academicYear->id,
                    'new_table_name' => 'academic_years'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." academic years have been added to the system and ".$updateCount." have been updated"); 
    }


    public function syncTerms(){
        
        $this->out("In sync terms");
        $this->loadModel('Terms');
        $data = $this->getCsvData('Terms', 'powerSchool/LB_Terms');
        if(!$data){
            return false;
        }

        $academicYears = $this->Terms->AcademicYears->find()->indexBy('name')->toArray();
        $divisions = $this->_divisions;

        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $this->out('Processing terms for academic years greater than or equal 2016-2017');
        foreach ($data as $row) {

            if($row['terms_yearid'] < 26 || !isset($divisions[$row['terms_schoolid']])){
                continue;
            }

            $yearName = (2000+$row['terms_yearid']-10)."_".(2000+$row['terms_yearid']-9);
            
            $this->out('Found term '.$row['terms_name']." for ".$yearName);

            $data = [
                'name' => $row['terms_name'],
                'start_date' => new FrozenDate($row['terms_firstday']),
                'end_date' =>  new FrozenDate($row['terms_lastday']),
                'academic_year_id' => $academicYears[$yearName]->id,
                'division_id' => $divisions[$row['terms_schoolid']]
            ];

            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row['terms_id'].$row['terms_schoolid'])->where(['new_table_name' => 'terms'])->first();

            if($powerSchoolHash){
                $this->out('Term already exists on new db. Value will be updated.');
                $term = $this->Terms->findById($powerSchoolHash->new_id)->first();
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
            if(!$powerSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => 'terms',
                    'old_id' => $row['terms_id'].$row['terms_schoolid'],
                    'new_id' => $term->id,
                    'new_table_name' => 'terms'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." terms have been added to the system and ".$updateCount." have been updated"); 
    }

    public function SyncUsers($role){
        
        $this->out("In sync Users for ".$role.'s');
        $this->loadModel('Users');

        $roleData = [
            'teacher' => [
                'id' => 3,
                'file_path' => 'powerSchool/LB_TeacherUsers',
                'old_table' => 'users',
                'id_field' => 'users_dcid',
                'first_name_field' => 'users_first_name',
                'middle_name_field' => 'users_middle_name',
                'last_name_field' => 'users_last_name',
                'gender_field' => 'userscorefields_gender',
                'dob_field' => 'userscorefields_dob',
                'email_field' => 'users_email_addr',
                'legacy_id_field' => false
            ],
            'student' => [
                'id' => 4,
                'file_path' => 'powerSchool/LB_Students',
                'old_table' => 'students',
                'id_field' => 'students_dcid',
                'first_name_field' => 'students_first_name',
                'middle_name_field' => 'students_middle_name',
                'last_name_field' => 'students_last_name',
                'gender_field' => 'students_gender',
                'dob_field' => 'students_gender',
                'email_field' => false,
                'legacy_id_field' => 'students_student_number'
            ]
        ];

        $roleData = $roleData[$role];
        $data = $this->getCsvData('Users', $roleData['file_path']);
        if(!$data){
            return false;
        }
        
        $faker = Faker::create();
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $this->out('Processing '.$role.'s for '.$this->_school->name);
        foreach ($data as $row){
            $data = [
                'first_name' => $row[$roleData['first_name_field']],
                'last_name' => $row[$roleData['last_name_field']],
                'school_id' => $this->_schoolId,
            ];

            if($roleData['id'] == 4 && !isset($this->_grades[$row['students_grade_level']])){
                $this->out('invalid grade');
                continue;
            }


            $data['first_name'] = mb_convert_encoding($data['first_name'], 'utf-8');
            if(strpos($data['first_name'], '?')){
                $this->out('Unknown encoding first name');
                print_r($data);
                // continue;
            }

            $data['last_name'] = mb_convert_encoding($data['last_name'], 'utf-8');
            if(strpos($data['last_name'], '?')){
                $this->out('Unknown encoding last name');
                print_r($data);
                // continue;
            }

            if($roleData['dob_field'] && !in_array($row[$roleData['dob_field']], [null, false, ''])){
                $data['dob'] = $row[$roleData['dob_field']];
            }

            if($roleData['gender_field'] && !in_array($row[$roleData['gender_field']], [null, false, ''])){
                $data['gender'] = $row[$roleData['gender_field']] == 'M' ? 'Male' : 'Female';
            }

            if($roleData['legacy_id_field'] && !in_array($row[$roleData['legacy_id_field']], [null, false, ''])){
                $data['legacy_id'] = $row[$roleData['legacy_id_field']];
            }

            if(!in_array($row[$roleData['middle_name_field']], [false, null, ''])){
                $data['middle_name'] = $row[$roleData['middle_name_field']];
                $data['middle_name'] = mb_convert_encoding($data['middle_name'], 'utf-8');
                if(strpos($data['middle_name'], '?')){
                    $this->out('Unknown encoding middle name.');
                    print_r($data);
                    // continue;
                }
            }

            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row[$roleData['id_field']])->where(['new_table_name' => 'users', 'old_table_name' => $roleData['old_table']])->first();
            
            if($powerSchoolHash){
                
                if($roleData['email_field'] && !in_array($row[$roleData['email_field']], [false, null, ''])){
                    
                    $data['email'] = $row[$roleData['email_field']];
                }


                $this->out('User already exists on new db. Value will be updated.');
                $user = $this->Users->findById($powerSchoolHash->new_id)->first();
                if($roleData['id'] == 3 && !$row['users_psaccess'] && !$row['users_ptaccess']){
                    $this->out('Teacher has been removed from powerSchool. Deleting teacher.');
                    $this->_deleteTeacher($user);
                    continue;
                }
                $updateCount++;
            
            }else{

                if($roleData['id'] == 3 && !$row['users_psaccess'] && !$row['users_ptaccess']){
                    $this->out('Teacher has been removed from powerSchool. Ignoring value.');
                    continue;
                }

                if($roleData['email_field']){
                    $data['email'] = $row[$roleData['email_field']];
                }else{
                    $data['email'] = $faker->email;
                }

                $data['role_id'] = $roleData['id'];

                if($roleData['email_field'] && in_array($row[$roleData['email_field']], [false, null, ''])){
                    $this->out('No Email found. Creating user with dummy email having name '.$data['first_name'].' '.$data['last_name']);
                    $data['email'] = $faker->email;
                }
                
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
                if($data['role_id'] == 3){
                    $data['campus_teachers'] = [
                        [
                            'campus_id' => $this->_school->campuses[0]->id
                        ]
                    ]; 
                }
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
            if(!$powerSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => $roleData['old_table'],
                    'old_id' => $row[$roleData['id_field']],
                    'new_id' => $user->id,
                    'new_table_name' => 'users'
                ];
            }
        }

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." users have been added to the system and ".$updateCount." have been updated");
    }

    private function _deleteTeacher($user){
        $this->out('Deleting teacher and realted data.');
        $this->loadModel('Users');
        $this->loadModel('UnitTeachers');
        $this->loadModel('TeacherEvidences');
        $this->loadModel('PowerSchoolHash');
        $this->Users->CampusCourseTeachers->deleteAll(['teacher_id' => $user->id]);
        $this->Users->CampusTeachers->deleteAll(['teacher_id' => $user->id]);
        $this->Users->ReportTemplateStudentComments->deleteAll(['teacher_id' => $user->id]);
        $this->Users->Sections->SectionTeachers->deleteAll(['teacher_id' => $user->id]);
        $this->TeacherEvidences->deleteAll(['teacher_id' => $user->id]);
        $this->UnitTeachers->deleteAll(['teacher_id' => $user->id]);
        $this->Users->delete($user);
        $this->PowerSchoolHash->deleteAll(['new_id' => $user->id, 'old_table_name' => 'users']);
    }

    public function syncStudentEmails(){
        $this->out("In sync Student Emails");
        
        $this->loadModel('Users');
        $data = $this->getCsvData('Student Emails', 'powerSchool/LB_StudentContacts');
        if(!$data){
            return false;
        }
        $updateCount = 0;
        $this->out('Processing Student Emails');
        foreach ($data as $row) {

            $this->out("Processing a new entry.");

            if(in_array($row['email'], [null, false, ''])){
                $this->out('Email not found. Ignoring value.');
                continue;
            }

            $this->out('Searching for student on new db');
            
            $student = $this->Users->findByLegacyId($row['student_number'])->where(['role_id' => 4])->first();
            
            if(!$student){
                $this->out('Student not found on new db. Ignoring Value.');
                continue;
            } 

            $this->out('Student found on new db.');
            $data = ['email' => $row['email']];
            if($student->email == $row['email']){
                $this->out("Both values are same. Value will not be updated.");
                continue;
            }
            $this->out('Values are not same. Values will be updated.');

            $student = $this->Users->patchEntity($student, $data);
            if(!$this->Users->save($student, $data)){
                $this->out('Could not be updated.');
                continue;
            }
            $this->out('Student email has been updated.');
            $updateCount++;
        }

        $this->out($updateCount." Student emails were updated.");
    }

    public function syncCourses(){
        $this->out("In sync courses");
        $this->loadModel('Courses');
        $data = $this->getCsvData('Courses', 'powerSchool/LB_Courses');
        if(!$data){
            return false;
        }
        
        $divisionGrades = $this->Courses->Grades->DivisionGrades->find()->groupBy('division_id')->toArray();
        $grades = $this->Courses->Grades->find()->combine('id', 'name')->toArray();

        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $campusCourse = [
            'campus_id' => $this->_school['campuses'][0]->id
        ];
        $this->out('Processing courses');
        foreach ($data as $row) {

            $this->out('Found course '.$row['courses_course_name']);

            
            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row['courses_course_number'])->where(['new_table_name' => 'courses'])->extract('new_id')->toArray();
            $courses =[];
            if(!empty($powerSchoolHash)){
            
                $this->out('Course already exists on new db. Updating Value.');
                $courses = $this->Courses->find()->where(['id IN' => $powerSchoolHash])->toArray();
                
                $name = $row['courses_course_name'];
                $data = [
                    'name' => $name,
                    'description' => $name,
                ];

                foreach($courses as $key => $course){
                    $courses[$key] = $this->Courses->patchEntity($course, $data);    
                    $updateCount++;
                }
            
            }else{
                
                if(!isset($this->_divisions[$row['courses_schoolid']])){
                    $this->out('Invalid Division');
                    continue;
                }

                $query = "SELECT DISTINCT(grade_level) FROM `ps_courses` as c inner join ps_sections as s on c.course_id = s.course_id inner JOIN ps_cc as cc on cc.section_id = s.section_id inner join ps_students as st on cc.student_id = st.student_ccid where c.course_id = '".$row['courses_course_number']."'" ;
                $connection = ConnectionManager::get('default');
                $queryData = $connection->execute($query)->fetchAll('assoc');
                $name = $row['courses_course_name'];

                foreach ($queryData as $key => $tempCourse) {
                     $courses[] = [
                                'name' => $name,
                                'description' => $name,
                                'learning_area_id' => 1,
                                'campus_courses' => [$campusCourse],
                                'grade_id' => $this->_grades[$tempCourse['grade_level']]
                            ];
                    $addCount++;

                }
                if(empty($courses)){
                    $this->out("No data for course formed that can be saved.");
                    continue;
                }
                $courses =  $this->Courses->newEntities($courses);
            }

            if(!$this->Courses->saveMany($courses)){
                $this->out('Courses could not be saved.');
                continue;
            }

            $this->out('Courses has been saved.');
            $this->addContentCategoryToCourses($courses);
            if(empty($powerSchoolHash)){
                foreach ($courses as $value) {
                    $hashEntries[] = [
                        'old_table_name' => 'courses',
                        'old_id' => $row['courses_course_number'],
                        'new_id' => $value->id,
                        'new_table_name' => 'courses'
                    ];
                }
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." courses have been added to the system and ".$updateCount." have been updated"); 
    }

    public function addContentCategoryToCourses($courses){
        foreach ($courses as $value) {
            $this->addCourseContentCategories($value);
        }
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
            $courseContentCategories[] = ['course_id' => $course->id, 'content_category_id' => $contentCategoryId];
        }

        $courseContentCategories = $this->CourseContentCategories->newEntities($courseContentCategories);
        if(!$this->CourseContentCategories->saveMany($courseContentCategories)){
            $this->out("Course content categories could not be saved.");
            return;
        }

        $this->out("Course content categories have been saved.");
    }

    public function syncSections(){
        $this->out("In sync Sections");
        $this->loadModel('Sections');
        $data = $this->getCsvData('Sections', 'powerSchool/LB_Sections');
        if(!$data){
            return false;
        }

        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        
        $this->out('Processing sections.');
        foreach ($data as $row) {

            $this->out('Processing section'.$row['sections_section_number']);

            if(!isset($this->_divisions[$row['sections_schoolid']])){
                $this->out('Invalid Division. Ignoring value.');
                continue;
            }
            
            $data = [
                'name' => $row['sections_section_number'],
            ];

            $this->out('Finding Course having id '.$row['sections_course_number']);


            $query = "SELECT DISTINCT(grade_level) FROM ps_sections as s  inner JOIN ps_cc as cc on cc.section_id = s.section_id inner join ps_students as st on cc.student_id = st.student_ccid where s.section_id = '".$row['sections_dcid']."' order by grade_level DESC LIMIT 0,1" ;
            $connection = ConnectionManager::get('default');
            $queryData = $connection->execute($query)->fetchAll('assoc');
            if(empty($queryData)) {
            $this->out('corresponding grade not found');   
            continue; 
            }
            
            $coursePowerSchoolHash = $this->PowerSchoolHash->findByOldId($row['sections_course_number'])->where(['new_table_name' => 'courses'])->extract('new_id')->toArray();
            
            
            $course = $this->Sections->Courses->find()->where(
                [
                    'id IN' => $coursePowerSchoolHash,
                    'grade_id' => $this->_grades[$queryData[0]['grade_level']],
            ])->first();
             
            if(!$course){
                // $gradeIds = $this->Sections->Courses->find()->where(['id IN' => $coursePowerSchoolHash ])->extract('grade_id')->toArray();
                // $this->out('Course not found with grade id '.$this->_grades[$row['sections_grade_level']].'. Ignoring value. But coures with same name was found for grades. '.implode(',', $gradeIds));
                $this->out('Course not found on new db. Ignoring value.');
                continue;
            }

            $data['course_id'] = $course->id;

            $this->out('Finding Term having id on old db '.$row['sections_termid'].$row['sections_schoolid']);
            
            $termPowerSchoolHash = $this->PowerSchoolHash->findByOldId($row['sections_termid'].$row['sections_schoolid'])->where(['new_table_name' => 'terms'])->first();
            
            if(!$termPowerSchoolHash){
                $this->out('Term not found on new db. Ignoring Value.');
                continue;
            }
            $data['term_id'] = $termPowerSchoolHash->new_id;

            $this->out('Finding Teacher');
            $psTeacherQuery = "SELECT * FROM ps_teachers where teacher_id = ".$row['sections_teacher'];
            $psTeacher = $connection->execute($psTeacherQuery)->fetch('assoc');
            if(!$psTeacher){
                $this->out('Teacher not found on new db. Ignoring Value.');
                continue;
            }
            $teacherPowerSchoolHash = $this->PowerSchoolHash->findByOldId($psTeacher['user_id'])->where(['old_table_name' => 'users'])->first();
            
            if(!$teacherPowerSchoolHash){
                $this->out('Teacher not found on new db. Ignoring Value.');
                continue;
            }
            $data['teacher_id'] = $teacherPowerSchoolHash->new_id;
            $data['section_teachers'] = [['teacher_id' => $data['teacher_id']]];

            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row['sections_dcid'])->where(['new_table_name' => 'sections'])->first();

            if($powerSchoolHash){
            
                $this->out('Section already exists on new db. Updating Value.');
                $section = $this->Sections->findById($powerSchoolHash->new_id)->first();
                $updateCount++;
            
            }else{
                
                $section =  $this->Sections->newEntity();
                $addCount++;
            }

            $section = $this->Sections->patchEntity($section, $data);
            
            if(!$this->Sections->save($section)){
                $this->out('Year could not be saved.');
                $this->out($section);
                continue;
            }

            $this->out('Section has been saved.');
            if((int) $row['sections_grade_level'] != 0){
                $this->out("Will check if course is associated with the corresponding grade.");
                $courseGrade = $this->Sections->Courses->CourseGrades->findByCourseId($course->id)->where(['grade_id' => $this->_grades[$row['sections_grade_level']]])->first();
                if(!$courseGrade){
                    $courseGrade = ['course_id' => $course->id, 'grade_id' => $this->_grades[$row['sections_grade_level']]];
                    $courseGrade = $this->Sections->Courses->CourseGrades->newEntity($courseGrade);
                    $courseGrade = $this->Sections->Courses->CourseGrades->save($courseGrade);
                }
            }
            if(!$powerSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => 'sections',
                    'old_id' => $row['sections_dcid'],
                    'new_id' => $section->id,
                    'new_table_name' => 'sections'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." sections have been added to the system and ".$updateCount." have been updated"); 
    }

    public function syncSectionStudents(){
        
        $this->out("In sync Section Students");
        
        $this->loadModel('SectionStudents');
        $data = $this->getCsvData('Section Students', 'powerSchool/LB_CC');
        if(!$data){
            return false;
        }
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $deleteCount = 0;
        $count = 0;
        $this->out('Processing Section Students');
        foreach ($data as $row) {
            $count++;
            $this->out("Count : ".$count);
            if(!isset($this->_divisions[$row['cc_schoolid']])){
                $this->out('Invalid Division');
                continue;
            }

            $this->out('Forming data for a Section Student');

            $sectionsPowerSchoolHash = $this->PowerSchoolHash->findByOldId($row['cc_sectionid'])->where(['old_table_name' => 'sections'])->first();
            
            if(!$sectionsPowerSchoolHash){
                $this->out('Section not found on new db. Ignoring Value.');
                continue;
            }


            $query = "SELECT * FROM ps_students as s where  s.student_ccid = ".$row['cc_studentid'] ;
            $connection = ConnectionManager::get('default');
            $queryData = $connection->execute($query)->fetch('assoc');

            if(!$queryData || empty($queryData)){
                $this->out('Student not found in temp students table. Ignoring Value.');
                continue;
            }

            $studentsPowerSchoolHash = $this->PowerSchoolHash->findByOldId($queryData['student_id'])->where(['old_table_name' => 'students'])->first();
            
            if(!$studentsPowerSchoolHash){
                $this->out('Student not found on new db. Ignoring Value.');
                continue;
            }

            $data = [
                'section_id' => $sectionsPowerSchoolHash->new_id,
                'student_id' => $studentsPowerSchoolHash->new_id
            ];

            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row['cc_dcid'])->where(['new_table_name' => 'section_students'])->first();

            if($powerSchoolHash){
                
                $sectionStudent = $this->SectionStudents->findById($powerSchoolHash->new_id)->first();
                if($row['cc_sectionid'] < 0){
                    $this->out('Section Student exists on new db. Student has been withdrawn. Deleting Value.');
                    $this->SectionStudents->delete($sectionStudent);
                    $this->PowerSchoolHash->delete($powerSchoolHash);
                    $deleteCount++;
                    continue;
                }
                $this->out('Section Student already exists on new db. Updating Value.');
                $updateCount++;
            
            }else{
                if($row['cc_sectionid'] < 0){
                    $this->out('Student has been withdrawn. Value will not be added.');
                    continue;
                }
                $sectionStudent =  $this->SectionStudents->newEntity();
                $addCount++;
            }

            $sectionStudent = $this->SectionStudents->patchEntity($sectionStudent, $data);
            if(!$this->SectionStudents->save($sectionStudent)){
                $this->out('Section Student could not be saved.');
                $this->out($sectionStudent);
                continue;
            }

            $this->out('Section Student has been saved.');
            if(!$powerSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => 'cc',
                    'old_id' => $row['cc_dcid'],
                    'new_id' => $sectionStudent->id,
                    'new_table_name' => 'section_students'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." section students have been added to the system, ".$updateCount." have been updated and ".$deleteCount." have been deleted."); 
    }

    public function syncSectionTeachers(){
        $this->out("In sync Section teachers");
        $connection = ConnectionManager::get('default');
        
        $this->loadModel('SectionTeachers');
        $data = $this->getCsvData('Terms', 'powerSchool/LB_SectionTeachers');
        if(!$data){
            return false;
        }
        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $this->out('Processing Section teachers');
        foreach ($data as $row) {

            $this->out('Forming data for a Section Teacher');

            $sectionsPowerSchoolHash = $this->PowerSchoolHash->findByOldId($row['sectionteacher_sectionid'])->where(['old_table_name' => 'sections'])->first();
            
            if(!$sectionsPowerSchoolHash){
                $this->out('Section not found on new db. Ignoring Value.');
                continue;
            }
            
            $psTeacherQuery = "SELECT * FROM ps_teachers where teacher_id = ".$row['sectionteacher_teacherid'];
            $psTeacher = $connection->execute($psTeacherQuery)->fetch('assoc');
            if(!$psTeacher){
                $this->out('Teacher not found on new db. Ignoring Value.');
                continue;
            }
            $teachersPowerSchoolHash = $this->PowerSchoolHash->findByOldId($psTeacher['user_id'])->where(['old_table_name' => 'users'])->first();
            
            if(!$teachersPowerSchoolHash){
                $this->out('Teacher not found on new db. Ignoring Value.');
                continue;
            }

            $data = [
                'section_id' => $sectionsPowerSchoolHash->new_id,
                'teacher_id' => $teachersPowerSchoolHash->new_id
            ];

            $powerSchoolHash = $this->PowerSchoolHash->findByOldId($row['sectionteacher_id'])->where(['new_table_name' => 'section_teachers'])->first();

            if($powerSchoolHash){
            
                $this->out('Section Teacher already exists on new db. Updating Value.');
                $sectionTeacher = $this->SectionTeachers->findById($powerSchoolHash->new_id)->first();
                $updateCount++;
            
            }else{
                
                $sectionTeacher =  $this->SectionTeachers->newEntity();
                $addCount++;
            }

            $sectionTeacher = $this->SectionTeachers->patchEntity($sectionTeacher, $data);
            if(!$this->SectionTeachers->save($sectionTeacher)){
                $this->out('Section Teacher could not be saved.');
                $this->out($sectionTeacher);
                continue;
            }

            $this->out('Section Teacher has been saved.');
            if(!$powerSchoolHash){
                $hashEntries[] = [
                    'old_table_name' => 'section_teachers',
                    'old_id' => $row['sectionteacher_id'],
                    'new_id' => $sectionTeacher->id,
                    'new_table_name' => 'section_teachers'
                ];
            }
        }  

        $this->_saveHashEntries($hashEntries);
        $this->out($addCount." section teachers have been added to the system and ".$updateCount." have been updated"); 
    }

    public function createCampusCourseTeachers(){
        $this->loadModel('CampusCourseTeachers');
        $sections = $this->CampusCourseTeachers->Teachers->Sections->find()->toArray();

        foreach ($sections as $key => $value) {
            
            $campusCourse = $this->CampusCourseTeachers
                                 ->CampusCourses
                                 ->find()
                                 ->where([
                                            'campus_id' => $this->_school['campuses'][0]->id,
                                            'course_id' => $value->course_id,
                                        ])
                                 ->first();

            $data = [
                'campus_course_id' => $campusCourse->id,
                'teacher_id' => $value->teacher_id 
            ]; 

            $campusCourseTeacher = $this->CampusCourseTeachers->find()->where($data)->first();
            if($campusCourseTeacher){
                continue;
            } 

            $campusCourseTeacher = $this->CampusCourseTeachers->newEntity($data);
            if(!$this->CampusCourseTeachers->save($campusCourseTeacher)){
                $this->out('campusCourseTeacher could not be saved.');
                $this->out($campusCourseTeacher);
                continue;
            }

            $this->out("Campus course teacher has been saved.");
        }
        $this->out('All Campus course teachers have been migrated.');
    }

    public function migrateData($schoolId){
        $this->out('In Power school migrate data shell.');
        $this->Schools = $this->loadModel('Schools');
        $this->PowerSchoolHash = $this->loadModel('PowerSchoolHash');
        if(in_array($schoolId, [null, false, ''])){
            $this->out('School id is required');
            return false;
        }

        $this->_schoolId = $schoolId;   

        $this->_getSchool();

        $this->migrateLearningAreas();
        $this->migrateStrands();
        $this->migrateStandards();
        // $this->migrateImpacts();

    }

    public function migrateLearningAreas(){
     
        $this->out("In migrate learning areas.");
        $this->loadModel('LearningAreas');
        $data = $this->getCsvData('LearningAreas', 'powerSchool/LB_Standards');
        if(!$data){
            return false;
        }
        $hashEntries = [];
        $addCount = 0;
        $updateCount = 0;
        $this->out('Processing Learning Areas.');
        foreach ($data as $row) {
            

            $this->out('Processing entry - '.$row['name']);
            if(isset($row['parentstandardidentifier']) && !in_array($row['parentstandardidentifier'], [null, false, ""])){
                $this->out('Invalid Identifier. Ignoring value.');
                continue;
            }

            $data = [
                'curriculum_id' => 1,
                'name' => $row['name'],
                'description' => $row['description']
            ];

            $powerSchoolHash = $this->PowerSchoolHash->find()->where(['old_id'=>$row['identifier'],'new_table_name'=>'learning_areas'])->first();

            if($powerSchoolHash){

                $this->out('Learning Area already exists, new Learning Area will not be created.');
                $learningArea =  $this->LearningAreas->findById($powerSchoolHash->new_id)->first();
                $updateCount++;

            }else{

                $this->out('Learning area found. Name is '.$row['name']);
                $learningArea = $this->LearningAreas->newEntity();
                $addCount++;
                
            }
            $learningArea = $this->LearningAreas->patchEntity($learningArea, $data);

            if(!$this->LearningAreas->save($learningArea)){
                print_r($learningArea);
                $this->out('Learning area could not be saved.');
                continue;
            }

            $this->out('Learning area has been saved.');
            if(!$powerSchoolHash){

                $hashEntry = [
                    'old_table_name' => 'standards',
                    'old_id' => $row['identifier'],
                    'new_id' => $learningArea->id,
                    'new_table_name' => 'learning_areas'
                ];

                if(!$this->PowerSchoolHash->save($hashEntry)){
                    $this->out("Hash entry could not be saved");
                    $this->out($hashEntry);
                    return false;
                }
            }
        }

        $this->out($addCount.'Learning areas have been saved.');
        $this->out($updateCount.'Learning areas have been updated.');
    }

    public function migrateStrands(){

        $this->out("In migrate Strands.");
        $this->loadModel('Strands');
        $data = $this->getCsvData('Strands', 'powerSchool/LB_Standards');
        if(!$data){
            return false;
        }
        
        $hashEntries = [];
        $addCount = 0;
        $updateCount = 0;
        $this->out('Processing Strands.');
        foreach ($data as $row) {
            
            if(!isset($row['parentstandardidentifier']) || in_array($row['parentstandardidentifier'], [null, false, ""])){
                continue;
            }

            $learningArea = $this->PowerSchoolHash->findByOldId($row['parentstandardidentifier'])
                                                  ->where(['new_table_name' => 'learning_areas'])
                                                  ->first();

            if(!$learningArea){
                $this->out('Learning Area not found. Ignoring value.');
                continue;
            }

            $this->out('Strand found. Name is '.$row['name']);

            $data = [
                'learning_area_id' => $learningArea->new_id,
                'name' => $row['name'],
                'description' => $row['description'],
                'code' => $row['identifier']
            ];


            $powerSchoolHash = $this->PowerSchoolHash->find()->where(['old_id'=>$row['identifier'],'new_table_name'=>'strands'])->first();

            if($powerSchoolHash){

                $this->out('Strand already exists, new strand will not be created.');
                $strand =  $this->Strands->findById($powerSchoolHash->new_id)->first();
                $updateCount++;

            }else{

                if(!in_array($row['primarycourses'], [null, false, ''])){
                    $this->out('Finding Courses for course_strands');
                    $courseArray = explode(',', $row['primarycourses']);
                    $data['course_strands'] = $this->PowerSchoolHash->findByNewTableName('courses')->select(['course_id' => 'new_id'])->where(['old_id IN' => $courseArray])->map(function($value, $key){
                        return $value->toArray();
                    })->toArray();

                    if(empty($data['course_strands'])){
                        $this->out('No Courses found on new db.');
                        unset($data['course_strands']);
                    }
                }
                $strand = $this->Strands->newEntity();
                $addCount++;
            }

            $strand = $this->Strands->patchEntity($strand, $data);

            if(!$this->Strands->save($strand)){
                print_r($strand);
                $this->out('Strand could not be saved.');
                continue;
            }

            $this->out('Strand has been saved.');
           
           if(!$powerSchoolHash){ 
                $hashEntry = [
                           'old_table_name' => 'standards',
                           'old_id' => $row['identifier'],
                           'new_id' => $strand->id,
                           'new_table_name' => 'strands'
                       ];
                if(!$this->PowerSchoolHash->save($hashEntry)){
                    $this->out("Hash entry could not be saved");
                    $this->out($hashEntry);
                    return false;
                }
            }
        }

        $this->fixCourseStrands();
        $this->out($addCount.'Strands have been saved.');
        $this->out($updateCount.'Strands have been updated.');

    }

    public function fixCourseStrands(){
        $this->loadModel('CourseStrands');
        $courseStrands = $this->CourseStrands->find()->contain(['Courses'])->all()->toArray();
        if(empty($courseStrands)){
            $this->out('No Course strands found.');
            return;
        }

        foreach ($courseStrands as $key => $value) {
            $courseStrands[$key]->grade_id = $value->course->grade_id;
        }

        if(!$this->CourseStrands->saveMany($courseStrands)){
            $this->out('courseStrands could not be saved.');
            print_r($courseStrands);
            return false;
        }

        $this->out('courseStrands have been be saved.');
    }

    public function migrateStandards(){
        $this->out("In migrate Standards.");
        $this->loadModel('Standards');
        $data = $this->getCsvData('Standards', 'powerSchool/LB_Standards');
        if(!$data){
            return false;
        }
        
        $hashEntries = [];
        $addCount = 0;
        $updateCount = 0;
        $this->out('Processing Standards.');
        foreach ($data as $row) {
            
            if(!isset($row['parentstandardidentifier']) || in_array($row['parentstandardidentifier'], [null, false, ""])){
                continue;
            }

            $learningArea = $this->PowerSchoolHash->findByOldId($row['parentstandardidentifier'])
                                                  ->where(['new_table_name' => 'learning_areas'])
                                                  ->first();

            if($learningArea){
                continue;
            }

            $data = [
                'name' => $row['name'],
                'description' => $row['description'],
                'code' => $row['identifier'],
                'is_selectable' => 1
            ];

            $this->out('Finding strand');
            $strand = $this->Standards->Strands->findByCode($row['parentstandardidentifier'])
                                              ->first();

            if($strand){
                $this->out('Stand Found. It is a root level standard.');
                $data['strand_id'] = $strand->id;
            }else{
                $this->out('Strand not found. Not a root level standard. Findiinf Parent.');
                $parent = $this->Standards->findByCode($row['parentstandardidentifier'])->first();
                if($parent){
                    $this->out('Found parent');
                    $data['parent_id'] = $parent->id;
                    $data['strand_id'] = $parent->strand_id;
                }else{
                    $this->out('Parent Not found. standard Wont be saved.');
                    continue;
                }

            }

            if(!in_array($row['primarycourses'], [null, false, ''])){
                $this->out('Finding Courses for standard grades.');
                $courseArray = explode(',', $row['primarycourses']);
                $courseIds = $this->PowerSchoolHash->findByNewTableName('courses')->where(['old_id IN' => $courseArray])->extract('new_id')->toArray();
                if(!empty($courseIds)){
                    $gradeIds = $this->Standards->StandardGrades
                                                    ->Grades->Courses
                                                    ->find()
                                                    ->where(['id IN' => $courseIds])
                                                    ->extract('grade_id')
                                                    ->toArray();

                    

                    $data['standard_grades'] =  [];
                    foreach (array_unique($gradeIds) as $grade) {
                        if(in_array($grade, $this->_grades)){
                            $data['standard_grades'][] = ['grade_id' => $grade];
                        }
                    }

                }else{
                    $this->out('No courses found on new db.');
                }
            }

            $this->out('Standard processed found. Name is '.$row['name']);

            $powerSchoolHash = $this->PowerSchoolHash->find()->where(['old_id'=>$row['identifier'],'new_table_name'=>'standards'])->first();

            if($powerSchoolHash){

                $this->out('Standard already exists, new Strandard will not be created.');
                $standard =  $this->Standards->findById($powerSchoolHash->new_id)->first();
                $updateCount++;

            }else{

                $standard = $this->Standards->newEntity();
                $addCount++;
            }

            $standard = $this->Standards->patchEntity($standard, $data);

            if(!$this->Standards->save($standard)){
                print_r($standard);
                $this->out('Standard could not be saved.');
                continue;
            }

            $this->out('Strandard has been saved.');
            if(!$powerSchoolHash){
                $hashEntry = [
                    'old_table_name' => 'standards',
                    'old_id' => $row['identifier'],
                    'new_id' => $standard->id,
                    'new_table_name' => 'standards'
                ];

                if(!$this->PowerSchoolHash->save($hashEntry)){
                    $this->out("Hash entry could not be saved");
                    $this->out($hashEntry);
                    return false;
                }
                
            }
        }

        $this->out($addCount.' standards have been saved.');
        $this->out($updateCount.'Standards have been updated.');

    }

    public function migrateImpacts(){
        $this->out("In migrate Impacts.");
        $this->loadModel('Impacts');
        $data = $this->getCsvData('Impacts', 'powerSchool/LB_Impacts');
        if(!$data){
            return false;
        }
        $impactCategories = [];
        $gradeImpactsMap = [];
        $count = 0;

        $grades = [1 => 'PK', 2 => 'K', 3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 7, 10 => 8, 11 => 9, 12 => 10, 13 => 11, 14 => 12];
        
        $this->out('Processing Impacts.');  
        foreach ($data as $key => $row) {

            $this->out('New Impact found. Name : '.$row['performance_indicator']);
            $parentName = $row['performance_area'];
            $start = strrpos($parentName, '(') + 1;
            $end = strrpos($parentName, ')');
            $impactType = substr($parentName, $start,  $end-$start);
            if(!isset($impactCategories[$row['educational_aim']])){
                $this->out('Impact Category not found creating new.');
                $impactCategory = [
                    'name' => $row['educational_aim'],
                    'description' => $row['educational_aim']
                ];

                $impactCategory = $this->Impacts->ImpactCategories->newEntity($impactCategory);

                if(!$this->Impacts->ImpactCategories->save($impactCategory)){
                    $this->out('Impact category could not be saved. Ignoring Value.');
                    continue;
                }
                $impactCategory['impacts'] = [];
                $impactCategories[$row['educational_aim']] = $impactCategory;
            }

            $impactCategory = $impactCategories[$row['educational_aim']];
            $this->out('Impact Category found');
            
            if(!isset($impactCategory['impacts'][$parentName])){
                $this->out('Parent not found creating new.');
                $parent = [
                    'name' => $parentName,
                    'description' => $parentName,
                    'impact_category_id' => $impactCategory->id,
                    'is_selectable' => 1,
                    'impact_type' => $impactType
                ];

                $parent = $this->Impacts->newEntity($parent);

                if(!$this->Impacts->save($parent)){
                    $this->out('Parent could not be saved. Ignoring Value.');
                    continue;
                }


                $impactCategory['impacts'][$parentName] = $parent;

                $impactCategories[$impactCategory->name] = $impactCategory;
            }

            $parent = $impactCategory['impacts'][$parentName];
            $this->out('Parent found');

            $impact = [
                'name' => $row['performance_indicator'],
                'description' => $row['performance_indicator'],
                'impact_category_id' => $impactCategory->id,
                'is_selectable' => 0,
                'impact_type' => $impactType,
                'parent_id' => $parent->id
            ];

            $impact = $this->Impacts->newEntity($impact);

            if(!$this->Impacts->save($impact)){
                $this->out('Impact could not be saved. Ignoring Value.');
                continue;
            }
            $this->out('Impact has been saved. Extracting grade impacts.');
            $count++;
            $gradeIds = [];
            $startGrade = array_search($row['start_grade'], $grades);
            $endGrade = array_search($row['end_grade'], $grades);
            for ($i=$startGrade; $i <= $endGrade; $i++) { 
                $gradeIds[] = $i;
            }

            $gradeImpactsMap[$impact->id] = $gradeIds;

            if(!isset($gradeImpactsMap[$parent->id])){
                $gradeImpactsMap[$parent->id] = [];
            }

            $gradeImpactsMap[$parent->id] = array_merge($gradeIds, $gradeImpactsMap[$parent->id]);
        }

        $this->out($count.' Impacts have been saved. Processing Grade Impacts.');

        $gradeImpacts = [];
        foreach ($gradeImpactsMap as $impactId => $value) {
            $value = (new Collection(array_unique($value)))->map(function($gradeId, $key) use ($impactId){
                return ['grade_id' => $gradeId, 'impact_id' => $impactId];
            })->toArray();
            $gradeImpacts = array_merge($gradeImpacts, $value);
        }

        $gradeImpacts = $this->Impacts->GradeImpacts->newEntities($gradeImpacts);
        if(!$this->Impacts->GradeImpacts->saveMany($gradeImpacts)){
            $this->out('Grade Impacts could not be saved.');
            return;
        }

        $this->out('Grade Impacts have been saved.');
    }
}

