<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UnitReflections Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\ReflectionCategoriesTable|\Cake\ORM\Association\BelongsTo $ReflectionCategories
 *
 * @method \App\Model\Entity\UnitReflection get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitReflection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitReflection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitReflection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitReflection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitReflection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitReflection findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitReflectionsTable extends Table
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

        $this->setTable('unit_reflections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Footprint.Footprint');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReflectionCategories', [
            'foreignKey' => 'reflection_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReflectionSubcategories', [
            'foreignKey' => 'reflection_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assessments', [
            'foreignKey' => 'object_identifier',
            'finder' => 'assessmentFinder',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'created_by'
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

        $validator
            ->integer('object_identifier')
            ->allowEmpty('object_identifier');

        $validator
            ->allowEmpty('object_name');

        $validator
            ->integer('created_by')
            ->allowEmpty('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmpty('modified_by');

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
        $rules->add($rules->existsIn(['unit_id'], 'Units'));
        $rules->add($rules->existsIn(['reflection_category_id'], 'ReflectionCategories'));

        return $rules;
    }

    public function afterSave($event, $entity){
      if($entity->isNew()){
       if($entity->object_name == 'unit'){
          $entity->object_identifier = $entity->unit_id;
       }
       $this->save($entity);
      }
    }
}
