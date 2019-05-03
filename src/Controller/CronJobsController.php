<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CronJobs Controller
 *
 * @property \App\Model\Table\CronJobsTable $CronJobs
 *
 * @method \App\Model\Entity\CronJob[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CronJobsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $cronJobs = $this->paginate($this->CronJobs);

        $this->set(compact('cronJobs'));
    }

    /**
     * View method
     *
     * @param string|null $id Cron Job id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cronJob = $this->CronJobs->get($id, [
            'contain' => []
        ]);

        $this->set('cronJob', $cronJob);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cronJob = $this->CronJobs->newEntity();
        if ($this->request->is('post')) {
            $cronJob = $this->CronJobs->patchEntity($cronJob, $this->request->getData());
            if ($this->CronJobs->save($cronJob)) {
                $this->Flash->success(__('The cron job has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cron job could not be saved. Please, try again.'));
        }
        $this->set(compact('cronJob'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Cron Job id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cronJob = $this->CronJobs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cronJob = $this->CronJobs->patchEntity($cronJob, $this->request->getData());
            if ($this->CronJobs->save($cronJob)) {
                $this->Flash->success(__('The cron job has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cron job could not be saved. Please, try again.'));
        }
        $this->set(compact('cronJob'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cron Job id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cronJob = $this->CronJobs->get($id);
        if ($this->CronJobs->delete($cronJob)) {
            $this->Flash->success(__('The cron job has been deleted.'));
        } else {
            $this->Flash->error(__('The cron job could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
