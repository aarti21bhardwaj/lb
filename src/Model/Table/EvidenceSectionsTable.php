<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EvidenceSections Model
 *
 * @property \App\Model\Table\EvidencesTable|\Cake\ORM\Association\BelongsTo $Evidences
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 *
 * @method \App\Model\Entity\EvidenceSection get($primaryKey, $options = [])
 * @method \App\Model\Entity\EvidenceSection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EvidenceSection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceSection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EvidenceSection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceSection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EvidenceSection findOrCreate($search, callable $callback = null, $options = [])
 */
class EvidenceSectionsTable extends Table
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

        $this->setTable('evidence_sections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Evidences', [
            'foreignKey' => 'evidence_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id',
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
        $rules->add($rules->existsIn(['section_id'], 'Sections'));

        return $rules;
    }
}
