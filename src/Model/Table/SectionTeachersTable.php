<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SectionTeachers Model
 *
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 * @property \App\Model\Table\TeachersTable|\Cake\ORM\Association\BelongsTo $Teachers
 *
 * @method \App\Model\Entity\SectionTeacher get($primaryKey, $options = [])
 * @method \App\Model\Entity\SectionTeacher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SectionTeacher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SectionTeacher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SectionTeacher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SectionTeacher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SectionTeacher findOrCreate($search, callable $callback = null, $options = [])
 */
class SectionTeachersTable extends Table
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

        $this->setTable('section_teachers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id',
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
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));

        return $rules;
    }
}
