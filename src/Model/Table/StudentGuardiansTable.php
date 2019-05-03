<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StudentGuardians Model
 *
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\GuardiansTable|\Cake\ORM\Association\BelongsTo $Guardians
 *
 * @method \App\Model\Entity\StudentGuardian get($primaryKey, $options = [])
 * @method \App\Model\Entity\StudentGuardian newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StudentGuardian[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StudentGuardian|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StudentGuardian patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StudentGuardian[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StudentGuardian findOrCreate($search, callable $callback = null, $options = [])
 */
class StudentGuardiansTable extends Table
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

        $this->setTable('student_guardians');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Guardians', [
            'className' => 'Users',
            'foreignKey' => 'guardian_id',
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
            ->scalar('relationship_type')
            ->maxLength('relationship_type', 255)
            ->requirePresence('relationship_type', 'create')
            ->notEmpty('relationship_type');

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
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['guardian_id'], 'Guardians'));

        return $rules;
    }
}
