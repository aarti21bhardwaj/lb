<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Schema\TableSchema;

/**
 * ContentCategories Model
 *
 * @property \App\Model\Table\ContentValuesTable|\Cake\ORM\Association\HasMany $ContentValues
 * @property \App\Model\Table\CourseContentCategoriesTable|\Cake\ORM\Association\HasMany $CourseContentCategories
 * @property \App\Model\Table\UnitContentsTable|\Cake\ORM\Association\HasMany $UnitContents
 * @property \App\Model\Table\UnitSpecificContentsTable|\Cake\ORM\Association\HasMany $UnitSpecificContents
 *
 * @method \App\Model\Entity\ContentCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ContentCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ContentCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContentCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ContentCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ContentCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContentCategory findOrCreate($search, callable $callback = null, $options = [])
 */
class ContentCategoriesTable extends Table
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

        $this->setTable('content_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ContentValues', [
            'foreignKey' => 'content_category_id'
        ]);
        $this->hasMany('CourseContentCategories', [
            'foreignKey' => 'content_category_id'
        ]);
        $this->hasMany('UnitContents', [
            'foreignKey' => 'content_category_id'
        ]);
        $this->hasMany('UnitSpecificContents', [
            'foreignKey' => 'content_category_id'
        ]);
        $this->hasMany('AssessmentContents', [
            'foreignKey' => 'content_category_id'
        ]);
        $this->hasMany('AssessmentSpecificContents', [
            'foreignKey' => 'content_category_id'
        ]);
        $this->hasMany('EvidenceContents', [
            'foreignKey' => 'content_category_id'
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
            // ->scalar('meta')
            ->allowEmpty('meta');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
