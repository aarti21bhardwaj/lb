<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UnitSpecificContents Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\ContentCategoriesTable|\Cake\ORM\Association\BelongsTo $ContentCategories
 * @property |\Cake\ORM\Association\HasMany $AssessmentContents
 *
 * @method \App\Model\Entity\UnitSpecificContent get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitSpecificContent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitSpecificContent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitSpecificContent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitSpecificContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitSpecificContent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitSpecificContent findOrCreate($search, callable $callback = null, $options = [])
 */
class UnitSpecificContentsTable extends Table
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

        $this->setTable('unit_specific_contents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ContentCategories', [
            'foreignKey' => 'content_category_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AssessmentContents', [
            'foreignKey' => 'unit_specific_content_id'
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
            ->requirePresence('text', 'create')
            ->notEmpty('text');

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
        $rules->add($rules->existsIn(['unit_id'], 'Units'));
        $rules->add($rules->existsIn(['content_category_id'], 'ContentCategories'));

        return $rules;
    }
}
