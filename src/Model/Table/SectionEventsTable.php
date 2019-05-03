<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SectionEvents Model
 *
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 *
 * @method \App\Model\Entity\SectionEvent get($primaryKey, $options = [])
 * @method \App\Model\Entity\SectionEvent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SectionEvent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SectionEvent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SectionEvent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SectionEvent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SectionEvent findOrCreate($search, callable $callback = null, $options = [])
 */
class SectionEventsTable extends Table
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

        $this->setTable('section_events');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->scalar('object_name')
            ->maxLength('object_name', 255)
            ->allowEmpty('object_name');

        $validator
            ->integer('object_identifier')
            ->allowEmpty('object_identifier');

        return $validator;
    }


    public function findSectionEvents(Query $query, array $options)
    {
        return $query->where(['object_name' => 'evaluation']);
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

        return $rules;
    }
}
