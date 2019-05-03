<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * Scales Model
 *
 * @property \App\Model\Table\EvaluationsTable|\Cake\ORM\Association\HasMany $Evaluations
 * @property \App\Model\Table\ScaleValuesTable|\Cake\ORM\Association\HasMany $ScaleValues
 *
 * @method \App\Model\Entity\Scale get($primaryKey, $options = [])
 * @method \App\Model\Entity\Scale newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Scale[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Scale|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Scale patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Scale[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Scale findOrCreate($search, callable $callback = null, $options = [])
 */
class ScalesTable extends Table
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

        $this->setTable('scales');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Evaluations', [
            'foreignKey' => 'scale_id'
        ]);
        $this->hasMany('ScaleValues', [
            'foreignKey' => 'scale_id'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
          'image_name' => [
            'path' => Configure::read('ImageUpload.uploadPathForUsers'),
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}
