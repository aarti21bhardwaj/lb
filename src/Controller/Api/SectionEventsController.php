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
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 *
 * @method \App\Model\Entity\Standard[] paginate($object = null, array $settings = [])
 */
class SectionEventsController extends ApiController
{

    public function add(){
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }
        $data = $this->request->data;
        $sectionEventsModel = TableRegistry::get('SectionEvents');
        $response = $sectionEventsModel->connection()->transactional(function () use ($sectionEventsModel, $data){
            $sectionEvents = [];
            foreach ($data as $key => $value) {
                $sectionEvents[] = $this->_postData($value);
            }
            $success = true;

            $this->set('data',$sectionEvents);
            $this->set('status',$success);
            $this->set('_serialize', ['status','data']);
        });
    }

    private function _postData($data){
        
        if(!isset($data['end_date']) || !$data['end_date']){
            throw new BadRequestException("Missing End date");
        }
        if(!isset($data['section_id']) || !$data['section_id']){
           throw new BadRequestException("Missing Section Id");
        }
        if($data['start_date']){
            $data['start_date'] = new Date($data['start_date']);
          }
          if($data['end_date']){
            $data['end_date'] = new Date($data['end_date']);
          }
        
        //Prepare event for Assessment
        if(isset($data['assessment_id']) && $data['assessment_id']){

            $assessmentId = $data['assessment_id'];
            $this->loadModel('Assessments');
            $assessmentData = $this->Assessments->findById($assessmentId)->first();
            if(!$assessmentData){
               throw new NotFoundException(__('Assessment Data has not been available for this corresponding Assessment Id.'));
            }
            $data['object_name'] = 'assessment';
            $data['object_identifier'] = $assessmentId;
            $data['name'] = $assessmentData->name;

            $evaluationData = [];
            if(!$assessmentData->is_accessible == 0){
                    $evaluationData = [
                                            'assessment_id' => $assessmentId,
                                            'section_id' => $data['section_id'],
                                            'scale_id' => 1,
                                            'status' => 1,
                                      ];
                    //Save evaluation
                    $this->loadModel('Evaluations');
                    $evaluations = $this->Evaluations->newEntity();
                    $evaluations = $this->Evaluations->patchEntity($evaluations,$evaluationData);
                    if (!$this->Evaluations->save($evaluations)) { 
                       //pr($evaluations); die;
                        throw new Exception("Evaluations could not be saved.");
                    }
                    $data['object_name'] = 'evaluation';
                    $data['object_identifier'] = $evaluations->id; //Save and get evaluation
            }
            
        }

        //Prepare data for adding unit to section events.
        if(isset($data['unit_id']) && $data['unit_id']){
            $unitId = $data['unit_id'];
            $this->loadModel('Units');
            $unitData = $this->Units->findById($unitId)->first();
            if(!$unitData){
               throw new NotFoundException(__('Unit not found.'));
            }
            $data['object_name'] = 'unit';
            $data['object_identifier'] = $unitId;
            $data['name'] = $unitData->name;
        }

        $sectionEvents = $this->SectionEvents->newEntity();
        $sectionEvents = $this->SectionEvents->patchEntity($sectionEvents,$data);
        if (!$this->SectionEvents->save($sectionEvents)) { 
           throw new Exception("sectionEvents could not be saved.");
        }

        return $sectionEvents;
        
    }
    private function _getBrightness($hex) {
        // returns brightness value from 0 to 255
       
        // strip off any leading #
        $hex = str_replace('#', '', $hex);
       
        $c_r = hexdec(substr($hex, 0, 2));
        $c_g = hexdec(substr($hex, 2, 2));
        $c_b = hexdec(substr($hex, 4, 2));
       
        return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
       } 

    public function view($sectionId){
        if(!$this->request->isGet()){
            throw new MethodNotAllowedException();
        }

        if(!$sectionId){
            throw new BadRequestException("Missing Section Id");
        }

        $this->loadModel('Units');
        $this->loadModel('Evaluations');

        $evaluations = $this->Evaluations->find()->where(['is_archived' => 0]);
        $evaluationIds = $evaluations->extract('id')->toArray();
        $assementEvaluations = $evaluations->combine('id', 'assessment_id')->toArray();
        // pr($evaluationIds); die;
        $unitIds = $this->Units->find()->where(['is_archived' => 0])->extract('id')->toArray();
        
$sectionEvents = $this->SectionEvents->findBySectionId($sectionId);
                                             // ->where(['section_id' => $sectionId])
                                            /* ->where(['object_name' => 'unit', 'object_identifier IN' => $unitIds])
                                             ->orWhere(['object_name' => 'evaluation', 'object_identifier IN' => $evaluationIds])
                                             ->contain(['Sections'])
                                             ->map(function($value, $key) use($assementEvaluations){
                                                if($value->object_name == 'evaluation'){
                                                    $value->indexKey = $value->object_name.$assementEvaluations[$value->object_identifier];  
                                                }else{
                                                    $value->indexKey = $value->object_name.$value->object_identifier;
                                                    
                                                }
                                                return $value;
                                             })
                                             ->indexBy('indexKey')
                                             ->toArray();

*/

	if(!empty($unitIds)){
            $sectionEvents = $sectionEvents->where(['object_name' => 'unit', 'object_identifier IN' => $unitIds]);
        }

        if(!empty($unitIds) && !empty($evaluationIds)){
            $sectionEvents = $sectionEvents->orWhere(['object_name' => 'evaluation', 'object_identifier IN' => $evaluationIds]);
        }elseif(empty($unitIds) && !empty($evaluationIds)) {
           $sectionEvents = $sectionEvents->where(['object_name' => 'evaluation', 'object_identifier IN' => $evaluationIds]);
        }

                                             // ->where(['section_id' => $sectionId])
                                             
                                             
        $sectionEvents = $sectionEvents->contain(['Sections'])
                                             ->map(function($value, $key) use($assementEvaluations){
                                                if($value->object_name == 'evaluation'){
                                                    $value->indexKey = $value->object_name.$assementEvaluations[$value->object_identifier];  
                                                }else{
                                                    $value->indexKey = $value->object_name.$value->object_identifier;
                                                    
                                                }
                                                return $value;
                                             })
                                             ->indexBy('indexKey')
                                             ->toArray();
        // pr($sectionEvents); die;
        $sectionEvents = array_values($sectionEvents);

        if(!empty($sectionEvents)){
            $sectionEvents = (new Collection ($sectionEvents))->map(function($value,$key){
                                
                                $value->start = $value->start_date->format('Y-m-d');
                                $value->end = $value->end_date->format('Y-m-d');
                                $value->title = $value->name;
                                $value->color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                                if ($this->_getBrightness($value->color) <= 130) {
                                    $value->textColor = '#fff';
                                }
//pr($value);
                                if($value->object_name == 'unit'){
                                    // pr('here');
                                   $unit =  $this->Units->findById($value->object_identifier)->contain(['Templates'])->first();
                                  if($unit){
					 $value->template_name = $unit->template->slug;
					}
                                }
                                // if($value->object_name == 'assessment'){
                                //     $value->color = $this->_random_color(); #green color code
                                // }else if($value->object_name == 'evaluation'){
                                //     $value->color = '#FFFF00'; #yellow color code
                                // }else{
                                //     $value->color = '#0000FF'; #blue color code 
                                // }
                                return $value;
            })->toArray();
        }
        $success = true;

        $this->set('data',$sectionEvents);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    private function _getAssesmentData($assessmentId){
        $this->loadModel('Assessments');
        $assessmentData = $this->Assessments->findById($assessmentId)
                                            ->first();
        $data = [];
        
        if($assessmentData){
            $this->loadModel('SectionEvents');
            if($assessmentData->is_accessible == 1){
                
                $this->loadModel('Evaluations');
                $evaluation = $this->Evaluations->findByAssessmentId($assessmentId)->all();
                $evaluationIds = (new Collection ($evaluation))->extract('id')->toArray();

                if($evaluation){
                    
                    $data = $this->SectionEvents->find()
                                                ->where(['object_identifier IN' => $evaluationIds,'object_name' => 'evaluation'])
                                                ->contain('Sections.Terms')
                                                ->all()
                                                ->toArray();
                }
                
            }else{

                $data = $this->SectionEvents->find()
                                            ->where(['object_identifier' => $assessmentId,'object_name' => 'assessment'])
                                            ->contain('Sections.Terms')
                                            ->all()
                                            ->toArray();
            }
        }
        return $data;
    }

    private function _getUnitData($unitId){
        $this->loadModel('Units');
        $unitData = $this->Units->findById($unitId)
                                ->first();

        if(!$unitData){
            throw new NotFoundException(__('Units not found'));
        }

        $this->loadModel('SectionEvents');
        $data = $this->SectionEvents->find()
                                    ->where(['object_identifier' => $unitId ,'object_name' => 'unit'])
                                    ->contain('Sections.Terms')
                                    ->all()
                                    ->toArray();

        return $data;
    }


    public function getPublishedContent(){
        if(!$this->request->isGet()){
            throw new MethodNotAllowedException();
        }
        $objectId = $this->request->query['object_id'];
        if(!isset($objectId) || !$objectId){
           throw new BadRequestException("Missing Object Id");
        }
        $objectName = $this->request->query['object_name'];
        if(!isset($objectName) || !$objectName){
           throw new BadRequestException("Missing Object Name");
        }
        if($objectName == 'assessment'){
            $data = $this->_getAssesmentData($objectId);  
        }else if($objectName == 'unit'){
            $data = $this->_getUnitData($objectId);  
        }

        $reqData = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                if($value->section->term->is_active == 1){
                    $reqData[] = $value;
                }
            }
        }

        $success = true;

        $this->set('data',$reqData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);

    }
}
