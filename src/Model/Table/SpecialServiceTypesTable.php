<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SpecialServiceTypes Model
 *
 * @property |\Cake\ORM\Association\HasMany $ReportTemplateStudentServices
 *
 * @method \App\Model\Entity\SpecialServiceType get($primaryKey, $options = [])
 * @method \App\Model\Entity\SpecialServiceType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SpecialServiceType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SpecialServiceType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SpecialServiceType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SpecialServiceType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SpecialServiceType findOrCreate($search, callable $callback = null, $options = [])
 */
class SpecialServiceTypesTable extends Table
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

        $this->setTable('special_service_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ReportTemplateStudentServices', [
            'foreignKey' => 'special_service_type_id'
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
