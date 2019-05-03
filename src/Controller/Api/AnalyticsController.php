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
use RecursiveIteratorIterator;
use RecursiveArrayIterator;
use Cake\I18n\Time;

/**
 * AssessmentStandards Controller
 *
 * @property \App\Model\Table\AssessmentStandardsTable $AssessmentStandards
 *
 * @method \App\Model\Entity\AssessmentStandard[] paginate($object = null, array $settings = [])
 */
class AnalyticsController extends ApiController
{
    public function initialize()
    {
        parent::initialize();
        $this->evaluations = [];
    }

    public $evaluations;

    public function courseMap($courseId = null){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Not a get request method", 1);
            
        }

        $this->loadModel('CourseStrands');
        $this->loadModel('Standards');

        $courseStrands = $this->CourseStrands->find()->where(['course_id' => $courseId])
                                                     ->all();


                                                     // pr($courseStrands); die;
        if(!$courseStrands){
          throw new NotFoundException("No strands set for course id ".$courseId, 1);
          
        }

        $strandIds = $courseStrands->extract('strand_id')->toArray();
        $gradeIds = $courseStrands->extract('grade_id')
                                  ->reject(function($value, $key){
                                     return in_array($value, [null, false, '', []]);
                                    })
                                  ->toArray();


        //Make the strandIds and gradeIds unique
        $strandIds = array_unique($strandIds);
        $gradeIds = array_unique($gradeIds);
        if(!empty($gradeIds)){
            $standards = $this->Standards->find()->where(['strand_id IN' => $strandIds])
                                                                          ->matching('StandardGrades' ,function($q) use($gradeIds){
                                                                                return $q->where(['grade_id IN' => $gradeIds]); 
                                                                            })
                                                                          ->all()
                                                                          ->groupBy('strand_id')
                                                                          ->toArray();
            

        }else{
           $standards = $this->Standards->find()->where(['strand_id IN' => $strandIds])
                                                                        ->all()
                                                                        ->groupBy('strand_id')
                                                                        ->toArray(); 
        }
        
        $strands = $this->CourseStrands->Strands->find()->where(['id IN' => $strandIds])
                                                        ->all()
                                                        ->map(function($value, $key) use($standards){
                                                                return [
                                                                          'name' => $value->name,
                                                                          'y' => count($standards[$value->id])
                                                                       ];
                                                        })
                                                        ->toArray();

        // // pr($strands); die;
        // $this->loadModel('AssessmentStrands');

        // $assessmentStrandIds = $this->AssessmentStrands->find()
        //                                                ->where(['strand_id NOT IN' => $strandIds])
        //                                                ->all()
        //                                                ->extract('strand_id')
        //                                                ->toArray();


        
        // $assessmentStrandIds = array_unique($assessmentStrandIds);
        // // pr($assessmentStrandIds); die;

        // if(!empty($assessmentStrandIds)){

        //   $assessmentStrandsStandards = $this->Standards->find()
        //                                                 ->where(['strand_id IN' => $assessmentStrandIds])
        //                                                 ->all()
        //                                                 ->groupBy('strand_id')
        //                                                 ->toArray();

        //   $otherStrandsStandards = $this->AssessmentStrands->Strands->find()
        //                                                             ->where(['id IN' => $assessmentStrandIds])
        //                                                             ->all()
        //                                                             ->map(function($value, $key) use($assessmentStrandsStandards){
        //                                                                 return [
        //                                                                     'name' => $value->name,
        //                                                                     'y' => count($assessmentStrandsStandards[$value->id])
        //                                                                  ];
        //                                                             })
        //                                                             ->toArray();
        // $this->set('other_standards', $otherStrandsStandards);
        
        // }
        
        
        $this->set('standards', $strands);
        $this->set('_serialize', ['standards', 'other_standards']);
        

    }


    public function standardCalculation($course_id){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Not a get request method", 1);
            
        }

        $this->loadModel('CourseStrands');
        $this->loadModel('Standards');
        $this->loadModel('AssessmentStrands');

        // filter this by assessments belonging to the course.
        $assessmentStrands = $this->AssessmentStrands->find()
                                                     ->contain(['Strands.Standards'])
                                                     ->matching('Assessments.Units.Courses', function($q) use ($course_id){
                                                        return $q->where(['Courses.id' => $course_id]);
                                                     })
                                                     ->all();
       // pr($assessmentStrands); die; 
        if(!$assessmentStrands || empty($assessmentStrands)){
            throw new BadRequestException('No such assessment strands set for this course');
        }                                                   

        $assessmentStrandsData = $assessmentStrands->extract('strand')->toArray();
        $assessmentStrandsData = array_unique($assessmentStrandsData);
        // pr($assessmentStrandsData); die;
        $strandIds = (new Collection($assessmentStrandsData))->extract('id')->toArray();
        // pr($strandId); die;
        // total no of standards in assessment_strands
        $totalStrandStandardInAssessment = (new Collection($assessmentStrandsData))->groupBy('id')
                                                                                   ->map(function($value, $key){
                                                                                        return [
                                                                                            'name' => $value[0]->name,
                                                                                            'y' => count($value[0]->standards),
                                                                                        ];
                                                                                    })
                                                                                    ->indexBy('name')
                                                                                    ->toArray();
        // pr($totalStrandStandardInAssessment); die;

        $strandAssessmentIds = $assessmentStrands->extract('assessment_id')->toArray();
        $strandAssessmentIds = array_unique($strandAssessmentIds);

        // calculate selected standards in assessment_standards
        $this->loadModel('AssessmentStandards');
        $assessmentStandards = $this->AssessmentStandards->find()
                                                         ->where(['assessment_id IN' => $strandAssessmentIds])
                                                         ->contain(['Standards.Strands' =>function($q) use($strandIds){
                                                            return $q->where(['Strands.id IN' => $strandIds]);
                                                         }])
                                                         // ->matching('Standards.Strands', function($q) use($strandIds){
                                                         //    return $q->where(['Strands.id IN' => $strandIds]);
                                                         // })
                                                         ->all()
                                                         ->extract('standard')
                                                         ->toArray();

        // pr($assessmentStandards); die;
        if(empty($assessmentStandards)){
            throw new NotFoundException("Insufficient data", 1);
            
        }
        $assessmentStandards = array_unique($assessmentStandards);

        $calStandardInAssessment = (new Collection($assessmentStandards))->groupBy('strand_id')
                                                                             ->map(function($value, $key){
                                                                                return [
                                                                                        'id' => $value[0]->strand_id,
                                                                                        'name' => $value[0]->strand->name,
                                                                                        'y' => count($value),
                                                                                       ];
                                                                             })
                                                                             ->indexBy('name')
                                                                             ->toArray();

                                                                            
        // pr($selectedStrandData); die;

        $unSelectedStrands = []; 

        foreach ($totalStrandStandardInAssessment as $key => $value) {
            if(isset($calStandardInAssessment[$key])){
                $unSelectedStrands[] = [
                                                    'name' => $calStandardInAssessment[$key]['name'],
                                                    'value' => $value['y'] -$calStandardInAssessment[$key]['y']
                                                  ];
            }

        }

        $selectedStrands = [];
        $strand_data = [];

        foreach ($calStandardInAssessment as $key => $value) {
          if(isset($totalStrandStandardInAssessment[$key])){
              $selectedStrands[] = [
                                     'name' => $value['name'],
                                     'value' => $value['y']
                                   ];
              $strand_data[] = [
                                    'id' => $value['id'],
                                    'text' => $value['name']
                               ]; 
            
          }
        }
        
        $countOfStrands = count($selectedStrands);
        $strandNames = [];
        for( $i=0; $i<$countOfStrands; $i++)
        {
            $strandNames[] = $selectedStrands[$i]['name']; 
            $total = $selectedStrands[$i]['value'] + $unSelectedStrands[$i]['value'];
            $selectedStrands[$i]['y'] = $selectedStrands[$i]['value']*100/$total;
            $unSelectedStrands[$i]['y'] = $unSelectedStrands[$i]['value']*100/$total;            
        }


        $data = array();
        $data['strand_names'] = $strandNames;
        $data['selected_standards'] = $selectedStrands;
        $data['unselected_standards'] = $unSelectedStrands;
        $data['strand_data'] = $strand_data;
     
        $this->set('data', $data);
        $this->set('_serialize', ['data']);


    }

    public function getData($courseId, $strandId){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Not a get request method", 1);
            
        }

        $this->loadModel('AssessmentStrands');
        $this->loadModel('AssessmentStandards');

        // filter this by assessments belonging to the course.
        $assessmentStrands = $this->AssessmentStrands->find()
                                                     ->where(['strand_id' => $strandId])
                                                     ->matching('Assessments.Units.Courses', function($q) use ($courseId){
                                                        return $q->where(['Courses.id' => $courseId]);
                                                     })
                                                     ->all();
        // pr($assessmentStrands); die; 
        if(!$assessmentStrands || empty($assessmentStrands)){
            throw new BadRequestException('No such assessment strands set for this course');
        }

        

        $assessmentIds = $assessmentStrands->extract('assessment_id')->toArray();
        $assessmentIds = array_unique($assessmentIds);
        // pr($assessmentIds); die;
        $assessmentTypes = $this->AssessmentStrands->Assessments->AssessmentTypes->find()
                                                                                 ->all()
                                                                                 ->toArray();

        $assessmentStandardData = $this->AssessmentStandards->find()->where(['assessment_id IN' => $assessmentIds])
                                                                 ->contain(['Assessments.Units.Templates', 'Standards.Strands' => function($q) use($strandId){
                                                                    return $q->where(['strand_id' => $strandId]);
                                                                 }])
                                                                 ->all();
                                                                 

        // pr($assessmentStandardData); die;
        if(empty($assessmentStandardData)){
            throw new NotFoundException('Standards are not set for assessment');
        }

        $assessmentStandards = $assessmentStandardData->groupBy('standard_id')
                                                                 ->map(function($value, $key){
                                                                    $assessment = (new Collection($value))->extract('assessment')
                                                                                                   ->groupBy('assessment_type_id')
                                                                                                   ->toArray();

                                                                    return $assessment;
                                                                 })
                                                                 ->reject(function($value, $key){
                                                                    return in_array($value, [null, false, '', []]);
                                                                 })
                                                                 ->toArray();
        

        $assessmentStandardId = (new Collection($assessmentStandardData))->extract('standard_id')->toArray();
        // pr($assessmentStandardId); die;
        $standards = $this->AssessmentStandards->Standards->find()
                                                          ->where(['Standards.id IN' => $assessmentStandardId])
                                                          ->contain(['Strands','Units.Templates'])
                                                          // ->contain([])
                                                          ->all()
                                                          ->toArray();

        // pr($standards); die;
        $data = [];
        foreach ($standards as $key => $value) {
            // if(isset($assessmentStandards[$value->id])){
                $standardAssessments = $assessmentStandards[$value->id];
                $data[$key] = [
                                'id' => $value->id,
                                'strand_name' => $value->strand->name,
                                'standard_name' => $value->name,
                                'standard_code' => $value->code,
                              ];
                // $units = [];
                foreach ($value->units as $unit) {
                        $data[$key]['unit'][] = [
                                                     'id' => $unit->id,
                                                     'name' => $unit->name,
                                                     'template' => [
                                                                     'id' => $unit->template->id,
                                                                     'name' => $unit->template->name,
                                                                     'slug' => $unit->template->slug
                                                                   ]
                                                   ];
                        $data[$key]['units'][] = $unit->name;
                }
                foreach ($assessmentTypes as $assessmentType) { 
                    $type = str_replace(' ', '_', $assessmentType->name);
                    $type = strtolower($type);
                    if(isset($standardAssessments[$assessmentType->id])){
                        $data[$key][$type] = count($standardAssessments[$assessmentType->id]);

                    }else{
                        $data[$key][$type] = 0;
                    }
                }
            // }
        }

        $types = [];
        
        foreach ($assessmentTypes as $assessmentType) {
            $type = str_replace(' ', '_', $assessmentType->name);
            $type = strtolower($type);
            $types[] = $type; 
        }

        // $data['assessment_types'] = $types; 
        $data = array_values($data);

        $this->set('data', $data);
        $this->set('assessment_types', $types);
        $this->set('_serialize', ['data', 'assessment_types']);


    }

    //show data in drop down
    // public function listData(){
    //     if(!$this->request->is('get')){
    //         throw new MethodNotAllowedException('Request method is not get');
    //     }


    //     $this->loadModel('Campuses');
    //     $this->loadModel('Sections');

    //     $campuses = $this->Campuses->find()
    //                                ->contain(['Divisions.Terms', 'Courses'])
    //                                ->all()
    //                                ->map(function($value, $key){
    //                                  $data = [
    //                                             'id' => $value->id,
    //                                             'text' => $value->name
    //                                          ];
    //                                 $data['courses'] = (new Collection($value->courses))->map(function($value, $key){
    //                                                         $courseData = [
    //                                                                         'id' => $value->id,
    //                                                                         'text' => $value->name
    //                                                                       ];
    //                                                         return $courseData;
    //                                                      })->toArray();
    //                                  $data['divisions'] = (new Collection($value->divisions))->map(function($value, $key){
    //                                                         $divisionData = [
    //                                                                             'id' => $value->id,
    //                                                                             'text' => $value->name
    //                                                                         ];
    //                                                         $divisionData['terms'] = (new Collection($value->terms))->map(function($value, $key){
    //                                                             $termData = [
    //                                                                             'id' => $value->id,
    //                                                                             'text' => $value->name
    //                                                                         ];
    //                                                             return $termData;
    //                                                         })->toArray();

    //                                                         return $divisionData;
    //                                                     })->toArray();

    //                                   return $data;
    //                                })
    //                                ->toArray();


    //     // pr($campuses); die;

    //     $sections = $this->Sections->find()
    //                                // ->contain(['Students'])
    //                                ->all()
    //                                ->map(function($value, $key){
    //                                     $value->text = $value->name;
    //                                     return $value;
    //                                })
    //                                ->groupBy('term_id')
    //                                ->map(function($value, $key){
    //                                  $data = (new Collection($value))->groupBy('course_id')->map(function($value, $key){
    //                                     // pr($value);
    //                                                 $sectionData = (new Collection($value))->map(function($value, $key){
    //                                                                     return [
    //                                                                         'id' => $value->id,
    //                                                                         'text' => $value->name
    //                                                                     ];
    //                                                                 })->toArray();
    //                                                  return $sectionData;
    //                                          })->toArray();
    //                                  return $data;
    //                                })
    //                                ->toArray();

    //     $this->set('campuses', $campuses);
    //     $this->set('sections', $sections);
    //     $this->set('_serialize', ['campuses', 'sections']);
            
    // }

    public function listData(){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Request method is not get');
        }


        $this->loadModel('Campuses');
        $this->loadModel('Sections');

        $courses = $this->Campuses->Divisions->find()->contain(['Terms.Sections.Courses'])
                                                       ->groupBy('campus_id')
                                                       ->map(function($value, $key){
                                                         return (new Collection($value))->groupBy('id')->map(function($value, $key){
                                                            return (new Collection($value))->map(function($value, $key){
                                                                  return (new Collection($value->terms))->groupBy('id')->map(function($value, $key){
                                                                    return (new Collection($value))->map(function($value, $key){
                                                                      if(!empty($value->sections)){
                                                                        return (new Collection($value->sections))->map(function($value, $key){
                                                                            $data = [
                                                                                      'id' => $value->course->id,
                                                                                      'text' => $value->course->name
                                                                                   ];
                                                                            return $data;
                                                                        }
                                                                        )->toArray();
                                                                      }
                                                                    })->toArray();
                                                                  })->toArray();
                                                            })->toArray();
                                                         })->toArray();
                                                       })
                                                       ->toArray();

        $campuses = $this->Campuses->find()
                                   ->contain(['Divisions.Terms'])
                                   ->all()
                                   ->map(function($value, $key){
                                     $data = [
                                                'id' => $value->id,
                                                'text' => $value->name
                                             ];
                                   
                                    $data['divisions'] = (new Collection($value->divisions))->map(function($value, $key){
                                                            $divisionData = [
                                                                                'id' => $value->id,
                                                                                'text' => $value->name
                                                                            ];
                                                            $divisionData['terms'] = (new Collection($value->terms))->map(function($value, $key){
                                                                $termData = [
                                                                                'id' => $value->id,
                                                                                'text' => $value->name
                                                                            ];
                                                                return $termData;
                                                            })->toArray();

                                                            return $divisionData;
                                                        })->toArray();

                                      return $data;
                                   })
                                   ->toArray();


        // pr($campuses); die;

        $sections = $this->Sections->find()
                                   // ->contain(['Students'])
                                   ->all()
                                   ->map(function($value, $key){
                                        $value->text = $value->name;
                                        return $value;
                                   })
                                   ->groupBy('term_id')
                                   ->map(function($value, $key){
                                     $data = (new Collection($value))->groupBy('course_id')->map(function($value, $key){
                                        // pr($value);
                                                    $sectionData = (new Collection($value))->map(function($value, $key){
                                                                        return [
                                                                            'id' => $value->id,
                                                                            'text' => $value->name
                                                                        ];
                                                                    })->toArray();
                                                     return $sectionData;
                                             })->toArray();
                                     return $data;
                                   })
                                   ->toArray();

          // pr($sections); die;                         

        $this->set('campuses', $campuses);
        $this->set('sections', $sections);
        $this->set('courseData', $courses);
        $this->set('_serialize', ['campuses', 'sections', 'courseData']);
            
    }


    public function performanceEvaluate($sectionId=null,$studentId = null){
        if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Request method is not get');
        }

         if(!$sectionId){
            throw new BadRequestException('Missing section_id', 1);
        }

        if(!$studentId){
            throw new BadRequestException('Missing student_id', 1);
        }

        $this->loadModel('Sections');

        $section = $this->Sections->findById($sectionId)
                                  ->contain(['Terms'])
                                  ->matching('SectionStudents', function($q) use($studentId){
                                    return $q->where(['SectionStudents.student_id' => $studentId]);
                                  })
                                  ->first();
        // pr($section); die;

        // get all assessments of course having start date and end date between the term start_date n end_date
        $this->loadModel('SectionEvents');
        $sectionEvents = $this->SectionEvents->findBySectionId($sectionId)
                                             ->where(['object_name' => 'evaluation'])
                                             ->andWhere(['start_date >=' => $section->term->start_date, 'end_date <=' => $section->term->end_date])
                                             ->all()
                                             ->toArray();

        if(!$sectionEvents){
            throw new NotFoundException("Record not found exception", 1);
            
        }

        $evaluationIds = (new Collection($sectionEvents))->extract('object_identifier')->toArray();
        // pr($evaluationIds); die;
        
        $this->loadModel('EvaluationStandardScores');

        $evaluations = $this->EvaluationStandardScores->find()
                                                      ->contain(['Evaluations.Assessments.AssessmentTypes', 'Evaluations.Assessments.Units','Standards.Strands', 'ScaleValues', 'Evaluations.SectionEvents'])
                                                      ->order(['SectionEvents.end_date' => 'ASC'])
                                                      ->where(['evaluation_id IN' => $evaluationIds, 'student_id' => $studentId])
                                                      ->all()
                                                      ->groupBy('standard.strand.name')
                                                      ->map(function($value, $key){
                                                            return (new Collection($value))->groupBy('evaluation.assessment.assessment_type.name')->toArray();
                                                      })
                                                      ->map(function($value, $key){
                                                        return (new Collection($value))->map(function($value, $key){
                                                            return (new Collection($value))->groupBy('standard_id')->toArray();
                                                        })->toArray();
                                                      })
                                                      ->toArray();
        // pr($evaluations);die;
        if(!$evaluations){
            throw new NotFoundException("Empty Data", 1);
            
        }
        

        $splineData = [];
        $data = [];
        $assessData = [];
        $strandData = [];
        $studentEvaluation = [];   
        $assessmentTypes = [];
        $assessmentTypeData = [];

        $this->evaluations = $evaluations;
       // / pr($evaluations); die;
        foreach ($evaluations as $key => $evaluationTypes){
            foreach ($evaluationTypes as $key1 => $strands) {
                foreach ($strands as $key2 => $assessmentData) {
                    foreach ($assessmentData as $key3 => $value) {
                      if(!isset($assessmentTypeData[$value->evaluation->assessment->assessment_type->id])){

                          $assessmentTypeData[$value->evaluation->assessment->assessment_type->id] = $value->evaluation->assessment->assessment_type;
                          
                        }

                        $splineData[$key2][] = [
                                            strtotime($value->evaluation->section_event->end_date) * 1000,
                                            $value->scale_value->value
                                         ];

                        if(isset($data[$value->standard->id])){
                           // echo "in continue"; 
                            continue;
                        }


                        $data[$value->standard->id] = [
                                    'standard_id' => $value->standard->id,
                                    'standard_name' => $value->standard->name,
                                    'standard_code' => $value->standard->code,
                                    'total_count' => count($assessmentData),
                                    'units' => $this->getUnitsArray($value->standard->id)['units'],
                                    'assessments' => $this->getUnitsArray($value->standard->id)['evaluations']
                                  ];
                         //   echo "not in continue"; 
                    }
                    
                    $strandData[$value->standard->strand_id] = [
                                                "strand_id" => $value->standard->strand_id,
                                                "strand_name" => $key,
                                                "strand_code" => $value->standard->strand->code,
                                                "data"  => array_values($data),
                                                'total_count' => count($strands)
                                    ];


                }
                    unset($data);


                 $assessData[$key1] =    [   
                                        'assessement_type' => $key1,

                                        'data' => array_values($strandData)
                                    ];


                $assessmentTypes[$key1] = $key1;
            }
            $studentEvaluation = array_values($assessData);
        }
        
        $this->set('assessmentTypeData', $assessmentTypeData);
        $this->set('evaluations', $studentEvaluation);
        $this->set('splineData', $splineData);
        $this->set('assessmentTypes', array_values($assessmentTypes));
        $this->set('_serialize', ['evaluations', 'splineData', 'assessmentTypes','assessmentTypeData']);

    }

    public function studentEvaluation($studentId){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request method is  not post');
        }

        $data = $this->request->getData();
        if(!$data['term_id']){  
            throw new BadRequestException("Missing term_id", 1);
            
        }

        if(!isset($data['type'])){
            throw new BadRequestException("Type is not defined", 1);
        }

        $termId = $data['term_id'];
        // $studentId = $data['student_id'];

        $this->loadModel('SectionStudents');
        $sectionStudentData = $this->SectionStudents->find()
                                                    ->where(['student_id' => $studentId])
                                                    ->contain(['Sections' => function($q) use($termId){
                                                                return $q->where(['term_id' => $termId]);
                                                        }, 'Sections.Terms.Divisions', 'Sections.Courses'])
                                                    ->all()
                                                    ->toArray();

        // pr($sectionStudentData); die;                                        
        if(!$sectionStudentData){ 
            throw new NotFoundException('No record found for student id '.$studentId.' in term id '.$termId);
        }

        $section = (new Collection($sectionStudentData))->extract('section')
                                                        ->map(function($value, $key){
                                                            return [
                                                                     'id' => $value->id,
                                                                     'term_id' => $value->term_id,
                                                                     'start_date' => $value->term->start_date,
                                                                     'end_date' => $value->term->end_date,
                                                                   ];    
                                                        })
                                                        ->indexBy('id')
                                                        ->toArray();

        $campusId = (new Collection($sectionStudentData))->extract('section.term.division.campus_id')->toArray();
        $campusId = array_unique($campusId);
        // pr($campusId); die;

        $this->loadModel('CampusSettings');
        $settings = $this->CampusSettings->find()->where(['campus_id IN' => $campusId])
                                                 ->contain(['SettingKeys'])
                                                 ->all()
                                                 ->indexBy('setting_key.name')
                                                 ->toArray();
        // pr($settings); die;
        $this->loadModel('SectionEvents');
        $sectionEvents = $this->SectionEvents->find()                            
                                             ->all()
                                             ->map(function($value, $key) use($section){
                                                if(($value->object_name == 'evaluation')){
                                                    if(isset($section[$value->section_id])){
                                                        if(($value->start_date >= $section[$value->section_id]['start_date']) && ($value->end_date <= $section[$value->section_id]['end_date'])){
                                                            return $value;
                                                        }
                                                    } 
                                                }
                                             })
                                             ->reject(function($value, $key){
                                                return in_array($value, [null, false, '', []]);
                                             })
                                             ->toArray();

        if(!$sectionEvents){
            throw new NotFoundException("Record not found", 1);
            
        }

        $evaluationIds = (new Collection($sectionEvents))->extract('object_identifier')->toArray();
        $evaluationIds = array_unique(array_values($evaluationIds));
        
        if($data['type'] == "Standards"){
            $this->loadModel('EvaluationStandardScores');
         
            $evaluations = $this->EvaluationStandardScores->find()
                          ->contain(['Evaluations.Assessments.AssessmentTypes', 'Standards.Strands', 'ScaleValues', 'Evaluations.SectionEvents' => function($q){
                                        return $q->order('end_date');
                            }, 'Evaluations.SectionEvents.Sections.Courses'])
                          ->where(['evaluation_id IN' => $evaluationIds, 'student_id' => $studentId])
                          ->all();
            
            // pr($evaluations->toArray()); die;
            if(!$evaluations){
                throw new NotFoundException("Record not found exception", 1);
            }
            $courses = array_unique($evaluations->extract('evaluation.section_event.section.course')->toArray());

            $courseColor = (new Collection($courses))->combine('name', 'color')->toArray();
            
            $courseList = $courses;
            $courseData = [];
            foreach ($courses as $value) {
                $courseData[] = $value->name;

            }

            $courses = $courseData;

            $categories = array_unique($evaluations->extract('standard.strand.name')->toArray());
            $category = [];
            foreach ($categories as $value) {
                $category[] = $value;
            }

            $categories = $category;
            // pr($categories); die;
            $evaluations =  $evaluations->groupBy('evaluation.section_event.section.course.name')
                          ->map(function($value, $key) use ($categories, $settings, $courses, $courseColor){
                               $course['course'] = (new Collection($value))
                                               ->groupBy('standard.strand.name')
                                               ->map(function($value, $key) use ($categories, $settings){
                                                    $strandData = [
                                                        'name' => $key
                                                    ];

                                                    $avgData = (new Collection($value))
                                                                      ->groupBy('evaluation.assessment.assessment_type.name')
                                                                      ->map(function($value, $key) use($settings){
                                                                            if($settings[$key.' Weightage']){
                                                                                $collection = (new Collection($value))->extract('scale_value.value')->toArray();
                                                                                $data = [
                                                                                    'scores' => $collection,
                                                                                    'sum' => array_sum($collection),
                                                                                    'count' => count($collection),
                                                                                ];
                                                                                $data['weightedScore'] = $settings[$key.' Weightage']->value * array_sum($data['scores']);

                                                                            }
                                                                            return $data;
                                                                            // pr($data);

                                                                      });
                                                    // pr($avgData->toArray()); die;
                                                    $strandData['x'] = array_search($key, $categories);
                                                    $strandData['y'] = $avgData->sumOf('weightedScore') / $avgData->sumOf('count');
                                                    return $strandData;
                                               });
                                               // ->reject(function($value, $key){
                                               //  return in_array($value, [null, false, '', []]);
                                               //  })
                                               // ->toArray();
                               $data = [
                                          'type' => 'column',
                                          'softThreshold' => true,
                                          'pointPlacement' => 'on',
                                          'name' => $key,
                                          'color' => $courseColor[$key],
                                          'data' => [[
                                                      'x' => array_search($key, $courses),
                                                      'y' => $course['course']->sumOf('y') / count($course['course']),
                                                    ]]
                                         
                                       ];
                               //  $data = [
                               //      'type' => 'column',
                               //      'softThreshold' => true,
                               //      'pointPlacement' => 'on',
                               //      'data' => $courseContent
                               // ];
                               // $data['data'] = array_values($data['data']);
                               return $data;
                          })
                          ->toArray();

            // die;
            $evaluations = array_values($evaluations);
            // pr($evaluations); die;
            $response = [
                // 'categories' => $categories,
                'courseList' => array_values($courseList),
                'categories' => $courses,
                'series' => $evaluations
            ];

            // pr($response);die;

        }

        if($data['type'] == 'Impacts'){
            $this->loadModel('EvaluationImpactScores');

            $evaluations = $this->EvaluationImpactScores->find()
                                        ->contain(['Evaluations.Assessments.AssessmentTypes', 'ScaleValues','Evaluations.SectionEvents' => function($q){
                                                    return $q->order('end_date');
                                            }, 'Evaluations.SectionEvents.Sections.Courses', 'Impacts.ImpactCategories'])
                                        ->where(['evaluation_id IN' => $evaluationIds])
                                        ->all();
            
            // pr($evaluations->toArray()); die;
            if(!$evaluations){
                throw new NotFoundException("Record not found exception", 1);
            }

            $impacts = $evaluations->extract('impact')->toArray();

            $impacts = array_unique($impacts);
            $impactData = [];
            foreach ($impacts as $value) {
                $impactData[] = $value->name;
            }

            $impacts = $impactData;
            $evaluations =  $evaluations->groupBy('impact.impact_category.name')
                                        ->map(function($value, $key) use($settings, $impacts){
                                          $data = [
                                                    'type' => 'column',
                                                    'softThreshold' => true,
                                                    'pointPlacement' => 'on',
                                                    'name' => $key
                                                  ];

                                          $data['data'] = (new Collection($value))
                                                       ->groupBy('impact.name')
                                                       ->map(function($value, $key) use($settings, $impacts){
                                                            $impactData = [
                                                                'name' => $key
                                                            ];

                                                            $avgData = (new Collection($value))
                                                                          ->groupBy('evaluation.assessment.assessment_type.name')
                                                                          ->map(function($value, $key) use($settings){
                                                                               if($settings[$key.' Weightage']){
                                                                                $collection = (new Collection($value))->extract('scale_value.value')->toArray();
                                                                                $data = [
                                                                                    'scores' => $collection,
                                                                                    'sum' => array_sum($collection),
                                                                                    'count' => count($collection),
                                                                                ];
                                                                                $data['weightedScore'] = $settings[$key.' Weightage']->value * array_sum($data['scores']);

                                                                            }
                                                                                // $collection = (new Collection($value))->extract('scale_value.value')->toArray();
                                                                                // $data = [
                                                                                //     'scores' => $collection,
                                                                                //     'sum' => array_sum($collection),
                                                                                //     'count' => count($collection),
                                                                                // ];
                                                                                // $data['weightedScore'] = $data['count'] * array_sum($data['scores']);

                                                                                return $data;

                                                                          });
                                                            $impactData['x'] = array_search($key, $impacts);
                                                            $impactData['y'] = $avgData->sumOf('weightedScore') / $avgData->sumOf('count');
                                                            return $impactData;
                                                       })
                                                       ->toArray();
                                          $data['data'] = array_values($data['data']);
                                          return $data;
                                        })
                                        ->toArray();

            $evaluations = array_values($evaluations);
            // pr($evaluations); die;
            $response = [
                // 'categories' => $categories,
                'categories' => $impacts,
                'series' => $evaluations
            ];

        }

        if(!$evaluations){
                throw new NotFoundException("Empty Data", 1);
                
         }

        $this->set('response', $response); 
        $this->set('_serialize', ['response']);

                
    }

    public function getUnitsArray($standardId){
        $unitsArray = [];        
        $evaluationsArray = [];
        
        foreach ($this->evaluations as $key => $evaluation){
            foreach ($evaluation as $key1 => $strands) {
                foreach ($strands as $key2 => $assessmentData) {
                    //pr($strands[$strandId]);// die;
                    if(isset($strands[$standardId])) {
                        $collection = (new Collection($strands[$standardId]));
                        $evaluationSummary = $collection->extract('evaluation')->map(function($value,$key){
                            return [
                            'evaluation_id' => $value->id,
                            'assessment' => $value->assessment->name,
                            'assessment_color'=>$value->assessment->assessment_type->color,
                            'end_date' => $value->section_event->end_date
                            ];
                        })->toArray(); 

                        $units = $collection->extract('evaluation.assessment.unit')->toArray();
                        break;       
                    }
                    continue;
                }
            }
        }
        return  ['units' => array_unique($units), 'evaluations' => $evaluationSummary];
    }


    public function studentStrandScore($studentId){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request method is  not post');
        }

        $data = $this->request->getData();
        if(!$data['term_id']){  
            throw new BadRequestException("Missing term_id", 1);
            
        }

        if(!isset($data['course_id'])){
            throw new BadRequestException("Missing course_id", 1);
        }

        $termId = $data['term_id'];
        $courseId = $data['course_id'];

        $this->loadModel('SectionStudents');
        $sectionStudentData = $this->SectionStudents->find()
                                                    ->where(['student_id' => $studentId])
                                                    ->contain(['Sections' => function($q) use($termId, $courseId){
                                                                return $q->where(['term_id' => $termId, 'course_id' => $courseId])->contain(['Terms.Divisions', 'Courses']);
                                                        }])
                                                    ->all()
                                                    ->toArray();

        // pr($sectionStudentData); die;                                        
        if(!$sectionStudentData){ 
            throw new NotFoundException('No record found for student id '.$studentId.' in term id '.$termId);
        }

        $section = (new Collection($sectionStudentData))->extract('section')
                                                        ->map(function($value, $key){
                                                            return [
                                                                     'id' => $value->id,
                                                                     'term_id' => $value->term_id,
                                                                     'start_date' => $value->term->start_date,
                                                                     'end_date' => $value->term->end_date,
                                                                   ];    
                                                        })
                                                        ->indexBy('id')
                                                        ->toArray();

        $campusId = (new Collection($sectionStudentData))->extract('section.term.division.campus_id')->toArray();
        $campusId = array_unique($campusId);
        // pr($campusId); die;

        $this->loadModel('CampusSettings');
        $settings = $this->CampusSettings->find()->where(['campus_id IN' => $campusId])
                                                 ->contain(['SettingKeys'])
                                                 ->all()
                                                 ->indexBy('setting_key.name')
                                                 ->toArray();
        // pr($settings); die;
        $this->loadModel('SectionEvents');
        $sectionEvents = $this->SectionEvents->find()                            
                                             ->all()
                                             ->map(function($value, $key) use($section){
                                                if(($value->object_name == 'evaluation')){
                                                    if(isset($section[$value->section_id])){
                                                        if(($value->start_date >= $section[$value->section_id]['start_date']) && ($value->end_date <= $section[$value->section_id]['end_date'])){
                                                            return $value;
                                                        }
                                                    } 
                                                }
                                             })
                                             ->reject(function($value, $key){
                                                return in_array($value, [null, false, '', []]);
                                             })
                                             ->toArray();

        if(!$sectionEvents){
            throw new NotFoundException("Record not found", 1);
            
        }

        $evaluationIds = (new Collection($sectionEvents))->extract('object_identifier')->toArray();
        $evaluationIds = array_unique(array_values($evaluationIds));
        
            $this->loadModel('EvaluationStandardScores');
         
            $evaluations = $this->EvaluationStandardScores->find()
                          ->contain(['Evaluations.Assessments.AssessmentTypes', 'Standards.Strands', 'ScaleValues', 'Evaluations.SectionEvents' => function($q){
                                        return $q->order('end_date');
                            }, 'Evaluations.SectionEvents.Sections.Courses' => function($q) use($courseId){
                                return $q->where(['Courses.id' => $courseId]);
                            }])
                          ->where(['evaluation_id IN' => $evaluationIds, 'student_id' => $studentId])
                          ->all();
            
            // pr($evaluations->toArray()); die;
            if(!$evaluations){
                throw new NotFoundException("Record not found exception", 1);
            }

            $categories = array_unique($evaluations->extract('standard.strand.name')->toArray());
            $category = [];
            foreach ($categories as $value) {
                $category[] = $value;
            }

            $categories = $category;

            $this->loadModel('Courses');
            $courseColor = $this->Courses->findById($courseId)->first()->color;
            // pr($categories); die;
            $evaluations =  $evaluations->groupBy('evaluation.section_event.section.course.name')
                          ->map(function($value, $key) use ($categories, $settings, $courseColor){
                               $data = [
                                           'type' => 'column',
                                           'softThreshold' => true,
                                           'pointPlacement' => 'on',
                                           'name' => $key,
                                           'color' => $courseColor
                                       ];

                               $data['data'] = (new Collection($value))
                                               ->groupBy('standard.strand.name')
                                               ->map(function($value, $key) use ($categories, $settings, $courseColor){
                                                    $strandData = [
                                                        'name' => $key
                                                    ];

                                                    $avgData = (new Collection($value))
                                                                      ->groupBy('evaluation.assessment.assessment_type.name')
                                                                      ->map(function($value, $key) use($settings){
                                                                            if($settings[$key.' Weightage']){
                                                                                $collection = (new Collection($value))->extract('scale_value.value')->toArray();
                                                                                $data = [
                                                                                    'scores' => $collection,
                                                                                    'sum' => array_sum($collection),
                                                                                    'count' => count($collection),
                                                                                ];
                                                                                $data['weightedScore'] = $settings[$key.' Weightage']->value * array_sum($data['scores']);

                                                                            }
                                                                            return $data;
                                                                            // pr($data);

                                                                      });
                                                    // pr($avgData->toArray()); die;
                                                    $strandData['x'] = array_search($key, $categories);
                                                    $strandData['y'] = $avgData->sumOf('weightedScore') / $avgData->sumOf('count');
                                                    if($strandData['y'] < 3) {
                                                        $this->adjustBrightness($courseColor, 50);
                                                        // pr($this->shadeColor2($courseColor, 50)); die;
                                                        $strandData['color'] = $this->adjustBrightness($courseColor, 60);
                                                    } 
                                                    return $strandData;
                                               })
                                               // ->reject(function($value, $key){
                                               //  return in_array($value, [null, false, '', []]);
                                               //  })
                                                ->toArray();
                          
                               $data['data'] = array_values($data['data']);
                               return $data;
                          })
                          ->toArray();

            // die;
            $evaluations = array_values($evaluations);
            // pr($evaluations); die;
            $response = [
                'categories' => $categories,
                'series' => $evaluations
            ];

            // pr($response);die;

        if(!$evaluations){
                throw new NotFoundException("Empty Data", 1);
                
         }

        $this->set('response', $response); 
        $this->set('_serialize', ['response']);

                
    }

   public function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
    }

}
