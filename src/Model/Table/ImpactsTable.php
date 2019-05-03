<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Impacts Model
 *
 * @property \App\Model\Table\ImpactCategoriesTable|\Cake\ORM\Association\BelongsTo $ImpactCategories
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\BelongsTo $ParentImpacts
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\HasMany $ChildImpacts
 * @property |\Cake\ORM\Association\HasMany $UnitImpacts
 *
 * @method \App\Model\Entity\Impact get($primaryKey, $options = [])
 * @method \App\Model\Entity\Impact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Impact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Impact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Impact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Impact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Impact findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class ImpactsTable extends Table
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

        $this->setTable('impacts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('ImpactCategories', [
            'foreignKey' => 'impact_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ParentImpacts', [
            'className' => 'Impacts',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildImpacts', [
            'className' => 'Impacts',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('UnitImpacts', [
            'foreignKey' => 'impact_id'
        ]);
        $this->hasMany('AssessmentImpacts', [
            'foreignKey' => 'impact_id'
        ]);
        $this->hasMany('EvidenceImpacts', [
            'foreignKey' => 'impact_id'
        ]);
        $this->hasMany('TeacherEvidenceImpacts', [
            'foreignKey' => 'impact_id'
        ]);
        $this->hasMany('GradeImpacts', [
            'foreignKey' => 'impact_id'
        ]);

        $this->hasMany('ReportTemplateImpacts', [
            'foreignKey' => 'impact_id'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
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
        $rules->add($rules->existsIn(['impact_category_id'], 'ImpactCategories'));
        $rules->add($rules->existsIn(['parent_id'], 'ParentImpacts'));

        return $rules;
    }
}
