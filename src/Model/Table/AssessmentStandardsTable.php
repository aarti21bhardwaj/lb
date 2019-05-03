<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentStandards Model
 *
 * @property \App\Model\Table\AssessmentsTable|\Cake\ORM\Association\BelongsTo $Assessments
 * @property \App\Model\Table\StandardsTable|\Cake\ORM\Association\BelongsTo $Standards
 *
 * @method \App\Model\Entity\AssessmentStandard get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentStandard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentStandard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentStandard|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentStandard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentStandard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentStandard findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentStandardsTable extends Table
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

        $this->setTable('assessment_standards');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Assessments', [
            'foreignKey' => 'assessment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Standards', [
            'foreignKey' => 'standard_id',
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
        $rules->add($rules->existsIn(['assessment_id'], 'Assessments'));
        $rules->add($rules->existsIn(['standard_id'], 'Standards'));
        $rules->add($rules->isUnique(['assessment_id', 'standard_id'],'This combination has already been used.'));

        return $rules;
    }
}
