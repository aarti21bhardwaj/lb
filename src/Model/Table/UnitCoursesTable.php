<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UnitCourses Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 *
 * @method \App\Model\Entity\UnitCourse get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitCourse newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitCourse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitCourse|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitCourse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitCourse[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitCourse findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitCoursesTable extends Table
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

        $this->setTable('unit_courses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
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
            ->boolean('is_primary')
            ->requirePresence('is_primary', 'create')
            ->notEmpty('is_primary');

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
        $rules->add($rules->existsIn(['unit_id'], 'Units'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, $entity){
        if($entity->isNew()){
            $courseStrands = $this->Courses->CourseStrands->find()->where(['course_id' => $entity->course_id])
                                                                  ->all()
                                                                  ->toArray();
            
            $unitStrandData = [];
            foreach ($courseStrands as $value) {
                $unitStrandData[] = [
                                        'unit_id' => $entity->unit_id,
                                        'strand_id' => $value->strand_id,
                                        'grade_id' => $value->grade_id,
                                        'course_id' => $value->course_id
                                     ];
            }

            $unitStrands = $this->Units->UnitStrands->newEntities($unitStrandData);
            $this->Units->UnitStrands->saveMany($unitStrands);

        }

    }
}
