<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OtherGoalCategories Model
 *
 * @property \App\Model\Table\UnitOtherGoalsTable|\Cake\ORM\Association\HasMany $UnitOtherGoals
 *
 * @method \App\Model\Entity\OtherGoalCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\OtherGoalCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OtherGoalCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OtherGoalCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OtherGoalCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OtherGoalCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OtherGoalCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OtherGoalCategoriesTable extends Table
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

        $this->setTable('other_goal_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('UnitOtherGoals', [
            'foreignKey' => 'other_goal_category_id'
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

        return $validator;
    }
}
