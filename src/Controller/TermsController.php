<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Log\Log;
use Cake\Core\Configure;



/**
 * Terms Controller
 *
 * @property \App\Model\Table\TermsTable $Terms
 *
 * @method \App\Model\Entity\Term[] paginate($object = null, array $settings = [])
 */
class TermsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $terms = $this->Terms->find()->contain(['AcademicYears', 'Divisions'])->all();

        $data = $this->_listOfArchiveUnits();

        $this->set(compact('terms'));
        $this->set('activeTermCourseUnits', array_values($data));
        $this->set('_serialize', ['terms', 'activeTermCourseUnits']);
    }

    /**
     * View method
     *
     * @param string|null $id Term id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $term = $this->Terms->get($id, [
            'contain' => ['AcademicYears', 'Divisions', 'ReportingPeriods']
        ]);

        $this->set('term', $term);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $term = $this->Terms->newEntity();
        if ($this->request->is('post')) {
            $term = $this->Terms->patchEntity($term, $this->request->getData());
            if ($this->Terms->save($term)) {
                $this->Flash->success(__('The term has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The term could not be saved. Please, try again.'));
        }
        $academicYears = $this->Terms->AcademicYears->find('list', ['limit' => 200]);
        $divisions = $this->Terms->Divisions->find('list', ['limit' => 200]);
        $this->set(compact('term', 'academicYears', 'divisions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Term id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $term = $this->Terms->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $term = $this->Terms->patchEntity($term, $this->request->getData());
            if ($this->Terms->save($term)) {
                $this->Flash->success(__('The term has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The term could not be saved. Please, try again.'));
        }
        $academicYears = $this->Terms->AcademicYears->find('list', ['limit' => 200]);
        $divisions = $this->Terms->Divisions->find('list', ['limit' => 200]);
        $this->set(compact('term', 'academicYears', 'divisions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Term id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $term = $this->Terms->get($id);
        if ($this->Terms->delete($term)) {
            $this->Flash->success(__('The term has been deleted.'));
        } else {
            $this->Flash->error(__('The term could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $session = $this->request->session();
        $superAdminUser = $session->read('superAdminUser');

        if($superAdminUser && ($this->request->params['action'] == 'index' || $this->request->params['action'] == 'view' || $this->request->params['action'] == 'edit' || $this->request->params['action'] == 'add' || $this->request->params['action'] == 'delete')){
          return true;
        }
        
        if (isset($user['role']) && $user['role']->name === 'admin'){
            return true;
        }else{
            return false;
        }

        return parent::isAuthorized($user);
    }


    // function to archive all units in courses for active terms
    public function archiveUnits(){

        if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request method is not get", 1);
            
        }
        
        $termIds = $this->Terms->TermArchiveUnits->find()->extract('term_id')->toArray();

        $terms = $this->Terms->find()
                             ->where(['is_active' => 1])
                             ->contain(['Sections.Courses.Units' => function($q){
                                return $q->where(['Units.is_archived' => 0]);
                             }]);
                             // ->matching('Sections.Courses.Units', function($q){
                             //    return $q->where(['Units.is_archived' => 0]);
                             // });
        if(!empty($termIds)){
            $terms = $terms->andWhere(['Terms.id NOT IN' => $termIds]);
        }
       
        // pr($terms->toArray()); die;
        if(!empty($terms)){

           $data =  $terms->indexBy('id')
                          ->map(function($value, $key){
                            return (new Collection($value->sections))->indexBy('course_id')
                                                                    ->map(function($value, $key){
                                                                        return (new Collection($value->course->units))->extract('id')->toArray();
                                                                     })
                                                                    ->reject(function($value, $key){
                                                                         return in_array($value, [null, false, '', []]);
                                                                     })
                                                                     ->toArray();
                          })
                          ->reject(function($value, $key){
                                         return in_array($value, [null, false, '', []]);
                          })
                          ->toArray();
            // pr($data); die;
            // $termArchiveUnits = [];
            // $unitIds = [];
            $processedUnit = [];
            foreach ($data as $termId => $coursData) {
                foreach ($coursData as $courseId => $units) {
                    foreach ($units as $unitId) {
                         $termArchiveUnits = [
                                        'term_id' => $termId,
                                        'course_id' => $courseId,
                                        'unit_id' => $unitId
                                      ];
                        // pr($unitId);
                         if(!in_array($unitId, $processedUnit)){
                            $processedUnit[] = $unitId;
                            $this->_copyUnit($courseId, $unitId);
                            $this->_archiveAndRoleOverUnits($termArchiveUnits, $unitId);
                         }

                    }   
                }
            }

            // $archiveAndRoleOverUnits = $this->_archiveAndRoleOverUnits($termArchiveUnits, $unitIds);

            $this->set('response', ['status' => true, 'message' => 'Archive Successfully']);
            $this->set('_serialize', ['response']);
        }

    }


    private function _copyUnit($courseId, $unitId){
        $this->loadModel('Units');
        $this->loadModel('AssessmentStandards');
        $this->loadModel('AssessmentImpacts');

        $oldUnitData = $this->Units->findById($unitId)
                            ->contain(['UnitTeachers', 'UnitStandards', 'UnitImpacts', 'UnitContents', 'UnitSpecificContents', 'UnitStrands', 'UnitResources' => function($q){
                                    return $q->where(['object_name' => 'unit']);
                                }, 'UnitReflections' => function($q){
                                    return $q->where(['object_name' => 'unit']); 
                            }, 'Assessments','Assessments.AssessmentStandards', 'Assessments.AssessmentImpacts']);
        $unitData = (new Collection($oldUnitData))->map(function($value, $key) use($courseId){
                                                        $this->_unsetData($value);
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

                                               
      $unitData = $this->Units->newEntity($unitData[0], ['associated' => ['Assessments.AssessmentStandards', 'Assessments.AssessmentImpacts', 'UnitTeachers', 'UnitCourses','UnitStandards', 'UnitImpacts', 'UnitContents', 'UnitSpecificContents', 'UnitStrands', 'UnitResources', 'UnitReflections']]);
      if(!$this->Units->save($unitData)){
        // pr($unitData);
        Log::write('debug', 'Copy of unit id '.$unitId.' is not created');    
      }



      Log::write('debug', 'Unit '.$unitId.' copied successfully');
      $data = ['is_archived' => 1];

      $existingUnit = $this->Units->findById($unitId)->first();
      $updateUnitData = $this->Units->patchEntity($existingUnit, $data);
      
      $this->Units->save($updateUnitData);
      // $this->set('response', $unitData);
      // $this->set('_serialize', ['response']);
      return;

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


    private function _archiveAndRoleOverUnits($termArchiveUnit, $unitId){
        // $this->loadModel('Units');

        // $units = $this->Units->updateAll(['is_archive' => 1],['id IN' => $unitIds]);
        // pr($termArchiveUnit); die;
        $this->loadModel('TermArchiveUnits');

        $termArchiveUnitData= $this->TermArchiveUnits->newEntity();
        $termArchiveUnitData= $this->TermArchiveUnits->patchEntity($termArchiveUnitData, $termArchiveUnit);

            
        if(!$this->TermArchiveUnits->save($termArchiveUnitData)){
            Log::write('debug', 'Unit not archived is '.$unitId);
        }

        return;
 
    }


    public function showArchiveUnits($useCakePdf = false){

      $this->viewBuilder()->layout('default-frame');

      if($useCakePdf){
          if(Configure::read('CakePdf')){
                    $this->viewBuilder()->layout('unit')->options([
                        'pdfConfig' => [
                            'filename' => 'archivedUnit.pdf'
                        ]
                    ]);
                    $this->RequestHandler->renderAs($this, 'pdf', ['attachment' => 'abc']);
                }

          $options = [
                        'margin-top' => '3mm',
                        'margin-bottom' => '3mm',
                        'margin-right' => '3mm',
                        'margin-left' => '2mm',
                        'zoom' => '1',
                     ];

         Configure::write('CakePdf.engine.options', $options);

      }

      $data = $this->_listOfArchiveUnits();
      // $termIds = $this->Terms->TermArchiveUnits->find()->extract('term_id')->toArray();

      // $terms = $this->Terms->find()
      //                      ->where(['is_active' => 1])
      //                      ->contain(['Sections.Courses.Units' => function($q){
      //                           return $q->where(['Units.is_archived' => 0]);
      //                        }, 'Divisions']);

      // // pr($terms->toArray()); die;
      // if(!empty($termIds)){
      //       $terms = $terms->andWhere(['Terms.id NOT IN' => $termIds]);
      // }

      // $data = [];

      // if(!empty($terms)){


      // $data =  $terms->indexBy('id')
      //                  ->map(function($value, $key){
      //                       $courseData = (new Collection($value->sections))->indexBy('course_id')
      //                                                               ->map(function($value, $key){
      //                                                                   if(!empty($value->course->units)){
      //                                                                       $unitData = (new Collection($value->course->units))->map(function($value, $key){
      //                                                                               return [
      //                                                                                        'id' => $value->id,
      //                                                                                        'name' => $value->name
      //                                                                                      ];

      //                                                                       })
      //                                                                       ->reject(function($value, $key){
      //                                                                                            return in_array($value, [null, false, '', []]);
      //                                                                                        })
      //                                                                       ->toArray();
      //                                                                       return [
      //                                                                                'id' => $value->course->id,
      //                                                                                'name' => $value->course->name,
      //                                                                                'units' => $unitData

      //                                                                              ];

      //                                                                   }
      //                                                                })
      //                                                               ->reject(function($value, $key){
      //                                                                    return in_array($value, [null, false, '', []]);
      //                                                                })
      //                                                                ->toArray();
      //                       if(!empty($courseData)){
      //                           $data = [
      //                                     'id' => $value->id,
      //                                     'name' => $value->name,
      //                                     'division_name' => $value->division->name,
      //                                     'courses' => array_values($courseData)
      //                                   ];

      //                           return $data;
      //                       }

      //                 })
      //                 ->reject(function($value, $key){
      //                                return in_array($value, [null, false, '', []]);
      //                 })
      //                 ->toArray();

      //   }

        // pr($data); die;
        $this->set('activeTermCourseUnits', array_values($data));
        $this->set('_serialize', ['activeTermCourseUnits']);

    }


    private function _listOfArchiveUnits(){
      $termIds = $this->Terms->TermArchiveUnits->find()->extract('term_id')->toArray();

      $terms = $this->Terms->find()
                           ->where(['is_active' => 1])
                           ->contain(['Sections.Courses.Units' => function($q){
                                return $q->where(['Units.is_archived' => 0]);
                             }, 'Divisions']);

      // pr($terms->toArray()); die;
      if(!empty($termIds)){
            $terms = $terms->andWhere(['Terms.id NOT IN' => $termIds]);
      }

      $data = [];

      if(!empty($terms)){


      $data =  $terms->indexBy('id')
                       ->map(function($value, $key){
                            $courseData = (new Collection($value->sections))->indexBy('course_id')
                                                                    ->map(function($value, $key){
                                                                        if(!empty($value->course->units)){
                                                                            $unitData = (new Collection($value->course->units))->map(function($value, $key){
                                                                                    return [
                                                                                             'id' => $value->id,
                                                                                             'name' => $value->name
                                                                                           ];

                                                                            })
                                                                            ->reject(function($value, $key){
                                                                                                 return in_array($value, [null, false, '', []]);
                                                                                             })
                                                                            ->toArray();
                                                                            return [
                                                                                     'id' => $value->course->id,
                                                                                     'name' => $value->course->name,
                                                                                     'units' => $unitData

                                                                                   ];

                                                                        }
                                                                     })
                                                                    ->reject(function($value, $key){
                                                                         return in_array($value, [null, false, '', []]);
                                                                     })
                                                                     ->toArray();
                            if(!empty($courseData)){
                                $data = [
                                          'id' => $value->id,
                                          'name' => $value->name,
                                          'division_name' => $value->division->name,
                                          'courses' => array_values($courseData)
                                        ];

                                return $data;
                            }

                      })
                      ->reject(function($value, $key){
                                     return in_array($value, [null, false, '', []]);
                      })
                      ->toArray();

        }

    return $data;
    }
}
