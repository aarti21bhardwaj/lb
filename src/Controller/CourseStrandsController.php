<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CourseStrands Controller
 *
 * @property \App\Model\Table\CourseStrandsTable $CourseStrands
 *
 * @method \App\Model\Entity\CourseStrand[] paginate($object = null, array $settings = [])
 */
class CourseStrandsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $courseStrands = $this->CourseStrands->find()->contain(['Courses', 'Strands', 'Grades'])->all()->toArray();
        $this->set(compact('courseStrands'));
        $this->set('_serialize', ['courseStrands']);
    }

    /**
     * View method
     *
     * @param string|null $id Course Strand id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $courseStrand = $this->CourseStrands->get($id, [
            'contain' => ['Courses', 'Strands', 'Grades']
        ]);

        $this->set('courseStrand', $courseStrand);
        $this->set('_serialize', ['courseStrand']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($courseId = null)
    {
        $course = null;
        $strandByCourse = null;
        if($courseId){
            $courseData = $this->CourseStrands->Courses->findById($courseId)->first();
            $strandByCourse = $this->CourseStrands->Strands->find()->where(['learning_area_id' => $courseData->learning_area_id])->all()->combine('id', 'name')->toArray();
        }
        if ($this->request->is('post')) {
            $reqData = $this->request->getData();
            $data = [];
            if($courseId){
                foreach ($reqData['strand_id'] as $key => $strandId) {
                    foreach ($reqData['grade_id'] as $gradeId) {
                        $data[] = [
                                    'course_id' => $courseId,
                                    'strand_id' => $strandId,
                                    'grade_id' =>  $gradeId
                                  ];
                     
                    }
                }
            }else{
                foreach ($reqData['strand_id'] as $key => $strandId) {
                    foreach ($reqData['grade_id'] as  $gradeId) {
                         $data[] = [
                                'course_id' => $reqData['course_id'],
                                'strand_id' => $strandId,
                                'grade_id' => $gradeId
                              ];
                    }
                   
                }
            }
            // pr($data); die;
            $courseStrand = $this->CourseStrands->newEntities($data);
            $courseStrand = $this->CourseStrands->patchEntities($courseStrand, $data);
            if ($this->CourseStrands->saveMany($courseStrand)) {
                $this->Flash->success(__('The course strand has been saved.'));
                if(isset($courseId)){
                    return $this->redirect(['controller' => 'Courses','action' => 'view', $courseId]);
                }else{
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__('The course strand could not be saved. Please, try again.'));
        }
        $courses = $this->CourseStrands->Courses->find('list', ['limit' => 200]);
        $strands = $this->CourseStrands->Strands->find('list', ['limit' => 200]);
        $grades = $this->CourseStrands->Grades->find()->all()->combine('id', 'name')->toArray();
        $this->set(compact('courseStrand', 'courses', 'strands', 'courseData', 'strandByCourse', 'grades'));
        $this->set('_serialize', ['courseStrand']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Course Strand id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $courseStrand = $this->CourseStrands->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $courseStrand = $this->CourseStrands->patchEntity($courseStrand, $this->request->getData());
            if ($this->CourseStrands->save($courseStrand)) {
                $this->Flash->success(__('The course strand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course strand could not be saved. Please, try again.'));
        }
        $courses = $this->CourseStrands->Courses->find('list', ['limit' => 200]);
        $strands = $this->CourseStrands->Strands->find('list', ['limit' => 200]);
        $this->set(compact('courseStrand', 'courses', 'strands'));
        $this->set('_serialize', ['courseStrand']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Course Strand id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $courseStrand = $this->CourseStrands->get($id);
        if ($this->CourseStrands->delete($courseStrand)) {
            $this->Flash->success(__('The course strand has been deleted.'));
        } else {
            $this->Flash->error(__('The course strand could not be deleted. Please, try again.'));
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
}
