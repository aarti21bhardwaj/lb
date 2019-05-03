<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Collection\Collection;
use Cake\Log\Log;

/**
 * UnitStrands Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\StrandsTable|\Cake\ORM\Association\BelongsTo $Strands
 * @property \App\Model\Table\GradesTable|\Cake\ORM\Association\BelongsTo $Grades
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 *
 * @method \App\Model\Entity\UnitStrand get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitStrand newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitStrand[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitStrand|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitStrand patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitStrand[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitStrand findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitStrandsTable extends Table
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

        $this->setTable('unit_strands');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Strands', [
            'foreignKey' => 'strand_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Grades', [
            'foreignKey' => 'grade_id'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id'
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
        $rules->add($rules->existsIn(['unit_id'], 'Units'));
        $rules->add($rules->existsIn(['strand_id'], 'Strands'));
        $rules->add($rules->existsIn(['grade_id'], 'Grades'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));
        // $rules->add($rules->isUnique(['unit_id', 'strand_id', 'grade_id', 'course_id'],'These combination has already been used.'));


        return $rules;
    }

    public function beforeSave(\Cake\Event\Event $event, $entity){
        // pr($entity); die;
        if($entity->isNew()){

            $unitStrand = $this->find()->where(['unit_id' => $entity->unit_id, 
                                                'strand_id' => $entity->strand_id])
                                       ->first();

            
            if($unitStrand){
                $this->delete($unitStrand);
            }

        }

    }
    public function afterSave(\Cake\Event\Event $event, $entity){
        // pr($entity); die;
            $unitStrandIds = $this->find()->where(['unit_id' => $entity->unit_id])
                                        ->extract('strand_id')->toArray();

            $unitStrandIds = array_unique($unitStrandIds);

            $assessments = $this->Units->Assessments
                                      ->findByUnitId($entity->unit_id)
                                      ->contain(['AssessmentStrands'])
                                      ->toArray();

            foreach ($assessments as $key => $value) {
                $assessmentId = $value->id;
                $assessmentStrandIds = [];
                if(!empty($value->assessment_strands)){
                    $assessmentStrandIds = (new Collection($value->assessment_strands))->extract('strand_id')->toArray();
                }

                $assessmentStrandIds = array_unique($assessmentStrandIds);

                $strandDiff = array_diff($unitStrandIds, $assessmentStrandIds);

                if(empty($strandDiff)){
                    continue;
                }

                $assessmentStrands = (new Collection($strandDiff))->map(function($val, $key) use($assessmentId){
                    return [
                        'assessment_id' => $assessmentId,
                        'strand_id' => $val
                    ];

                })->toArray();

                $assessmentStrands = $this->Units->Assessments->AssessmentStrands->newEntities($assessmentStrands);

                if(!$this->Units->Assessments->AssessmentStrands->saveMany($assessmentStrands)){
                    Log::write('debug', "AssessmentStrands coulld not be saved");
                    Log::write('debug', $assessmentStrands);
                }

            }            

    }
}
