<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use App\Model\Entity\UnitResource;

/**
 * UnitResources Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 *
 * @method \App\Model\Entity\UnitResource get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitResource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitResource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitResource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitResource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitResource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitResource findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitResourcesTable extends Table
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

        $this->setTable('unit_resources');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Footprint.Footprint');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
          'image_name' => [
            'path' => Configure::read('ImageUpload.uploadPathForUnitResources'),
            'fields' => [
              'dir' => 'image_path'
            ],
            'nameCallback' => function ($data, $settings) {
              return time(). $data['name'];
            },
          ],
        ]);
        $this->belongsTo('Assessments', [
            'foreignKey' => 'object_identifier',
            'finder' => 'assessmentFinder',
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
            ->scalar('resource_type')
            ->maxLength('resource_type', 255)
            ->requirePresence('resource_type', 'create')
            ->notEmpty('resource_type');
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmpty('url');

        $validator
            ->scalar('file_path')
            ->maxLength('file_path', 255)
            ->allowEmpty('file_path');

        $validator
            ->allowEmpty('file_name');
        $validator
            ->integer('object_identifier')
            ->allowEmpty('object_identifier');

        $validator
            ->allowEmpty('object_name');

        $validator
            ->integer('created_by')
            ->allowEmpty('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmpty('modified_by');

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

        return $rules;
    }

    public function afterSave($event, $entity){
      if($entity->isNew()){
       if($entity->object_name == 'unit'){
          $entity->object_identifier = $entity->unit_id;
       }
       $this->save($entity);
      }
    }
}
