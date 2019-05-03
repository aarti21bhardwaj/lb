<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateCourseScores Model
 *
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\BelongsTo $ReportTemplates
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\BelongsTo $ScaleValues
 *
 * @method \App\Model\Entity\ReportTemplateCourseScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateCourseScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateCourseScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateCourseScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateCourseScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateCourseScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateCourseScore findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportTemplateCourseScoresTable extends Table
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

        $this->setTable('report_template_course_scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Muffin/Footprint.Footprint');

        $this->belongsTo('ReportTemplates', [
            'foreignKey' => 'report_template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
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
            ->boolean('is_completed')
            ->requirePresence('is_completed', 'create')
            ->notEmpty('is_completed');

        $validator
            ->scalar('comment')
            ->allowEmpty('comment');

        $validator
            ->integer('created_by')
            ->allowEmpty('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmpty('modified_by');

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
        // $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['scale_value_id'], 'ScaleValues'));

        return $rules;
    }
}
