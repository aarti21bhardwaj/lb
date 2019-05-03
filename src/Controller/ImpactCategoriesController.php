<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ImpactCategories Controller
 *
 * @property \App\Model\Table\ImpactCategoriesTable $ImpactCategories
 *
 * @method \App\Model\Entity\ImpactCategory[] paginate($object = null, array $settings = [])
 */
class ImpactCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $impactCategories = $this->ImpactCategories->find()->all();

        $this->set(compact('impactCategories'));
        $this->set('_serialize', ['impactCategories']);
    }

    /**
     * View method
     *
     * @param string|null $id Impact Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $impactCategory = $this->ImpactCategories->get($id, [
            'contain' => ['Impacts']
        ]);

        $this->set('impactCategory', $impactCategory);
        $this->set('_serialize', ['impactCategory']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $impactCategory = $this->ImpactCategories->newEntity();
        if ($this->request->is('post')) {
            $impactCategory = $this->ImpactCategories->patchEntity($impactCategory, $this->request->getData());
            if ($this->ImpactCategories->save($impactCategory)) {
                $this->Flash->success(__('The impact category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The impact category could not be saved. Please, try again.'));
        }
        $this->set(compact('impactCategory'));
        $this->set('_serialize', ['impactCategory']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Impact Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $impactCategory = $this->ImpactCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $impactCategory = $this->ImpactCategories->patchEntity($impactCategory, $this->request->getData());
            if ($this->ImpactCategories->save($impactCategory)) {
                $this->Flash->success(__('The impact category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The impact category could not be saved. Please, try again.'));
        }
        $this->set(compact('impactCategory'));
        $this->set('_serialize', ['impactCategory']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Impact Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $impactCategory = $this->ImpactCategories->get($id);
        if ($this->ImpactCategories->delete($impactCategory)) {
            $this->Flash->success(__('The impact category has been deleted.'));
        } else {
            $this->Flash->error(__('The impact category could not be deleted. Please, try again.'));
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
