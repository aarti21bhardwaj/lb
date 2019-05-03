<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Network\Session;
use Cake\Collection\Collection;
use Cake\Datasource\ConnectionManager;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth');
        $this->Auth->allow(['forgotPassword', 'resetPassword','logout', 'exitSuperAdminLogin', 'getPicFromBlackBaud']);
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->Users->find()->contain(['Roles'])->all()->toArray();

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }
    
    public function studentGuardian($studentId,$studentName){
        if(!$studentId){
          throw new NotFoundException(__('We cant identify the user.'));
        }
        $guardians = $this->Users->find()
                                 ->where(['role_id' => 5])
                                 ->all()
                                 ->map(function($value,$key){
                                  $value->full_name = $value->first_name." ".$value->last_name;
                                  return $value;
                                 })
                                 ->combine('id','full_name')
                                 ->toArray();
        // pr($guardians);die;

        $relationType = ['mother' => 'Mother', 'father' => 'Father', 'legal_guardian' => 'Legal Guardian'];

        $this->loadModel('StudentGuardians');

        $studentGuardian = $this->StudentGuardians->newEntity();
        

        if ($this->request->is('post')) {
          $data = [ 
                    'student_id' => $studentId,
                    'guardian_id' => $this->request->getData('guardian_id'),
                    'relationship_type' => $this->request->getData('relationship_type')
                  ];
          $studentGuardian = $this->StudentGuardians->patchEntity($studentGuardian,$data);
          if ($this->StudentGuardians->save($studentGuardian)) {
                $this->Flash->success(__('The guardian has been saved.'));

                return $this->redirect(['action' => 'view',$studentId]);
          }
          $this->Flash->error(__('The guardian could not be saved. Please, try again.'));        
        }
        
        $this->set('studentName',$studentName);
        $this->set('studentGuardian', $studentGuardian);
        $this->set('guardians', $guardians);
        $this->set('relationType', $relationType);
        $this->set('_serialize', ['studentGuardian']);        
    }

    public function deleteStudentGuardian($studentGuardianId){
      $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('StudentGuardians');
        $studentGuardians = $this->StudentGuardians->get($studentGuardianId);
        
        if ($this->StudentGuardians->delete($studentGuardians)) {
            $this->Flash->success(__('The guardian data has been deleted.'));
        } else {
            $this->Flash->error(__('The guardian data could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'view',$studentGuardians->student_id]);

    }
    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'SchoolUsers']
        ]);
        if($user->role_id == 4){
          $this->loadModel('StudentGuardians');
          $studentGuardian = $this->StudentGuardians->findByStudentId($user->id)->contain(['Guardians','Students'])->all();
          $this->set('studentGuardian', $studentGuardian);
        }
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Schools');
        $this->loadModel('Divisions');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {

            $this->request->data['user_permissions'][] =['meta' => [
                                                                      'division_id' => $this->request->data['division_id'],
                                                                      'model' => 'Divisions'
                                                                   ]
                                                        ];
            $user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserPermissions']]);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $gender = ['male' =>'Male','female' => 'Female'];
        $schools = $this->Schools->find('list');
        $divisions = $this->Divisions->find('list');
        $this->set(compact('user', 'roles', 'schools','gender', 'divisions'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Divisions');

        $loggedInUser = $this->Auth->user();
        $user = $this->Users->findById($id)->contain(['SchoolUsers.Schools', 'UserPermissions'])->first();
        
        $userDivisions = [];
        foreach ($user->user_permissions as $key => $value) {
            $userDivisions = $value->meta['division_id'];
        }
        // pr($userDivisions); die;
        //If old image is available, unlink the path(and delete the image) and and  upload image from "upload" folder in webroot.
        $oldImageName = $user->image_name;
        $path = Configure::read('ImageUpload.unlinkPathForUsers');
        if ($this->request->is(['patch', 'post', 'put'])) {

            // pr($this->request->data); die;
            $userPermissions = $this->Users->UserPermissions->find()->where(['user_id' => $user->id])->first();
            if(!empty($userPermissions)){
              $this->request->data['user_permissions'] = [[
                                                            'id' => $user->user_permissions[0]->id,              
                                                            'meta' =>[ 
                                                                     'division_id' => $this->request->data['division_id'],
                                                                     'model' => 'Divisions'
                                                                     ] 
                                                         ]];
              // $user->user_permissions[0]->meta = 
            }else{
              $this->request->data['user_permissions'][] =['meta' => [
                                                                        'division_id' => $this->request->data['division_id'],
                                                                        'model' => 'Divisions'
                                                                     ],

                                                            'user_id' => $user->id
                                                          ];
            }
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['associated' => ['UserPermissions']]);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $schools = $this->Users->SchoolUsers->Schools->find('list');
        $divisions = $this->Divisions->find('list');
        $this->set(compact('user', 'roles', 'schools', 'loggedInUser', 'divisions'));
        $this->set('userDivisions', $userDivisions);
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


 // public function login()
 //    {
 //        $this->viewBuilder()->layout('login-admin');

 //        // $school = $this->Users->Schools->find()->first();

 //        if ($this->request->is('post')) {
 //            $user = $this->Auth->identify();
 //            if ($user) {
 //                $user['role'] = $this->Users->Roles->findById($user['role_id'])->first();
 //                $userId = $user['id'];
                
 //                $this->loadModel('CampusTeachers');

 //                  //When there is only a single campus
 //                  // TODO: Handle the case when there are multiple campuses
 //                  $campus = $this->CampusTeachers->Campuses->find()
 //                                                          ->contain(['CampusSettings.SettingKeys'])
 //                                                          ->first();
 //                  if($campus){
 //                    $campusSettings = (new Collection($campus->campus_settings))->indexBy('setting_key.name')->toArray();
                    
 //                    $session = new Session();
 //                    $session->write('campusSettings',$campusSettings);
 //                  }

 //                // $this->loadModel('CampusTeachers');
 //                // $campusTeachers = $this->CampusTeachers->findByTeacherId($userId)
 //                //                                         ->contain(['Campuses.CampusSettings.SettingKeys'])
 //                //                                         ->first();
 //                // if($campusTeachers){
 //                //   $campusSettings = (new Collection($campusTeachers->campus->campus_settings))->indexBy('setting_key.name')->toArray();
                  
 //                //   $session = new Session();
 //                //   $session->write('campusSettings',$campusSettings);
 //                // }
                
 //                if($user['role']->label == 'Teacher'){
 //                  $this->Auth->setUser($user);
 //                  $url = Router::url('/', true);
 //                  $url = $url.'teachers';
 //                  return $this->redirect($url);
 //                }else{
 //                  $this->Auth->setUser($user);
 //                  return $this->redirect(['action' => 'index']);
 //                }

 //            } else {
 //                $this->Flash->error(__('Username or password is incorrect'));
 //            }
 //        }

 //        // $this->set('school', $school);
 //    }

    public function login()
    {
        $this->viewBuilder()->layout('login-admin');
        // $school = $this->Users->Schools->find()->first();

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
              $this->_loginUser($user);

            } else {
                $this->Flash->error(__('Username or password is incorrect'));
            }
        }

        // $this->set('school', $school);
    }

    public function logout()
    {
        $session = $this->request->session();
        $session->delete('superAdminUser');
        $this->_deleteSession();
        return $this->redirect(['action' => 'login']);
    }

    public function updatePassword(){
        $data = $this->request->data;
        if(!isset($data['new_password'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','new_password'));
        }
        if(isset($data['new_password']) && empty($data['new_password'])){
            throw new BadRequestException(__('EMPTY_NOT_ALLOWED','new_password'));
        }
        if(!isset($data['user_id'])){
            throw new BadRequestException(__('MANDATORY_FIELD_MISSING','user_id'));
        }
        if(isset($data['user_id']) && empty($data['user_id'])){
            throw new BadRequestException(__('EMPTY_NOT_ALLOWED','user_id'));
        }

        $user = $this->Users->findById($data['user_id'])->first();

        if(!$user){
            throw new BadRequestException("Entity does not exist");
            
        }

        $password = $data['new_password'];
        $hasher = new DefaultPasswordHasher();

        if(! preg_match("/^[A-Za-z0-9~!@#$%^*&;?.+_]{8,}$/", $password)){
            throw new BadRequestException(__('Only numbers 0-9, alphabets a-z A-Z and special characters ~!@#$%^*&;?.+_ are allowed.'));
        }

        $reqData = ['password'=>$password];

        $user = $this->Users->patchEntity($user, $reqData);
        
        if($this->Users->save($user)){
            $data =array();
            $data['status']=true;
            $data['data']['id']=$user->id;
            $data['data']['message']='password saved';
        }else{
            throw new InternalErrorException("Something went wrong, try after some time!");
        }

        $this->set('response',$data);
        $this->set('_serialize', ['response']);

    }

    public function forgotPassword(){
      $this->viewBuilder()->layout('login-admin');
      if($this->Auth->user()){
            $this->Flash->error(__("UNAUTHORIZED_REQUEST"));
            $this->redirect(['action' => 'logout']);
      }

      if ($this->request->is('post')) {
          $email = $this->request->data['email'];
          $user = $this->Users->find('all')->where(['email'=>$email])->first();
          if(!$user){
            return $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS','Email'));
          }
           $hashResetPassword = $this->Users->ResetPasswordHashes->find()->where(['user_id' => $user->id])->first();
           if(empty($hashResetPassword)){
            $resetPwdHash = $this->_createResetPasswordHash($user->id);
          }else{
            $resetPwdHash = $hashResetPassword->hash;
            $time = new Time($hashResetPassword->created);
            if(!$time->wasWithinLast(1)){
              $this->Users->ResetPasswordHashes->delete($hashResetPassword);
              $resetPwdHash =$this->_createResetPasswordHash($user->id);
            }
          }
          $url = Router::url('/', true);
          $url = $url.'users/resetPassword/?reset-token='.$resetPwdHash;

          $email = new Email('default');
          $email->to($user->email)
                ->subject('Reset Password Link')
                ->send('To update your password please click the link '.$url);

        $this->Flash->success(__('Please check your email to verify yourself and change your password'));
        $this->redirect(['action' => 'login']);

       }


    }

     protected function _createResetPasswordHash($userId){
        $this->loadModel('ResetPasswordHashes');
        $resetPasswordrequestData = $this->ResetPasswordHashes->findByUserId($userId)->first();
        if($resetPasswordrequestData){
            return $resetPasswordrequestData->hash;
        }
        $hasher = new DefaultPasswordHasher();
        $reqData = ['user_id'=>$userId,'hash'=> $hasher->hash($userId)];
        $createPasswordhash = $this->ResetPasswordHashes->newEntity($reqData);
        $createPasswordhash = $this->ResetPasswordHashes->patchEntity($createPasswordhash,$reqData);
        if($this->ResetPasswordHashes->save($createPasswordhash)){
          return $createPasswordhash->hash;
      }else{
        Log::write('error','error in creating resetpassword hash for user id '.$userId);
        Log::write('error',$createPasswordhash);
      }
        return false;
    }

    public function resetPassword(){
        $this->viewBuilder()->layout('login-admin');
        $resetToken = $this->request->query('reset-token');

        if ($this->request->is('post')) {
          $uuid = (isset($this->request->data['reset-token']))?$this->request->data['reset-token']:'';
          if(!$uuid){
            $this->Flash->error(__('BAD_REQUEST'));
            $this->redirect(['action' => 'login']);
            return;
          }
          $password = (isset($this->request->data['new_pwd']))?$this->request->data['new_pwd']:'';
          if(!$password){
            $this->Flash->error(__('PROVIDE_PASSWORD'));
            $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
            return;
          }
          $cnfPassword = (isset($this->request->data['cnf_new_pwd']))?$this->request->data['cnf_new_pwd']:'';
          if(!$cnfPassword){
            $this->Flash->error(__('CONFIRM_PASSWORD'));
            $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
            return;
          }
          if($password !== $cnfPassword){
            $this->Flash->error(__('MISMATCH_PASSWORD'));
            $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
            return;
          }

          $this->loadModel('ResetPasswordHashes');
          $checkExistPasswordHash = $this->ResetPasswordHashes->find()->where(['hash'=>$uuid])->first();

          if(!$checkExistPasswordHash){
            $this->Flash->error(__('INVALID_RESET_PASSWORD'));
            $this->redirect(['action' => 'login']);
            return;
          }
           $user = $this->Users->findById($checkExistPasswordHash->user_id)->first();
           if(!$user){
            $this->Flash->error(__('ENTITY_DOES_NOT_EXISTS','User'));
            $this->redirect(['action' => 'login']);
            return;
           }
           if(! preg_match("/^[A-Za-z0-9~!@#$%^*&;?.+_]{8,}$/", $password)){
            $this->Flash->error(__('PASSWORD_CONDITION'));
            $this->redirect(['action' => 'resetPassword','?'=>['reset-token'=>$uuid]]);
            return;
           }

           $reqData = ['password'=>$password];
           $hasher = new DefaultPasswordHasher();

           $user = $this->Users->patchEntity($user, $this->request->data);
        
            if($this->Users->save($user)){
                $data =array();
                $data['status']=true;
                $data['data']['id']=$user->id;
                $this->Flash->success("Password reset successfully");
                return $this->redirect(['action' => 'login']);
            }else{
                throw new InternalErrorException("Something went wrong, try after some time!");
            }
            $this->set('response',$data);
            $this->set('_serialize', ['response']);
        }

        $this->set('resetToken',$resetToken);
        $this->set('_serialize', ['reset-token']);
    }

    public function loginThroughSuperAdmin($userId = null){

      if(!$userId){
        $this->Flash->error(__('User id is required'));
        return $this->redirect($this->referer());
      }

      $user = $this->Users->findById($userId)->first();
      // pr($user);die;
      if(!$user || $user == null){
        $this->Flash->error(__('User not found'));
        return $this->redirect($this->referer());
      }
      $user =  $user->toArray();
      $superAdminUser = $this->Users->findById($this->Auth->user('id'))->first()->toArray();
      $this->_deleteSession();
      $session = $this->request->session();
      $session->write('superAdminUser', $superAdminUser);
      $this->_loginUser($user);
    }

    private function _loginUser($user){
      $user['role'] = $this->Users->Roles->findById($user['role_id'])->first();
                
      $this->loadModel('CampusTeachers');

      //When there is only a single campus
      // TODO: Handle the case when there are multiple campuses
      $campus = $this->CampusTeachers->Campuses->find()
                                               ->contain(['CampusSettings.SettingKeys'])
                                               ->first();

      // pr($campus); die;
      if($campus){
        $campusSettings = (new Collection($campus->campus_settings))->indexBy('setting_key.name')->toArray();
        
        // pr($campusSettings); die;
        $session = $this->request->session();
        $session->write('campusSettings',$campusSettings);
      }
                
      if($user['role']->label == 'Teacher' || $user['role']->label == 'Student' || $user['role']->label == 'Guardian'){
        $this->Auth->setUser($user);
        $url = Router::url('/', true);
        $url = $url.'teachers';
        return $this->redirect($url);
      }elseif($user['role']->name == 'school'){
        $this->Auth->setUser($user);
        return $this->redirect(['controller' => 'ReportTemplates','action' => 'index']);
      }else{
        $this->Auth->setUser($user);
        return $this->redirect(['controller' => 'Campuses','action' => 'index']);
      }
    }

    public function exitSuperAdminLogin(){

     $session = $this->request->session();
     $superAdminUser = $session->read('superAdminUser');
     // pr($superAdminUser); die;
     if(!$superAdminUser){
      $this->Flash->error(__('No super admin user is set.'));
      return $this->redirect($this->referer());
     }

     $this->_deleteSession();
     $this->_loginUser($superAdminUser);
    }

    private function _deleteSession(){

    $user = $this->Auth->user();
    $this->Auth->logout();
   

    $session = $this->request->session();
    $session->destroy();
    // pr($session->read('superAdminUser'));

  }

  public function getPicFromBlackBaud($legacyid){

    $user = $this->Users->findByLegacyId($legacyid)->contain(['Roles'])->first();
    if(!$user){
      throw new NotFoundException('User not found.');
    }
    
    $queries = [
      'student' => "SELECT [PHOTO] FROM [dbo].[vLB_StudentsEnrolled] where [Student_ID] ='".$user->legacy_id."'",
      'teacher' => "SELECT [PHOTO] FROM [dbo].[vLB_Teachers] where [Record ID] = '".$user->legacy_id."'",
      'admin' => "SELECT [PHOTO] FROM [dbo].[vLB_Teachers] where [Record ID] = '".$user->legacy_id."'"
    ];

    $query = $queries[$user->role->name];

    $conn = ConnectionManager::get('mssql');
    $response = $conn->execute($query)->fetch('assoc');

    if(!isset($response['PHOTO']) || empty($response['PHOTO'])){
      throw new NotFoundException('User Image not found.');
    }    
    
    header("Content-type: image/jpg");
    echo $response['PHOTO'];
  }
}

