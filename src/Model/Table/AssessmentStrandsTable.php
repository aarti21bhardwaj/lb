<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentStrands Model
 *
 * @property \App\Model\Table\AssessmentsTable|\Cake\ORM\Association\BelongsTo $Assessments
 * @property \App\Model\Table\StrandsTable|\Cake\ORM\Association\BelongsTo $Strands
 * @property \App\Model\Table\GradesTable|\Cake\ORM\Association\BelongsTo $Grades
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 *
 * @method \App\Model\Entity\AssessmentStrand get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssessmentStrand newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssessmentStrand[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentStrand|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssessmentStrand patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentStrand[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentStrand findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentStrandsTable extends Table
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

        $this->setTable('assessment_strands');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Assessments', [
            'foreignKey' => 'assessment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Strands', [
            'foreignKey' => 'strand_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id'
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
        $rules->add($rules->existsIn(['assessment_id'], 'Assessments'));
        $rules->add($rules->existsIn(['strand_id'], 'Strands'));
        $rules->add($rules->existsIn(['grade_id'], 'Grades'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));

        return $rules;
    }
}
