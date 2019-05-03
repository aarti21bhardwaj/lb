<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\School;
use Cake\Core\Configure;

/**
 * Schools Model
 *
 * @property \App\Model\Table\CampusesTable|\Cake\ORM\Association\HasMany $Campuses
 * @property \App\Model\Table\DivisionsTable|\Cake\ORM\Association\HasMany $Divisions
 * @property \App\Model\Table\SchoolUsersTable|\Cake\ORM\Association\HasMany $SchoolUsers
 *
 * @method \App\Model\Entity\School get($primaryKey, $options = [])
 * @method \App\Model\Entity\School newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\School[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\School|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\School patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\School[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\School findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchoolsTable extends Table
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

        $this->setTable('schools');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Campuses', [
            'foreignKey' => 'school_id'
        ]);
        $this->hasMany('Divisions', [
            'foreignKey' => 'school_id'
        ]);
        $this->hasMany('SchoolUsers', [
            'foreignKey' => 'school_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'school_id'
        ]);
        $this->hasMany('AcademicYears', [
            'foreignKey' => 'school_id'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
          'logo_image_name' => [
            'path' => Configure::read('ImageUpload.uploadPathForSchoolLogo'),
            'fields' => [
              'dir' => 'logo_image_path'
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

        $validator
            ->scalar('logo_image_path')
            ->allowEmpty('logo_image_path');

        $validator
            ->allowEmpty('logo_image_name');

        return $validator;
    }
}
