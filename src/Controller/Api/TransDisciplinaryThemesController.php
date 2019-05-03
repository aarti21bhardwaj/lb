<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Firebase\JWT\JWT;
use Cake\Log\Log;
use Cake\I18n\Time;


class TransDisciplinaryThemesController extends ApiController
{


    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
      if(!$this->request->is('get')){
        throw new MethodNotAllowedException("Method not allowed", 1);
        
      }
      $themes = $this->TransDisciplinaryThemes->find()->all()
                                                      ->toArray();

      $success = true;

      $this->set('data',$themes);
      $this->set('status',$success);
      $this->set('_serialize', ['status','data']);
      
    }
}
