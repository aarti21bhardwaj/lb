<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SpecialServiceTypes Controller
 *
 * @property \App\Model\Table\SpecialServiceTypesTable $SpecialServiceTypes
 *
 * @method \App\Model\Entity\SpecialServiceType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SpecialServiceTypesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $specialServiceTypes = $this->paginate($this->SpecialServiceTypes);

        $this->set(compact('specialServiceTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Special Service Type id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $specialServiceType = $this->SpecialServiceTypes->get($id, [
            'contain' => []
        ]);

        $this->set('specialServiceType', $specialServiceType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $specialServiceType = $this->SpecialServiceTypes->newEntity();
        if ($this->request->is('post')) {
            $specialServiceType = $this->SpecialServiceTypes->patchEntity($specialServiceType, $this->request->getData());
            if ($this->SpecialServiceTypes->save($specialServiceType)) {
                $this->Flash->success(__('The special service type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The special service type could not be saved. Please, try again.'));
        }
        $this->set(compact('specialServiceType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Special Service Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $specialServiceType = $this->SpecialServiceTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $specialServiceType = $this->SpecialServiceTypes->patchEntity($specialServiceType, $this->request->getData());
            if ($this->SpecialServiceTypes->save($specialServiceType)) {
                $this->Flash->success(__('The special service type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The special service type could not be saved. Please, try again.'));
        }
        $this->set(compact('specialServiceType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Special Service Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $specialServiceType = $this->SpecialServiceTypes->get($id);
        if ($this->SpecialServiceTypes->delete($specialServiceType)) {
            $this->Flash->success(__('The special service type has been deleted.'));
        } else {
            $this->Flash->error(__('The special service type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
