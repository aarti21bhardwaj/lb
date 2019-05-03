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
use Cake\I18n\Date;
use Cake\Cache\Cache;
use Cake\Log\Log;

/**
 * Units Controller
 *
 * @property \App\Model\Table\UnitsTable $Units
 *
 * @method \App\Model\Entity\Unit[] paginate($object = null, array $settings = [])
 */
class UnitsController extends ApiController
{
    
	public function index(){
		if(!$this->request->is('get')){
			throw new MethodNotAllowedException('Request is not get');
		}

		$units = $this->Units->find()->contain(['Courses', 'Teachers'])->all()->toArray();

		$this->set('units', $units);
		$this->set('_serialize', ['units']);
	}

    
    public function assessmentStandards($assessment_id, $unit_id) {
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Request is not get');
        }

        if(!$assessment_id){
            throw new BadRequestException('Missing assessment id', 1);
        }
//        $standards = Cache::read('assessmentStandardTree'.$assessment_id);
        $standards = false;
        if(!$standards){

            $assessment = $this->Units->Assessments->findById($assessment_id)
                                                   ->contain(['AssessmentTypes'])
                                                   ->first();
            $assessmentSetting = $assessment->assessment_type;

            $assessmentStrands = $this->Units->Assessments->AssessmentStrands->find()
                                                        ->where(['assessment_id' => $assessment_id])
                                                        ->all()
                                                        ->toArray();
            // pr($assessmentStrands); die;


            $strandIds = (new Collection($assessmentStrands))->extract('strand_id')->toArray();
            $gradeIds = (new Collection($assessmentStrands))->extract('grade_id')
                                                            // ->reject(function($value, $key){
                                                            //      return in_array($value, [null, false, '', []]);
                                                            //  })
                                                            ->toArray();

            //Make the strandIds and gradeIds unique
            $strandIds = array_unique($strandIds);
            $gradeIds = array_unique($gradeIds);

            $this->loadModel('Curriculums');
            $this->loadModel('Standards');
            $standards1 = [];
             if(!empty($gradeIds)){
                $standards1 = $this->Standards->find()->where(['strand_id IN' => $strandIds])
                                                     ->matching('StandardGrades' ,function($q) use($gradeIds){
                                                        return $q->where(['grade_id IN' => $gradeIds]); 
                                                     })
                                                     ->contain(['UnitStandards' => function($q) use($unit_id){
                                                           return $q->where(['unit_id' => $unit_id]);
                                                     }])
                                                     ->all()
                                                     ->map(function($value, $key) use($assessmentSetting){
                                                        if($assessmentSetting->standard_display_setting == 0){
                                                            $value->is_selectable = true;
                                                        }

                                                        if($assessmentSetting->standard_display_setting == 1){
                                                            if($value->parent_id != NULL){
                                                                $value->is_selectable = false;   
                                                            }

                                                        }
                                                        if($assessmentSetting->standard_display_setting == 2){
                                                            if($value->parent_id == NULL){
                                                                $value->is_selectable = false;   
                                                            }

                                                        }
                                                        if(!empty($value->unit_standards)){
                                                            $value->is_unit_standard = true;
                                                        }else{
                                                            $value->is_unit_standard = false;
                                                        }
                                                        
                                                        unset($value->unit_standards);
                                                        return $value;
                                                     })
                                                     ->toArray();
             }
                $standards2 = $this->Standards->find()->where(['Standards.strand_id IN' => $strandIds])
                                                     ->matching('Strands.UnitStrands', function($q) use($unit_id){
                                                       
                                                         return $q->where(['UnitStrands.unit_id' => $unit_id ,
                                                                'UnitStrands.grade_id IS NULL'
                                                        ]);
                                                     })
                                                     ->contain(['UnitStandards'=> function($q) use($unit_id){
                                                           return $q->where(['unit_id' => $unit_id]);
                                                     }])
                                                     ->all()
                                                      ->map(function($value, $key){
                                                        if(!empty($value->unit_standards)){
                                                            $value->is_unit_standard = true;
                                                        }else{
                                                            $value->is_unit_standard = false;
                                                        }
                                                        unset($value->unit_standards);
                                                        return $value;
                                                     })
                                                     ->indexBy('id')
                                                     ->toArray();
            // }

            // pr($standards2); die;
            $standards = array_merge($standards1,$standards2);                                                     
            // pr($standards); die;
	$standardIds = (new Collection($standards))->extract('id')->toArray();



        $strands = $this->Standards->Strands->find()->contain(['LearningAreas.Curriculums'])
                                                     ->where(['Strands.id IN' => $strandIds, 'Strands.id !=' => 1874]);

        if(!empty($standardIds)){
            $strands = $strands->matching('Standards', function($q) use ($standardIds){
                return $q->where(['Standards.id IN' => $standardIds]);
            });
                    $strands = $strands->indexBy('id');
        }

        $strandIds =  $strands->extract('id')->toArray();    
	$strands = array_values($strands->toArray());
       // $strands = $this->Standards->Strands->find()->contain(['LearningAreas.Curriculums'])->where(['Strands.id IN' => $strandIds, 'Strands.id !=' => 1874])
         //   ->all()->toArray();


            $learningArea = [];
            $curriculums = [];

            foreach ($strands as $key => $value) {
                if(isset($learningArea['learningArea'.$value->learning_area->id])){
                    continue;
                }
                $learningArea['learningArea'.$value->learning_area->id] = [ 'name' => $value->learning_area->name,
                                                            'id' => 'learningArea'.$value->learning_area->id,
                                                            'parent_id' => 'curriculum'.$value->learning_area->curriculum_id, 
                                                            ];
            }

            foreach ($strands as $key => $value) {
                if(isset($curriculums['curriculum'.$value->learning_area->curriculum_id])){
                    continue;
                }
                $curriculums['curriculum'.$value->learning_area->curriculum_id] = [ 'name' => $value->learning_area->curriculum->name,
                                                                        'id' => 'curriculum'.$value->learning_area->curriculum->id,
                                                                        'parent_id' => NULL, 
                                                                        ];
            }

            $strandData = [];


            foreach ($strands as $key => $value) {
                if(isset($strandData['strand'.$value->id])){
                    continue;
                }
                $strandData['strand'.$value->id] =  [ 'id' => 'strand'.$value->id,
                                                        'parent_id' => 'learningArea'.$value->learning_area_id,
                                                        'name' => $value->name
                                                        ];
            }

     

            foreach ($standards as $key => $standard) {
                if($standard->parent_id == NULL){
                    $standard->parent_id = 'strand'.$standard->strand_id; 
                }          
            }


            
            $newArray = array_merge($strandData, $learningArea, $curriculums, $standards);

            $standards = (new Collection($newArray))->nest('id','parent_id')->toArray();
            

//            Cache::write('assessmentStandardTree'.$assessment_id,$standards);
        }



        $this->set('standard_sets', $standards);
        $this->set('_serialize', ['standard_sets']);
    }

	public function view($id = null){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Request is not get');
        }

        $contentCategories = $this->Units->UnitContents->ContentCategories->find()->indexBy('id')->toArray();
        $assessmentTypes = $this->Units->Assessments->AssessmentTypes->find()->indexBy('name')->toArray();

$unit = $this->Units->findById($id)->contain(['Courses', 'Teachers','UnitSpecificContents', 'UnitContents.ContentValues', 'Assessments.AssessmentTypes', 'UnitReflections', 'UnitResources', 'UnitStandards', 'TransDisciplinaryThemes', 'UnitTypes.Types'])->first();
       

        $unit->teachers = (new Collection($unit->teachers))->map(function($value, $key){
                                                            $value->first_name = $value->first_name.' '.$value->last_name;
                                                                  return $value;
                                                          })->toArray();
        if(!empty($unit->assessments)){
            $unitAssessments = (new Collection($unit->assessments))->groupBy('assessment_type.name')
                                                                   ->toArray();

            $unitAssessType = (new Collection($assessmentTypes))->map(function($value, $key) use($unitAssessments, $unit){
                                                                    $type = str_replace(' ', '_', $key);
                                                                    $type = strtolower($type);
                                                                    if(isset($unitAssessments[$key])){
                                                                        $unit[$type] = count($unitAssessments[$key]);
                                                                    }else{
                                                                        $unit[$type] = 0;
                                                                    }
                                                                    return $unit;
                                                                })->toArray();
        }


        $unit->unit_contents = (new Collection($unit->unit_contents))->groupBy('content_category_id')
                                                              ->map(function($value, $key) use($contentCategories){
                                                                // pr($value); die;
                                                                $data['content_categories'] = $contentCategories[$key]->toArray();
                                                                $data['content_categories']['content_values'] = (new Collection($value))->extract('content_value')->toArray();  
                                                                return $data;
                                                              })
                                                              ->toArray();

        $unit->resources = count($unit->unit_resources);
        $unit->reflections = count($unit->reflections);
		$this->set('unit', $unit);
		$this->set('_serialize', ['unit']);
	}

    public function add(){
    	if(!$this->request->is('post')){
    		throw new MethodNotAllowedException("Request is not post");
    		
    	}
    	$courseId = $this->request->getParam('course_id');

    	if(!$courseId){
    		throw new BadRequestException("Missing Course Id");
    		
    	}

    	$data = $this->request->getData();
        
        if((isset($data['start_date']) && !empty($data['start_date'])) && (isset($data['end_date']) && !empty($data['end_date']))){
          $data['start_date'] = new Date($data['start_date']);
          $data['end_date'] = new Date($data['end_date']);
        }
        // $data['start_date'] = new Date($data['start_date']);
        // $data['end_date'] = new Date($data['end_date']);
        
        // Time::createFromTimestamp($ts);
        $data['unit_courses'][] = [
                                    'course_id' => $courseId,
                                    'is_primary' => 1
                                  ];

        $data['unit_teachers'][] = [
                                    'teacher_id' => $this->Auth->user('id'),
                                    'is_creator' => 1
                                  ];
        $unit = $this->Units->newEntity();
        // $unit->course_id = $courseId;
        $unit = $this->Units->patchEntity($unit, $data, ['associated' => ['UnitCourses', 'UnitTeachers']]);
        
        // pr($unit); die;
        if(!$this->Units->save($unit, ['associated' => ['UnitCourses', 'UnitTeachers']])){
            throw new InternalErrorException("Something went wrong");
        }
    	$response = array();
    	$response['status'] = true;
    	$response['data'] = $unit;

    	$this->set('response', $response);
    	$this->set('_serialize', ['response']);
    }

    public function edit($id = null){
        if(!$this->request->is('put')){
            throw new MethodNotAllowedException("Request is not put");
            
        }
        // pr($this->request->data); die;
        $unit = $this->Units->findById($id)->contain(['UnitTeachers'])->first();
    	if(!$unit){
    		throw new BadRequestException("Entity does not exist");
    		
    	}
    	$courseId = $this->request->getParam('course_id');

    	if(!$courseId){
    		throw new BadRequestException("Missing Course Id");
    		
    	}

        // find campus in which course is taught
         $this->loadModel('CampusCourses'); 
         $campusIds = $this->CampusCourses->find()->where(['course_id' => $courseId])
                                                   ->all()
                                                   ->extract('campus_id')
                                                   ->toArray();


    	$data = $this->request->getData();
        // pr($data['course_id']); die;
       if((isset($data['start_date']) && !empty($data['start_date'])) && (isset($data['end_date']) && !empty($data['end_date']))){
          $data['start_date'] = new Date($data['start_date']);
          $data['end_date'] = new Date($data['end_date']);
        }
        
        if(isset($data['course_id'])){
            $deleteUnitData = $this->Units->UnitCourses->deleteAll(['unit_id' => $id]);
            $unitCourses = [];
                foreach ($data['course_id'] as $course){

                   $unitCourses[] = [
                                        'course_id' => $course['id'],
                                        'is_primary' => 0,
                                        'unit_id' => $id
                                      ]; 

                }
                if(isset($unitCourses) && !empty($unitCourses)){
                    $unitCoursesData = $this->Units->UnitCourses->newEntities($unitCourses);
                    if(!$this->Units->UnitCourses->saveMany($unitCoursesData)){
                        // pr($unitCoursesData); die;
                        Log::write('error', $unitCoursesData);
                        throw new InternalErrorException("Something went wrong, adding course in a unit");
                    }
                }
                
        }
        
        if(isset($data['teacher_id'])){
            $deleteUnitTeacher = $this->Units->UnitTeachers->deleteAll(['unit_id' => $id]);
        	$unitTeachers = [];
        	foreach ($data['teacher_id'] as $teacher){	
                    $unitTeachers[] = [
                                                'teacher_id' => $teacher['id'],
                                                'is_creator' => 0,
                                                'unit_id' => $id
                                              ];

            }
        	if(isset($unitTeachers) && !empty($unitTeachers)){
        		$unitTeacherData = $this->Units->UnitTeachers->newEntities($unitTeachers);
        		if(!$this->Units->UnitTeachers->saveMany($unitTeacherData)){
        			throw new InternalErrorException("Something went wrong while assigning teachers");
        		}
        	}

            
        }
        
        if(isset($data['unit_type_id'])){
            $deleteUnitType = $this->Units->UnitTypes->deleteAll(['unit_id' => $id]);

            $unitTypes = [
                                        'type_id' => $data['unit_type_id'][0]['id'],
                                        'unit_id' => $id    
                                       ]; 
            if(isset($unitTypes) && !empty($unitTypes)){
                $unitTypesData = $this->Units->UnitTypes->newEntity();
                $unitTypesData = $this->Units->UnitTypes->patchEntity($unitTypesData,$unitTypes);
                // pr($unitTypesData); die;
                if(!$this->Units->UnitTypes->save($unitTypesData)){
                    throw new InternalErrorException("Something went wrong while assigning unit types");
                }
            }

        }
        $unit = $this->Units->findById($id)->contain(['UnitTeachers', 'UnitTypes'])->first();
        $unit = $this->Units->patchEntity($unit, $data);

    	if(!$this->Units->save($unit)){
    		throw new InternalErrorException("Something went wrong");
    	}

    	$response = array();
    	$response['status'] = true;
    	$response['data'] = $unit;

    	$this->set('response', $response);
    	$this->set('_serialize', ['response']);
    }

    public function unitStandards($id){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request method is not get", 1);
            
        }

                // $id = $this->request->getParam('unit_id');
        if(!$id){
            throw new BadRequestException('Missing unit id', 1);
        }

        $unitStrands = $this->Units->UnitStrands->find()
                                                ->where(['unit_id' => $id])
                                                ->all()
                                                ->toArray();
//                                                pr($unitStrands); die;
        $strandIds = (new Collection($unitStrands))->extract('strand_id')->toArray();
        $gradeIds = (new Collection($unitStrands))->extract('grade_id')
                                                            ->reject(function($value, $key){
                                                                 return in_array($value, [null, false, '', []]);
                                                             })
                                                            ->toArray();

        //Make the strandIds and gradeIds unique
        $strandIds = array_unique($strandIds);
        $gradeIds = array_unique($gradeIds); 

        $this->loadModel('Standards');
        //if(!empty($gradeIds)){
            $standards1 = $this->Standards->find()->where(['strand_id IN' => $strandIds])
                                                 ->matching('StandardGrades' ,function($q) use($gradeIds){
                                                    return $q->where(['grade_id IN' => $gradeIds]); 
                                                 })
                                                 ->all()
                                                 ->toArray();
        //}else{
            $standards2 = $this->Standards->find()->where(['Standards.strand_id IN' => $strandIds])
                                                 ->matching('Strands.UnitStrands', function($q) use($id) {
                                                    return $q->where(['UnitStrands.unit_id' => $id ,
                                                                'UnitStrands.grade_id IS NULL'
                                                ]);
                                                 })
                                                 ->all()
                                                 ->toArray();
        //}

        $standards = array_merge($standards1, $standards2);

        $standardIds = (new Collection($standards))->extract('id')->toArray();
            


        $strandData = $this->Standards->Strands->find()->contain(['LearningAreas.Curriculums'])
                                                     ->where(['Strands.id IN' => $strandIds, 'Strands.id !=' => 1874]);

        if(!empty($standardIds)){
            $strandData = $strandData->matching('Standards', function($q) use ($standardIds){
                return $q->where(['Standards.id IN' => $standardIds]);
            });
		    $strandData = $strandData->indexBy('id');
        }
 
        $strandIds =  $strandData->extract('id')->toArray();
        $strandData = array_values($strandData->toArray());

        $strands = [];
        $learningArea = [];
        $curriculums = [];

        foreach ($strandData as $key => $value) {
            if(isset($learningArea['learningArea'.$value->learning_area->id])){
                continue;
            }
            $learningArea['learningArea'.$value->learning_area->id] = [ 'name' => $value->learning_area->name,
                                                        'id' => 'learningArea'.$value->learning_area->id,
                                                        'parent_id' => 'curriculum'.$value->learning_area->curriculum_id, 
                                                        ];
        }

        foreach ($strandData as $key => $value) {
            if(isset($curriculums['curriculum'.$value->learning_area->curriculum_id])){
                continue;
            }
            $curriculums['curriculum'.$value->learning_area->curriculum_id] = [ 'name' => $value->learning_area->curriculum->name,
                                                                    'id' => 'curriculum'.$value->learning_area->curriculum->id,
                                                                    'parent_id' => NULL, 
                                                                    ];
        }


        foreach ($strandData as $key => $value) {
            if(isset($strandData['strand'.$value->id])){
                    continue;
            }
            $strands['strand'.$value->id] =  [ 'id' => 'strand'.$value->id,
                                                        'parent_id' => 'learningArea'.$value->learning_area_id,
                                                        'name' => $value->name
                                                        ];
        }

     

        foreach ($standards as $key => $standard) {
            
	    if(!in_array($standard->strand_id, $strandIds)){
                unset($standards[$key]);
            }
            if($standard->parent_id == NULL){
                $standard->parent_id = 'strand'.$standard->strand_id;                 
            }          
        }
	$standards = array_values($standards);

        $newArray = array_merge($strands, $learningArea, $curriculums, $standards);
        $standards = (new Collection($newArray))->nest('id','parent_id')->toArray();
        $this->set('unit_standard_sets', $standards);
        $this->set('_serialize', ['unit_standard_sets']);

    }


    public function copyOfUnit(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request method is not post", 1);
            
        }

        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $reqData = $this->request->getData();
        if(!$reqData){
            throw new BadRequestException("Empty request data", 1);
        }

        if(!isset($reqData['unit_id']) && empty($reqData['unit_id'])){
            throw new BadRequestException("Missing unit id", 1);
            
        }

        if(!isset($reqData['name']) && empty($reqData['name'])){
            throw new BadRequestException("Missing unit name", 1);
            
        }

        $unitId = $reqData['unit_id'];
        $newUnitName = $reqData['name'];

        $this->loadModel('CourseStrands');
        $courseStrandsData = $this->CourseStrands->find()->where(['course_id' => $courseId])->all();
        $courseStrandIds = $courseStrandsData->extract('strand_id')->toArray();
        $courseGradeIds = $courseStrandsData->extract('grade_id')->toArray();

        $oldUnitData = $this->Units->findById($unitId)
                            ->contain(['UnitTeachers', 'UnitStandards', 'UnitImpacts', 'UnitContents', 'UnitSpecificContents', 'UnitStrands', 'UnitResources' => function($q){
                                    return $q->where(['object_name' => 'unit']);
                                }, 'UnitReflections' => function($q){
                                    return $q->where(['object_name' => 'unit']); 
                            }, 'Assessments','Assessments.AssessmentStandards', 'Assessments.AssessmentImpacts'])
                            ->all();
         // pr($oldUnitData); die;
        if(!$oldUnitData){
            throw new NotFoundException("Unit not found", 1);
            
        }
        
        $this->loadModel('AssessmentStandards');
        $this->loadModel('AssessmentImpacts');
        $oldUnitStrands = (new Collection($oldUnitData))->extract('unit_strands.{*}')->toArray();
        // pr($oldUnitStrands); die;
        $newUnit = (new Collection($oldUnitData))->map(function($value, $key) use($newUnitName, $courseId){
                                                        $this->_unsetData($value);
                                                        $value->name = $newUnitName;
                                                        $value->is_archived = 0;
                                                        $value->unit_courses = [[
                                                                                    'course_id' => $courseId,
                                                                                    'is_primary' => 1
                                                                               ]];
                                                        $value->unit_teachers = (new Collection($value->unit_teachers))->map(function($value, $key){
                                                           return $this->_unsetData($value);
                                                        })->toArray();

                                                    $value->unit_contents = (new Collection($value->unit_contents))->map(function($value, $key){
                                                      return $this->_unsetData($value);

                                                    })->toArray();

                                                    $value->unit_specific_contents = (new Collection($value->unit_specific_contents))->map(function($value, $key){
                                                        return $this->_unsetData($value);

                                                    })->toArray();

                                                    $value->unit_resources = (new Collection($value->unit_resources))->map(function($value, $key){
                                                        return $this->_unsetData($value);

                                                    })->toArray();

                                                    $value->unit_reflections = (new Collection($value->unit_reflections))->map(function($value, $key){
                                                        return $this->_unsetData($value);

                                                    })->toArray();

                                                    // $value->unit_strands = (new Collection($value->unit_strands))->map(function($value, $key){
                                                    //    return $this->_unsetData($value);

                                                    // })->toArray();

                                                    $value->unit_standards = (new Collection($value->unit_standards))->map(function($value, $key){
                                                       return $this->_unsetData($value);

                                                    })->toArray();

                                                    if(!empty($value->unit_impacts)){
                                                        $value->unit_impacts = (new Collection($value->unit_impacts))->map(function($value, $key){
                                                         return $this->_unsetData($value);

                                                        })->toArray();

                                                    }


                                                    $value->assessments = (new Collection($value->assessments))->map(function($value, $key){

                                                        $value->assessment_standards = (new Collection($value->assessment_standards))->map(function($value, $key){
                                                            return $this->AssessmentStandards->newEntity($this->_unsetData($value));
                                                        })->toArray();

                                                        if(!empty($value->assessment_impacts)){
                                                            $value->assessment_impacts = (new Collection($value->assessment_impacts))->map(function($value, $key){
                                                                return $this->AssessmentImpacts->newEntity($this->_unsetData($value));
                                                            })->toArray();
                                                        }
                                                        
                                                        return $this->_unsetData($value);

                                                    })->toArray();

                                                    return $value->toArray();
                                                 })
                                                 ->toArray();
                                               
      $newUnit = $this->Units->newEntity($newUnit[0], ['associated' => ['Assessments.AssessmentStandards', 'Assessments.AssessmentImpacts', 'UnitTeachers', 'UnitCourses','UnitStandards', 'UnitImpacts', 'UnitContents', 'UnitSpecificContents', 'UnitStrands', 'UnitResources', 'UnitReflections']]);

      if(!$this->Units->save($newUnit)){
            throw new InternalErrorException("Something went wrong in copy unit", 1);
            
      }

      $response = array();
      $response['status'] = true;
      $response['data'] = $newUnit;

      $this->set('response', $response);
      $this->set('_serialize', ['response']);


    }

    private function _unsetData($value){
        if(isset($value->id)){
            unset($value->id);
        }
        if(isset($value->created)){
         unset($value->created);
        }
        if(isset($value->modified)){
         unset($value->modified);
        }
        if(isset($value->unit_id)){
         unset($value->unit_id);
        }
        if(isset($value->unit_strands)){
            unset($value->unit_strands);
        }

        if(isset($value->object_identifier)){
            unset($value->object_identifier);
        }

        if(isset($value->assessment_id)){
            unset($value->assessment_id);
        }
         
        return $value->toArray();
    }

    public function deleteUnit($id = null) {
        if(!$this->request->is('delete')){
            throw new MethodNotAllowedException("Request method is not delete", 1);     
        }

         $unitData = $this->Units->findById($id)
                            // ->contain(['UnitTeachers', 'UnitStandards', 'UnitImpacts', 'UnitContents', 'UnitSpecificContents', 'UnitStrands', 'UnitResources' => function($q){
                            //         return $q->where(['object_name' => 'unit']);
                            //     }, 'UnitReflections' => function($q){
                            //         return $q->where(['object_name' => 'unit']); 
                            // }, 'Assessments','Assessments.AssessmentStandards', 'Assessments.AssessmentImpacts'])
                            ->first();

        if(!$unitData){
            throw new NotFoundException("Record not found");
            
        }

         if ($this->Units->delete($unitData)) {
            $response['status'] = true;
            $response['message'] = "Deleted Successfully";
        } else {
            throw new InternalErrorException('Unit is not deleted');
            
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);

    }

    public function archivedUnits($termId){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request method is not get", 1);     
        }

        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }


        $course = $this->_termArchivedUnits($courseId, $termId);

        $this->set('data', $course);
        $this->set('_serialize', ['data']);
    }

    public function academicTerms(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request method is not get", 1);     
        }


        $this->loadModel('AcademicYears');

        $academicYears = $this->AcademicYears->find()
                                             ->toArray();

        $terms = $this->AcademicYears->Terms->find()
                                            ->contain(['Divisions'])
					    ->groupBy('academic_year_id')
                                            ->toArray();

        $this->set('academic_years', array_values($academicYears));
        $this->set('terms', $terms);
        $this->set('_serialize', ['academic_years', 'terms']);
    }


    private function _termArchivedUnits($courseId, $termId){
        $this->loadModel('TermArchiveUnits');
        $this->loadModel('Courses');
        $archiveUnitIds = $this->TermArchiveUnits->findByTermId($termId)
                                                 ->extract('unit_id')
                                                 ->toArray();


        if(!$archiveUnitIds){
            $course = $this->Courses->findById($courseId)->first();
        }else{
            $course = $this->Courses->findById($courseId)
                                    ->contain(['Units' => function($q) use($archiveUnitIds){
                                        return $q->where(['Units.id IN' => $archiveUnitIds]);
                                    },'Units.Templates', 'Units.Assessments.AssessmentTypes', 'Units.UnitReflections', 'Units.UnitResources'])
                                   
                                    ->first();


            $assessmentTypes = $this->Courses->Units->Assessments->AssessmentTypes->find()->indexBy('name')->toArray(); 

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
        }

        return $course;
    }


        public function getUnits(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request method is not get", 1);     
        }

        $courseId = $this->request->getParam('course_id');

        if(!$courseId){
            throw new BadRequestException("Missing Course Id");
            
        }

        $this->loadModel('Courses');
        $this->loadModel('Terms');
        $this->loadModel('Sections');

        $lastSection = $this->Sections->find()->where(['course_id' => $courseId])
                                              ->last();
        if($lastSection){
            $term = $this->Terms->find()->where(['id' => $lastSection->term_id])->first();
            
        }

        if(isset($term)){
            if($term->is_active){
                $course = $this->Courses->findById($courseId)
                                        ->contain(['Units' => function($q){
                                        return $q->where(['is_archived' => 0]);
                                      },'Units.Templates', 'Units.Assessments.AssessmentTypes', 'Units.UnitReflections', 'Units.UnitResources'])
                                  ->first();
                


                $assessmentTypes = $this->Courses->Units->Assessments->AssessmentTypes->find()->indexBy('name')->toArray(); 

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
            }else{
                $course = $this->_termArchivedUnits($courseId, $term->id);
                
            }

        }else{
            $course = $this->Courses->findById($courseId)->first();

        }

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

        
        $this->set('status', true);    
        $this->set('data', $course);
        $this->set('_serialize', ['status','data']);
    } 



}
