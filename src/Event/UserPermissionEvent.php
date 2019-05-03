<?php
namespace App\Event;

use Cake\Controller\Controller;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\BadRequestException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\ORM\Query;
use ArrayObject;



class UserPermissionEvent implements EventListenerInterface {
  /**
     *
     * @var mixed
     */
    protected $meta;

    /**
     * The current user name or id.
     *
     * @var mixed
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param Request $request The current request
     * @param string|int $user The current user id or usernam
     */
    public function __construct($user = null, $data)
    { 
        $this->meta = $data;
        $this->user = $user;
    }

    /**
     * Returns an array with the events this class listens to.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return ['UserPermissionEvent.getUser' => 'getUser'];
    }

    public function getUser(Event $event){
      return [
                'meta' => $this->meta,
                'user' => $this->user
             ];
    }
}
?>
