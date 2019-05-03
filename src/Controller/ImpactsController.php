<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Collection\Collection;

/**
 * Impacts Controller
 *
 * @property \App\Model\Table\ImpactsTable $Impacts
 *
 * @method \App\Model\Entity\Impact[] paginate($object = null, array $settings = [])
 */
class ImpactsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $impacts = $this->Impacts->find()->contain(['ImpactCategories', 'ParentImpacts'])->all();

        $this->set(compact('impacts'));
        $this->set('_serialize', ['impacts']);
    }

    /**
     * View method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $impact = $this->Impacts->get($id, [
            'contain' => ['ImpactCategories', 'ParentImpacts', 'ChildImpacts']
        ]);

        $this->set('impact', $impact);
        $this->set('_serialize', ['impact']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $impact = $this->Impacts->newEntity();
        if ($this->request->is('post')) {
            $impact = $this->Impacts->patchEntity($impact, $this->request->getData());
            if ($this->Impacts->save($impact)) {
                $this->Flash->success(__('The impact has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The impact could not be saved. Please, try again.'));
        }
        $impactCategories = $this->Impacts->ImpactCategories->find('list', ['limit' => 200]);
        $parentImpacts = $this->Impacts->ParentImpacts->find('list', ['limit' => 200]);
        $this->set(compact('impact', 'impactCategories', 'parentImpacts'));
        $this->set('_serialize', ['impact']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $impact = $this->Impacts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $impact = $this->Impacts->patchEntity($impact, $this->request->getData());
            if ($this->Impacts->save($impact)) {
                $this->Flash->success(__('The impact has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The impact could not be saved. Please, try again.'));
        }
        $impactCategories = $this->Impacts->ImpactCategories->find('list', ['limit' => 200]);
        $parentImpacts = $this->Impacts->ParentImpacts->find('list', ['limit' => 200]);
        $this->set(compact('impact', 'impactCategories', 'parentImpacts'));
        $this->set('_serialize', ['impact']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $impact = $this->Impacts->get($id);
        if ($this->Impacts->delete($impact)) {
            $this->Flash->success(__('The impact has been deleted.'));
        } else {
            $this->Flash->error(__('The impact could not be deleted. Please, try again.'));
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

    // public function impactsByImpactCategories(){
    //     if(!$this->request->is(['post'])){
    //         throw new MethodNotAllowedException('This request is not post');
    //     }
    //     $data = $this->request->data;

    //     if(empty($data['impact_category_id'])){
    //         throw new BadRequestException("Missing field impact_category_id");
            
    //     }

    //     $impacts = $this->Impacts->find()
    //                              ->where(['impact_category_id' => $data['impact_category_id']])
    //                              ->all()
    //                              ->map(function($value, $key) {
    //                                     $value->text = $value->name; 
    //                                     return $value;
    //                                  })
    //                             ->toArray();
        
    //     $impacts = (new Collection($impacts))->nest('id','parent_id')->toArray();
    //     $resData = array();
    //     if(!$impacts){
    //         $resData['status'] = false;
    //         $resData['message'] = 'No impacts till yet for this category';
    //     }else{
    //         $resData['status'] = true;
    //         $resData['data'] = $impacts;
    //     }

    //     $this->set('resData', $resData);
    //     $this->set('_serialize', ['resData']);

    // }
}
