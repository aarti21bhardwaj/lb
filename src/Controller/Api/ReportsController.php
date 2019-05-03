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
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;

/**
 * AssessmentImpacts Controller
 *
 * @property \App\Model\Table\AssessmentImpactsTable $AssessmentImpacts
 *
 * @method \App\Model\Entity\AssessmentImpact[] paginate($object = null, array $settings = [])
 */
class ReportsController extends ApiController
{

  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');    
   
  }


  public function add(){
    if(!$this->request->is('post')){
        throw new MethodNotAllowedException("Request is not post");
        
      }
      // pr($this->request); die;
      $data = $this->request->getData();
      // pr($data); die;
      if(empty($data['report_page_id'])){
        throw new BadRequestException("Missing report_page_id", 1);
      }
      // pr($data); die;
      $reports = $this->Reports->newEntity();

      $reportsData = $this->Reports->find()
                                   ->where(['report_template_id' => $data['report_template_id'], 'grade_id' => $data['grade_id']])
                                   ->order(['sort_order' => 'DESC'])
                                   ->all()
                                   ->toArray();

      
      $data['sort_order'] = $reportsData[0]->sort_order + 1;

      $reports = $this->Reports->patchEntity($reports, $data);
      // pr($reports); die;

      if (!$this->Reports->save($reports)){
             $this->_sendErrorResponse($reports->errors());
      }

      $reportPage = $this->Reports->ReportPages->find()->where(['id' => $reports->report_page_id])->first();
      // pr($reportPage); die;
      $response = array();
      $response['status'] = true;
      $response['data'] = [
                             'id' => $reports->id,
                             'text' => $reportPage->title,
                             'parent_id' => NULL,
                             'report_page_id' => $reportPage->id
                          ];

      $this->set(compact('response'));
      $this->set('_serialize', ['response']);
  }


  public function updateReports(){
    // pr($this->request->getData());die;
    if(!$this->request->is('post')){
        throw new MethodNotAllowedException("Request is not put");
    }

    $data = $this->request->getData();
    // pr($data); die;
    $reportTemplateId = $this->request->query('report_template_id');
    $gradeId = $this->request->query('grade_id');
    $reports = [];
    foreach ($data as $key => $value) {
      if($value['id'][0] == 'c'){
          $courseId = substr($value['id'], 1);
          $reportData = $this->Reports->find()
                                      ->where(['report_template_id' => $reportTemplateId, 'grade_id' => $gradeId, 'course_id' => $courseId, 'report_page_id IS NULL'])
                                      ->first();
        }else if($value['id'][0] == 'p'){
          $reportPageId = substr($value['id'], 1);
          $reportData = $this->Reports->find()
                                      ->where(['report_template_id' => $reportTemplateId, 'grade_id' => $gradeId, 'report_page_id' => $reportPageId, 'course_id IS NULL'])
                                      ->first();
       }

       $reqData['sort_order'] = $key + 1;
       $reportData = $this->Reports->patchEntity($reportData, $reqData);
       $reports[] = $reportData;
    }
    // pr($reports); die;
    $connection = ConnectionManager::get('default');
    $response = $connection->transactional(function () use ($reports){
      if(!$this->Reports->saveMany($reports)){
        throw new InternalErrorException('Something went wrong in updating');
      };
      $this->set('response', $reports);
      $this->set('_serialize', ['response']);
    });
       // if(!$this->Reports->save($reportData)){
       //    throw new InternalErrorException('Something went wrong during updating the sort order');
       // }
       // $this->set('response', $reportData);
       // $this->set('_serialize', ['response']);
    // pr($data); die;
    // $connection = ConnectionManager::get('default');
    // $response = $connection->transactional(function () use ($reportTemplateId,$gradeId, $data){
    //   $reportData = [];
    //   foreach ($data as $key => $value) {

    //     if($value['id'][0] == 'c'){
    //       $courseId = substr($value['id'], 1);
    //       $reportPageId = NULL;
    //     }else{
    //       $courseId = NULL;
    //       if($value['id'][0] == 'p'){
    //         $reportPageId = substr($value['id'], 1);
    //       }else{
    //         $reportPageId = $value['id'];
    //       }
    //     }

    //     $reportData[] = [
    //                         'course_id' => $courseId,
    //                         'sort_order' => $key + 1,
    //                         'report_template_id' => $this->request->query('report_template_id'),
    //                         'grade_id' => $this->request->query('grade_id'),
    //                         'report_page_id' => $reportPageId
    //                     ]; 
    //   }
      
      
    //   $this->Reports->deleteAll(['report_template_id' => $reportTemplateId, 'grade_id' => $gradeId]);

    //   $reports = $this->Reports->newEntities($reportData);
    //   // pr($reports); die;

    //   if(!$this->Reports->saveMany($reports)){
    //     // $this->_sendErrorResponse($reports->errors()); 
    //     pr('some error occur');
    //   }
    //   $this->set('response', $reports);
    //   $this->set('_serialize', ['response']);
      
    // });


    // $this->Reports->updateAll(['report_template_id' => $this->request->query('report_template_id')])
  }

  public function delete($reportPageId = null){
    if(!$this->request->is(['post', 'delete'])){
            throw new MethodNotAllowedException('Request is not delete');
      }

    $report = $this->Reports->find()->where(['report_page_id' => $reportPageId])->first();
    if ($this->Reports->delete($report)) {
        $response['status'] = true;
        $response['message'] = "Deleted Successfully";
    } else {
        throw new InternalErrorException('Something went wrong');
        
    }

    $this->set(compact('response'));
    $this->set('_serialize', ['response']);
  }

  public function uploadImage(){
      if(!$this->request->is(['post'])){
        throw new MethodNotAllowedException(__('BAD_REQUEST'));
      }
    
      $target_path = WWW_ROOT . Configure::read('ImageUpload.uploadImage');
      $file = $_FILES['image_param']['name'];

      $url = Router::url('/', true);
      $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
      $file = time().$file;
      $success = false;
      $data = [];
      if ($file != '') {
        if (move_uploaded_file($_FILES['image_param']['tmp_name'], $target_path.DS.$file)) {
            $data = [
                      'link' => $url.'webroot'.DS.Configure::read('ImageUpload.uploadImage').DS.$file
                    ];
                  
        } 
      }
      
      $this->set('link',$data['link']);
      $this->set('_serialize', ['link']);
    }

}
