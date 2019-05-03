<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateStrandScores Model
 *
 * @property \App\Model\Table\ReportTemplateStrandsTable|\Cake\ORM\Association\BelongsTo $ReportTemplateStrands
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\BelongsTo $ScaleValues
 *
 * @method \App\Model\Entity\ReportTemplateStrandScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateStrandScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStrandScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStrandScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateStrandScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStrandScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStrandScore findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportTemplateStrandScoresTable extends Table
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

        $this->setTable('report_template_strand_scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ReportTemplateStrands', [
            'foreignKey' => 'report_template_strand_id',
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
        $rules->add($rules->existsIn(['report_template_strand_id'], 'ReportTemplateStrands'));
        // $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['scale_value_id'], 'ScaleValues'));

        return $rules;
    }
}
