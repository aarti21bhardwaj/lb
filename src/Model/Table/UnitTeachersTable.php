<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UnitTeachers Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\TeachersTable|\Cake\ORM\Association\BelongsTo $Teachers
 *
 * @method \App\Model\Entity\UnitTeacher get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitTeacher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitTeacher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitTeacher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitTeacher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitTeacher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitTeacher findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitTeachersTable extends Table
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

        $this->setTable('unit_teachers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Teachers', [
            'className' => 'Users',
            'foreignKey' => 'teacher_id',
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
            ->boolean('is_creator')
            ->requirePresence('is_creator', 'create')
            ->notEmpty('is_creator');

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
        $rules->add($rules->existsIn(['unit_id'], 'Units'));
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));

        return $rules;
    }
}
