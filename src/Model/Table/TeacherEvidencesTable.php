<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * TeacherEvidences Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Teachers
 * @property |\Cake\ORM\Association\HasMany $TeacherEvidenceContexts
 * @property |\Cake\ORM\Association\HasMany $TeacherEvidenceImpacts
 * @property |\Cake\ORM\Association\HasMany $TeacherEvidenceSections
 *
 * @method \App\Model\Entity\TeacherEvidence get($primaryKey, $options = [])
 * @method \App\Model\Entity\TeacherEvidence newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TeacherEvidence[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidence|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TeacherEvidence patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidence[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TeacherEvidence findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TeacherEvidencesTable extends Table
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

        $this->setTable('teacher_evidences');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Teachers', [
            'className' => 'Users',
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER'
        ]);
        // $this->hasMany('TeacherEvidenceContexts', [
        //     'foreignKey' => 'teacher_evidence_id',
        //     'saveStrategy' => 'replace',
        //     'dependent' => true,
        //     'cascadeCallback' => true
        // ]);
        $this->hasMany('TeacherEvidenceImpacts', [
            'foreignKey' => 'teacher_evidence_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallback' => true
        ]);
        $this->hasMany('TeacherEvidenceSections', [
            'foreignKey' => 'teacher_evidence_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallback' => true
        ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
          'image_name' => [
            'path' => Configure::read('FileUpload.uploadPathForEvidence'),
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->scalar('file_path')
            ->maxLength('file_path', 255)
            ->allowEmpty('file_path');

        $validator
            ->scalar('file_name')
            ->maxLength('file_name', 255)
            ->allowEmpty('file_name');

        $validator
            ->scalar('reflection_description')
            ->maxLength('reflection_description', 255)
            ->allowEmpty('reflection_description');

        $validator
            ->scalar('reflection_file_path')
            ->maxLength('reflection_file_path', 255)
            ->allowEmpty('reflection_file_path');

        $validator
            ->scalar('reflection_file_name')
            ->maxLength('reflection_file_name', 255)
            ->allowEmpty('reflection_file_name');

        // $validator
        //     ->boolean('digital_tool_used')
        //     ->requirePresence('digital_tool_used', 'create')
        //     ->notEmpty('digital_tool_used');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmpty('url');

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
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));

        return $rules;
    }
}
