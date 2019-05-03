<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Strands Model
 *
 * @property \App\Model\Table\LearningAreasTable|\Cake\ORM\Association\BelongsTo $LearningAreas
 * @property |\Cake\ORM\Association\HasMany $AssessmentStrands
 * @property \App\Model\Table\CourseStrandsTable|\Cake\ORM\Association\HasMany $CourseStrands
 * @property \App\Model\Table\StandardsTable|\Cake\ORM\Association\HasMany $Standards
 *
 * @method \App\Model\Entity\Strand get($primaryKey, $options = [])
 * @method \App\Model\Entity\Strand newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Strand[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Strand|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Strand patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Strand[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Strand findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StrandsTable extends Table
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

        $this->setTable('strands');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LearningAreas', [
            'foreignKey' => 'learning_area_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AssessmentStrands', [
            'foreignKey' => 'strand_id'
        ]);

        $this->hasMany('ReportTemplateCourseStrands', [
            'foreignKey' => 'strand_id'
        ]);

        $this->hasMany('ReportTemplateStrands', [
            'foreignKey' => 'strand_id'
        ]);

        $this->hasMany('UnitStrands', [
            'foreignKey' => 'strand_id'
        ]);

        $this->hasMany('CourseStrands', [
            'foreignKey' => 'strand_id'
        ]);
        $this->hasMany('Standards', [
            'foreignKey' => 'strand_id'
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->scalar('code')
            ->maxLength('code', 255)
            ->requirePresence('code', 'create')
            ->notEmpty('code');

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
        $rules->add($rules->existsIn(['learning_area_id'], 'LearningAreas'));

        return $rules;
    }
}
