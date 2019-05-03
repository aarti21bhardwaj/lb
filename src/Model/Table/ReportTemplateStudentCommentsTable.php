<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateStudentComments Model
 *
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\BelongsTo $ReportTemplates
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\TeachersTable|\Cake\ORM\Association\BelongsTo $Teachers
 *
 * @method \App\Model\Entity\ReportTemplateStudentComment get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentComment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentComment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentComment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentComment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentComment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentComment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTemplateStudentCommentsTable extends Table
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

        $this->setTable('report_template_student_comments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportTemplates', [
            'foreignKey' => 'report_template_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Teachers', [
            'className' => 'Users',
            'foreignKey' => 'teacher_id',
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
            ->scalar('teacher_comment')
            ->allowEmpty('teacher_comment');

        $validator
            ->scalar('student_comment')
            ->allowEmpty('student_comment');

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
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));
        $rules->add($rules->isUnique(['report_template_id', 'student_id'],'This combination has already been used.'));
        


        return $rules;
    }
}
