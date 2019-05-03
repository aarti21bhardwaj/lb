<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateVariables Model
 *
 * @property \App\Model\Table\ReportTemplateTypesTable|\Cake\ORM\Association\BelongsTo $ReportTemplateTypes
 *
 * @method \App\Model\Entity\ReportTemplateVariable get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateVariable newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateVariable[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateVariable|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateVariable patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateVariable[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateVariable findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTemplateVariablesTable extends Table
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

        $this->setTable('report_template_variables');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportTemplateTypes', [
            'foreignKey' => 'report_template_type_id',
            'joinType' => 'INNER'
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
            ->scalar('identifier')
            ->maxLength('identifier', 255)
            ->requirePresence('identifier', 'create')
            ->notEmpty('identifier');

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
        $rules->add($rules->existsIn(['report_template_type_id'], 'ReportTemplateTypes'));

        return $rules;
    }
}
