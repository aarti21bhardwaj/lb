<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Units Model
 *
 * @property \App\Model\Table\TemplatesTable|\Cake\ORM\Association\BelongsTo $Templates
 * @property \App\Model\Table\UnitTypesTable|\Cake\ORM\Association\BelongsTo $UnitTypes
 * @property \App\Model\Table\TransDisciplinaryThemesTable|\Cake\ORM\Association\BelongsTo $TransDisciplinaryThemes
 * @property |\Cake\ORM\Association\HasMany $UnitContents
 * @property \App\Model\Table\UnitCoursesTable|\Cake\ORM\Association\HasMany $UnitCourses
 * @property \App\Model\Table\UnitImpactsTable|\Cake\ORM\Association\HasMany $UnitImpacts
 * @property \App\Model\Table\UnitOtherGoalsTable|\Cake\ORM\Association\HasMany $UnitOtherGoals
 * @property \App\Model\Table\UnitReflectionsTable|\Cake\ORM\Association\HasMany $UnitReflections
 * @property \App\Model\Table\UnitResourcesTable|\Cake\ORM\Association\HasMany $UnitResources
 * @property |\Cake\ORM\Association\HasMany $UnitSpecificContents
 * @property \App\Model\Table\UnitStandardsTable|\Cake\ORM\Association\HasMany $UnitStandards
 * @property \App\Model\Table\UnitTeachersTable|\Cake\ORM\Association\HasMany $UnitTeachers
 *
 * @method \App\Model\Entity\Unit get($primaryKey, $options = [])
 * @method \App\Model\Entity\Unit newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Unit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Unit|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Unit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Unit[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Unit findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitsTable extends Table
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

        $this->setTable('units');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Footprint.Footprint');

        $this->belongsTo('Templates', [
            'foreignKey' => 'template_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('UnitTypes', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsTo('TransDisciplinaryThemes', [
            'foreignKey' => 'trans_disciplinary_theme_id'
        ]);
        $this->hasMany('UnitContents', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitCourses', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitImpacts', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitOtherGoals', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitReflections', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitResources', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitSpecificContents', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Assessments', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitStandards', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitStrands', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('UnitTeachers', [
            'foreignKey' => 'unit_id',
             'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('TermArchiveUnits', [
            'foreignKey' => 'unit_id'
        ]);
        $this->belongsToMany('Courses', [
            'through' => 'UnitCourses'
        ]);
        $this->belongsToMany('Teachers', [
            'through' => 'UnitTeachers',
            'className' => 'Users'
        ]);
        $this->belongsToMany('ContentCategories', [
            'through' => 'UnitContents',
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->date('start_date')
            ->allowEmpty('start_date');
            //->requirePresence('start_date', 'create')
            //->notEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');
            //->requirePresence('end_date', 'create')
            //->notEmpty('end_date');

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
        $rules->add($rules->existsIn(['template_id'], 'Templates'));
      //  $rules->add($rules->existsIn(['unit_type_id'], 'UnitTypes'));
       // $rules->add($rules->existsIn(['trans_disciplinary_theme_id'], 'TransDisciplinaryThemes'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, $entity){
            if($entity->isNew()){
                $courseIds = $this->UnitCourses->find()
                                                      ->where(['unit_id' => $entity->id])
                                                      ->all()
                                                      ->extract('course_id')
                                                      ->toArray();


                if(empty($courseIds)){
                  return;
                }

                $courseStrands = $this->UnitCourses->Courses->CourseStrands->find()
                                                                                  ->where(['course_id IN' => $courseIds])
                                                                                  // ->select(['strand_id', 'grade_id'])
                                                                                  // ->distinct(['strand_id', 'grade_id'])
                                                                                  ->all()
                                                                                  ->toArray();

                $unitStrandData = [];
                foreach ($courseStrands as $value) {
                    $unitStrandData[] = [
                                            'unit_id' => $entity->id,
                                            'strand_id' => $value->strand_id,
                                            'grade_id' => $value->grade_id,
                                            'course_id' => $value->course_id
                                         ];
                }


                $unitStrands = $this->UnitStrands->newEntities($unitStrandData);
                $this->UnitStrands->saveMany($unitStrands);

            }else{
              if(!empty($entity->is_archived)){
                // $this->Assessments = TableRegistry::get('Assessments');
                $this->Evaluations = TableRegistry::get('Evaluations');


                $assessmentIds = $this->Assessments->find()->where(['unit_id' => $entity->id])
                                                           ->extract('id')
                                                           ->toArray();

                if(!empty($assessmentIds)){
                  $evaluations = $this->Evaluations->updateAll(['is_archived' => 1],
                                                               ['assessment_id IN' => $assessmentIds]
                                                              );

                  // $evaluations = $this->Evaluations->update()
                  //                                  ->set(['is_archived' => 1])
                  //                                  ->where(['assessment_id IN' => $assessmentIds])
                  //                                  ->execute();
                }
              }   
            }

        }

}
