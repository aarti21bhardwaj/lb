<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AcademicYears Controller
 *
 * @property \App\Model\Table\AcademicYearsTable $AcademicYears
 *
 * @method \App\Model\Entity\AcademicYear[] paginate($object = null, array $settings = [])
 */
class AcademicYearsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $academicYears = $this->AcademicYears->find()->contain('Schools')->all();

        $this->set(compact('academicYears'));
        $this->set('_serialize', ['academicYears']);
    }

    /**
     * View method
     *
     * @param string|null $id Academic Year id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $academicYear = $this->AcademicYears->get($id, [
            'contain' => ['Terms']
        ]);

        $this->set('academicYear', $academicYear);
        $this->set('_serialize', ['academicYear']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $academicYear = $this->AcademicYears->newEntity();
        $this->loadModel('Schools');
        $schools = $this->Schools->find('list');
        if ($this->request->is('post')) {
            $this->request->data['school_id'] = $this->request->getData('school_id');
            $academicYear = $this->AcademicYears->patchEntity($academicYear, $this->request->data);
            if ($this->AcademicYears->save($academicYear)) {
                $this->Flash->success(__('The academic year has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The academic year could not be saved. Please, try again.'));
        }
        $this->set(compact('academicYear','schools'));
        $this->set('_serialize', ['academicYear']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Academic Year id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $academicYear = $this->AcademicYears->get($id, [
            'contain' => ['Schools']
        ]);
        $this->loadModel('Schools');
        $schools = $this->Schools->find('list');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $academicYear = $this->AcademicYears->patchEntity($academicYear, $this->request->getData());
            if ($this->AcademicYears->save($academicYear)) {
                $this->Flash->success(__('The academic year has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The academic year could not be saved. Please, try again.'));
        }
        $this->set(compact('academicYear','schools'));
        $this->set('_serialize', ['academicYear']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Academic Year id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $academicYear = $this->AcademicYears->get($id);
        if ($this->AcademicYears->delete($academicYear)) {
            $this->Flash->success(__('The academic year has been deleted.'));
        } else {
            $this->Flash->error(__('The academic year could not be deleted. Please, try again.'));
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
