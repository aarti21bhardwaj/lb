<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EvidenceImpactScores Model
 *
 * @property \App\Model\Table\EvidenceImpactsTable|\Cake\ORM\Association\BelongsTo $EvidenceImpacts
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\BelongsTo $ScaleValues
 *
 * @method \App\Model\Entity\EvidenceImpactScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\EvidenceImpactScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EvidenceImpactScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceImpactScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EvidenceImpactScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceImpactScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceImpactScore findOrCreate($search, callable $callback = null, $options = [])
 */
class EvidenceImpactScoresTable extends Table
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

        $this->setTable('evidence_impact_scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('EvidenceImpacts', [
            'foreignKey' => 'evidence_impact_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('ScaleValues', [
            'foreignKey' => 'scale_value_id',
            'joinType' => 'LEFT'
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

        $validator
            ->boolean('is_completed')
            ->allowEmpty('is_completed');

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
        $rules->add($rules->existsIn(['evidence_impact_id'], 'EvidenceImpacts'));
        $rules->add($rules->existsIn(['scale_value_id'], 'ScaleValues'));

        return $rules;
    }
}
