<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;

/**
 * Divisions Controller
 *
 * @property \App\Model\Table\DivisionsTable $Divisions
 *
 * @method \App\Model\Entity\Division[] paginate($object = null, array $settings = [])
 */
class DivisionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $divisions = $this->Divisions->find()->contain(['Schools', 'Campuses'])->all();

        $this->set(compact('divisions'));
        $this->set('_serialize', ['divisions']);
    }

    /**
     * View method
     *
     * @param string|null $id Division id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $division = $this->Divisions->get($id, [
            'contain' => ['Schools', 'DivisionGrades','Terms', 'Campuses']
        ]);

        $this->set('division', $division);
        $this->set('_serialize', ['division']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $division = $this->Divisions->newEntity();
        if ($this->request->is('post')) {
            $divisionGrades = $this->request->data['grades'];
            unset($this->request->data['grades']);
            foreach ($divisionGrades as $value) {
                $this->request->data['division_grades'][]=['grade_id'=>$value];
            }

            // $this->request->getData()['grade'][] = ['user_id'=>$this->Auth->user('id')];
            $division = $this->Divisions->patchEntity($division, $this->request->data, ['associated' => ['DivisionGrades']]);

            $save = $this->Divisions->save($division);
            if ($save) {
                $this->Flash->success(__('The division has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The division could not be saved. Please, try again.'));
        }
        $templates = $this->Divisions->Templates->find('list', ['limit' => 200]);
        $schools = $this->Divisions->Schools->find('list', ['limit' => 200]);
        $schoolsCampuses = $this->Divisions->Schools->Campuses->find()->all()->groupBy('school_id');
        $grades = $this->Divisions->DivisionGrades->Grades->find('list', ['limit' => 200]);
        $this->set(compact('division', 'schools', 'grades', 'schoolsCampuses','templates'));
        $this->set('_serialize', ['division']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Division id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $division = $this->Divisions->get($id, [
            'contain' => ['DivisionGrades']
        ]);
        $divisionGrades = [];
        foreach ($division->division_grades as $key => $value) {
             $divisionGrades[] = $value->grade_id;
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $divisionGrades = $this->request->data['grades'];
            foreach ($divisionGrades as $value) {
                $this->request->data['division_grades'][]=['grade_id'=>$value];
            }
            $division = $this->Divisions->patchEntity($division, $this->request->data, ['associated' => ['DivisionGrades']]);
            if ($this->Divisions->save($division)) {
                $this->Flash->success(__('The division has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The division could not be saved. Please, try again.'));
        }
        $templates = $this->Divisions->Templates->find('list', ['limit' => 200]);
        $schools = $this->Divisions->Schools->find('list', ['limit' => 200]);
        $campuses = $this->Divisions->Schools->Campuses->find('list', ['limit' => 200]);
        $schoolsCampuses = $this->Divisions->Schools->Campuses->find()->all()->groupBy('school_id');
        $grades = $this->Divisions->DivisionGrades->Grades->find()->all()->combine('id','name')->toArray();
        $this->set(compact('division', 'schools', 'grades', 'campuses', 'schoolsCampuses','templates','divisionGrades'));
        $this->set('_serialize', ['division']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Division id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $division = $this->Divisions->get($id);
        if ($this->Divisions->delete($division)) {
            $this->Flash->success(__('The division has been deleted.'));
        } else {
            $this->Flash->error(__('The division could not be deleted. Please, try again.'));
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
