<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;

/**
 * Sections Controller
 *
 * @property \App\Model\Table\SectionsTable $Sections
 *
 * @method \App\Model\Entity\Section[] paginate($object = null, array $settings = [])
 */
class SectionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $sections = $this->Sections->find()->contain(['Courses', 'Teachers', 'Terms.Divisions'])->all();
        $termIds = $sections->extract('term_id')->toArray();

        $campus = $this->Sections->Terms->find()->where(['Terms.id IN' => $termIds])
                                                   ->contain(['Divisions.Campuses'])
                                                   ->indexBy('id')
                                                   ->extract('division.campus')
                                                   ->toArray();
        // pr($divisions); die;
        $this->set(compact('sections', 'campus'));
        $this->set('_serialize', ['sections', 'campus']);
    }

    /**
     * View method
     *
     * @param string|null $id Section id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $section = $this->Sections->get($id, [
            'contain' => ['Courses', 'Teachers', 'SectionStudents.Students', 'SectionStudents.Sections']
        ]);


        $this->set('section', $section);
        $this->set('_serialize', ['section']);
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
            // $data = [];
            // foreach ($reqData['teacher_id'] as  $teacherId) {
            //     $data[] = [
            //                 'name' => $reqData['name'],
            //                 'course_id' => $reqData['course_id'],
            //                 'term_id' => $reqData['term_id'],
            //                 'teacher_id' => $teacherId
            //               ];
            // }
            $section = $this->Sections->newEntity();
            $section = $this->Sections->patchEntity($section, $reqData);

            if ($this->Sections->save($section)) {
                $this->Flash->success(__('The section has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section could not be saved. Please, try again.'));
        }
        $courses = $this->Sections->Courses->find('list', ['limit' => 200]);
        $teachers = $this->Sections->Teachers->find()->where(['role_id' => 3])
                                                     ->all()
                                                     ->combine('id', 'first_name')
                                                     ->toArray();
        $terms = $this->Sections->Terms->find()->all()->combine('id', 'name')->toArray();
        $this->set(compact('section', 'courses', 'teachers', 'terms'));
        $this->set('_serialize', ['section']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Section id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {   
        $section = $this->Sections->get($id, [
            'contain' => ['Teachers']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $section = $this->Sections->patchEntity($section, $this->request->getData());
            if ($this->Sections->save($section)) {
                $this->Flash->success(__('The section has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section could not be saved. Please, try again.'));
        }
        $courses = $this->Sections->Courses->find('list', ['limit' => 200]);
        $teachers = $this->Sections->Teachers->find()->where(['role_id' => 3])
                                                     ->all()
                                                     ->combine('id', 'first_name')
                                                     ->toArray();
        $terms = $this->Sections->Terms->find()->all()->combine('id', 'name')->toArray();
        $this->set(compact('section', 'courses', 'teachers', 'terms'));
        $this->set('_serialize', ['section']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Section id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $section = $this->Sections->get($id);
        if ($this->Sections->delete($section)) {
            $this->Flash->success(__('The section has been deleted.'));
        } else {
            $this->Flash->error(__('The section could not be deleted. Please, try again.'));
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
