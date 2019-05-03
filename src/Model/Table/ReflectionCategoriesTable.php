<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReflectionCategories Model
 *
 * @property \App\Model\Table\UnitReflectionsTable|\Cake\ORM\Association\HasMany $UnitReflections
 *
 * @method \App\Model\Entity\ReflectionCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReflectionCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReflectionCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReflectionCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReflectionCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReflectionCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReflectionCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReflectionCategoriesTable extends Table
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

        $this->setTable('reflection_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('UnitReflections', [
            'foreignKey' => 'reflection_category_id'
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
