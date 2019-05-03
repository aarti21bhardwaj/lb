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
 * AssessmentImpacts Controller
 *
 * @property \App\Model\Table\AssessmentImpactsTable $AssessmentImpacts
 *
 * @method \App\Model\Entity\AssessmentImpact[] paginate($object = null, array $settings = [])
 */
class ReportSettingsController extends ApiController
{
  // function to set strands and standards in particular templates
   public function setStrandsAndStandards(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request method is not post");
        }

        $data = $this->request->getData();
        // pr($data); die;

        if(!$data || empty($data)){
            throw new BadRequestException("request data is missing", 1);
            
        }

        if(!$data['grade_id'] && empty($data['grade_id'])){
            throw new BadRequestException("Missing grade_id", 1);
            
        }

        if(!$data['report_template_id'] && empty($data['report_template_id'])){
            throw new BadRequestException("Missing 'report_template_id'", 1);
            
        }

        $this->loadModel('ReportTemplateStrands');
        if(isset($data['strand_id']) && !empty($data['strand_id'])){

          $reportTemplateStrands = $this->ReportTemplateStrands->deleteAll(['report_template_id' => $data['report_template_id'], 'course_id' => $data['course_id']]);
            $reportStrandsData = [];
            foreach ($data['strand_id'] as $strandId) {
                $reportStrandsData[] = [
                                          'report_template_id'  => $data['report_template_id'],
                                          'course_id' => $data['course_id'],
                                          'grade_id' => $data['grade_id'],
                                          'strand_id' => $strandId

                                       ];
            }
            $reportTemplateStrands = $this->ReportTemplateStrands->newEntities($reportStrandsData);
            // pr($reportTemplateStrands);

            if(!$this->ReportTemplateStrands->saveMany($reportTemplateStrands)){
                throw new InternalErrorException("Something went wrong in setting strands for report");
            }

        }

        $this->loadModel('ReportTemplateStandards');
        if(isset($data['standard_id']) && !empty($data['standard_id'])){
          $reportTemplateStandards = $this->ReportTemplateStandards->deleteAll(['report_template_id' => $data['report_template_id'], 'course_id' => $data['course_id']]);
          
            $reportStandardData = [];
            foreach ($data['standard_id'] as $standardId) {
                $reportStandardData[] = [
                                          'report_template_id'  => $data['report_template_id'],
                                          'course_id' => $data['course_id'],
                                          'grade_id' => $data['grade_id'],
                                          'standard_id' => $standardId

                                       ];
            }
            // pr($reportStandardData); die;

            $reportTemplateStandards = $this->ReportTemplateStandards->newEntities($reportStandardData);

            if(!$this->ReportTemplateStandards->saveMany($reportTemplateStandards)){
                throw new InternalErrorException("Something went wrong in setting standard for report");
            }

        }

        $response = array();
        $response['status'] = true;
        if(isset($reportTemplateStrands)){
            $response['data']['strands'] = $reportTemplateStrands;
        }
        if(isset($reportTemplateStandards)){
            $response['data']['standards'] = $reportTemplateStandards;
        }

        $this->set('response',$response);
        $this->set('_serialize', ['response']);
        
   
   }

   public function setImpacts(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request method is not post");
        }

        $data = $this->request->getData();
        // pr($data); die;

        if(!$data || empty($data)){
            throw new BadRequestException("request data is missing", 1);
            
        }

    
        $this->loadModel('ReportTemplateImpacts');
        $reportTemplateImpacts = $this->ReportTemplateImpacts->deleteAll(['report_template_id' => $data['report_template_id'],'course_id' => $data['course_id']]);

        $reportImpactsData = [];
        if(isset($data['impact_id']) && !empty($data['impact_id'])){
            foreach ($data['impact_id'] as  $impactId) {
                $reportImpactsData[] = [
                                              'report_template_id'  => $data['report_template_id'],
                                              'course_id' => $data['course_id'],
                                              'grade_id' => $data['grade_id'],
                                              'impact_id' => $impactId
                                        ];
            }
            // pr($reportImpactsData); die;
            $reportImpacts = $this->ReportTemplateImpacts->newEntities($reportImpactsData);
            if(!$this->ReportTemplateImpacts->saveMany($reportImpacts)){
                throw new InternalErrorException("Something went wrong in setting impacts");
            }
        }


        $response = array();
        $response['status'] = true;
        $response['data'] = $reportImpacts;

        $this->set('response',$response);
        $this->set('_serialize', ['response']);

   }

   // function to get template settings data 
   public function getData(){
    if(!$this->request->is('get')){
        throw new MethodNotAllowedException("Request method is not get", 1);
    }

    $reportTemplateId = $this->request->query('report_template_id');
    $gradeId = $this->request->query('grade_id');

    $courseIds = $this->ReportSettings->find()
                                      ->where(['report_template_id' => $reportTemplateId, 'grade_id' => $gradeId, 'course_status' => true])
                                      ->all()
                                      ->extract('course_id')
                                      ->toArray();

    $this->loadModel('Courses');

    $courses = $this->Courses->find()->where(['id IN' => $courseIds])
                                    ->all()
                                    ->map(function($value, $key){
                                        $value->parent_id = NULL;
                                        $value->text = $value->name;
                                        return $value;
                                    })
                                    ->toArray();

    $this->set('response',$courses);
    $this->set('_serialize', ['response']);

   }

}
