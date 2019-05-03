<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TeacherEvidenceImpacts Model
 *
 * @property \App\Model\Table\TeacherEvidencesTable|\Cake\ORM\Association\BelongsTo $TeacherEvidences
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\BelongsTo $Impacts
 *
 * @method \App\Model\Entity\TeacherEvidenceImpact get($primaryKey, $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceImpact findOrCreate($search, callable $callback = null, $options = [])
 */
class TeacherEvidenceImpactsTable extends Table
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

        $this->setTable('teacher_evidence_impacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('TeacherEvidences', [
            'foreignKey' => 'teacher_evidence_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Impacts', [
            'foreignKey' => 'impact_id',
            'joinType' => 'INNER'
        ]);
        $this->hasOne('TeacherEvidenceImpactScores', [
            'foreignKey' => 'teacher_evidence_impact_id'
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
        $rules->add($rules->existsIn(['teacher_evidence_id'], 'TeacherEvidences'));
        $rules->add($rules->existsIn(['impact_id'], 'Impacts'));

        return $rules;
    }
}
