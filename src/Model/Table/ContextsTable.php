<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contexts Model
 *
 * @property |\Cake\ORM\Association\HasMany $GradeContexts
 *
 * @method \App\Model\Entity\Context get($primaryKey, $options = [])
 * @method \App\Model\Entity\Context newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Context[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Context|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Context patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Context[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Context findOrCreate($search, callable $callback = null, $options = [])
 */
class ContextsTable extends Table
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

        $this->setTable('contexts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('GradeContexts', [
            'foreignKey' => 'context_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallback' => true
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
