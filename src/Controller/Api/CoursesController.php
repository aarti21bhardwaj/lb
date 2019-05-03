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

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class CoursesController extends ApiController
{
    public function index(){
 		if(!$this->request->isGet()){
 			throw new MethodNotAllowedException();
 		}
    $courses = $this->Courses->find()
                             ->order(['Courses.name' => 'ASC'])
                             ->contain(['Sections', 'Grades'])
                             ->matching('Sections.Terms' , function($q){
                                return $q->where(['Terms.is_active' => 1]);
                              })
                             ->map(function($value, $key){
                               $value->name = $value->name.'-Grade '.$value->grade->name;
                               return $value;
                             })
                             ->indexBy('id')
                             ->toArray();

    // pr($courses); die;
    $courses = array_values($courses);

   //     	$courses = $this->Courses->find()->matching(
   //     			'CampusCourses.CampusCourseTeachers',function($q){
   //     				return $q->where(
   //     					[
   //     						'teacher_id' => $this->Auth->user('id')
   //     					]);
   //     			}
   //     		)
			// ->all()
			// ->toArray();
       	
    $success = true;

    $this->set('data',$courses);
		$this->set('status',$success);
		$this->set('_serialize', ['status','data']);
    }

    public function view($id){
 		if(!$this->request->isGet()){
 			throw new MethodNotAllowedException();
 		}

 		if(!$id){
 			throw new BadRequestException();
 		}

/*   	$course = $this->Courses->findById($id)
                            ->contain(['Units.Templates','ContentCategories.ContentValues', 'Units.Assessments.AssessmentTypes', 'Units.UnitReflections', 'Units.UnitResources','Sections'])
                            ->first();
*/
    $this->loadModel('Users');
    $user = $this->Users->findById($this->Auth->user('id'))->contain(['Roles'])->first();
    if($user->role->label == 'Admin'){
      $course = $this->Courses->findById($id)
                            ->contain(['Units' => function($q){
                                return $q->where(['is_archived' => 0]);
                              },'Units.Templates','ContentCategories.ContentValues', 'Units.Assessments.AssessmentTypes', 'Units.UnitReflections', 'Units.UnitResources','Sections.Terms'=> function($q){
                              return $q->where(['Terms.is_active' => true]);
                            }, 'Units.UnitTeachers'])
                            ->first();      
    }else{
      $course = $this->Courses->findById($id)
                              ->contain(['Units' => function($q){
                                return $q->where(['is_archived' => 0]);
                              },'Units.Templates','ContentCategories.ContentValues', 'Units.Assessments.AssessmentTypes', 'Units.UnitReflections', 'Units.UnitResources','Sections.Terms'=> function($q){
                                return $q->where(['Terms.is_active' => true]);
                              }, 'Units.UnitTeachers' => function($q){
                                  return $q->where(['teacher_id' =>$this->Auth->user('id')]);
                              }])
                              ->first();
      
    }

    // pr($course); die;

    if(isset($course->sections) && !empty($course->sections)){
      $course->sections = array_values((new Collection($course->sections))->sortBy('name', SORT_ASC)->toArray());
    }
// pr($course); die;
    $divisionGrades = $this->Courses->Grades->DivisionGrades->find()->where(['grade_id' => $course->grade_id])
                                                                    ->contain(['Divisions.Templates'])
                                                                    ->first();

    // pr($divisionGrades); die;
    if(!empty($divisionGrades)){
       $course->template = [
                                'id' => $divisionGrades->division->template_id,
                                'template' => $divisionGrades->division->template

                          ];
    }else{
       throw new BadRequestException("Division Grades for this course is not set, Please set it first");
    }

    $course->content_categories = (new Collection($course->content_categories))->map(function($value, $key){
                                      $value->content_values = (new Collection($value->content_values))
                                                          ->map(function($value, $key){
                                                                 if($value->parent_id == NULL){
                                                                   $value->is_selectable = true;
                                                                 }
                                                                 return $value;
                                                               })
                                                                ->nest('id', 'parent_id')
                                                                ->toArray();
                                      return $value;
                                  })
				->indexBy('id')
                                  ->toArray();
 $course->content_categories = array_values($course->content_categories);


    $assessmentTypes = $this->Courses->Units->Assessments->AssessmentTypes->find()->indexBy('name')->toArray();


/*    $course->units = (new Collection($course->units))->reject(function($value, $key){
                                                     if(empty($value->unit_teachers)){
                                                      return $value;
                                                     }
                                                    })
                                                    ->toArray();

// pr($course->units); die;
    $course->units = array_values($course->units);*/
    $unitAssessments = (new Collection($course->units))->extract('assessments.{*}')
                                                       ->groupBy('assessment_type.name')
                                                       ->toArray();

    $unitAssessment = (new Collection($course->units))->map(function($value, $key) use($assessmentTypes){
                                                        $unit = $value;
                                                        if(!empty($value->unit_resources)){
                                                          $resources = (new Collection($value->unit_resources))->map(function($value, $key) use($unit){
                                                            $unit->resources = count($value);
                                                          })->toArray();
                                                        }else{
                                                            $unit->resources = 0;
                                                        }

                                                        if(!empty($value->unit_reflections)){
                                                          $reflections = (new Collection($value->unit_reflections))->map(function($value, $key) use($unit){
                                                            $unit->reflections = count($value);
                                                          })->toArray();
                                                        }else{
                                                            $unit->reflections = 0;
                                                        }
                                                        $assessment = (new Collection($value->assessments))->groupBy('assessment_type.name')->toArray();
                                                        return (new Collection($assessmentTypes))->map(function($value, $key) use($assessment, $unit){
                                                            $type = str_replace(' ', '_', $key);
                                                            $type = strtolower($type);
                                                            if(isset($assessment[$key])){
                                                                $unit[$type] = count($assessment[$key]);
                                                            }else{
                                                                $unit[$type] = 0;
                                                            }
                                                            return $unit;

                                                        })->toArray();

                                                      })
                                                      ->toArray();
// die;
                                                      // pr($unitAssessment); die;
    $unitAssessType = (new Collection($assessmentTypes))->map(function($value, $key) use($unitAssessments, $course){
                                                                    $type = str_replace(' ', '_', $key);
                                                                    $type = strtolower($type);
                                                                    if(isset($unitAssessments[$key])){
                                                                        $course[$type] = count($unitAssessments[$key]);
                                                                    }else{
                                                                        $course[$type] = 0;
                                                                    }
                                                                    return $course;
                                                                })->toArray();
    
    $unitResources = (new Collection($course->units))->extract('unit_resources.{*}')
                                                      ->toArray();

    $unitReflections = (new Collection($course->units))->extract('unit_reflections.{*}')
                                                      ->toArray();


    unset($course->grade);
                                                      
    $course->total_units = count($course->units);
    $course->resources = count($unitResources);
    $course->reflections = count($unitReflections);
   	$success = true;

   	$this->set('data',$course);
		$this->set('status',$success);
		$this->set('_serialize', ['status','data']);
    }

    public function getAllCourses(){
      if(!$this->request->isGet()){
        throw new MethodNotAllowedException();
      }
      $courses = $this->Courses->find()
                                ->order(['Courses.name' => 'ASC'])
                               ->contain(['Grades'])
                               ->map(function($value, $key){
                                 $value->name = $value->name.'-Grade '.$value->grade->name;
                                 return $value;
                               })
                              ->indexBy('id')
                              ->toArray();

      $courses = array_values($courses);
          
      $success = true;

      $this->set('data',$courses);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']); 
    }

}
