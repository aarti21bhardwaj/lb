<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Core\Configure;

/**
 * ReportPages Controller
 *
 * @property \App\Model\Table\ReportPagesTable $ReportPages
 *
 * @method \App\Model\Entity\ReportPage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportPagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $reportPages = $this->paginate($this->ReportPages);

        $this->set(compact('reportPages'));
    }

    /**
     * View method
     *
     * @param string|null $id Report Page id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportPage = $this->ReportPages->get($id, [
            'contain' => ['Reports']
        ]);

        $this->set('reportPage', $reportPage);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportPage = $this->ReportPages->newEntity();
        if ($this->request->is('post')) {
            $reportPage = $this->ReportPages->patchEntity($reportPage, $this->request->getData());
            if ($this->ReportPages->save($reportPage)) {
                $this->Flash->success(__('The report page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report page could not be saved. Please, try again.'));
        }
        $this->loadModel('ReportTemplateVariables');
        $reportTemplateVariables = $this->ReportTemplateVariables->find()
                                                                 ->where(['report_template_type_id' => 1])
                                                                 ->all()
                                                                 ->map(function($value,$key){
                                                                    $value->identifier = '{{'.$value->identifier.'}}';
                                                                    return $value;
                                                                 })
                                                                ->combine('identifier', 'name')
                                                                ->toArray();

        $this->set(compact('reportPage', 'reportTemplateVariables'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report Page id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportPage = $this->ReportPages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportPage = $this->ReportPages->patchEntity($reportPage, $this->request->getData());
            if ($this->ReportPages->save($reportPage)) {
                $this->Flash->success(__('The report page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report page could not be saved. Please, try again.'));
        }

        $this->loadModel('ReportTemplateVariables');
        $reportTemplateVariables = $this->ReportTemplateVariables->find()
                                                                 ->where(['report_template_type_id' => 1])
                                                                 ->all()
                                                                 ->map(function($value,$key){
                                                                    $value->identifier = '{{'.$value->identifier.'}}';
                                                                    return $value;
                                                                 })
                                                                ->combine('identifier', 'name')
                                                                ->toArray();
                                                                
        $this->set(compact('reportPage', 'reportTemplateVariables'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report Page id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportPage = $this->ReportPages->get($id);
        if ($this->ReportPages->delete($reportPage)) {
            $this->Flash->success(__('The report page has been deleted.'));
        } else {
            $this->Flash->error(__('The report page could not be deleted. Please, try again.'));
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
        }elseif (isset($user['role']) && $user['role']->name === 'school') {
            return true;
        }else{
            return false;
        }
        
        return parent::isAuthorized($user);
    }

    // public function uploadImage(){
    //     // Allowed extentions.
    //     $allowedExts = array("gif", "jpeg", "jpg", "png");
    //     // Get filename.
    //     $temp = explode(".", $_FILES['image_param']["name"]);

    //     // Get extension.
    //     $extension = end($temp);

    //     // An image check is being done in the editor but it is best to
    //     // check that again on the server side.
    //     // Do not use $_FILES["file"]["type"] as it can be easily forged.
    //     $finfo = finfo_open(FILEINFO_MIME_TYPE);
    //     $mime = finfo_file($finfo, $_FILES["image_param"]["tmp_name"]);
    //     // pr($mime); die;

    //     // pr($mime); die;
    //     if ((($mime == "image/gif")
    //     || ($mime == "image/jpeg")
    //     || ($mime == "image/pjpeg")
    //     || ($mime == "image/x-png")
    //     || ($mime == "image/png"))
    //     && in_array($extension, $allowedExts)) {
    //         // Generate new random name.
    //         $name = sha1(microtime()) . "." . $extension;

    //         // Save file in the uploads folder.
    //         move_uploaded_file($_FILES["file"]["tmp_name"], getcwd() . "/uploads/" . $name);

    //         // Generate response.
    //         $response = new StdClass;
    //         $response->link = "/uploads/" . $name;
    //         pr($response); die;
    //         // echo stripslashes(json_encode($response));
    //     }
    // }
}
