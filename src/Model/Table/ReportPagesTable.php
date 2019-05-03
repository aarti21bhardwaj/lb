<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportPages Model
 *
 * @property \App\Model\Table\ReportsTable|\Cake\ORM\Association\HasMany $Reports
 *
 * @method \App\Model\Entity\ReportPage get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportPage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportPage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportPage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportPage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportPage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportPage findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportPagesTable extends Table
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

        $this->setTable('report_pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Reports', [
            'foreignKey' => 'report_page_id'
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
}
