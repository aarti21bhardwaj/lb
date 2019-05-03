<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Schema\TableSchema;

/**
 * CronJobs Model
 *
 * @method \App\Model\Entity\CronJob get($primaryKey, $options = [])
 * @method \App\Model\Entity\CronJob newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CronJob[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CronJob|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CronJob patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CronJob[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CronJob findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CronJobsTable extends Table
{

    protected function _initializeSchema(TableSchema $schema){
        $schema->columnType('meta', 'json');
        return $schema;
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cron_jobs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('shell_name')
            ->maxLength('shell_name', 255)
            ->requirePresence('shell_name', 'create')
            ->notEmpty('shell_name');

        $validator
            ->scalar('method_name')
            ->maxLength('method_name', 255)
            ->requirePresence('method_name', 'create')
            ->notEmpty('method_name');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->boolean('in_process')
            ->requirePresence('in_process', 'create')
            ->notEmpty('in_process');

        $validator
            ->allowEmpty('meta');

        return $validator;
    }
}
