<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Collection\Collection;
use Cake\Log\Log;

/**
 * Assessments Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\AssessmentTypesTable|\Cake\ORM\Association\BelongsTo $AssessmentTypes
 * @property \App\Model\Table\AssessmentContentsTable|\Cake\ORM\Association\HasMany $AssessmentContents
 * @property \App\Model\Table\AssessmentImpactsTable|\Cake\ORM\Association\HasMany $AssessmentImpacts
 * @property \App\Model\Table\AssessmentSpecificContentsTable|\Cake\ORM\Association\HasMany $AssessmentSpecificContents
 * @property \App\Model\Table\AssessmentStandardsTable|\Cake\ORM\Association\HasMany $AssessmentStandards
 * @property \App\Model\Table\AssessmentStrandsTable|\Cake\ORM\Association\HasMany $AssessmentStrands
 * @property |\Cake\ORM\Association\HasMany $Evaluations
 *
 * @method \App\Model\Entity\Assessment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Assessment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Assessment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Assessment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Assessment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Assessment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Assessment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssessmentsTable extends Table
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

        $this->setTable('assessments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Footprint.Footprint');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AssessmentTypes', [
            'foreignKey' => 'assessment_type_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AssessmentSubtypes', [
            'foreignKey' => 'assessment_subtype_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('AssessmentContents', [
            'foreignKey' => 'assessment_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('AssessmentImpacts', [
            'foreignKey' => 'assessment_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('AssessmentSpecificContents', [
            'foreignKey' => 'assessment_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('AssessmentStandards', [
            'foreignKey' => 'assessment_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('AssessmentStrands', [
            'foreignKey' => 'assessment_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Evaluations', [
            'foreignKey' => 'assessment_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsToMany('Standards', [
            'through' => 'AssessmentStandards'
        ]);
        $this->belongsToMany('Strands', [
            'through' => 'AssessmentStrands'
        ]);
        $this->belongsToMany('Impacts', [
            'through' => 'AssessmentImpacts'
        ]);
    }

    public function findAssessmentFinder(Query $query, array $options){
        return $query->where(['object_name' => 'assessment']);
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
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->date('start_date')
            ->allowEmpty('start_date');
            // ->requirePresence('start_date', 'create')
            // ->notEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');
            // ->requirePresence('end_date', 'create')
            // ->notEmpty('end_date');

        $validator
            ->boolean('is_accessible')
            ->allowEmpty('is_accessible');

        $validator
            ->boolean('is_published')
            ->allowEmpty('is_published');

        $validator
            ->boolean('is_digital_tool_used')
            ->allowEmpty('is_digital_tool_used');


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
        $rules->add($rules->existsIn(['assessment_type_id'], 'AssessmentTypes'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, $entity){
        if($entity->isNew()){
            $courseIds = $this->Units->UnitCourses->find()
                                                  ->where(['unit_id' => $entity->unit_id])
                                                  ->all()
                                                  ->extract('course_id')
                                                  ->toArray();

            if(empty($courseIds)){
              return;
            }

            $courseStrands = $this->Units->UnitCourses->Courses->CourseStrands->find()
                                                                              ->where(['course_id IN' => $courseIds])
                                                                              // ->select(['strand_id', 'grade_id'])
                                                                              // ->distinct(['strand_id', 'grade_id'])
                                                                              ->all()
                                                                              ->toArray();
            $assessmentId = $entity->id;


            $assessmentStrandData = [];
            foreach ($courseStrands as $value) {
                $assessmentStrandData[] = [
                                        'assessment_id' => $entity->id,
                                        'strand_id' => $value->strand_id,
                                        'grade_id' => $value->grade_id,
                                        'course_id' => $value->course_id
                                     ];
            }

            
            $unitStrandIds = $this->Units->UnitStrands->find()
                                                      ->where(['unit_id' => $entity->unit_id])
                                                      // ->select(['strand_id', 'grade_id'])
                                                      // ->distinct(['strand_id', 'grade_id'])
                                                      ->extract('strand_id')
                                                      ->toArray();
            $unitStrandIds = array_unique($unitStrandIds);
            $unitAssessmentStrands = [];
            if(!empty($unitStrandIds)){
              $courseStrandIds = (new Collection($courseStrands))->extract('strand_id')->toArray();
              $strandDiff = array_diff($unitStrandIds, $courseStrandIds);
              if(!empty($strandDiff)){
                 $unitAssessmentStrands = (new Collection($strandDiff))->map(function($val, $key) use($assessmentId){
                    return [
                        'assessment_id' => $assessmentId,
                        'strand_id' => $val
                    ];

                })->toArray();
                $assessmentStrandData = array_merge($assessmentStrandData, $unitAssessmentStrands);
              }
            }

            $assessmentStrands = $this->AssessmentStrands->newEntities($assessmentStrandData);
            $this->AssessmentStrands->saveMany($assessmentStrands);

        }

    }
}
