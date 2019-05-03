<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TeacherEvidenceImpactScores Model
 *
 * @property \App\Model\Table\TeacherEvidenceImpactsTable|\Cake\ORM\Association\BelongsTo $TeacherEvidenceImpacts
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\BelongsTo $ScaleValues
 *
 * @method \App\Model\Entity\TeacherEvidenceImpactScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpactScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpactScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpactScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpactScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpactScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpactScore findOrCreate($search, callable $callback = null, $options = [])
 */
class TeacherEvidenceImpactScoresTable extends Table
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

        $this->setTable('teacher_evidence_impact_scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('TeacherEvidenceImpacts', [
            'foreignKey' => 'teacher_evidence_impact_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ScaleValues', [
            'foreignKey' => 'scale_value_id',
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
            ->scalar('comment')
            ->maxLength('comment', 255)
            ->allowEmpty('comment');

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
        $rules->add($rules->existsIn(['teacher_evidence_impact_id'], 'TeacherEvidenceImpacts'));
        $rules->add($rules->existsIn(['scale_value_id'], 'ScaleValues'));

        return $rules;
    }
}
