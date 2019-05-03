<?php
/**
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link      http://cakephp.org CakePHP(tm) Project
* @since     0.2.9
* @license   http://www.opensource.org/licenses/mit-license.php MIT License
*/
namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Cache\Cache;
use Cake\Network\Request;
use Cake\Network\Session;
use Muffin\Footprint\Auth\FootprintAwareTrait;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use App\Event\UserPermissionEvent;

/**
* Application Controller
*
* Add your application-wide methods in the class below, your controllers
* will inherit them.
*
* @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
*/
class ApiController extends Controller
{

   use FootprintAwareTrait;
  
  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
    $this->loadComponent('Flash');
    $session = new Session();
    $user = $session->read('Auth.User');
    if($this->request->params['action'] == 'login' || !in_array($user,[null,false,''])){
      
    $this->loadComponent('Auth', [
        'authenticate' => [
            'Form' => [
                'fields' => ['username' => 'email', 'password' => 'password']
            ]
        ],
        'unauthorizedRedirect' => false
        ]);
    }else{
      $this->loadComponent('Auth', [
        'storage' => 'Memory',
        'authorize' => 'Controller',
        'authenticate' => [
          'ADmad/JwtAuth.Jwt' => [
            'parameter' => 'token',
            'userModel' => 'Users',
            'fields' => ['username' => 'id'],
            'queryDatasource' => true
          ]
        ],
        'unauthorizedRedirect' => false,
        'checkAuthIn' => 'Controller.initialize',
        'loginAction' => false,
        'logoutRedirect' => false,
      ]);
    }
  }

  protected function _sendErrorResponse($err){
    $errorMsg = [];
    foreach($err as $errors){
      if(is_array($errors)){
        foreach($errors as $error){
          $errorMsg[]    =   $error;
        }
      }else{
        $errorMsg[]    =   $errors;
      }
    }
    throw new InternalErrorException(__(implode("\n \r", $errorMsg)));
  }

  public function beforeFilter(Event $event)
  {
    $user = $this->Auth->user();
    if(isset($user) && $user['role_id'] == 2){
        $this->loadModel('UserPermissions');
        $userData = $this->UserPermissions->find()->where(['user_id' => $user['id']])->first();
        EventManager::instance()->on(new UserPermissionEvent($this->Auth->user(), $userData->meta));

     }
    $origin = $this->request->header('Origin');
    if($this->request->header('CONTENT_TYPE') != "application/x-www-form-urlencoded; charset=UTF-8"){
      $this->request->env('CONTENT_TYPE', 'application/json');
    }
    $this->request->env('HTTP_ACCEPT', 'application/json');
    if (!empty($origin)) {
      $this->response->header('Access-Control-Allow-Origin', $origin);
    }

    if ($this->request->method() == 'OPTIONS') {
      $method  = $this->request->header('Access-Control-Request-Method');
      $headers = $this->request->header('Access-Control-Request-Headers');
      $this->response->header('Access-Control-Allow-Headers', $headers);
      $this->response->header('Access-Control-Allow-Methods', empty($method) ? 'GET, POST, PUT, DELETE' : $method);
      $this->response->header('Access-Control-Allow-Credentials', 'true');
      $this->response->header('Access-Control-Max-Age', '120');
      $this->response->send();
      die;
    }
    // die;
    $this->response->cors($this->request)
    ->allowOrigin(['*'])
    ->allowMethods(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'])
    ->allowHeaders(['X-CSRF-Token','token'])
    ->allowCredentials()
    ->exposeHeaders(['Link'])
    ->maxAge(300)
    ->build();
  } 

  public function beforeRender(Event $event)
  {
        //Log will keep the track of all the response and store it in logs/audit.log file
      //  Log::write('debug', $this->viewVars);
  }

  public function isAuthorized(){
    return true;
  }

}
