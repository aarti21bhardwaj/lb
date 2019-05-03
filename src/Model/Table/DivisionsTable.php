<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Divisions Model
 *
 * @property \App\Model\Table\SchoolsTable|\Cake\ORM\Association\BelongsTo $Schools
 * @property |\Cake\ORM\Association\BelongsTo $Campuses
 * @property \App\Model\Table\TemplatesTable|\Cake\ORM\Association\BelongsTo $Templates
 * @property \App\Model\Table\DivisionGradesTable|\Cake\ORM\Association\HasMany $DivisionGrades
 * @property \App\Model\Table\TermsTable|\Cake\ORM\Association\HasMany $Terms
 *
 * @method \App\Model\Entity\Division get($primaryKey, $options = [])
 * @method \App\Model\Entity\Division newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Division[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Division|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Division patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Division[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Division findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DivisionsTable extends Table
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

        $this->setTable('divisions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Campuses', [
            'foreignKey' => 'campus_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Templates', [
            'foreignKey' => 'template_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('DivisionGrades', [
            'foreignKey' => 'division_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallback' => true
        ]);
        $this->hasMany('Terms', [
            'foreignKey' => 'division_id'
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
        $rules->add($rules->existsIn(['school_id'], 'Schools'));
        $rules->add($rules->existsIn(['campus_id'], 'Campuses'));
        $rules->add($rules->existsIn(['template_id'], 'Templates'));

        return $rules;
    }
}
