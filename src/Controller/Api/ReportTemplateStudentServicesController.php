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
 * AssessmentStandards Controller
 *
 * @property \App\Model\Table\AssessmentStandardsTable $AssessmentStandards
 *
 * @method \App\Model\Entity\AssessmentStandard[] paginate($object = null, array $settings = [])
 */
class ReportTemplateStudentServicesController extends ApiController
{
    //Adds Student Reflection and teachers reflections
    public function add(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request is not post");
            
        }

        $reqData = $this->request->getData();
        // pr($reqData); die;
        if(!isset($reqData) && empty($reqData)){
            throw new BadRequestException("Request data is missing", 1);
            
        }

        if(!isset($reqData['section_id']) && empty($reqData['section_id'])){
            throw new BadRequestException('Missing section_id', 1);
        }

        if(!isset($reqData['student_id']) && empty($reqData['student_id'])){
            throw new BadRequestException('Missing student_id', 1);
        }
        $sectionId = $reqData['section_id'];
        $this->loadModel('ReportTemplates');
        $reportTemplates = $this->ReportTemplates->find()
                                                 ->contain(['ReportingPeriods.Terms.Sections' => function($x)use($sectionId){
                                                      return $x->where(['Sections.id' => $sectionId ]);
                                                    }])
                                                 ->first();
        
        $reqData['report_template_id'] = $reportTemplates->id;

        $studentServices = $this->ReportTemplateStudentServices->newEntity();
        $studentServices = $this->ReportTemplateStudentServices->patchEntity($studentServices, $reqData);

        if(!$this->ReportTemplateStudentServices->save($studentServices)){
            throw new InternalErrorException("Something went wrong", 1);
            
        }

        $this->set('response', $studentServices);

        $this->set('_serialize', ['response']);

    }

    public function getStudentService($studentId = null){
      if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request is not get");
            
      }

      $studentServices = $this->ReportTemplateStudentServices->find()
                                                             ->where(['student_id' => $studentId])
                                                             ->all();

     if(!$studentServices){
        throw new NotFoundException('Record Not found');
     }

      $this->set('response', $studentServices);

      $this->set('_serialize', ['response']);

    }

    public function edit($studentId = null){
      if(!$this->request->is('put')){
            throw new MethodNotAllowedException("Request is not put");
            
      }
      $reqData = $this->request->getData();

      $studentServices = $this->ReportTemplateStudentServices->find()
                                                             ->where(['student_id' => $studentId, 'special_service_type_id' => $reqData['special_service_type_id']])
                                                             ->first();




      if(!$studentServices){
        throw new NotFoundException('Record Not found');
      }

      $studentServices = $this->ReportTemplateStudentServices->patchEntity($studentServices, $reqData);

      if(!$this->ReportTemplateStudentServices->save($studentServices)){
            throw new InternalErrorException("Something went wrong", 1);
            
       }

      $this->set('response', $studentServices);

      $this->set('_serialize', ['response']);

    }

    public function removeStudentService($studentId = null){
      if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request is not put");
            
      }
      $reqData = $this->request->getData();

      $studentServices = $this->ReportTemplateStudentServices->find()
                                                             ->where(['student_id' => $studentId, 'special_service_type_id' => $reqData['special_service_type_id']])
                                                             ->first();


      if(!$studentServices){
        throw new NotFoundException('Record Not found');
      }

      if(!$this->ReportTemplateStudentServices->delete($studentServices)){
            throw new InternalErrorException("Something went wrong", 1);
            
       }

      $this->set('response', $studentServices);

      $this->set('_serialize', ['response']);

    } 

}
