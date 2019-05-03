<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateStudentServices Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Students
 * @property |\Cake\ORM\Association\BelongsTo $SpecialServiceTypes
 *
 * @method \App\Model\Entity\ReportTemplateStudentService get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateStudentService findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportTemplateStudentServicesTable extends Table
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

        $this->setTable('report_template_student_services');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SpecialServiceTypes', [
            'foreignKey' => 'special_service_type_id',
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
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['special_service_type_id'], 'SpecialServiceTypes'));

        return $rules;
    }
}
