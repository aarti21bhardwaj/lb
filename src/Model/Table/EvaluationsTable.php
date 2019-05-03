<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ModelAwareTrait;
use Cake\Controller\Controller;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;

/**
 * Evaluations Model
 *
 * @property \App\Model\Table\AssessmentsTable|\Cake\ORM\Association\BelongsTo $Assessments
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 * @property \App\Model\Table\ScalesTable|\Cake\ORM\Association\BelongsTo $Scales
 * @property \App\Model\Table\EvaluationFeedbacksTable|\Cake\ORM\Association\HasMany $EvaluationFeedbacks
 * @property \App\Model\Table\EvaluationImpactScoresTable|\Cake\ORM\Association\HasMany $EvaluationImpactScores
 * @property \App\Model\Table\EvaluationStandardScoresTable|\Cake\ORM\Association\HasMany $EvaluationStandardScores
 *
 * @method \App\Model\Entity\Evaluation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Evaluation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Evaluation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation findOrCreate($search, callable $callback = null, $options = [])
 */
class EvaluationsTable extends Table
{
    use ModelAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('evaluations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Assessments', [
            'foreignKey' => 'assessment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Scales', [
            'foreignKey' => 'scale_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('EvaluationFeedbacks', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->hasMany('EvaluationImpactScores', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->hasMany('EvaluationStandardScores', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->hasOne('SectionEvents', [
            'foreignKey' => 'object_identifier',
            'finder' => 'sectionEvents'
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
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['assessment_id'], 'Assessments'));
        $rules->add($rules->existsIn(['section_id'], 'Sections'));
        $rules->add($rules->existsIn(['scale_id'], 'Scales'));

        return $rules;
    }

    // public function afterSave($event,$entity, $options)
    // {
    
    //     if($entity->isNew()){
            
    //         $sectionEventData = $options->offsetGet('section_event_data');
            
    //         $reqData = [
    //                               'section_id' => $entity->section_id,
    //                               'name' => $sectionEventData['section_event_name'],
    //                               'end_date' => $sectionEventData['end_date'],
    //                               'object_name' => 'evaluation',
    //                               'object_identifier' => $entity->id,
    //                             ];
            
    //         if(!empty($sectionEventData['start_date'])){
    //             $reqData['start_date'] = $sectionEventData['start_date'];
    //         }

    //         $this->loadModel('SectionEvents');
    //         $sectionEvents = $this->SectionEvents->newEntity();
    //         $sectionEvents = $this->SectionEvents->patchEntity($sectionEvents,$reqData);
    //         if (!$this->SectionEvents->save($sectionEvents)) { 
    //           throw new Exception("Section Events could not be saved.");
    //         }
    //     }
    // }
}
