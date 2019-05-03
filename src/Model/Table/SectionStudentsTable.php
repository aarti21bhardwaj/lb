<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SectionStudents Model
 *
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 *
 * @method \App\Model\Entity\SectionStudent get($primaryKey, $options = [])
 * @method \App\Model\Entity\SectionStudent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SectionStudent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SectionStudent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SectionStudent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SectionStudent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SectionStudent findOrCreate($search, callable $callback = null, $options = [])
 */
class SectionStudentsTable extends Table
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

        $this->setTable('section_students');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
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
        $rules->add($rules->existsIn(['section_id'], 'Sections'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));

        return $rules;
    }
}
