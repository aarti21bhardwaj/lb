<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplatePages Model
 *
 * @property \App\Model\Table\ReportTemplateTypesTable|\Cake\ORM\Association\BelongsTo $ReportTemplateTypes
 *
 * @method \App\Model\Entity\ReportTemplatePage get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplatePage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplatePage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplatePage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplatePage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplatePage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplatePage findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTemplatePagesTable extends Table
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

        $this->setTable('report_template_pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportTemplateTypes', [
            'foreignKey' => 'report_template_type_id',
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmpty('body');

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
        $rules->add($rules->existsIn(['report_template_type_id'], 'ReportTemplateTypes'));

        return $rules;
    }
}
