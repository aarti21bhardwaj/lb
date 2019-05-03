<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\ScaleValue;
use Cake\Core\Configure;

/**
 * ScaleValues Model
 *
 * @property \App\Model\Table\ScalesTable|\Cake\ORM\Association\BelongsTo $Scales
 * @property \App\Model\Table\EvaluationImpactScoresTable|\Cake\ORM\Association\HasMany $EvaluationImpactScores
 * @property \App\Model\Table\EvaluationStandardScoresTable|\Cake\ORM\Association\HasMany $EvaluationStandardScores
 *
 * @method \App\Model\Entity\ScaleValue get($primaryKey, $options = [])
 * @method \App\Model\Entity\ScaleValue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ScaleValue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ScaleValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ScaleValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ScaleValue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ScaleValue findOrCreate($search, callable $callback = null, $options = [])
 */
class ScaleValuesTable extends Table
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

        $this->setTable('scale_values');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Scales', [
            'foreignKey' => 'scale_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('EvaluationImpactScores', [
            'foreignKey' => 'scale_value_id'
        ]);
        $this->hasMany('EvaluationStandardScores', [
            'foreignKey' => 'scale_value_id'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
          'image_name' => [
            'path' => Configure::read('ImageUpload.scaleImage'),
            'fields' => [
              'dir' => 'image_path'
            ],
            'nameCallback' => function ($data, $settings) {
              return time(). $data['name'];
            },
          ],
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
            ->allowEmpty('name');

        $validator
            ->integer('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->integer('sort_order')
            ->requirePresence('sort_order', 'create')
            ->notEmpty('sort_order');

        $validator
            ->boolean('numeric_value')
            ->allowEmpty('numeric_value');

        $validator
            ->boolean('is_default')
            ->allowEmpty('is_default');

        $validator
            ->scalar('image_path')
            // ->maxLength('image_path', 255)
            ->allowEmpty('image_path');

        $validator
            ->allowEmpty('image_name');

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
        $rules->add($rules->existsIn(['scale_id'], 'Scales'));

        return $rules;
    }
}
