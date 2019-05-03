<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EvidenceContexts Model
 *
 * @property \App\Model\Table\EvidencesTable|\Cake\ORM\Association\BelongsTo $Evidences
 * @property \App\Model\Table\ContextsTable|\Cake\ORM\Association\BelongsTo $Contexts
 *
 * @method \App\Model\Entity\EvidenceContext get($primaryKey, $options = [])
 * @method \App\Model\Entity\EvidenceContext newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EvidenceContext[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceContext|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EvidenceContext patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceContext[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceContext findOrCreate($search, callable $callback = null, $options = [])
 */
class EvidenceContextsTable extends Table
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

        $this->setTable('evidence_contexts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Evidences', [
            'foreignKey' => 'evidence_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Contexts', [
            'foreignKey' => 'context_id',
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
        $rules->add($rules->existsIn(['evidence_id'], 'Evidences'));
        $rules->add($rules->existsIn(['context_id'], 'Contexts'));

        return $rules;
    }
}
