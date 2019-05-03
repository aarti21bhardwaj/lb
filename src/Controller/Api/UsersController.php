<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Firebase\JWT\JWT;
use Cake\Log\Log;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class UsersController extends ApiController
{


    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login']);
    }

    public function me(){
   		if(!$this->request->isGet()){
   			throw new MethodNotAllowedException();
   		}
      $userId = $this->Auth->user('id');
      $user = $this->Users->findById($userId)->contain(['Roles'])->first();

      $courses = [];
      $this->loadModel('Courses');
      if($user->role->label == "Teacher"){
        $courses = $this->Courses->find()
                                 ->order(['Courses.name' => 'ASC'])
                                 ->contain(['Sections' => [
                                        'sort' => ['Sections.name' => 'ASC']
                                    ],'Sections.Terms' => function($q){
                                    return $q->where(['Terms.is_active' => true]);
                                 }, 'Sections.SectionTeachers' => function($q){
                                                return $q->where([
                                                                  'teacher_id' =>$this->Auth->user('id')
                                                                ]);

                                    }, 'Grades'])
                                 ->matching('CampusCourses.CampusCourseTeachers',function($q)
                                            {
                                               return $q->where([
                                                                  'teacher_id' =>$this->Auth->user('id')
                                                                ]);
                                             }
                                           )
                                ->map(function($value, $key){
                                    $sections = (new Collection($value->sections))
                                                ->reject(function($value, $key){
                                                  if(empty($value->section_teachers)){
                                                    return true;
                                                  }
                                                })->toArray();
                                    $value->sections = array_values($sections);
                                    return $value;  
				}) 
				->reject(function($value, $key){
                                                  if(empty($value->sections)){
                                                    return true;
                                                  }
                                                })
				->toArray();
	
        
        if(!empty($courses)){

          $gradeIds = (new Collection($courses))->extract('grade_id')->toArray();
          $gradeIds = array_unique($gradeIds);

          $this->loadModel('Contexts');
          $contexts = $this->Contexts->find()->matching('GradeContexts',function($q) use($gradeIds){
                                                return $q->where(['grade_id IN' => $gradeIds]);
                                              })
                                              ->indexBy('id')
                                              ->toArray(); 
          $contexts = array_values($contexts);
          
          $courseIds = (new Collection($courses))->extract('id')->toArray();

  	      $courses = array_values($courses);
        }
        if(isset($courseIds)){
          $otherCourses = $this->Courses->find()
                                          ->order(['Courses.name' => 'ASC'])
                                          ->where(['Courses.id NOT IN' => $courseIds])
                                          ->contain(['Sections' => [
                                                'sort' => ['Sections.name' => 'ASC']
                                            ],'Sections.Terms' => function($q){
                                            return $q->where(['Terms.is_active' => true]);
                                          }])
                                          ->matching('UnitCourses.Units.UnitTeachers', function($q){
                                                  return $q->where(['teacher_id' =>$this->Auth->user('id')]);
                                            })
                                          ->map(function($value, $key){
                                                unset($value->_matchingData);
                                                $value->other_course = true;
                                                return $value;
                                          })
                                          ->toArray();

        }else{
          $otherCourses = $this->Courses->find()
                                        ->order(['Courses.name' => 'ASC'])       
                                        ->contain(['Sections' => [
                                          'sort' => ['Sections.name' => 'ASC']
                                          ],'Sections.Terms' => function($q){
                                          return $q->where(['Terms.is_active' => true]);
                                        }])
                                        ->matching('UnitCourses.Units.UnitTeachers', function($q){
                                                return $q->where(['teacher_id' =>$this->Auth->user('id')]);
                                          })
                                        ->map(function($value, $key){
                                              unset($value->_matchingData);
                                              $value->other_course = true;
                                              return $value;
                                        })
                                        ->toArray();
        }
        if(!empty($otherCourses)){
          $courses = array_merge($courses, $otherCourses);
        }
        
      }elseif($user->role->label == "Student"){
        $courses = $this->Courses->find()
                                  ->order(['Courses.name' => 'ASC'])
                                 ->contain(['ContentCategories.ContentValues','Sections' => [
                                        'sort' => ['Sections.name' => 'ASC']
                                    ],'Sections.Terms' => function($q){
                                    return $q->where(['Terms.is_active' => true]);
                                 }, 'Grades'])
                                 ->matching('Sections.SectionStudents',function($q)
                                            {
                                               return $q->where([
                                                                  'student_id' => $this->Auth->user('id')
                                                                ]);
                                             }
                                           )
                                 ->all()
                                 ->toArray();
        
        if(!empty($courses)){
          $gradeIds = (new Collection($courses))->extract('grade_id')->toArray();
          $gradeIds = array_unique($gradeIds);

          $this->loadModel('Contexts');
          $contexts = $this->Contexts->find()->matching('GradeContexts',function($q) use($gradeIds){
                                                return $q->where(['grade_id IN' => $gradeIds]);
                                              })
                                              ->indexBy('id')
                                              ->toArray(); 
          $contexts = array_values($contexts);
        }


      }elseif($user->role->label == "Guardian"){
        $this->loadModel('StudentGuardians');

        $guardians = $this->StudentGuardians->findByGuardianId($userId)
                                             ->contain(['Students.SectionStudents.Sections' => function($q){
                                                return $q->contain(['Courses', 'Terms' =>  function($q){
                                                      return $q->where(['Terms.is_active' => true]);
                                                }, 'Teachers']);
                                                }])
                                             ->all();  

        // $sectionId = $guardians->extract('student.section_students.{*}.section.id')->toArray();
        // pr($sectionId); die;
        $courses = null;
        
      }elseif($user->role_id == 2 || $user->role_id == 1){
        $courses1 = $this->Courses->find()
                                 ->order(['Courses.name' => 'ASC'])
                                 ->contain(['Sections' => [
                                        'sort' => ['Sections.name' => 'ASC']
                                    ],'Sections.Terms' => function($q){
                                    return $q->where(['Terms.is_active' => true]);
                                 },'Sections.Teachers', 'Grades'])
                                 // ->matching('CampusCourses.Campuses',function($q) use ($user)
                                 //            {
                                 //               return $q->where([
                                 //                                  'school_id' => $user->school_id
                                 //                                ]);
                                 //             }
                                 //           )
                                 ->all()
                                 ->toArray();

        foreach($courses1 as $course){
        	if(!empty($course->sections)){
        		$courses[] = $course;
        	}
      	}
      }

      $sessionData = $this->request->session()->read('campusSettings');

      $requiredData = [
                        'name' => $user->first_name." ".$user->last_name,
                        'userId' => $user->id,
                        'role' => $user->role->label,
                        'image' => $user->image_url,
                        'campusSettings' => $sessionData,
                        'courseData' => $courses,
                      ];

     
      if(isset($contexts) && !empty($contexts)){
        $requiredData['contextData'] = $contexts;
      }
       if(isset($guardians) && !empty($guardians)){
        $requiredData['guardians'] = $guardians;
      }
      
      $success = true;

      $this->set('data',$requiredData);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function login(){
     
      if (!$this->request->is(['post'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $data =array();
      $user = $this->Auth->identify();
      if (!$user) {
        throw new NotFoundException(__('LOGIN_FAILED'));
      }

      $time = time() + 10000000;
      $expTime = Time::createFromTimestamp($time);
      $expTime = $expTime->format('Y-m-d H:i:s');
      $data['status']=true;
      $data['data']['user']=$user;
      $data['data']['token']=JWT::encode([
        'sub' => $user['id'],
        'exp' =>  $time,
        ],Security::salt());
      $data['data']['expires']=$expTime;
      $this->set('data',$data['data']);
      $this->set('status',$data['status']);
      $this->set('_serialize', ['status','data']);
    }

     public function updatePassword(){
        $data = $this->request->data;
        if(!isset($data['new_password'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','new_password'));
        }
        if(isset($data['new_password']) && empty($data['new_password'])){
            throw new BadRequestException(__('EMPTY_NOT_ALLOWED','new_password'));
        }
        if(!isset($data['user_id'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','user_id'));
        }
        if(isset($data['user_id']) && empty($data['user_id'])){
            throw new BadRequestException(__('EMPTY_NOT_ALLOWED','user_id'));
        }

        $user = $this->Users->findById($data['user_id'])->first();

        if(!$user){
            throw new BadRequestException("Entity does not exist");
            
        }

        $password = $data['new_password'];
        $hasher = new DefaultPasswordHasher();

        if(! preg_match("/^[A-Za-z0-9~!@#$%^*&;?.+_]{8,}$/", $password)){
            throw new BadRequestException(__('Only numbers 0-9, alphabets a-z A-Z and special characters ~!@#$%^*&;?.+_ are allowed.'));
        }

        $reqData = ['password'=>$password];

        $user = $this->Users->patchEntity($user, $reqData);
        
        if($this->Users->save($user)){
            $data =array();
            $data['status']=true;
            $data['data']['id']=$user->id;
            $data['data']['message']='password saved';
        }else{
            throw new InternalErrorException("Something went wrong, try after some time!");
        }

        $this->set('response',$data);
        $this->set('_serialize', ['response']);

    }

}
