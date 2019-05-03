<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ImpactCategories Model
 *
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\HasMany $Impacts
 *
 * @method \App\Model\Entity\ImpactCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ImpactCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ImpactCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ImpactCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ImpactCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ImpactCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ImpactCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ImpactCategoriesTable extends Table
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

        $this->setTable('impact_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Impacts', [
            'foreignKey' => 'impact_category_id'
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
}
