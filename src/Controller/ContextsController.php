<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Contexts Controller
 *
 * @property \App\Model\Table\ContextsTable $Contexts
 *
 * @method \App\Model\Entity\Context[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContextsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $contexts = $this->paginate($this->Contexts);

        $this->set(compact('contexts'));
    }

    /**
     * View method
     *
     * @param string|null $id Context id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $context = $this->Contexts->get($id, [
            'contain' => ['GradeContexts']
        ]);

        $this->set('context', $context);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $context = $this->Contexts->newEntity();
        if ($this->request->is('post')) {
            $contextGrades = $this->request->data['grades'];
            unset($this->request->data['grades']);
            foreach ($contextGrades as $value) {
                $this->request->data['grade_contexts'][]=['grade_id'=>$value];
            }
            $context = $this->Contexts->patchEntity($context, $this->request->data, ['associated' => ['GradeContexts']]);
            
            if ($this->Contexts->save($context)) {
                $this->Flash->success(__('The context has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The context could not be saved. Please, try again.'));
        }
        $grades = $this->Contexts->GradeContexts->Grades->find('list', ['limit' => 200]);
    
        $this->set(compact('context','grades'));
        $this->set('_serialize', ['division']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Context id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $context = $this->Contexts->get($id, [
            'contain' => ['GradeContexts']
        ]);
        $contextGrades = [];
        foreach ($context->grade_contexts as $key => $value) {
             $contextGrades[] = $value->grade_id;
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contextGrades = $this->request->data['grades'];
            foreach ($contextGrades as $value) {
                $this->request->data['grade_contexts'][]=['grade_id'=>$value];
            }
            $context = $this->Contexts->patchEntity($context, $this->request->getData(), ['associated' => ['GradeContexts']]);
            if ($this->Contexts->save($context)) {
                $this->Flash->success(__('The context has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The context could not be saved. Please, try again.'));
        }
        $grades = $this->Contexts->GradeContexts->Grades->find('list', ['limit' => 200]);
    
        $this->set(compact('context','grades','contextGrades'));
        $this->set('_serialize', ['division']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Context id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $context = $this->Contexts->get($id);
        if ($this->Contexts->delete($context)) {
            $this->Flash->success(__('The context has been deleted.'));
        } else {
            $this->Flash->error(__('The context could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
