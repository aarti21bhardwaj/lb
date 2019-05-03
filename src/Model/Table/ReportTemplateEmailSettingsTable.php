<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplateEmailSettings Model
 *
 * @property \App\Model\Table\ReportTemplatesTable|\Cake\ORM\Association\BelongsTo $ReportTemplates
 *
 * @method \App\Model\Entity\ReportTemplateEmailSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplateEmailSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplateEmailSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateEmailSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplateEmailSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateEmailSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplateEmailSetting findOrCreate($search, callable $callback = null, $options = [])
 */
class ReportTemplateEmailSettingsTable extends Table
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

        $this->setTable('report_template_email_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ReportTemplates', [
            'foreignKey' => 'report_template_id',
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
            ->scalar('sender_email')
            ->maxLength('sender_email', 255)
            ->requirePresence('sender_email', 'create')
            ->notEmpty('sender_email');

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmpty('body');

        $validator
            ->boolean('live_mode')
            ->requirePresence('live_mode', 'create')
            ->notEmpty('live_mode');

        $validator
            ->scalar('test_receiver_email')
            ->maxLength('test_receiver_email', 255)
            ->allowEmpty('test_receiver_email');

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
        $rules->add($rules->existsIn(['report_template_id'], 'ReportTemplates'));

        return $rules;
    }
}
