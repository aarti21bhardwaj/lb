<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TermArchiveUnits Model
 *
 * @property \App\Model\Table\TermsTable|\Cake\ORM\Association\BelongsTo $Terms
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 *
 * @method \App\Model\Entity\TermArchiveUnit get($primaryKey, $options = [])
 * @method \App\Model\Entity\TermArchiveUnit newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TermArchiveUnit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TermArchiveUnit|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TermArchiveUnit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TermArchiveUnit[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TermArchiveUnit findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TermArchiveUnitsTable extends Table
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

        $this->setTable('term_archive_units');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Terms', [
            'foreignKey' => 'term_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
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
        $rules->add($rules->existsIn(['term_id'], 'Terms'));
        $rules->add($rules->existsIn(['unit_id'], 'Units'));

        return $rules;
    }
}
