<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Grades Model
 *
 * @property \App\Model\Table\AssessmentStrandsTable|\Cake\ORM\Association\HasMany $AssessmentStrands
 * @property \App\Model\Table\CourseStrandsTable|\Cake\ORM\Association\HasMany $CourseStrands
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\HasMany $Courses
 * @property \App\Model\Table\DivisionGradesTable|\Cake\ORM\Association\HasMany $DivisionGrades
 * @property |\Cake\ORM\Association\HasMany $GradeImpacts
 * @property |\Cake\ORM\Association\HasMany $ReportSettings
 * @property |\Cake\ORM\Association\HasMany $Reports
 * @property \App\Model\Table\StandardGradesTable|\Cake\ORM\Association\HasMany $StandardGrades
 * @property \App\Model\Table\UnitStrandsTable|\Cake\ORM\Association\HasMany $UnitStrands
 * @property |\Cake\ORM\Association\BelongsToMany $ReportTemplates
 *
 * @method \App\Model\Entity\Grade get($primaryKey, $options = [])
 * @method \App\Model\Entity\Grade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Grade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Grade|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Grade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Grade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Grade findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GradesTable extends Table
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

        $this->setTable('grades');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AssessmentStrands', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('CourseStrands', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('Courses', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('DivisionGrades', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('GradeImpacts', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('ReportSettings', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('StandardGrades', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('UnitStrands', [
            'foreignKey' => 'grade_id'
        ]);
        $this->hasMany('ReportTemplateCourseStrands', [
            'foreignKey' => 'grade_id'
        ]);
        $this->belongsToMany('ReportTemplates', [
            'foreignKey' => 'grade_id',
            'targetForeignKey' => 'report_template_id',
            'joinTable' => 'report_template_grades'
        ]);
        $this->hasMany('GradeContexts', [
            'foreignKey' => 'grade_id'
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
            ->integer('sort_order')
            ->requirePresence('sort_order', 'create')
            ->notEmpty('sort_order');

        return $validator;
    }
}
