<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReportTemplates Model
 *
 * @property \App\Model\Table\ReportingPeriodsTable|\Cake\ORM\Association\BelongsTo $ReportingPeriods
 * @property \App\Model\Table\ReportSettingsTable|\Cake\ORM\Association\HasMany $ReportSettings
 * @property |\Cake\ORM\Association\HasMany $ReportTemplateCourseScores
 * @property \App\Model\Table\ReportTemplateCourseStrandsTable|\Cake\ORM\Association\HasMany $ReportTemplateCourseStrands
 * @property \App\Model\Table\ReportTemplateGradesTable|\Cake\ORM\Association\HasMany $ReportTemplateGrades
 * @property \App\Model\Table\ReportTemplateImpactsTable|\Cake\ORM\Association\HasMany $ReportTemplateImpacts
 * @property \App\Model\Table\ReportTemplateStandardsTable|\Cake\ORM\Association\HasMany $ReportTemplateStandards
 * @property \App\Model\Table\ReportTemplateStrandsTable|\Cake\ORM\Association\HasMany $ReportTemplateStrands
 * @property \App\Model\Table\ReportsTable|\Cake\ORM\Association\HasMany $Reports
 *
 * @method \App\Model\Entity\ReportTemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReportTemplate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReportTemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReportTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReportTemplate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTemplatesTable extends Table
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

        $this->setTable('report_templates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReportingPeriods', [
            'foreignKey' => 'reporting_period_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReportSettings', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('ReportTemplateCourseScores', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('ReportTemplateCourseStrands', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('ReportTemplateGrades', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('ReportTemplateImpacts', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('ReportTemplateStandards', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('ReportTemplateStrands', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->hasOne('ReportTemplateEmailSettings', [
            'foreignKey' => 'report_template_id'
        ]);
        $this->addBehavior('UserPermission', [
                            'foreignKey' => 'division_id',
                            'pathToModel' => 'ReportingPeriods.Terms.Divisions'
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
            ->integer('academic_scale')
            ->requirePresence('academic_scale', 'create')
            ->notEmpty('academic_scale');

        $validator
            ->integer('impact_scale')
            ->requirePresence('impact_scale', 'create')
            ->notEmpty('impact_scale');

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
        $rules->add($rules->existsIn(['reporting_period_id'], 'ReportingPeriods'));

        return $rules;
    }

    public function substitute($content, $hash){
        if(isset($content->body)){
            $content->body = str_replace('"', "'", $content->body);
        }
       
        foreach ($hash as $key => $value) {
        
            if(!is_array($value)){
                $value = trim($value);
                $value = str_replace('"', '\"', $value);
                $placeholder = sprintf('{{%s}}', $key);
                // if($placeholder=="{{".$key."}}"){
                            // $content = str_replace($placeholder, $value, $content);
                if(strpos($placeholder, 'strand_scale_value_image') || strpos($placeholder, 'standard_scale_value_image') || strpos($placeholder, 'impact_scale_value_image') || strpos($placeholder, 'student_image') || strpos($placeholder, 'teacher_image')){
                    if(strpos($placeholder, 'strand_scale_value_image') || strpos($placeholder, 'standard_scale_value_image') || strpos($placeholder, 'impact_scale_value_image')){
                        $content = str_replace($placeholder, "<img src = '".$value."' style='margin-left:-23px;' width = 530px; height = '50px;'>", $content);
                    }else if(strpos($placeholder, 'student_image')){
                        
                        $content = str_replace($placeholder, "<img src = '".$value."' style='width: 130px; height: 170px;'>", $content);                    
                    }else{
                        $content = str_replace($placeholder, "<img src = '".$value."' style='width: 90px; height: auto;'>", $content);
                    }
                }else if(strpos($placeholder, 'student_reflection') && !in_array($value, [null, false, ''])){
                        $content = str_replace($placeholder, "<h2><strong>Student Reflection</strong></h2><p style='font-size: 18px; background-color: #e4f2f1 !important; padding: 7px 0 11px 8px;'>".$value."</p>", $content);
                }else if(strpos($placeholder, 'teacher_reflection') && !in_array($value, [null, false, ''])){

                        $content = str_replace($placeholder, "<h2><strong>Teacher General Comment</strong></h2><p style='font-size: 18px; background-color: #e4f2f1 !important; padding: 7px 0 11px 8px;'>".$value."</p>", $content);
                }else if(strpos($placeholder, 'services')){
                        if(!in_array($value, [null, false, ''])){
                            $content = str_replace($placeholder, "<span style='font-size: 16px;'>&nbsp;".$value."</span>", $content);

                        }else{
                            $content = str_replace($placeholder, " ", $content);
                            if(strpos($content, 'Supported received from:')){
                              $content =  str_replace('Supported received from:', '', $content);
                            }
                            
                        }

                }else{
                      $content = str_replace($placeholder, $value, $content);
                }
              // }
            }
        }
        return $content;
    }
}
