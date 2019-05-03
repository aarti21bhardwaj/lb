<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Collection\Collection;
use Cake\Utility\Text;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use Faker\Factory as Faker;
use Cake\Network\Exception\BadRequestException;

/**
 * UploadData shell command.
 */
class UploadDataShell extends Shell
{

    public function initialize(){
        $this->Schools = $this->loadModel('Schools');
    }

    public function getCsvData($tableName){
        
        $fileName = $this->in("Please enter the file name without the extension for ".$tableName);
        while(in_array($fileName, [null, false, ""])){
          $this->out('Filename not entered please try again.');
          $fileName = $this->in("Please enter the file name without the extension for ".$tableName);
        }

        $fileName = $fileName.'.csv';

        if (!file_exists($fileName) ) {
            $this->out('File not found.');
            return false;
        }
        
        $fp = fopen($fileName,'r');

        while (($row = fgetcsv($fp))) {
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

    public function uploadTerms($schoolId = null){

        $this->out("In Upload terms");
        $data = $this->getCsvData('Terms');
        if(!$data){
            return false;
        }
        
        $school = $this->Schools->findById($schoolId)->contain(['AcademicYears', 'Campuses.Divisions'])->first();
        if(!$school){
            $this->out("School not found.");
            return false;
        }
        if(empty($school->academic_years)){
            $this->out("Academic Years not found.");
            return;
        }
        if(empty($school->campuses)){
            $this->out("No Campus found for this school.");
            return;
        }
        $academicYears = (new Collection($school->academic_years))->indexBy('name')->toArray();
        $campuses = (new Collection($school->campuses))->indexBy('name')
                                                       ->map(function($value, $key){
                                                            if(empty($value->divisions)){
                                                                return [];
                                                            }
                                                            return (new Collection($value->divisions))->indexBy('name')->toArray();
                                                        })->toArray();
        $terms = [];
        $startDate = new FrozenDate("2017-01-01");
        $endDate = new FrozenDate("2018-12-31");

        foreach ($data as $key => $value) {
            $this->out("Value to be transformed.");
            print_r($value);

            if(!isset($academicYears[$value['academic_year']])){
                $this->out("Ignoring value as academic year is not present in school.");
                continue;
            }

            if(!isset($campuses[$value['campus_name']][$value['division_name']])){
                $this->out("Ignoring value as corresponding campus or division is not present in school.");
                continue;
            }


            $terms[] = [
                'name' => $value['term'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'division_id' => $campuses[$value['campus_name']][$value['division_name']]->id,
                'academic_year_id' => $academicYears[$value['academic_year']]->id
            ]; 

        }
        print_r($terms);
        if(!count($terms)){
            $this->out('No terms found');
            return false;
        }

        $terms = $this->saveData('Terms', [], $terms);
        if($terms){
            $this->out('Terms have been saved.');
        }
        return $terms;

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


    public function uploadUsers($schoolId, $roleId){
        
        $this->out("In Upload Users");

        $this->loadModel('Users');
        $role = $this->Users->Roles->findById($roleId)->first();
        
        if(!$role){
            $this->out('Role not found');
            return false;
        }
      
        $associated = [];
        if($roleId == 3){
            $associated =  ['CampusTeachers'];
        }

        $data = $this->getCsvData($role->label);
        if(!$data){
            return false;
        }
        
        $school = $this->Schools->findById($schoolId)->contain(['Campuses'])->first();
        if(!$school){
            $this->out("School not found.");
            return false;
        }

        if(empty($school->campuses)){
            $this->out("No Campus found for this school.");
        }
        $campuses = (new Collection($school->campuses))->indexBy('name')
                                                       ->toArray();

        $faker = Faker::create();

        foreach ($data as $key => $value) {
            if(!isset($value['first_name']) || in_array($value['first_name'], [null, false, ''])){
                unset($data[$key]);
                continue;
            }

            if(!isset($value['middle_name']) || in_array($value['middle_name'], [null, false, ''])){
                unset($data[$key]['middle_name']);
            }
            if(!isset($value['email']) || in_array($value['email'], [null, false, ''])){
                $data[$key]['email'] = $faker->email;
            }
            if(!isset($value['password']) || in_array($value['password'], [null, false, ''])){
                $data[$key]['password'] = "123456789";
            }
            if(!isset($value['dob']) || in_array($value['dob'], [null, false, ''])){
                unset($data[$key]['dob']);
            }
            if(!isset($value['gender']) || in_array($value['gender'], [null, false, ''])){
                unset($data[$key]['gender']);
            }
            if(!isset($value['legacy_id']) || in_array($value['legacy_id'], [null, false, ''])){
                unset($data[$key]['legacy_id']);
            }

            $data[$key]['role_id'] = $roleId;
            $data[$key]['school_id'] = $schoolId;
            if(isset($value['campus_name']) && isset($campuses[$value['campus_name']])){ 
                $data[$key]['campus_teachers'] = [['campus_id' => $campuses[$value['campus_name']]->id]];
            }
        }

        print_r($data);
        if(!count($data)){
            $this->out('No Users found');
            return false;
        }

        $users = $this->saveData('Users', $associated, $data);
        if($users){
            $this->out('Users have been saved.');
        }
        return $users;
    }

    public function uploadLearningAreas(){

        $this->out('In upload learning areas');
        $this->loadModel('Curriculums');
        $curriculums = $this->Curriculums->find()->indexBy('name')->toArray();
        if(empty($curriculums)){
            $this->out('No curriculums found');
            return false;
        }

        $data = $this->getCsvData('Learning Areas');
        if(!$data){
            return false;
        }

        $learningAreas = [];
        foreach ($data as $key => $value) {
            $this->out("Value to be transformed.");
            print_r($value);

            if(!isset($curriculums[$value['name_of_provider']])){
                $this->out("Ignoring value as Curriculum was not found.");
                continue;
            }

            $learningAreas[] = [
                            
                            'curriculum_id' => $curriculums[$value['name_of_provider']]->id,
                            'name' => $value['subject_name'],
                            'description' => $value['subject_name'],
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

    public function uploadCourses($schoolId, $curriculumId){
        
        $this->out("In Upload courses");
        $data = $this->getCsvData('Courses');
        if(!$data){
            return false;
        }
        $school = $this->Schools->findById($schoolId)->contain(['Campuses'])->first();
        if(!$school){
            $this->out("School not found.");
            return false;
        }

        if(empty($school->campuses)){
            $this->out("No Campus found for this school.");
            return;
        }

        $campuses = (new Collection($school->campuses))->indexBy('name')->toArray();
        $this->loadModel('LearningAreas');
        $learningAreas = $this->LearningAreas->findByCurriculumId($curriculumId)->indexBy('name')->toArray();

        if(empty($learningAreas)){
            $this->out('No Learning Areas found.');
            return false;
        }

        $this->loadModel('Grades');
        $grades = $this->Grades->find()->indexBy('name')->toArray();

        if(empty($grades)){
            $this->out('No Grades found.');
            return false;
        }

        $campusCourses = [];
        foreach ($data as $key => $value) {
            $this->out("Value to be transformed.");
            print_r($value);

            if(!isset($value['grade']) || in_array($value['grade'], [null, false, '']) || !isset($grades[$value['grade']])){
                $this->out("Ignoring value as grade is invalid.");
                continue;
            }

            if(!isset($value['subject_name']) || in_array($value['subject_name'], [null, false, '']) || !isset($learningAreas[$value['subject_name']])){
                $this->out("Ignoring value as grade is invalid.");
                continue;
            }

            $course = $this->LearningAreas->Courses
                                          ->findByName($value['course_name'])
                                          ->where([
                                            'learning_area_id' => $learningAreas[$value['subject_name']]->id, 
                                            'grade_id' => $grades[$value['grade']]->id
                                          ])
                                          ->first();
            if(!$course){
                $this->out('Course not found. Creating new Course');
                $course = [
                            'name' => $value['course_name'],
                            'description' => $value['course_name'],
                            'learning_area_id' => $learningAreas[$value['subject_name']]->id,
                            'grade_id' => $grades[$value['grade']]->id,
                            'course_grades' => [['grade_id' => $grades[$value['grade']]->id]]
                          ];
                $course = $this->LearningAreas->Courses->newEntity($course);
                if(!$this->LearningAreas->Courses->save($course)){
                    $this->out('Course could not be created.');
                    continue;
                }
            }

            if(!isset($campuses[$value['campus_name']])){
                $this->out("Ignoring value as corresponding campus is not present in school.");
                continue;
            }

            $campusCourses[] = [
                            'campus_id' => $campuses[$value['campus_name']]->id,
                            'course_id' => $course->id,
                        ]; 
        }

        print_r($campusCourses);
        if(!count($campusCourses)){
            $this->out('No campus courses found');
            return false;
        }

        $campusCourses = $this->saveData('CampusCourses', [], $campusCourses);
        if($campusCourses){
            $this->out('Campus Courses have been saved.');
        }
        return $campusCourses;
    }

    public function uploadCourseTeachers($schoolId){

        $this->out("In upload course teachers");
        $data = $this->getCsvData('Course Teachers');
        if(!$data){
            return false;
        }

        $campusCourseTeachers = [];
        foreach ($data as $key => $value) {
            
            $this->out("Value to be transformed.");
            print_r($value);
            
            if(!isset($value["campus_name"]) || in_array($value['campus_name'], [false, null, ''])){
                $this->out("Ignoring value as campus name is invalid");
                continue;
            }

            if(!isset($value["course_name"]) || in_array($value['course_name'], [false, null, ''])){
                $this->out("Ignoring value as course name is invalid");
                continue;
            }

            if(!isset($value["teacher_name"]) || in_array($value['teacher_name'], [false, null, ''])){
                $this->out("Ignoring value as teacher name is invalid");
                continue;
            }

            $campusName = $value['campus_name'];
            $name = explode(' ', $value['teacher_name']);
            if(count($name) == 2){
                $whereCond = ['first_name' => $name[0], 'last_name' => $name[1], 'role_id' => 3];
            }else{
                $whereCond = ['first_name' => $name[0], 'middle_name' => $name[1], 'last_name' => $name[2], 'role_id' => 3];
            }
            
            $teacher = $this->Schools->Users->findBySchoolId($schoolId)
                                            ->where($whereCond)
                                            ->matching('Schools.Campuses', function($q) use($campusName){
                                                return $q->where(['Campuses.name' => $campusName]);
                                            })
                                            ->first();
            if(!$teacher){
                $this->out('Teacher not found');
                continue;
            }

            $courseName = $value['course_name'];
            $campusCourse = $this->Schools->Campuses
                                          ->CampusCourses
                                          ->find()
                                          ->matching('Campuses', function($q) use($campusName, $schoolId){
                                            return $q->where([
                                                                'Campuses.name' => $campusName, 
                                                                'school_id' => $schoolId
                                                            ]);
                                           })
                                          ->matching('Courses', function($q) use($courseName){
                                            return $q->where(['Courses.name' => $courseName]);
                                          })
                                          ->first();
            if(!$campusCourse){
                $this->out('campus Course not found');
                continue;
            }

            $campusCourseTeachers[] = [
                                        'teacher_id' => $teacher->id,
                                        'campus_course_id' => $campusCourse->id
                                      ];
        }

        print_r($campusCourseTeachers);
        if(!count($campusCourseTeachers)){
            $this->out('No campus course Teachers found');
            return false;
        }

        $campusCourseTeachers = $this->saveData('CampusCourseTeachers', [], $campusCourseTeachers);
        if($campusCourseTeachers){
            $this->out('Campus Courses Teachers have been saved.');
        }
        return $campusCourseTeachers;
    }

    public function uploadSections($campusId){

        $this->out('In upload sections');
        $this->loadModel('Users');
        $teachers = $this->Users->findByRoleId(3)
                                ->indexBy('full_name')
                                ->toArray();

        if(!$teachers || empty($teachers)){
            $this->out('No Teachers found');
            return false;
        }
// print_r($teachers);
        $this->loadModel('CampusCourses');
        $courses = $this->CampusCourses->findByCampusId($campusId)
                                        ->contain('Courses')
                                        ->indexBy('course.name')
                                        ->toArray();

        if(!$courses || empty($courses)){
            $this->out('No Courses found for this Campus');
            return false;
        }

        $data = $this->getCsvData('Sections');
        if(!$data){
            return false;
        }

        $sectionData = [];
        foreach ($data as $key => $value) {

            if(!isset($value['course_name']) || !isset($courses[$value['course_name']]) || empty($courses[$value['course_name']])){
                $this->out('Course '.$value['course_name'].' not found.');
                continue;
            }

            if(!isset($value['teacher_name']) || !isset($teachers[$value['teacher_name']]) || empty($teachers[$value['teacher_name']])){
                $this->out('Teacher '.$value['teacher_name'].' not found.');
                continue;
            }

            $duplicateEntry = false;
            foreach ($sectionData as $key2 => $value2) {
                if($value2['course_id'] == $courses[$value['course_name']]->course_id && $value2['name'] == $value['section_name']){
                    $sectionData[$key2]['section_teachers'][] = ['teacher_id' => $teachers[$value['teacher_name']]->id];
                    $duplicateEntry = true;
                }

            }

            if($duplicateEntry){
                continue;
            }

            $sectionData[] = [
                                'name' => $value['section_name'],
                                'course_id' => $courses[$value['course_name']]->course_id,
                                'teacher_id' => $teachers[$value['teacher_name']]->id,
                                'term_id' => $value['terms'],
                                'section_teachers' => [
                                                        [
                                                            'teacher_id' => $teachers[$value['teacher_name']]->id
                                                        ]
                                                      ]
                            ];
        }

        print_r($sectionData);
        if(!count($sectionData)){
            $this->out('No Sections found');
            return false;
        }

        $sections = $this->saveData('Sections', ['SectionTeachers'], $sectionData);
        if($sections){
            $this->out('Sections have been saved.');
        }
        return $sections;
    }

    public function uploadSectionStudents($schoolId, $campusId){

        $this->out('In upload section students');
        
        $this->loadModel('Sections');
        $sections = $this->Sections->find()
                                ->matching('Courses.CampusCourses', function($q) use ($campusId){
                                    return $q->where(['CampusCourses.campus_id' => $campusId]);
                                })
                                ->groupBy('name')
                                ->map(function($value, $key){
                                    return (new Collection($value))->extract('id')->toArray();
                                })
                                ->toArray();

        $this->loadModel('Users');
        $students = $this->Users->findBySchoolId($schoolId)
                                ->where(['role_id' => 4])
                                ->indexBy('full_name')
                                ->toArray();

        $data = $this->getCsvData('Section Students');
        if(!$data){
            return false;
        }

        $studentSectionData = [];
        foreach ($data as $key => $value) {
            foreach ($sections[$value['section_name']] as $k => $val) {

                if(!isset($students[$value['student_name']]) || empty($students[$value['student_name']])){
                    $this->out('Student '.$value['student_name'].' not found.' );
                    continue;
                }

                $studentSectionData[] = [
                                            'section_id' => $val,
                                            'student_id' => $students[$value['student_name']]->id
                                        ];
            }
        }

        print_r($studentSectionData);
        if(!count($studentSectionData)){
            $this->out('No Section students found');
            return false;
        }

        $sectionStudents = $this->saveData('SectionStudents', [], $studentSectionData);
        if($sectionStudents){
            $this->out('Section students have been saved.');
        }
        return $sectionStudents;
    }

    public function uploadStandards($curriculumId){

        $this->out("In upload standards");
        $data = $this->getCsvData('Strands & standards');
        if(!$data){
            return false;
        }

        $this->loadModel('Standards');

        $this->out("uploading Strands");
        $this->uploadStrands($curriculumId, $data);
        $this->out("strands have been uploaded");
        
        $strands = $this->Standards->Strands->find()
                                            ->matching('LearningAreas', function($q) use($curriculumId){
                                                return $q->where(['curriculum_id' => $curriculumId]);
                                            })
                                            ->indexBy('code')
                                            ->toArray();

        if(empty($strands)){
            $this->out('No strands found.');
            return false;
        }

        $grades = $this->Standards->StandardGrades->Grades->find()->indexBy('name')->toArray();

        $parentStandards = [];
        $childStandards = [];

        foreach ($data as $key => $value) {
            $this->out("Value to be transformed.");
            // print_r($value);

            if(!isset($value["strand"]) || in_array($value['strand'], [false, null, '']) || !isset($strands[$value['strand']])){
                $this->out("Ignoring value as strand code is invalid or not found");
                continue;
            }

            if(!isset($value["standard"]) || in_array($value['standard'], [false, null, ''])){
                $this->out("Ignoring value as parent standard code is invalid or not found");
                continue;
            }

            if(!isset($value["standard_name"]) || in_array($value['standard_name'], [false, null, ''])){
                $this->out("Ignoring value as parent standard name is invalid or not found");
                continue;
            }

            if(!isset($value["detail"]) || in_array($value['detail'], [false, null, ''])){
                $this->out("Ignoring value as child standard code is invalid or not found");
                continue;
            }

            if(!isset($value["standard_detail"]) || in_array($value['standard_detail'], [false, null, ''])){
                $this->out("Ignoring value as child standard name is invalid or not found");
                continue;
            }

            if(!isset($value["start_grade"]) || in_array($value['start_grade'], [false, null, ''])){
                $this->out("Ignoring value as start grade is invalid or not found");
                continue;
            }

            if(!isset($value["end_grade"]) || in_array($value['end_grade'], [false, null, ''])){
                $this->out("Ignoring value as end grade is invalid or not found");
                continue;
            }

            if(!isset($parentStandards[$value['standard']])){
                $this->out('Parent standard not found creating new parent standard.');
                $parentStandard = [
                    'name' => $value["standard_name"],
                    'description' => $value["standard_name"],
                    'strand_id' => $strands[$value['strand']]->id,
                    'code' => $value['standard']
                ];
                $parentStandard = $this->Standards->newEntity($parentStandard);
                if(!$this->Standards->save($parentStandard)){
                    $this->out('parent Standard could not be created. Ignoring value');
                    continue;
                }

                $this->out('parent Standard has been created.');
                $parentStandards[$parentStandard->code] = [
                    'id' => $parentStandard->id,
                    'grades' => [],
                ];
            }


            $this->out('Creating data for child standard');
            $childStandard = [
                'name' => $value["standard_detail"],
                'description' => $value["standard_detail"],
                'strand_id' => $strands[$value['strand']]->id,
                'code' => $value['detail'],
                'parent_id'=>  $parentStandards[$value['standard']]['id'],
                'standard_grades' => []
            ];

            $startGrade = $grades[$value['start_grade']]->id;
            $endGrade = $grades[$value['end_grade']]->id;

            for($x = $startGrade; $x <= $endGrade; $x++){
                
                $childStandard['standard_grades'][] = [
                    'grade_id' => $x
                ];

                if(!in_array($x, $parentStandards[$value['standard']]['grades'])){
                    $parentStandards[$value['standard']]['grades'][] = $x;
                }
            }

            $childStandards[] = $childStandard;
        }
        
        $this->out('Child standards');
        if(!count($childStandards)){
            $this->out('No Child found');
            
        }else{

            $childStandards = $this->saveData('Standards', ['StandardGrades'], $childStandards);
            if($childStandards){
                $this->out('Child Standards have been saved.');
            }
        }

        $this->out('Saving parent Standard Grades');

        foreach ($parentStandards as $standard) {
            if(empty($standard['grades'])){
                continue;
            }
            $standardGrades = [];
            foreach ($standard['grades'] as $value) {
                $standardGrades[] =  [
                    'grade_id' => $value,
                    'standard_id' => $standard['id']
                ];
            }
            $this->out('Saving standard grades for standard id '.$standard['id']);
            print_r($standardGrades);
            
            $standardGrades = $this->saveData('StandardGrades', [], $standardGrades);
            
            if($standardGrades){
                $this->out('Standard Grades have been saved.');
            }

        }

        return true;
    }

    public function uploadStrands($curriculumId, $data = null){

        $this->out("In upload strands");
        if(!$data){
            $data = $this->getCsvData('Strands');
            if(!$data){
                return false;
            }
        }

        $this->loadModel('Strands');
        $learningAreas = $this->Strands->LearningAreas->findByCurriculumId($curriculumId)
                                                      ->indexBy('name')
                                                      ->toArray();

        $grades = $this->Strands->Standards->StandardGrades->Grades->find()->indexBy('name')->toArray();

        $strands = [];
        $courseStrands = [];

        foreach ($data as $key => $value) {
            $this->out('Extracting value from following data.');
            // print_r($value);
            if(!isset($value["strand"]) || in_array($value['strand'], [false, null, ''])){
                $this->out("Ignoring value as strand code is invalid");
                continue;
            }
            if(!isset($value["strand_name"]) || in_array($value['strand_name'], [false, null, ''])){
                $this->out("Ignoring value as strand name is invalid");
                continue;
            }
            if(!isset($value["subject_name"]) || in_array($value['subject_name'], [false, null, '']) || !isset($learningAreas[$value['subject_name']])){
                $this->out("Ignoring value as subject name is invalid");
                continue;
            }

            if(in_array($value['strand'], $strands)){
                $this->out('strand already exists.');
                continue;
            }

            $strand = $this->Strands->findByCode($value['strand'])
                                    ->where([
                                                'name' => $value["strand_name"],
                                                'learning_area_id' => $learningAreas[$value['subject_name']]->id 
                                            ])
                                    ->first();
            if($strand){
                $this->out('strand already exists.');
                continue;
            }

            $this->out('Creating new strand');
            $strand = [
                'code' => $value['strand'],
                'name' => $value["strand_name"],
                'description' => $value["strand_name"],
                'learning_area_id' => $learningAreas[$value['subject_name']]->id
            ];

            $strand = $this->Strands->newEntity($strand);

            if(!$this->Strands->save($strand)){
                $this->out('Strand could not be created');
                // print_r($strand);
                continue;
            }
            $strands[] = $value['strand'];
            $this->out('Strand has been created');

            $this->out('Finding courses and creating data for course strands.');
            $startGrade = $grades[$value['start_grade']]->id;
            $endGrade = $grades[$value['end_grade']]->id;

            for($x = $startGrade; $x <= $endGrade; $x++){
            
                $course = $this->Strands->CourseStrands
                                        ->Courses
                                        ->findByLearningAreaId($strand->learning_area_id)
                                        ->where([
                                            'grade_id' => $x
                                        ])
                                        ->first();

                if($course){
                    $courseStrands[] = [
                                            'course_id' => $course->id,
                                            'strand_id' => $strand->id,
                                            'grade_id' => $course->grade_id
                                       ];
                }
            }
        }

        // print_r($courseStrands);
        if(!count($courseStrands)){
            $this->out('No course strands to be saved');
            return;
        }

        $courseStrands = $this->saveData('CourseStrands', [], array_unique($courseStrands, SORT_REGULAR));
        if($courseStrands){
            $this->out('Course strands have been saved.');
        }
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

    public function fixContentCategories(){
        $this->loadModel('ContentCategories');
        $contentCategories = $this->ContentCategories->find()->all()->toArray();
        if(empty($contentCategories)){
            $this->out('No Caontent Categories found.');
            return;
        }

        foreach ($contentCategories as $key => $value) {
            // pr(str_replace('Common', 'Unit Specific', $value->meta['heading_2']));
            $x = $contentCategories[$key]; 
            $contentCategories[$key]->meta['heading_3'] = str_replace('Common', 'Unit Specific', $value->meta['heading_2']);
            $contentCategories[$key]->meta['heading_3'] = str_replace('COMMON', 'UNIT SPECIFIC', $contentCategories[$key]->meta['heading_3']);
             $x = $this->ContentCategories->patchEntity($x,$contentCategories[$key]->toArray());
            $this->ContentCategories->save($x);
//            die;
        }   

        $this->out('contentCategories have been be saved.');
    }
}