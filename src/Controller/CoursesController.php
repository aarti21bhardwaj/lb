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

/**
 * Courses Controller
 *
 * @property \App\Model\Table\CoursesTable $Courses
 *
 * @method \App\Model\Entity\Course[] paginate($object = null, array $settings = [])
 */
class CoursesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $courses = $this->Courses->find()->contain(['Grades', 'LearningAreas']);

        $this->set(compact('courses'));
        $this->set('_serialize', ['courses']);
    }

    /**
     * View method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $course = $this->Courses->get($id, [
            'contain' => ['Grades', 'LearningAreas', 'CampusCourses', 'Sections', 'CourseStrands.Strands', 'CourseStrands.Grades']
        ]);

        $campusCourseId = (new Collection($course->campus_courses))->extract('id')->toArray();

        $campusCourseTeachers = $this->Courses->CampusCourses->CampusCourseTeachers->find()
                                                                                    ->where(['campus_course_id IN' => $campusCourseId])
                                                                                    ->contain(['CampusCourses.Campuses', 'CampusCourses.Courses', 'Teachers'])
                                                                                    ->toArray();

        $this->set('course', $course);
        $this->set('campusCourseTeachers', $campusCourseTeachers);
        $this->set('_serialize', ['course']);
    }

    public function strands(){
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException("Request is not post");
        }

        $reqData = $this->request->getData();

        if(empty($reqData['learning_area_id']) && !isset($reqData['learning_area_id'])){
            throw new BadRequestException('Missing field learning area id');
            
        }
        if(empty($reqData['grade_id']) && !isset($reqData['grade_id'])){
            throw new BadRequestException('Missing field grade id');
            
        }

        $strands = $this->Courses->CourseStrands->Strands->find()
                                                         ->where(['learning_area_id' => $reqData['learning_area_id'], 'grade_id' => $reqData['grade_id']])
                                                        ->all()
                                                        ->toArray();
        
        $data =array();
        if(!$strands){
            $data['data']['message']='No strands set for this learning area and grade, please set them first';
        }else{
            $data['data']=$strands;
        }

        $this->set('response', $data);
        $this->set('_serialize', ['response']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
        
        if ($this->request->is('post')) {
            $reqData = $this->request->getData();
            if(empty($reqData['strand_id'])){
                throw new BadRequestException("Strands not selected");
                
            }
            foreach ($reqData['strand_id'] as $strandId){
                $reqData['course_strands'][] = [
                                                  'strand_id' =>$strandId
                                               ];
            }
            foreach ($reqData['teacher_id'] as  $teacherId) {
                        $data['campus_course_teachers'][] = ['teacher_id' => $teacherId];
                    }
            $reqData['campus_courses'][] = ['campus_id' => $this->request->getData('campus_id')];
            $course = $this->Courses->newEntity($reqData, ['associated' => ['CourseStrands','CampusCourses']]);

            // $course = $this->Courses->patchEntity($course, $reqData, ['associated' => ['CourseStrands']]);
            $data = $this->Courses->save($course);
            // pr($data); die;
            if ($data) {
                $campusCourse = $this->Courses->CampusCourses->findByCourseId($course->id)->first();
                if(!empty($campusCourse)){
                    $data = [];
                    foreach ($reqData['teacher_id'] as  $teacherId) {
                        $data[] = [
                                                                'campus_course_id' => $campusCourse->id,
                                                                'teacher_id' => $teacherId
                                                            ];
                    }

                    $campusCourseTeachers = $this->Courses->CampusCourses->CampusCourseTeachers->newEntities($data);
                    $campusCourseTeachers = $this->Courses->CampusCourses->CampusCourseTeachers->patchEntities($campusCourse, $data);
                    $this->Courses->CampusCourses->CampusCourseTeachers->saveMany($campusCourseTeachers);
                }
                
                $this->Flash->success(__('The course has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course could not be saved. Please, try again.'));
        }
        $grades = $this->Courses->Grades->find('list', ['limit' => 200]);
        $learningAreas = $this->Courses->LearningAreas->find('list', ['limit' => 200]);
        // $terms = $this->Courses->Terms->find('list', ['limit' => 200]);
        $campuses = $this->Courses->CampusCourses->Campuses->find()->all()->combine('id', 'name')->toArray();
        $teachers = $this->Courses->CampusCourses->CampusCourseTeachers->Teachers->find()->where(['role_id' => 3])->all()->combine('id', 'first_name')->toArray();
        $strands = $this->Courses->CourseStrands->Strands->find()->all()->combine('id', 'name')->toArray();
        $this->set(compact('course', 'grades', 'learningAreas', 'campuses', 'teachers', 'strands'));
        $this->set('_serialize', ['course']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $course = $this->Courses->get($id, [
            'contain' => ['CampusCourses.Campuses', 'CampusCourses.CampusCourseTeachers.Teachers']
        ]);
        // pr($course); die;
        
        $campusCourseTeachers = (new Collection($course->campus_courses))->extract('campus_course_teachers.{*}')->toArray();
        $courseTeachers = [];

        foreach ($campusCourseTeachers as $key => $value) {
            $courseTeachers[] = $value->teacher_id;
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $course = $this->Courses->patchEntity($course, $this->request->getData());
            // pr($course); die;
            if ($this->Courses->save($course)) {
                $this->Flash->success(__('The course has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course could not be saved. Please, try again.'));
        }
        $grades = $this->Courses->Grades->find('list', ['limit' => 200]);
        $learningAreas = $this->Courses->LearningAreas->find('list', ['limit' => 200]);
        // $terms = $this->Courses->Terms->find('list', ['limit' => 200]);
        $campuses = $this->Courses->CampusCourses->Campuses->find()->all()->combine('id', 'name')->toArray();
        $teachers = $this->Courses->CampusCourses->CampusCourseTeachers->Teachers->find()->where(['role_id' => 3])->all()->combine('id', 'first_name')->toArray();

        $this->set(compact('course', 'grades', 'learningAreas', 'campuses','teachers'));
        $this->set('courseTeachers', $courseTeachers);
        $this->set('_serialize', ['course']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $course = $this->Courses->get($id);
        if ($this->Courses->delete($course)) {
            $this->Flash->success(__('The course has been deleted.'));
        } else {
            $this->Flash->error(__('The course could not be deleted. Please, try again.'));
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
