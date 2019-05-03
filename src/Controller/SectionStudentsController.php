<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SectionStudents Controller
 *
 * @property \App\Model\Table\SectionStudentsTable $SectionStudents
 *
 * @method \App\Model\Entity\SectionStudent[] paginate($object = null, array $settings = [])
 */
class SectionStudentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $sectionStudents = $this->SectionStudents->find()->contain(['Sections', 'Students'])->all();

        $this->set(compact('sectionStudents'));
        $this->set('_serialize', ['sectionStudents']);
    }

    /**
     * View method
     *
     * @param string|null $id Section Student id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sectionStudent = $this->SectionStudents->get($id, [
            'contain' => ['Sections', 'Students']
        ]);

        $this->set('sectionStudent', $sectionStudent);
        $this->set('_serialize', ['sectionStudent']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($sectionId = null)
    {
        $sectionData = null;
        if($sectionId){
            $sectionData = $this->SectionStudents->Sections->findById($sectionId)->first();
        }

        if ($this->request->is('post')) {
            $reqData = $this->request->getData();
            foreach ($reqData['student_id'] as $studentId) {
                $data[] = [
                            'section_id' => $reqData['section_id'],
                            'student_id'=> $studentId
                          ]; 
            }
            $sectionStudent = $this->SectionStudents->newEntities($data);
            $sectionStudent = $this->SectionStudents->patchEntities($sectionStudent, $data);

            if ($this->SectionStudents->saveMany($sectionStudent)) {
                $this->Flash->success(__('The section student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section student could not be saved. Please, try again.'));
        }
        $sections = $this->SectionStudents->Sections->find('list', ['limit' => 200]);
        $students = $this->SectionStudents->Students->find()->where(['role_id' => 4])->all()->combine('id', 'first_name')->toArray();;
        $this->set(compact('sectionStudent', 'sections', 'students'));
        $this->set('sectionData',$sectionData);
        $this->set('_serialize', ['sectionStudent']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Section Student id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sectionStudent = $this->SectionStudents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sectionStudent = $this->SectionStudents->patchEntity($sectionStudent, $this->request->getData());
            if ($this->SectionStudents->save($sectionStudent)) {
                $this->Flash->success(__('The section student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section student could not be saved. Please, try again.'));
        }
        $sections = $this->SectionStudents->Sections->find('list', ['limit' => 200]);
        $students = $this->SectionStudents->Students->find('list', ['limit' => 200]);
        $this->set(compact('sectionStudent', 'sections', 'students'));
        $this->set('_serialize', ['sectionStudent']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Section Student id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sectionStudent = $this->SectionStudents->get($id);
        if ($this->SectionStudents->delete($sectionStudent)) {
            $this->Flash->success(__('The section student has been deleted.'));
        } else {
            $this->Flash->error(__('The section student could not be deleted. Please, try again.'));
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
