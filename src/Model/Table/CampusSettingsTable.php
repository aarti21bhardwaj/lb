<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CampusSettings Model
 *
 * @property \App\Model\Table\CampusesTable|\Cake\ORM\Association\BelongsTo $Campuses
 * @property \App\Model\Table\SettingKeysTable|\Cake\ORM\Association\BelongsTo $SettingKeys
 *
 * @method \App\Model\Entity\CampusSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\CampusSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CampusSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CampusSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CampusSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CampusSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CampusSetting findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CampusSettingsTable extends Table
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

        $this->setTable('campus_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Campuses', [
            'foreignKey' => 'campus_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SettingKeys', [
            'foreignKey' => 'setting_key_id',
            'joinType' => 'INNER'
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
            ->scalar('value')
            ->maxLength('value', 255)
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['campus_id'], 'Campuses'));
        $rules->add($rules->existsIn(['setting_key_id'], 'SettingKeys'));

        return $rules;
    }
}
