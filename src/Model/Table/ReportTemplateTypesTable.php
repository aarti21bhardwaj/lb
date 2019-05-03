<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateTypes Model
 *
 * @property \App\Model\Table\ReportTemplatePagesTable|\Cake\ORM\Association\HasMany $ReportTemplatePages
 *
 * @method \App\Model\Entity\ReportTemplateType get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateType findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportTemplateTypesTable extends Table
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

        $this->setTable('report_template_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('ReportTemplatePages', [
            'foreignKey' => 'report_template_type_id'
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
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
