<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * DivisionGrades Controller
 *
 * @property \App\Model\Table\DivisionGradesTable $DivisionGrades
 *
 * @method \App\Model\Entity\DivisionGrade[] paginate($object = null, array $settings = [])
 */
class DivisionGradesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $divisionGrades = $this->DivisionGrades->find()->contain(['Divisions', 'Grades'])->all();

        $this->set(compact('divisionGrades'));
        $this->set('_serialize', ['divisionGrades']);
    }

    /**
     * View method
     *
     * @param string|null $id Division Grade id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $divisionGrade = $this->DivisionGrades->get($id, [
            'contain' => ['Divisions', 'Grades']
        ]);

        $this->set('divisionGrade', $divisionGrade);
        $this->set('_serialize', ['divisionGrade']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $divisionGrade = $this->DivisionGrades->newEntity();
        if ($this->request->is('post')) {
            $divisionGrade = $this->DivisionGrades->patchEntity($divisionGrade, $this->request->getData());
            if ($this->DivisionGrades->save($divisionGrade)) {
                $this->Flash->success(__('The division grade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The division grade could not be saved. Please, try again.'));
        }
        $divisions = $this->DivisionGrades->Divisions->find('list', ['limit' => 200]);
        $grades = $this->DivisionGrades->Grades->find('list', ['limit' => 200]);
        $this->set(compact('divisionGrade', 'divisions', 'grades'));
        $this->set('_serialize', ['divisionGrade']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Division Grade id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $divisionGrade = $this->DivisionGrades->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $divisionGrade = $this->DivisionGrades->patchEntity($divisionGrade, $this->request->getData());
            if ($this->DivisionGrades->save($divisionGrade)) {
                $this->Flash->success(__('The division grade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The division grade could not be saved. Please, try again.'));
        }
        $divisions = $this->DivisionGrades->Divisions->find('list', ['limit' => 200]);
        $grades = $this->DivisionGrades->Grades->find('list', ['limit' => 200]);
        $this->set(compact('divisionGrade', 'divisions', 'grades'));
        $this->set('_serialize', ['divisionGrade']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Division Grade id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $divisionGrade = $this->DivisionGrades->get($id);
        if ($this->DivisionGrades->delete($divisionGrade)) {
            $this->Flash->success(__('The division grade has been deleted.'));
        } else {
            $this->Flash->error(__('The division grade could not be deleted. Please, try again.'));
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
