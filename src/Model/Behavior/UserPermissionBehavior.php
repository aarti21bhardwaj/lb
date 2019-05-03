<?php

namespace App\Model\Behavior;

use ArrayObject;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\Utility\Text;
use Cake\ORM\TableRegistry;

/**
 * This behavior can be used to log all the creations, modifications and deletions
 * done to a particular table.
 */
class UserPermissionBehavior extends Behavior
{

     protected $_defaultConfig = [
        'foreign_key'=>null,
        'pathToModel'=> null,
    ];

    public function initialize(array $config)
    {
       $this->_config = $config;
    }

    /**
     * Returns the list of implemented events.
     *
     * @return array
    */
    public function implementedEvents()
    {
        return [
            'Model.beforeFind' => 'beforeFind',
        ];
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options){
            // pr('hello'); die;

        $data = $this->_table->dispatchEvent('UserPermissionEvent.getUser');
        $data = $data->getResult();
        // pr($data); die;
        if(!empty($data)){
            $divisionId = $data['meta']['division_id'];
            $model = $data['meta']['model'];
            unset($data);
            // ini_set('memory_limit', '500M');
            $this->_table->removeBehavior('UserPermission');
            $ids = $this->_table->find()
                                  ->select(['id'])
                                  ->matching($this->_config['pathToModel'], function($q) use($divisionId, $model){
                                                return $q->where([$model.'.id IN' => $divisionId]);
                                            })
                                  ->extract('id')
                                  ->toArray();

            if(empty($ids)){
                $ids = [null];
            }

            $query->where([$this->_table->getRegistryAlias().'.id IN' => $ids]);
        }
        return;

    }
}
