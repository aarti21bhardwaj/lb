<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportingPeriods Model
 *
 * @property \App\Model\Table\TermsTable|\Cake\ORM\Association\BelongsTo $Terms
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\HasMany $ReportTemplates
 *
 * @method \App\Model\Entity\ReportingPeriod get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportingPeriod newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportingPeriod[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportingPeriod|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportingPeriod patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportingPeriod[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportingPeriod findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportingPeriodsTable extends Table
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

        $this->setTable('reporting_periods');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Terms', [
            'foreignKey' => 'term_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReportTemplates', [
            'foreignKey' => 'reporting_period_id'
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
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->date('closing_date')
            ->requirePresence('closing_date', 'create')
            ->notEmpty('closing_date');

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
        $rules->add($rules->existsIn(['term_id'], 'Terms'));

        return $rules;
    }
}
