<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Courses Model
 *
 * @property \App\Model\Table\GradesTable|\Cake\ORM\Association\BelongsTo $Grades
 * @property \App\Model\Table\LearningAreasTable|\Cake\ORM\Association\BelongsTo $LearningAreas
 * @property \App\Model\Table\AssessmentStrandsTable|\Cake\ORM\Association\HasMany $AssessmentStrands
 * @property \App\Model\Table\CampusCoursesTable|\Cake\ORM\Association\HasMany $CampusCourses
 * @property \App\Model\Table\CourseContentCategoriesTable|\Cake\ORM\Association\HasMany $CourseContentCategories
 * @property \App\Model\Table\CourseStrandsTable|\Cake\ORM\Association\HasMany $CourseStrands
 * @property |\Cake\ORM\Association\HasMany $ReportSettings
 * @property |\Cake\ORM\Association\HasMany $Reports
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\HasMany $Sections
 * @property \App\Model\Table\UnitCoursesTable|\Cake\ORM\Association\HasMany $UnitCourses
 * @property \App\Model\Table\UnitStrandsTable|\Cake\ORM\Association\HasMany $UnitStrands
 *
 * @method \App\Model\Entity\Course get($primaryKey, $options = [])
 * @method \App\Model\Entity\Course newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Course[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Course|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Course patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Course[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Course findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CoursesTable extends Table
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

        $this->setTable('courses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('UserPermission', [
                            'foreignKey' => 'division_id',
                            'pathToModel' => 'Grades.DivisionGrades.Divisions'
                          ]);

        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('LearningAreas', [
            'foreignKey' => 'learning_area_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AssessmentStrands', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('CampusCourses', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('CourseContentCategories', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('CourseStrands', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('ReportSettings', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('ReportTemplateCourseStrands', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('ReportTemplateStrands', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('ReportTemplateStandards', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('ReportTemplateImpacts', [
            'foreignKey' => 'course_id'
        ]);

        $this->hasMany('ReportTemplateCourseScores', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('Sections', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('UnitCourses', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('UnitStrands', [
            'foreignKey' => 'course_id'
        ]);
        $this->hasMany('CourseGrades', [
            'foreignKey' => 'course_id'
        ]);
        $this->belongsToMany('Units', [
            'through' => 'UnitCourses'
        ]);
        $this->belongsToMany('ContentCategories', [
            'through' => 'CourseContentCategories'
        ]);
        $this->belongsToMany('ReportTemplatePages', [
            'through' => 'ReportSettings'
        ]);
        
        // $this->belongsToMany('ReportTemplateStrandScores', [
        //     'through' => 'ReportTemplateStrands'
        // ]);
        // $this->belongsToMany('ReportTemplateStandardScores', [
        //     'through' => 'ReportTemplateStandards'
        // ]);

        // $this->belongsToMany('ReportTemplateImpactScores', [
        //     'through' => 'ReportTemplateImpacts'
        // ]);
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');

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
        $rules->add($rules->existsIn(['grade_id'], 'Grades'));
        $rules->add($rules->existsIn(['learning_area_id'], 'LearningAreas'));

        return $rules;
    }
}
