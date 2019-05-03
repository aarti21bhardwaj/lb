<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CampusCourses Model
 *
 * @property \App\Model\Table\CampusesTable|\Cake\ORM\Association\BelongsTo $Campuses
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 * @property \App\Model\Table\CampusCourseTeachersTable|\Cake\ORM\Association\HasMany $CampusCourseTeachers
 *
 * @method \App\Model\Entity\CampusCourse get($primaryKey, $options = [])
 * @method \App\Model\Entity\CampusCourse newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CampusCourse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CampusCourse|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CampusCourse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CampusCourse[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CampusCourse findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CampusCoursesTable extends Table
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

        $this->setTable('campus_courses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Campuses', [
            'foreignKey' => 'campus_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CampusCourseTeachers', [
            'foreignKey' => 'campus_course_id'
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
        $rules->add($rules->existsIn(['campus_id'], 'Campuses'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));
        $rules->add($rules->IsUnique(['campus_id', 'course_id'], false));

        return $rules;
    }
}
