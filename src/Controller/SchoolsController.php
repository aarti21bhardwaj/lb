<?php
namespace App\Controller;

use App\Controller\AppController;
// use Cake\Core\Configure;

/**
 * Schools Controller
 *
 * @property \App\Model\Table\SchoolsTable $Schools
 *
 * @method \App\Model\Entity\School[] paginate($object = null, array $settings = [])
 */
class SchoolsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $schools = $this->Schools->find()->all();

        $this->set(compact('schools'));
        $this->set('_serialize', ['schools']);
    }

    /**
     * View method
     *
     * @param string|null $id School id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $school = $this->Schools->get($id, [
            'contain' => ['Campuses', 'Divisions', 'SchoolUsers']
        ]);

        $this->set('school', $school);
        $this->set('_serialize', ['school']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $school = $this->Schools->newEntity();
        if ($this->request->is('post')) {
            // pr($this->request->data); die;
            $school = $this->Schools->patchEntity($school, $this->request->getData());
            // pr($school); die;
            if ($this->Schools->save($school)) {
                $this->Flash->success(__('The school has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school could not be saved. Please, try again.'));
        }
        $this->set(compact('school'));
        $this->set('_serialize', ['school']);
    }

    /**
     * Edit method
     *
     * @param string|null $id School id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $school = $this->Schools->get($id, [
            'contain' => []
        ]);
         //If old image is available, unlink the path(and delete the image) and and  upload image from "upload" folder in webroot.
        $oldImageName = $school->logo_image_name;
        $path = Configure::read('ImageUpload.unlinkPathForSchoolLogo');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $school = $this->Schools->patchEntity($school, $this->request->getData());
            if ($this->Schools->save($school)) {
                $this->Flash->success(__('The school has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school could not be saved. Please, try again.'));
        }
        $this->set(compact('school'));
        $this->set('_serialize', ['school']);
    }

    /**
     * Delete method
     *
     * @param string|null $id School id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $school = $this->Schools->get($id);
        if ($this->Schools->delete($school)) {
            $this->Flash->success(__('The school has been deleted.'));
        } else {
            $this->Flash->error(__('The school could not be deleted. Please, try again.'));
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
        }elseif (isset($user['role']) && $user['role']->name === 'school' && $this->request->params['action'] == 'index') {
            return true;
        }else{
            return false;
        }

        return parent::isAuthorized($user);
    }
}
