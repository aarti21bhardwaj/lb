<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EvaluationFeedbacks Model
 *
 * @property \App\Model\Table\EvaluationsTable|\Cake\ORM\Association\BelongsTo $Evaluations
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Students
 *
 * @method \App\Model\Entity\EvaluationFeedback get($primaryKey, $options = [])
 * @method \App\Model\Entity\EvaluationFeedback newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EvaluationFeedback[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EvaluationFeedback|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EvaluationFeedback patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EvaluationFeedback[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EvaluationFeedback findOrCreate($search, callable $callback = null, $options = [])
 */
class EvaluationFeedbacksTable extends Table
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

        $this->setTable('evaluation_feedbacks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Evaluations', [
            'foreignKey' => 'evaluation_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER'
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
            ->scalar('comment')
            ->allowEmpty('comment');

        $validator
            ->scalar('numeric_grade')
            ->maxLength('numeric_grade', 255)
            ->allowEmpty('numeric_grade');

        $validator
            ->boolean('is_completed')
            // ->requirePresence('is_completed', 'create')
            ->allowEmpty('is_completed');
            // ->notEmpty('is_completed');

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
        $rules->add($rules->existsIn(['evaluation_id'], 'Evaluations'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        // $rules->add($rules->isUnique(['expert_id','specialization_id'], 'Specialization for this expert is already saved.'));

        return $rules;
    }
}
