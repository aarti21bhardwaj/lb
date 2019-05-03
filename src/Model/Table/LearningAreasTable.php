<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LearningAreas Model
 *
 * @property \App\Model\Table\CurriculumsTable|\Cake\ORM\Association\BelongsTo $Curriculums
 * @property |\Cake\ORM\Association\HasMany $Courses
 * @property \App\Model\Table\StrandsTable|\Cake\ORM\Association\HasMany $Strands
 *
 * @method \App\Model\Entity\LearningArea get($primaryKey, $options = [])
 * @method \App\Model\Entity\LearningArea newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LearningArea[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LearningArea|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LearningArea patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LearningArea[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LearningArea findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LearningAreasTable extends Table
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

        $this->setTable('learning_areas');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Curriculums', [
            'foreignKey' => 'curriculum_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Courses', [
            'foreignKey' => 'learning_area_id'
        ]);
        $this->hasMany('Strands', [
            'foreignKey' => 'learning_area_id'
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
        $rules->add($rules->existsIn(['curriculum_id'], 'Curriculums'));

        return $rules;
    }
}
