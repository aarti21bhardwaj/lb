<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SettingKeys Model
 *
 * @property \App\Model\Table\CampusSettingsTable|\Cake\ORM\Association\HasMany $CampusSettings
 *
 * @method \App\Model\Entity\SettingKey get($primaryKey, $options = [])
 * @method \App\Model\Entity\SettingKey newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SettingKey[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SettingKey|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SettingKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SettingKey[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SettingKey findOrCreate($search, callable $callback = null, $options = [])
 */
class SettingKeysTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('setting_keys');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CampusSettings', [
            'foreignKey' => 'setting_key_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        return $validator;
    }
}
