<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CampusTeachers Model
 *
 * @property \App\Model\Table\CampusesTable|\Cake\ORM\Association\BelongsTo $Campuses
 * @property \App\Model\Table\TeachersTable|\Cake\ORM\Association\BelongsTo $Teachers
 *
 * @method \App\Model\Entity\CampusTeacher get($primaryKey, $options = [])
 * @method \App\Model\Entity\CampusTeacher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CampusTeacher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CampusTeacher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CampusTeacher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CampusTeacher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CampusTeacher findOrCreate($search, callable $callback = null, $options = [])
 */
class CampusTeachersTable extends Table
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

        $this->setTable('campus_teachers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Campuses', [
            'foreignKey' => 'campus_id',
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
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));
        $rules->add($rules->IsUnique(['campus_id', 'teacher_id'], false));

        return $rules;
    }
}
