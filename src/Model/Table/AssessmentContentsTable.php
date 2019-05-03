<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentContents Model
 *
 * @property \App\Model\Table\AssessmentsTable|\Cake\ORM\Association\BelongsTo $Assessments
 * @property \App\Model\Table\ContentCategoriesTable|\Cake\ORM\Association\BelongsTo $ContentCategories
 * @property \App\Model\Table\ContentValuesTable|\Cake\ORM\Association\BelongsTo $ContentValues
 * @property \App\Model\Table\UnitSpecificContentsTable|\Cake\ORM\Association\BelongsTo $UnitSpecificContents
 *
 * @method \App\Model\Entity\AssessmentContent get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentContent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentContent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentContent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentContent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentContent findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentContentsTable extends Table
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

        $this->setTable('assessment_contents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Assessments', [
            'foreignKey' => 'assessment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ContentCategories', [
            'foreignKey' => 'content_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ContentValues', [
            'foreignKey' => 'content_value_id'
        ]);
        $this->belongsTo('UnitSpecificContents', [
            'foreignKey' => 'unit_specific_content_id'
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
        $rules->add($rules->existsIn(['content_category_id'], 'ContentCategories'));
        $rules->add($rules->existsIn(['content_value_id'], 'ContentValues'));
        $rules->add($rules->existsIn(['unit_specific_content_id'], 'UnitSpecificContents'));

        return $rules;
    }
}
