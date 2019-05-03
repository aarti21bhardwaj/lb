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
use Cake\Log\Log;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class EvaluationsController extends ApiController
{
    public function view($id){
      if(!$this->request->isGet()){
        throw new MethodNotAllowedException();
      }
      // pr($this->request->query['student_id']);die;
      if(!isset($this->request->query['student_id'])){
        return $this->getStudents($id);
      }
      $student_id = $this->request->query['student_id'];
      if(!$student_id){
        throw new BadRequestException("Missing Student Id");
      }

      $evaluations = $this->Evaluations->findById($id)
                                ->contain(['Assessments.Strands',
                                  'Assessments.Standards.Strands',
                                  'Assessments.Impacts.ImpactCategories',
                                  'Sections.SectionStudents.Students' => function($q) use($student_id){
                                    return $q->where(['Students.id' => $student_id]);
                                  },'Scales.ScaleValues', 'EvaluationFeedbacks' => function($q) use ($student_id) {
                                    return $q->where(['student_id' => $student_id ]);
                                  },'EvaluationStandardScores'=> function($q) use ($student_id) {
                                    return $q->where(['student_id' => $student_id ]);
                                  },'EvaluationImpactScores'=> function($q) use ($student_id) {
                                    return $q->where(['student_id' => $student_id ]);
                                  }])
                                ->first();
      

      // TODO: Make a setting of standard and impact scale
      $this->loadModel('Scales');
      $sessionData = $this->request->session()->read('campusSettings');
      // pr($sessionData);die;

      //Pull from settings and set the key for the scale here.
      $evaluations->impacts_scale = $this->Scales->findById($sessionData['Impact Scale']->value)->contain(['ScaleValues'])->first()->toArray();
      $evaluations->standards_scale = $this->Scales->findById($sessionData['Standard Scale']->value)->contain(['ScaleValues'])->first()->toArray();

      // $evaluations->impacts_scale = $this->Scales->findById(1)->contain(['ScaleValues'])->first()->toArray();
      // $evaluations->standards_scale = $this->Scales->findById(2)->contain(['ScaleValues'])->first()->toArray(); 

      $evalId = $evaluations->id;
      $this->loadModel('ImpactCategories');
      $impact_categories = $this->ImpactCategories->find()
                                                  ->matching('Impacts.AssessmentImpacts.Assessments.Evaluations', 
                                                              function($q) use ($evalId){
                                                                return $q->where(['Evaluations.id' =>$evalId]);
                                                    })
                                                  ->all()
                                                  ->indexBy('id')
                                                  ->toArray();

      $evaluations->assessment->impact_categories = array_values($impact_categories);

      $impacts_score = new Collection ($evaluations->evaluation_impact_scores);
      $evaluations->evaluation_impact_scores = $impacts_score->indexBy('impact_id')->toArray();
      
      $standards_score = new Collection ($evaluations->evaluation_standard_scores);
      $evaluations->evaluation_standard_scores = $standards_score->indexBy('standard_id');

      $impacts = new Collection ($evaluations->assessment->impacts);
      $impacts = $impacts->groupBy('impact_category_id')->toArray();
      $evaluations->assessment->impacts = $impacts;

      $standards = new Collection ($evaluations->assessment->standards);
      $standards = $standards->groupBy('strand_id')->toArray();
      $evaluations->assessment->standards = $standards;

      $success = true;

      $this->set('data',$evaluations);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function edit($id = null){
      if (!$this->request->is(['patch', 'post', 'put'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $evaluations = $this->Evaluations->get($id, [
            'contain' => []
      ]);

      $data = $this->request->data;
      $evaluations = $this->Evaluations->patchEntity($evaluations,$data);
      
      if (!$this->Evaluations->save($evaluations)) {  
        throw new Exception("Evaluations could not be saved.");
      }

      $success = true;

      $this->set('data',$evaluations);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function delete($id = null){
      if (!$this->request->is(['delete'])) {
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      if(!$id){
        throw new BadRequestException();
      }
      $evaluations = $this->Evaluations->get($id);

      if (!$this->Evaluations->delete($evaluations)) {
          throw new Exception("Evaluations could not be deleted.");
      } 
        
      $success = true;
      
      $this->set(compact('success'));
      $this->set('_serialize', ['success']);
    }

    public function evaluationSave(){
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
      $data = $this->request->data;
      if(!isset($data['evaluation_id']) || !$data['evaluation_id']){
        throw new BadRequestException("Missing Evaluation id");
      }
      if(!isset($data['student_id']) || !$data['student_id']){
        throw new BadRequestException("Missing Student id");
      }
      
      $evaluationId = $data['evaluation_id'];
      $studentId = $data['student_id'];
      $evaluations = $this->Evaluations->get($evaluationId, [
            'contain' => []
      ]);
      if(!empty($data['evaluation_feedbacks'])){

        $this->loadModel('EvaluationFeedbacks');
        $this->EvaluationFeedbacks->deleteAll([
                                                    'student_id' => $studentId,
                                                    'evaluation_id' => $evaluationId
                                                  ]);

        if(isset($data['evaluation_feedbacks']['is_completed']) && $data['evaluation_feedbacks']['is_completed'] == 1){
          $data['evaluation_feedbacks']['is_completed'] = $data['evaluation_feedbacks']['is_completed'];  
        }
        $data['evaluation_feedbacks']['student_id'] = $data['student_id'];
        $data['evaluation_feedbacks'] = [$data['evaluation_feedbacks']];
      }
      if(!empty($data['evaluation_standard_scores'])){
        
        $standardIds = (new Collection($data['evaluation_standard_scores']))->extract('standard_id')->toArray();
        
        $this->loadModel('EvaluationStandardScores');
        $this->EvaluationStandardScores->deleteAll([
                                                          'student_id' => $studentId,
                                                          'evaluation_id' => $evaluationId,
                                                          'standard_id IN' => $standardIds
                                                      ]);
        
        $standardData = (new Collection($data['evaluation_standard_scores']))->map(function($value, $key) use($evaluationId,$studentId){
                                              $value['student_id'] = $studentId;
                                              return $value;
                                          })
                                          ->toArray();

        $data['evaluation_standard_scores'] = $standardData;
      }

      if(!empty($data['evaluation_impact_scores'])){
        $impactIds = (new Collection($data['evaluation_impact_scores']))->extract('impact_id')->toArray();
        $this->loadModel('EvaluationImpactScores');
        $this->EvaluationImpactScores->deleteAll([
                                                        'student_id' => $studentId,
                                                        'evaluation_id' => $evaluationId,
                                                        'impact_id IN' => $impactIds
                                                  ]);
        
        $impactData = (new Collection($data['evaluation_impact_scores']))->map(function($value, $key) use($evaluationId,$studentId){
                                              $value['student_id'] = $studentId;
                                              return $value;
                                          })
                                          ->toArray();

        $data['evaluation_impact_scores'] = $impactData;
      }
      $evaluations = $this->Evaluations->patchEntity($evaluations, $data, ['associated' => ['EvaluationStandardScores','EvaluationImpactScores','EvaluationFeedbacks']]);
      
      if (!$this->Evaluations->save($evaluations, ['associated' => ['EvaluationStandardScores','EvaluationImpactScores','EvaluationFeedbacks']])) {   
        Log::write('error',$evaluations);
        throw new Exception("Evaluations and its corresponding standards, impacts and feedbacks could not be saved.");
      }

      $success = true;

      $this->set('data',$evaluations);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);

    }

    public function getStudents($id = null){
      if(!$this->request->isGet()){
        throw new MethodNotAllowedException();
      }

      if(!$id){
        throw new BadRequestException();
      }

      $evaluations = $this->Evaluations->findById($id)->contain(['Sections.SectionStudents.Students'])->first();
      if(!$evaluations){
        throw new NotFoundException(__('Evaluation Data has not been available for this corresponding Evaluation Id.'));
      }

      //Fetch the evaluation_feedback is complete for the evaluation
      $this->loadModel('EvaluationFeedbacks');
      $evaluationFeedbacks = $this->EvaluationFeedbacks->findByEvaluationId($id)
                                                      ->all()
                                                      ->indexBy('student_id')
                                                      ->toArray();

      if(!empty($evaluations->section->section_students)){
          $data = (new Collection($evaluations->section->section_students))->map(function($value, $key) use($evaluationFeedbacks){
                        
                        $value->full_name = $value->student->full_name;
                        
                        if(isset($evaluationFeedbacks[$value->student_id])){
                          $value->is_completed = $evaluationFeedbacks[$value->student_id]->is_completed;
                        }else{
                          $value->is_completed = null;
                        }  
                        return $value;    
                      })
                  ->sortBy('student.last_name',SORT_ASC,SORT_STRING)
                  ->toArray();

          $data = array_values($data);
          
      }else{
        throw new NotFoundException(__('Section Student data has not been available for this corresponding Evaluation Id.'));
      }

      $success = true;

      $this->set('data',$data);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
    }

    public function getEvaluations($sectionId = null){
      if(!$this->request->isGet()){
        throw new MethodNotAllowedException();
      }

      if(!$sectionId){
        throw new BadRequestException();
      }

      $listData = $this->Evaluations->findBySectionId($sectionId)
                                    ->contain(['SectionEvents', 'Sections' ,'Assessments.AssessmentTypes'])
                                    ->order(['SectionEvents.end_date' => 'DESC'])
                                    ->where(['Assessments.is_accessible' => true])
                                    ->all()
                                    ->toArray();
      
      $success = false;
      
      if(!empty($listData)){
        $success = true;
      }

      $this->set('data',$listData);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);

    }

    public function updateArchievedStatus($id = null){
        if(!isset($id) || !$id){
           throw new BadRequestException("Missing Evaluation Id");
        }

        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $evaluation = $this->Evaluations->findById($id)->first();
        if(!$evaluation){
           throw new NotFoundException(__('Evaluation not found for this corresponding evaluation id.'));
        }

        $evaluation = $this->Evaluations->updateAll(    ['is_archived' => !$evaluation->is_archived], // fields
                                                        ['id' => $evaluation->id] // conditions
                                                    );
        Log::write('debug', $evaluation);
        
        $success = true;
        
        $this->set('data',$evaluation);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }
}