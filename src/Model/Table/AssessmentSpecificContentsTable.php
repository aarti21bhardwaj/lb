<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentSpecificContents Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Assessments
 * @property |\Cake\ORM\Association\BelongsTo $ContentCategories
 *
 * @method \App\Model\Entity\AssessmentSpecificContent get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentSpecificContent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentSpecificContent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSpecificContent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentSpecificContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSpecificContent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSpecificContent findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentSpecificContentsTable extends Table
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

        $this->setTable('assessment_specific_contents');
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');

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

        return $rules;
    }
}
