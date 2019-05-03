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
class StandardsController extends ApiController
{

    public function initialize(){
        parent::initialize();
        $this->Auth->allow(['get']);
    }
    /**
     * View method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $standard = $this->Standards->findById($id)->contain(['StandardGrades'])->first();

        $standard->standardGrades = [];
        foreach ($standard->standard_grades as $key => $value) {
            $standard->standardGrades[] = $value->grade_id;
        }
        $this->set('standard', $standard);
        $this->set('_serialize', ['standard']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Request is not post');
            
        }
        $data = $this->request->data;
        if(!isset($data['name']) && empty($data['name'])){
                throw new BadRequestException("Missing field name in standards");
        }

        if(!isset($data['code']) && empty($data['code'])){
            throw new BadRequestException("Missing field code in standards");
        }

        if(!isset($data['grades']) && empty($data['grades'])){
            throw new BadRequestException("Missing grades");
        }

        // pr($this->request->data); die;

        foreach ($data['grades'] as $gradeId) {
            $data['standard_grades'][] = [
                                            'grade_id' => $gradeId
                                         ];
        }


        $standard = $this->Standards->newEntity();
        $standard = $this->Standards->patchEntity($standard, $data, ['associated' => ['StandardGrades']]);
        // pr($standard); die;

        if (!$this->Standards->save($standard)) {
             throw new InternalErrorException('Something went wrong');
        }
        if($standard->parent_id == NULL){
            $standard->parent_id = 's'.$standard->strand_id;
        }
        $response = array();
        $response['status'] = true;
        $response['data'] = ['id' => $standard->id,
                             'text' => $standard->name,
                             'parent_id' => $standard->parent_id,
                             'strand_id' => $standard->strand_id];

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if(!$this->request->is(['put'])){
            throw new MethodNotAllowedException('Request is not put');
        }
        $data = $this->request->data;
        if(!isset($data['name']) && empty($data['name'])){
                throw new BadRequestException("Missing field name in standards");
        }

        if(!isset($data['code']) && empty($data['code'])){
            throw new BadRequestException("Missing field code in standards");
        }

        $standard = $this->Standards->findById($id)->contain(['StandardGrades'])->first();

        $data = $this->request->getData();
        // pr($data); die;
        foreach ($data['grades'] as $gradeId) {
            $data['standard_grades'][] = [
                                            'standard_id' => $id,
                                            'grade_id' => $gradeId
                                         ];
        }

        $standard = $this->Standards->patchEntity($standard, $data, ['associated' => ['StandardGrades']]);

        if (!$this->Standards->save($standard)) {
             throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = [
                                'id' => $standard->id,
                                'text' => $standard->name,
                                'parent_id' => $standard->parent_id,
                                'strand_id' => $standard->strand_id
                            ];
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if(!$this->request->is(['post', 'delete'])){
            throw new MethodNotAllowedException('Request is not delete');
        }
        $this->request->allowMethod(['post', 'delete']);
        $standard = $this->Standards->findById($id)->first();
        if ($this->Standards->delete($standard)) {
            $response['status'] = true;
            $response['message'] = "Deleted Successfully";
        } else {
            throw new InternalErrorException('Something went wrong');
            
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);

    }

    public function get(){
        if(!$this->request->is(['get'])){
            throw new MethodNotAllowedException(__('BAD_REQUEST'));
        }

        $this->loadModel('Curriculums');
        $this->loadModel('Grades');

        $curriculums = $this->Curriculums->find()->contain('LearningAreas')
                                                 ->all()
                                                 ->toArray();

        $grades = $this->Grades->find()->all()
                                       ->toArray();

        $this->set(compact('curriculums','grades'));
        $this->set('_serialize', ['curriculums','grades']);

    }
}
