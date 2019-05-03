<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateStandardScores Model
 *
 * @property \App\Model\Table\ReportTemplateStandardsTable|\Cake\ORM\Association\BelongsTo $ReportTemplateStandards
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\BelongsTo $ScaleValues
 *
 * @method \App\Model\Entity\ReportTemplateStandardScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateStandardScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandardScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandardScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateStandardScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandardScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandardScore findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportTemplateStandardScoresTable extends Table
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

        $this->setTable('report_template_standard_scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ReportTemplateStandards', [
            'foreignKey' => 'report_template_standard_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ScaleValues', [
            'foreignKey' => 'scale_value_id'
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
            ->scalar('comment')
            ->allowEmpty('comment');

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
        $rules->add($rules->existsIn(['report_template_standard_id'], 'ReportTemplateStandards'));
        // $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['scale_value_id'], 'ScaleValues'));

        return $rules;
    }
}
