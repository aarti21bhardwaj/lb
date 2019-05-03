<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CampusCourseTeachers Model
 *
 * @property \App\Model\Table\CampusCoursesTable|\Cake\ORM\Association\BelongsTo $CampusCourses
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Teachers
 *
 * @method \App\Model\Entity\CampusCourseTeacher get($primaryKey, $options = [])
 * @method \App\Model\Entity\CampusCourseTeacher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CampusCourseTeacher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CampusCourseTeacher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CampusCourseTeacher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CampusCourseTeacher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CampusCourseTeacher findOrCreate($search, callable $callback = null, $options = [])
 */
class CampusCourseTeachersTable extends Table
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

        $this->setTable('campus_course_teachers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('CampusCourses', [
            'foreignKey' => 'campus_course_id',
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
            ->boolean('is_leader')
            ->allowEmpty('is_leader');

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
        $rules->add($rules->existsIn(['campus_course_id'], 'CampusCourses'));
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));

        return $rules;
    }
}
