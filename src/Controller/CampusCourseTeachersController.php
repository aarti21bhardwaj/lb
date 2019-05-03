<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;

/**
 * CampusCourseTeachers Controller
 *
 * @property \App\Model\Table\CampusCourseTeachersTable $CampusCourseTeachers
 *
 * @method \App\Model\Entity\CampusCourseTeacher[] paginate($object = null, array $settings = [])
 */
class CampusCourseTeachersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $campusCourseTeachers = $this->CampusCourseTeachers->find()->contain(['CampusCourses.Courses', 'Teachers'])->all();

        $this->set(compact('campusCourseTeachers'));
        $this->set('_serialize', ['campusCourseTeachers']);
    }

    /**
     * View method
     *
     * @param string|null $id Campus Course Teacher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $campusCourseTeacher = $this->CampusCourseTeachers->get($id, [
            'contain' => ['CampusCourses', 'Teachers']
        ]);

        $this->set('campusCourseTeacher', $campusCourseTeacher);
        $this->set('_serialize', ['campusCourseTeacher']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        if($id){
            $courseTeachers = $this->CampusCourseTeachers->find()->contain(['CampusCourses.Campuses', 'CampusCourses.Courses'])
                                                                 ->where(['campus_course_id' =>$id])
                                                                 ->all()
                                                                 ->toArray();
        }

        if ($this->request->is('post')) {
            $reqData = $this->request->getData();
            $data = [];
            foreach ($reqData['teacher_id'] as  $teacherId) {
                $data[] = [
                            'campus_course_id' => $id,
                            'teacher_id' => $teacherId
                          ];
            }
            $campusCourseTeacher = $this->CampusCourseTeachers->newEntities($data);
            $campusCourseTeacher = $this->CampusCourseTeachers->patchEntities($campusCourseTeacher, $data);
            
            if ($this->CampusCourseTeachers->saveMany($campusCourseTeacher)) {
                $this->Flash->success(__('The campus course teacher has been saved.'));
                if(isset($id)){
                    return $this->redirect(['controller' => 'CampusCourses','action' => 'view', $id]);
                }else{
                    return $this->redirect(['controller' => 'CampusCourseTeachers','action' => 'index']);
                }
            }
            $this->Flash->error(__('The campus course teacher could not be saved. Please, try again.'));
        }
        $campusCourses = $this->CampusCourseTeachers->CampusCourses->find('list', ['limit' => 200]);
        $teachers = $this->CampusCourseTeachers->Teachers->find()->where(['role_id' => 3])->all()->combine('id', 'first_name')->toArray();
        $campuses = $this->CampusCourseTeachers->CampusCourses->Campuses->find('list', ['limit' => 200]);
        $courses = $this->CampusCourseTeachers->CampusCourses->Courses->find('list', ['limit' => 200]);
        $this->set(compact('campusCourseTeacher', 'campusCourses', 'teachers', 'courseTeachers', 'campuses', 'courses'));
        $this->set('_serialize', ['campusCourseTeacher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Campus Course Teacher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $campusCourseTeacher = $this->CampusCourseTeachers->get($id, [
            'contain' => ['CampusCourses.Courses']
        ]);
        // pr($campusCourseTeacher->campus_course); die;
        if ($this->request->is(['put'])) {
            $campusCourseTeacher = $this->CampusCourseTeachers->patchEntity($campusCourseTeacher, $this->request->getData());
            if ($this->CampusCourseTeachers->save($campusCourseTeacher)) {
                $this->Flash->success(__('The campus course teacher has been saved.'));

                return $this->redirect(['controller' => 'Courses','action' => 'view', $campusCourseTeacher->campus_course->course->id]);
            }
            $this->Flash->error(__('The campus course teacher could not be saved. Please, try again.'));
        }
        $campusCourses = $this->CampusCourseTeachers->CampusCourses->find('list', ['limit' => 200]);
        $teachers = $this->CampusCourseTeachers->Teachers->find()->combine('id', 'first_name')->toArray();
        $this->set(compact('campusCourseTeacher', 'campusCourses', 'teachers'));
        $this->set('_serialize', ['campusCourseTeacher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Campus Course Teacher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $campusCourseTeacher = $this->CampusCourseTeachers->get($id);
        if ($this->CampusCourseTeachers->delete($campusCourseTeacher)) {
            $this->Flash->success(__('The campus course teacher has been deleted.'));
        } else {
            $this->Flash->error(__('The campus course teacher could not be deleted. Please, try again.'));
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
