<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CampusCourses Controller
 *
 * @property \App\Model\Table\CampusCoursesTable $CampusCourses
 *
 * @method \App\Model\Entity\CampusCourse[] paginate($object = null, array $settings = [])
 */
class CampusCoursesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $campusCourses = $this->CampusCourses->find()->contain(['Campuses', 'Courses'])->all();

        $this->set(compact('campusCourses'));
        $this->set('_serialize', ['campusCourses']);
    }

    /**
     * View method
     *
     * @param string|null $id Campus Course id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $campusCourse = $this->CampusCourses->get($id, [
            'contain' => ['Campuses', 'Courses', 'CampusCourseTeachers.Teachers']
        ]);

        $this->set('campusCourse', $campusCourse);
        $this->set('_serialize', ['campusCourse']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reqData = $this->request->getData();
        $campusCourse = $this->CampusCourses->newEntity();

        if ($this->request->is('post')) {
            foreach ($reqData['teacher_id'] as $teacherId) {
                $reqData['campus_course_teachers'][] = [
                                                            'teacher_id' => $teacherId
                                                       ];
            }
            $campusCourse = $this->CampusCourses->patchEntity($campusCourse, $reqData, ['associated' => ['CampusCourseTeachers']]);
            if ($this->CampusCourses->save($campusCourse)) {
                $this->Flash->success(__('The campus course has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The campus course could not be saved. Please, try again.'));
        }
        $campuses = $this->CampusCourses->Campuses->find('list', ['limit' => 200]);
        $courses = $this->CampusCourses->Courses->find('list', ['limit' => 200]);
        $teachers = $this->CampusCourses->CampusCourseTeachers->Teachers->find()
                                                                        ->where(['role_id' => 3])
                                                                        ->all()
                                                                        ->combine('id', 'first_name')
                                                                        ->toArray();
        $this->set(compact('campusCourse', 'campuses', 'courses', 'teachers'));
        $this->set('_serialize', ['campusCourse']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Campus Course id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $campusCourse = $this->CampusCourses->findById($id)
                                            ->contain(['CampusCourseTeachers.Teachers'])
                                            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $campusCourse = $this->CampusCourses->patchEntity($campusCourse, $this->request->getData());
            if ($this->CampusCourses->save($campusCourse)) {
                $this->Flash->success(__('The campus course has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The campus course could not be saved. Please, try again.'));
        }
        $campuses = $this->CampusCourses->Campuses->find('list', ['limit' => 200]);
        $courses = $this->CampusCourses->Courses->find('list', ['limit' => 200]);
        $teachers = $this->CampusCourses->CampusCourseTeachers->Teachers->find()
                                                                        ->where(['role_id' => 3])
                                                                        ->all()
                                                                        ->combine('id', 'first_name')
                                                                        ->toArray();
        $this->set(compact('campusCourse', 'campuses', 'courses', 'teachers'));
        $this->set('_serialize', ['campusCourse']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Campus Course id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $campusCourse = $this->CampusCourses->get($id);
        if ($this->CampusCourses->delete($campusCourse)) {
            $this->Flash->success(__('The campus course has been deleted.'));
        } else {
            $this->Flash->error(__('The campus course could not be deleted. Please, try again.'));
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
