<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EvaluationImpactScores Model
 *
 * @property \App\Model\Table\EvaluationsTable|\Cake\ORM\Association\BelongsTo $Evaluations
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\BelongsTo $ScaleValues
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\BelongsTo $Impacts
 *
 * @method \App\Model\Entity\EvaluationImpactScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\EvaluationImpactScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EvaluationImpactScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EvaluationImpactScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EvaluationImpactScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EvaluationImpactScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EvaluationImpactScore findOrCreate($search, callable $callback = null, $options = [])
 */
class EvaluationImpactScoresTable extends Table
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

        $this->setTable('evaluation_impact_scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Evaluations', [
            'foreignKey' => 'evaluation_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ScaleValues', [
            'foreignKey' => 'scale_value_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
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
        $rules->add($rules->existsIn(['evaluation_id'], 'Evaluations'));
        $rules->add($rules->existsIn(['scale_value_id'], 'ScaleValues'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['impact_id'], 'Impacts'));

        return $rules;
    }
}
