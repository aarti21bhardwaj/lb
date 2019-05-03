<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DivisionGrades Model
 *
 * @property \App\Model\Table\DivisionsTable|\Cake\ORM\Association\BelongsTo $Divisions
 * @property \App\Model\Table\GradesTable|\Cake\ORM\Association\BelongsTo $Grades
 *
 * @method \App\Model\Entity\DivisionGrade get($primaryKey, $options = [])
 * @method \App\Model\Entity\DivisionGrade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DivisionGrade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DivisionGrade|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DivisionGrade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DivisionGrade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DivisionGrade findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DivisionGradesTable extends Table
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

        $this->setTable('division_grades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Divisions', [
            'foreignKey' => 'division_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id',
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
        $rules->add($rules->existsIn(['division_id'], 'Divisions'));
        $rules->add($rules->existsIn(['grade_id'], 'Grades'));
        // $rules->add($rules->isUnique(['grade_id']));

        return $rules;
    }
}
