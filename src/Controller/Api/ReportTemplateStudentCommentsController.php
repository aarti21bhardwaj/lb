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
class ReportTemplateStudentCommentsController extends ApiController
{
    //Adds Student Reflection and teachers reflections
    public function add(){
      // pr('here'); die;
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException("Request is not post");
            
        }

        $reqData = $this->request->getData();
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

        $this->loadModel('Sections');
        $section = $this->Sections->findById($sectionId)->contain(['Courses'])->first();
        
        $this->loadModel('ReportTemplates');
        $reportTemplates = $this->ReportTemplates->find()
                                                 ->matching('ReportTemplateGrades', function($q) use($section){
                                                        return $q->where(['ReportTemplateGrades.grade_id' => $section->course->grade_id]);
                                                    })
                                                 ->contain(['ReportingPeriods.Terms.Sections' => function($x)use($sectionId){
                                                      return $x->where(['Sections.id' => $sectionId ]);
                                                    }])
                                                 ->first();
        
        $reqData['report_template_id'] = $reportTemplates->id;
        $reqData['teacher_id'] = $this->Auth->user('id');

        $studentAndTeacherReflections = $this->ReportTemplateStudentComments->newEntity();
        $studentAndTeacherReflections = $this->ReportTemplateStudentComments->patchEntity($studentAndTeacherReflections, $reqData);

        if(!$this->ReportTemplateStudentComments->save($studentAndTeacherReflections)){
            // pr($studentAndTeacherReflections->errors());
            throw new InternalErrorException("Something went wrong", 1);
            
        }

        $this->set('response', $studentAndTeacherReflections);

        $this->set('_serialize', ['response']);

    }

    public function getStudentRecord($studentId = null){
      if(!$this->request->is('get')){
            throw new MethodNotAllowedException("Request is not get");
            
      }

      $studentAndTeacherReflections = $this->ReportTemplateStudentComments->find()
                                                                         ->where(['student_id' => $studentId])
                                                                         ->first();

     if(!$studentAndTeacherReflections){
        throw new NotFoundException('Record Not found');
     }

      $this->set('response', $studentAndTeacherReflections);

      $this->set('_serialize', ['response']);

    }

    public function edit($id = null){
      if(!$this->request->is('put')){
            throw new MethodNotAllowedException("Request is not put");
            
      }

      $studentAndTeacherReflections = $this->ReportTemplateStudentComments->findById($id)
                                                                         ->first();

      $reqData = $this->request->getData();



      if(!$studentAndTeacherReflections){
        throw new NotFoundException('Record Not found');
      }

      $studentAndTeacherReflections = $this->ReportTemplateStudentComments->patchEntity($studentAndTeacherReflections, $reqData);

      if(!$this->ReportTemplateStudentComments->save($studentAndTeacherReflections)){
            throw new InternalErrorException("Something went wrong", 1);
            
       }

      $this->set('response', $studentAndTeacherReflections);

      $this->set('_serialize', ['response']);

    } 

}
