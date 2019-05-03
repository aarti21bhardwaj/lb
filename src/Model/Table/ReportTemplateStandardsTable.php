<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateStandards Model
 *
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\BelongsTo $ReportTemplates
 * @property \App\Model\Table\StandardsTable|\Cake\ORM\Association\BelongsTo $Standards
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 * @property |\Cake\ORM\Association\HasMany $ReportTemplateStandardScores
 *
 * @method \App\Model\Entity\ReportTemplateStandard get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateStandard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandard|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateStandard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStandard findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTemplateStandardsTable extends Table
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

        $this->setTable('report_template_standards');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportTemplates', [
            'foreignKey' => 'report_template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Standards', [
            'foreignKey' => 'standard_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReportTemplateStandardScores', [
            'foreignKey' => 'report_template_standard_id'
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
        $rules->add($rules->existsIn(['report_template_id'], 'ReportTemplates'));
        $rules->add($rules->existsIn(['standard_id'], 'Standards'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));

        return $rules;
    }
}
