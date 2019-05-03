<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateImpacts Model
 *
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\BelongsTo $ReportTemplates
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 * @property \App\Model\Table\ImpactsTable|\Cake\ORM\Association\BelongsTo $Impacts
 * @property |\Cake\ORM\Association\HasMany $ReportTemplateImpactScores
 *
 * @method \App\Model\Entity\ReportTemplateImpact get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateImpact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateImpact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateImpact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateImpact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateImpact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateImpact findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTemplateImpactsTable extends Table
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

        $this->setTable('report_template_impacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportTemplates', [
            'foreignKey' => 'report_template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Impacts', [
            'foreignKey' => 'impact_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReportTemplateImpactScores', [
            'foreignKey' => 'report_template_impact_id'
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
        $rules->add($rules->existsIn(['course_id'], 'Courses'));
        $rules->add($rules->existsIn(['impact_id'], 'Impacts'));

        return $rules;
    }
}
