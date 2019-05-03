<?php
use Migrations\AbstractSeed;

/**
 * ReportTemplateVariables seed.
 */
class ReportTemplateVariablesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [

                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Course Name',
                      'identifier' => 'course_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Grade',
                      'identifier' => 'grade',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Academic Year',
                      'identifier' => 'academic_year',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Absences',
                      'identifier' => 'absences',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Tardies',
                      'identifier' => 'tardies',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'First Name',
                      'identifier' => 'first_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Last Name',
                      'identifier' => 'last_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Student Image',
                      'identifier' => 'student_image',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Student Id',
                      'identifier' => 'student_id',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Date of Birth',
                      'identifier' => 'dob',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Home Teacher',
                      'identifier' => 'home_teacher',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Student Reflection',
                      'identifier' => 'student_reflection',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Teacher Reflection',
                      'identifier' => 'teacher_reflection',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Services',
                      'identifier' => 'services',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Strand Template',
                      'identifier' => 'strand_template = 1',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Standard Template',
                      'identifier' => 'standard_template = 2',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Impact Template',
                      'identifier' => 'impact_template = 3',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Teacher Name',
                      'identifier' => 'teacher_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Teacher Image',
                      'identifier' => 'teacher_image',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Course Comment',
                      'identifier' => 'course_comment',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Course Scale Name',
                      'identifier' => 'course_scale_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 1,
                      'name'   =>'Course Scale Value Image',
                      'identifier' => 'course_scale_value_image',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 2,
                      'name'   =>'Strand Name',
                      'identifier' => 'strand_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 2,
                      'name'   =>'Strand Scale Name',
                      'identifier' => 'strand_scale_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 2,
                      'name'   =>'Strand Scale Value Image',
                      'identifier' => 'strand_scale_value_image',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 2,
                      'name'   =>'Strand Comment',
                      'identifier' => 'strand_comment',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 3,
                      'name'   =>'Standards Name',
                      'identifier' => 'standard_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 3,
                      'name'   =>'Standard Scale Name',
                      'identifier' => 'standard_scale_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 3,
                      'name'   =>'Standard Scale Value Image',
                      'identifier' => 'standard_scale_value_image',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 3,
                      'name'   =>'Standard Comment',
                      'identifier' => 'standard_comment',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 4,
                      'name'   =>'Impact Name',
                      'identifier' => 'impact_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 4,
                      'name'   =>'Impact Scale Name',
                      'identifier' => 'impact_scale_name',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 4,
                      'name'   =>'Impact Scale Value Image',
                      'identifier' => 'impact_scale_value_image',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
                [ 
                      'report_template_type_id'  => 4,
                      'name'   =>'Impact Comment',
                      'identifier' => 'impact_comment',
                      'created'  => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s')
                ],
        ];

        $table = $this->table('report_template_variables');
        $table->insert($data)->save();
    }
}
