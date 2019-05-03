<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GradeImpacts Model
 *
 * @property \App\Model\Table\GradesTable|\Cake\ORM\Association\BelongsTo $Grades
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\BelongsTo $Impacts
 *
 * @method \App\Model\Entity\GradeImpact get($primaryKey, $options = [])
 * @method \App\Model\Entity\GradeImpact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GradeImpact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GradeImpact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GradeImpact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GradeImpact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GradeImpact findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GradeImpactsTable extends Table
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

        $this->setTable('grade_impacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Impacts', [
            'foreignKey' => 'impact_id',
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
        $rules->add($rules->existsIn(['grade_id'], 'Grades'));
        $rules->add($rules->existsIn(['impact_id'], 'Impacts'));

        return $rules;
    }
}
