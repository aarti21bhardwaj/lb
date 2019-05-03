<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Curriculums Model
 *
 * @property \App\Model\Table\LearningAreasTable|\Cake\ORM\Association\HasMany $LearningAreas
 *
 * @method \App\Model\Entity\Curriculum get($primaryKey, $options = [])
 * @method \App\Model\Entity\Curriculum newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Curriculum[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Curriculum|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Curriculum patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Curriculum[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Curriculum findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CurriculumsTable extends Table
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

        $this->setTable('curriculums');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('LearningAreas', [
            'foreignKey' => 'curriculum_id'
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
