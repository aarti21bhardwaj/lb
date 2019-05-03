<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;
use Cake\Collection\Collection;


/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property |\Cake\ORM\Association\BelongsTo $Schools
 * @property |\Cake\ORM\Association\BelongsTo $Legacies
 * @property \App\Model\Table\ResetPasswordHashesTable|\Cake\ORM\Association\HasMany $ResetPasswordHashes
 * @property \App\Model\Table\SchoolUsersTable|\Cake\ORM\Association\HasMany $SchoolUsers
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER'
        ]);
        // $this->belongsTo('Legacies', [
        //     'foreignKey' => 'legacy_id'
        // ]);
        $this->hasMany('ResetPasswordHashes', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Sections', [
            'foreignKey' => 'teacher_id',
        ]);
        $this->hasMany('CampusCourseTeachers', [
            'foreignKey' => 'teacher_id',
        ]);

        $this->hasMany('SchoolUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('StudentGuardians', [
            'foreignKey' => 'guardian_id'
        ]);
        $this->hasMany('StudentGuardians', [
            'foreignKey' => 'student_id'
        ]);

        $this->hasMany('SectionStudents', [
            'foreignKey' => 'student_id'
        ]);

        $this->hasMany('UserPermissions', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('ReportTemplateStudentServices', [
            'foreignKey' => 'student_id'
        ]);

        $this->hasOne('ReportTemplateStudentComments', [
            'foreignKey' => 'student_id'
        ]);

        $this->hasMany('CampusTeachers', [
            'foreignKey' => 'teacher_id',
        ]);
        $this->hasMany('ReportTemplateCourseScores', [
            'foreignKey' => 'student_id',
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
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 255)
            ->allowEmpty('middle_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->scalar('dob')
            ->maxLength('dob', 255)
            ->allowEmpty('dob');

        $validator
            ->scalar('image_path')
            // ->maxLength('image_path', 255)
            ->allowEmpty('image_path');

        $validator
            ->allowEmpty('image_name');

        $validator
            ->scalar('gender')
            ->maxLength('gender', 255)
            ->allowEmpty('gender');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['school_id'], 'Schools'));
        // $rules->add($rules->existsIn(['legacy_id'], 'Legacies'));

        return $rules;
    }

    public function getStudentAttendance($legacyId = null, $termId = null, $courseIds = []){

        if(empty($courseIds)){
            throw new BadRequestException('Course ids are required.');
        }

        $user = $this->findByLegacyId($legacyId)->where(['role_id' => 4])->first();
        if(!$user){
          throw new NotFoundException('User not found.');
        }

        $this->BlackBaudHash = TableRegistry::get('BlackBaudHash');
        $this->Terms = TableRegistry::get('Terms');

        $term = $this->Terms->findById($termId)->first();
        if(!$term){
          throw new NotFoundException('Term not found.');
        }

        $legcayDivisionId = $this->BlackBaudHash->findByNewId($term->division_id)->where(['new_table_name' => 'divisions'])->first();
	
   $query =     "SELECT distinct  attendance.[StudentID],
            attendance.[Status date],
            attendance.[ATTENDANCECODE],
            attendance.[Code type],
            attendance.[Absence],
            attendance.[Tardy],
            attendance.[Expr1],
            attendance.[Cycle Day],
            attendance.[Term],
            attendance.[Academic year],
            attendance.[Class ID],
            attendance.[Added by],
            attendance.[ATTENDANCEDATE],
            attendance.[AYFull],
            attendance.[SCHOOLSID],
            attendance.[COURSENAME],
            attendance.[COURSEID] as c_id,
            crse.[EA7COURSESID] AS course_id

  FROM [AAS_EE].[dbo].[vLB_StudentsAttendanceClass] attendance INNER JOIN [dbo].[EA7COURSES] crse ON crse.[SCHOOLSID] = attendance.[SCHOOLSID]
                                                       AND crse.[COURSEID] = attendance.[COURSEID]
  where    attendance.[COURSEID] IS NOT NULL AND attendance.[SCHOOLSID] = '".$legcayDivisionId->old_id."' 
                  AND attendance.ATTENDANCEDATE >= '".$term->start_date."' 
                  AND attendance.ATTENDANCEDATE <= '".$term->end_date."'
                  AND ATTENDANCECODE <> 'FLD' and [StudentID] = '".$legacyId."'";



                  // pr($query);die;

        $conn = ConnectionManager::get('mssql');
        $response = $conn->execute($query)->fetchAll('assoc');
        if(empty($response)){
          throw new NotFoundException('Attendance for the student not found for the provided term.');
        }


        $response = (new Collection($response))->groupBy('course_id')->map(function($value, $key) use($user, $term){
          $data = [

                'student_id' => $user->id,
                'academic_year_id'  => $term->academic_year_id,
                'term_id' => $term->id,
                'division_id' => $term->division_id,
                'absence' => (new Collection($value))->sumOf('Absence'),
                'tardy' => (new Collection($value))->sumOf('Tardy'),
                'course_id' => $value[0]['course_id']
            ];
            return $data;
        })->toArray();

        foreach ($response as $key => $value) {             

            $course = $this->BlackBaudHash->find()->where(['old_id LIKE' => '%'.$value['course_id'].'%', 'new_table_name' => 'courses', 'new_id IN' => $courseIds])->first();
            if(!$course){
                //Course not found on new db.
                unset($response[$key]);
                continue;    
            }

            $response[$key]['course_id'] = $course->new_id;
        }
        $response = (new Collection($response))->indexBy('course_id')->toArray();
        return $response;    


}


    public function getStudentGrade($legacyId = null, $returnType = null){
        $user = $this->findByLegacyId($legacyId)->where(['role_id' => 4])->first();
        if(!$user){
          throw new NotFoundException('User not found.');
        }
        $query = "SELECT [Current Grade] as grade_id from dbo.vLB_StudentsEnrolled where Student_ID = '".$legacyId."'";
        $conn = ConnectionManager::get('mssql');
        $response = $conn->execute($query)->fetch('assoc');
        if(empty($response)){
          throw new NotFoundException('Student not found on old DB.');
        }
        $grades = ["PK" => 1, "K" => 2, "01" => 3, "02" => 4, "03" => 5, "04" => 6, "05" => 7, "06" => 8, "07" => 9, "08" => 10, "09" => 11, "10" => 12, "11" => 13, "12" => 14];
        $gradeId = $grades[$response['grade_id']];
        if(!isset($gradeId)){
          throw new NotFoundException('Grade not found on new db.');
        }
	if($returnType) {
          return $response['grade_id'];
        }

        return $gradeId;
    }
}
