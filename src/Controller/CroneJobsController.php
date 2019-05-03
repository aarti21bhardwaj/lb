<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CroneJobs Controller
 *
 * @property \App\Model\Table\CroneJobsTable $CroneJobs
 *
 * @method \App\Model\Entity\CroneJob[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CroneJobsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $croneJobs = $this->paginate($this->CroneJobs);

        $this->set(compact('croneJobs'));
    }

    /**
     * View method
     *
     * @param string|null $id Crone Job id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $croneJob = $this->CroneJobs->get($id, [
            'contain' => []
        ]);

        $this->set('croneJob', $croneJob);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $croneJob = $this->CroneJobs->newEntity();
        if ($this->request->is('post')) {
            $croneJob = $this->CroneJobs->patchEntity($croneJob, $this->request->getData());
            if ($this->CroneJobs->save($croneJob)) {
                $this->Flash->success(__('The crone job has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The crone job could not be saved. Please, try again.'));
        }
        $this->set(compact('croneJob'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Crone Job id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $croneJob = $this->CroneJobs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $croneJob = $this->CroneJobs->patchEntity($croneJob, $this->request->getData());
            if ($this->CroneJobs->save($croneJob)) {
                $this->Flash->success(__('The crone job has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The crone job could not be saved. Please, try again.'));
        }
        $this->set(compact('croneJob'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Crone Job id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $croneJob = $this->CroneJobs->get($id);
        if ($this->CroneJobs->delete($croneJob)) {
            $this->Flash->success(__('The crone job has been deleted.'));
        } else {
            $this->Flash->error(__('The crone job could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
