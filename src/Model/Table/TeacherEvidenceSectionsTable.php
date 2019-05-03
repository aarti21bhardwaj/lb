<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TeacherEvidenceSections Model
 *
 * @property \App\Model\Table\TeacherEvidencesTable|\Cake\ORM\Association\BelongsTo $TeacherEvidences
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 *
 * @method \App\Model\Entity\TeacherEvidenceSection get($primaryKey, $options = [])
 * @method \App\Model\Entity\TeacherEvidenceSection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceSection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceSection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TeacherEvidenceSection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceSection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidenceSection findOrCreate($search, callable $callback = null, $options = [])
 */
class TeacherEvidenceSectionsTable extends Table
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

        $this->setTable('teacher_evidence_sections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('TeacherEvidences', [
            'foreignKey' => 'teacher_evidence_id',
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
        $rules->add($rules->existsIn(['teacher_evidence_id'], 'TeacherEvidences'));
        $rules->add($rules->existsIn(['section_id'], 'Sections'));

        return $rules;
    }
}
