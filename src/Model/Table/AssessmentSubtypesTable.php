<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentSubtypes Model
 *
 * @property |\Cake\ORM\Association\HasMany $Assessments
 *
 * @method \App\Model\Entity\AssessmentSubtype get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentSubtype newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentSubtype[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSubtype|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentSubtype patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSubtype[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentSubtype findOrCreate($search, callable $callback = null, $options = [])
 */
class AssessmentSubtypesTable extends Table
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

        $this->setTable('assessment_subtypes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Assessments', [
            'foreignKey' => 'assessment_subtype_id'
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
