<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportSettings Model
 *
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\BelongsTo $ReportTemplates
 * @property \App\Model\Table\GradesTable|\Cake\ORM\Association\BelongsTo $Grades
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 * @property |\Cake\ORM\Association\BelongsTo $ReportTemplatePages
 *
 * @method \App\Model\Entity\ReportSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportSetting findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportSettingsTable extends Table
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

        $this->setTable('report_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportTemplates', [
            'foreignKey' => 'report_template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ReportTemplatePages', [
            'foreignKey' => 'report_template_page_id',
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
            ->boolean('course_status')
            ->requirePresence('course_status', 'create')
            ->notEmpty('course_status');

        $validator
            ->boolean('course_comment_status')
            ->requirePresence('course_comment_status', 'create')
            ->notEmpty('course_comment_status');

        $validator
            ->boolean('course_scale_status')
            ->allowEmpty('course_scale_status');

        $validator
            ->boolean('strand_status')
            ->allowEmpty('strand_status');

        $validator
            ->boolean('strand_comment_status')
            ->allowEmpty('strand_comment_status');

        $validator
            ->boolean('standard_status')
            ->allowEmpty('standard_status');

        $validator
            ->boolean('standard_comment_status')
            ->allowEmpty('standard_comment_status');

        $validator
            ->boolean('impact_status')
            ->allowEmpty('impact_status');

        $validator
            ->boolean('show_teacher_reflection')
            ->allowEmpty('show_teacher_reflection');

        $validator
            ->boolean('show_student_reflection')
            ->allowEmpty('show_student_reflection');

        $validator
            ->boolean('show_special_services')
            ->allowEmpty('show_special_services');

        $validator
            ->boolean('impact_comment_status')
            ->requirePresence('impact_comment_status', 'create')
            ->notEmpty('impact_comment_status');

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
        $rules->add($rules->existsIn(['grade_id'], 'Grades'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));
        $rules->add($rules->existsIn(['report_template_page_id'], 'ReportTemplatePages'));

        return $rules;
    }
}
