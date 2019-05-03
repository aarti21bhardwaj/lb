<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UnitContents Model
 *
 * @property \App\Model\Table\ContentCategoriesTable|\Cake\ORM\Association\BelongsTo $ContentCategories
 * @property \App\Model\Table\ContentValuesTable|\Cake\ORM\Association\BelongsTo $ContentValues
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 *
 * @method \App\Model\Entity\UnitContent get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitContent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitContent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitContent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitContent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitContent findOrCreate($search, callable $callback = null, $options = [])
 */
class UnitContentsTable extends Table
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

        $this->setTable('unit_contents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ContentCategories', [
            'foreignKey' => 'content_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ContentValues', [
            'foreignKey' => 'content_value_id',
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
        $rules->add($rules->existsIn(['content_category_id'], 'ContentCategories'));
        $rules->add($rules->existsIn(['content_value_id'], 'ContentValues'));
        $rules->add($rules->existsIn(['unit_id'], 'Units'));

        return $rules;
    }
}
