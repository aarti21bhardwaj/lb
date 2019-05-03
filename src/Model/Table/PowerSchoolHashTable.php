<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PowerSchoolHash Model
 *
 * @property \App\Model\Table\OldsTable|\Cake\ORM\Association\BelongsTo $Olds
 * @property \App\Model\Table\NewsTable|\Cake\ORM\Association\BelongsTo $News
 *
 * @method \App\Model\Entity\PowerSchoolHash get($primaryKey, $options = [])
 * @method \App\Model\Entity\PowerSchoolHash newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PowerSchoolHash[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PowerSchoolHash|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PowerSchoolHash patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PowerSchoolHash[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PowerSchoolHash findOrCreate($search, callable $callback = null, $options = [])
 */
class PowerSchoolHashTable extends Table
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

        $this->setTable('power_school_hash');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Olds', [
            'foreignKey' => 'old_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('News', [
            'foreignKey' => 'new_id',
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
            ->scalar('old_id')
            ->maxLength('old_id', 255)
            ->requirePresence('old_id', 'create')
            ->notEmpty('old_id');

        $validator
            ->scalar('old_table_name')
            ->maxLength('old_table_name', 255)
            ->requirePresence('old_table_name', 'create')
            ->notEmpty('old_table_name');

        $validator
            ->scalar('new_table_name')
            ->maxLength('new_table_name', 255)
            ->requirePresence('new_table_name', 'create')
            ->notEmpty('new_table_name');

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
        return $rules;
    }
}
