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
 * FixBlackBaudSync shell command.
 */
class FixBlackBaudSyncShell extends Shell
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

    
    public function courseHashes(){
        $this->out("In fix courses");
        $this->loadModel('Courses');
        $this->loadModel('Divisions');
        $this->loadModel('BlackBaudHash');

        $query = "SELECT * from dbo.vLB_CoursesGradeLevels_20172018 c inner join dbo.VLB_Schools s on c.School = s.Description";
        $results = $this->connectWithMigrationDB($query);
        $this->out('Getting Courses');

        $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
        $divisionCampuses = $this->Divisions->find()->combine('id', 'campus_id')->toArray();

        $addCount = 0;
        $updateCount = 0;
        $hashEntries = [];
        $count = 0;
        $totalCount = count($results);
        
       
        foreach ($results as $row) {
            $count++;
            $this->out("Count : ".$count." of ".$totalCount);

            $this->out('Found course '.$row['Course name']);

            
            $blackBaudHash = $this->BlackBaudHash->findByOldId($row['Course ID'].$row['Grade levels allowed'])->where(['new_table_name' => 'courses'])->first();
            
            $blackBaudHashData = [
                'old_id' => $row['EA7COURSESID'].'-'.$row['Grade levels allowed'],
            ];
            
            if($blackBaudHash){
                $this->out('Course already exists on new db. Updating BlackBaudHash Entry.');
                $blackBaudHash = $this->BlackBaudHash->patchEntity($blackBaudHash, $blackBaudHashData);    
                if(!$this->BlackBaudHash->save($blackBaudHash)){
                    $this->out('Hash Entry could not be updated.');
                    print_r($blackBaudHash);
                    continue;
                }
                $updateCount++;
            }
        }

        $message = $updateCount." hash entries have been updated";
        $this->out($message);
    }

    public function sectionHashes($oldAcademicYearId){
        $this->out("In sync Sections");
        $this->loadModel('Sections');
        $this->loadModel('BlackBaudHash');
        $query = 'SELECT DISTINCT sct.[Instructor ID],sct.[Student Grade], sct.[SCHOOLSID], sct.[EA7COURSESID] as COURSEID, sct.[COURSEID] as tempId, sct.[CLASSSECTION], sct.[CAL_Term_FK], t.[YEARID] from dbo.vLB_StudentCoursesTerms_20172018 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] = '.$oldAcademicYearId;
        
        $results = $this->connectWithMigrationDB($query);
        $this->out('Getting Sections');
        $divisions = $this->BlackBaudHash->findByNewTableName('divisions')->combine('old_id', 'new_id')->toArray();
        $divisionCampuses = $this->Sections->Terms->Divisions->find()->combine('id', 'campus_id')->toArray();


        $hashEntries = [];
        $updateCount = 0;
        $addCount = 0;
        $count = 0;
        $totalCount = count($results);

        $this->out('Processing sections.');
        foreach ($results as $row) {
            $count++;
            $this->out("Count : ".$count." of ".$totalCount);

            $this->out('Processing section'.$row['CLASSSECTION']);

            // $this->out('Getting grade from student of this section. For Course.');

            // $gradeQuery = "SELECT sct.[Student Grade] from dbo.vLB_StudentCoursesTerms_20172018 sct inner join dbo.vLB_SchoolsTerms t on sct.[CAL_Term_FK] = t.[CAL_Term_PK] and sct.[SCHOOLSID] = t.[SCHOOLSID] where year(t.[STARTDATE]) >= 2017 and t.[YEARID] = ".$oldAcademicYearId." and sct.[SCHOOLSID] = ".$row['SCHOOLSID']." and sct.[EA7COURSESID] = '".$row['COURSEID']."' and sct.[COURSEID] = '".$row['tempId']."' and sct.[CLASSSECTION] = '".$row['CLASSSECTION']."' and sct.[CAL_Term_FK] = ".$row['CAL_Term_FK'];

            // if($row['Instructor ID'] && !in_array($row['Instructor ID'], [null, false, ''])){
            //     $gradeQuery = $gradeQuery." and sct.[Instructor ID] = '".$row['Instructor ID']."'";
            // }


            // $gradeResult = $this->connectWithMigrationDB($gradeQuery)->fetch('assoc');
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

            $oldSectionId = $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$row['tempId'];
            $blackBaudHash = $this->BlackBaudHash->findByOldId($oldSectionId)->where(['new_table_name' => 'sections'])->first();
            $blackBaudHashData = [
                'old_id' => $row['CLASSSECTION'].$row['CAL_Term_FK'].$row['YEARID'].$row['SCHOOLSID'].$oldCourseId,
            ];


            if($blackBaudHash){
            
                $this->out('Section already exists on new db. Updating Hash Value.');
                $blackBaudHash = $this->BlackBaudHash->patchEntity($blackBaudHash, $blackBaudHashData);    
                if(!$this->BlackBaudHash->save($blackBaudHash)){
                    $this->out('Hash Entry could not be updated.');
                    print_r($blackBaudHash);
                    continue;
                }
                $updateCount++;

            }
        }  

        $message = $updateCount." hash entries have been updated";
        $this->out($message);
    }

   public function getPicsFromBlackBaud(){

        $this->loadModel('Users');
        $this->out('Getting Users.');
        $users = $this->Users->find()->contain(['Roles'])->toArray();
        $total = count($users);
        $count = 0;
	$notFound = [
                        'students' => [],
                        'teachers' => []
                    ];
        foreach($users as $key => $user){
            $count++;
            $this->out("Count: ".$count." of ".$total);
            $queries = [
              'student' => "SELECT [PHOTO] FROM [dbo].[vLB_StudentsEnrolled] where [Student_ID] ='".$user->legacy_id."'",
              'teacher' => "SELECT [PHOTO] FROM [dbo].[vLB_Teachers] where [Record ID] = '".$user->legacy_id."'",
'admin' => "SELECT [PHOTO] FROM [dbo].[vLB_Teachers] where [Record ID] = '".$user->legacy_id."'",
              'school' => "SELECT [PHOTO] FROM [dbo].[vLB_Teachers] where [Record ID] = '".$user->legacy_id."'"            
];

	    if(!isset($queries[$user->role->name])){
                $this->out('Not getting images for role '.$user->role->name);
                continue;
            }

            $query = $queries[$user->role->name];
            $this->out("Getting image of ".$user->role->name.", ".$user->full_name);

            $conn = ConnectionManager::get('mssql');
            $response = $conn->execute($query)->fetch('assoc');


            if(!isset($response['PHOTO']) || empty($response['PHOTO'])){
              if($user->role->name == 'student'){
                $notFound['students'][$user->id] = $user->legacy_id; 
              }else{
                $notFound['teachers'][$user->id] = $user->legacy_id; 
              }
		$this->out("Image not found.");
              continue;
            }
	    continue;
            $this->out('Writing file.');
            $file = fopen("webroot/user_images/".$user->id.".jpg","w");    
            fwrite($file, $response['PHOTO']);
            fclose($file);
        }
	print_r($notFound);
        $this->out('Done!!!');

    }
}
