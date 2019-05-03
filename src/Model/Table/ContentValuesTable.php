<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContentValues Model
 *
 * @property \App\Model\Table\ContentCategoriesTable|\Cake\ORM\Association\BelongsTo $ContentCategories
 * @property \App\Model\Table\ContentValuesTable|\Cake\ORM\Association\BelongsTo $ParentContentValues
 * @property \App\Model\Table\ContentValuesTable|\Cake\ORM\Association\HasMany $ChildContentValues
 * @property \App\Model\Table\UnitContentsTable|\Cake\ORM\Association\HasMany $UnitContents
 *
 * @method \App\Model\Entity\ContentValue get($primaryKey, $options = [])
 * @method \App\Model\Entity\ContentValue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ContentValue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContentValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ContentValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ContentValue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContentValue findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class ContentValuesTable extends Table
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

        $this->setTable('content_values');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('ContentCategories', [
            'foreignKey' => 'content_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ParentContentValues', [
            'className' => 'ContentValues',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildContentValues', [
            'className' => 'ContentValues',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('UnitContents', [
            'foreignKey' => 'content_value_id'
        ]);
        $this->hasMany('EvidenceContents', [
            'foreignKey' => 'content_value_id'
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
            ->scalar('text')
            ->requirePresence('text', 'create')
            ->notEmpty('text');

        $validator
            ->boolean('is_selectable')
            ->allowEmpty('is_selectable');

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
        $rules->add($rules->existsIn(['content_category_id'], 'ContentCategories'));
        $rules->add($rules->existsIn(['parent_id'], 'ParentContentValues'));

        return $rules;
    }
}
