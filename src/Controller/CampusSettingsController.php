<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * CampusSettings Controller
 *
 * @property \App\Model\Table\CampusSettingsTable $CampusSettings
 *
 * @method \App\Model\Entity\CampusSetting[] paginate($object = null, array $settings = [])
 */
class CampusSettingsController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($campusId = null)
    {
        if(!$campusId){
            $this->Flash->error(__('Invalid Access.'));
            return $this->redirect($this->referer());
        }

        $settingKeys = $this->CampusSettings->SettingKeys->find()->all()->combine('id','name')->toArray();
        $descriptions = $this->CampusSettings->SettingKeys->find()->all()->indexBy('id')->combine('id','description')->toArray();
        // pr($descriptions);die;
        $this->loadModel('Scales');
        $scales = $this->Scales->find()->all()->combine('id','name')->toArray();
        $campusSettings = $this->CampusSettings->findByCampusId($campusId)->contain(['SettingKeys'])->indexBy('setting_key_id')->toArray();
        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->request->data);die;
            if(empty($campusSettings)){
                $campusSettings = $this->CampusSettings->newEntities($this->request->getData());
            }
            $campusSettings = $this->CampusSettings->patchEntities($campusSettings, $this->request->getData());
            if ($this->CampusSettings->saveMany($campusSettings)) {
                $this->Flash->success(__('The campus setting has been saved.'));

                return $this->redirect(['controller' => 'campuses','action' => 'index']);
            }
            $this->Flash->error(__('The campus setting could not be saved. Please, try again.'));
        }
        $campus = $this->CampusSettings->Campuses->findById($campusId)->first();
        $this->set(compact('campusSettings', 'campus', 'settingKeys','scales','descriptions'));
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
