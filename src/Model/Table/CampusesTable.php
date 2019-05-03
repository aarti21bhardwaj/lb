<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Campuses Model
 *
 * @property \App\Model\Table\SchoolsTable|\Cake\ORM\Association\BelongsTo $Schools
 * @property \App\Model\Table\CampusCoursesTable|\Cake\ORM\Association\HasMany $CampusCourses
 * @property |\Cake\ORM\Association\HasMany $CampusSettings
 * @property \App\Model\Table\CampusTeachersTable|\Cake\ORM\Association\HasMany $CampusTeachers
 * @property \App\Model\Table\DivisionsTable|\Cake\ORM\Association\HasMany $Divisions
 *
 * @method \App\Model\Entity\Campus get($primaryKey, $options = [])
 * @method \App\Model\Entity\Campus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Campus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Campus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Campus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Campus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Campus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CampusesTable extends Table
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

        $this->setTable('campuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CampusCourses', [
            'foreignKey' => 'campus_id'
        ]);
        $this->hasMany('CampusSettings', [
            'foreignKey' => 'campus_id'
        ]);
        $this->hasMany('CampusTeachers', [
            'foreignKey' => 'campus_id'
        ]);
        $this->hasMany('Divisions', [
            'foreignKey' => 'campus_id'
        ]);
        $this->belongsToMany('Courses', [
            'through' => 'CampusCourses'
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
        $rules->add($rules->existsIn(['school_id'], 'Schools'));

        return $rules;
    }
}
