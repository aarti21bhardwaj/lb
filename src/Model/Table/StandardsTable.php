<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Standards Model
 *
 * @property \App\Model\Table\StrandsTable|\Cake\ORM\Association\BelongsTo $Strands
 * @property \App\Model\Table\StandardsTable|\Cake\ORM\Association\BelongsTo $ParentStandards
 * @property |\Cake\ORM\Association\HasMany $StandardGrades
 * @property \App\Model\Table\StandardsTable|\Cake\ORM\Association\HasMany $ChildStandards
 * @property \App\Model\Table\UnitStandardsTable|\Cake\ORM\Association\HasMany $UnitStandards
 *
 * @method \App\Model\Entity\Standard get($primaryKey, $options = [])
 * @method \App\Model\Entity\Standard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Standard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Standard|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Standard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Standard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Standard findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class StandardsTable extends Table
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

        $this->setTable('standards');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('Strands', [
            'foreignKey' => 'strand_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ParentStandards', [
            'className' => 'Standards',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('StandardGrades', [
            'foreignKey' => 'standard_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasMany('AssessmentStandards', [
            'foreignKey' => 'standard_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasMany('ChildStandards', [
            'className' => 'Standards',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('UnitStandards', [
            'foreignKey' => 'standard_id'
        ]);

        $this->hasMany('ReportTemplateStandards', [
            'foreignKey' => 'standard_id'
        ]);

        $this->belongsToMany('Units', [
            'through' => 'UnitStandards'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('code')
            ->maxLength('code', 255)
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->boolean('is_selected')
            ->allowEmpty('is_selected');

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
        $rules->add($rules->existsIn(['strand_id'], 'Strands'));
        $rules->add($rules->existsIn(['parent_id'], 'ParentStandards'));

        return $rules;
    }
}
