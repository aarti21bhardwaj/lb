<?php
use Migrations\AbstractSeed;

/**
 * AssessmentTypes seed.
 */
class AssessmentTypesSeed extends AbstractSeed
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
                      'name'    => 'Formative Assessments',
                      'standard_display_setting' => 0,
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27',
                      'color'=>'#FFB6C1'
                      
                    ],
                    [ 
                      'name'    => 'Summative Assessments',
                      'standard_display_setting' => 0,
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27',
                      'color'=>'#00FFFF'
                      
                    ],
                    [ 
                      'name'    => 'Learning Activities',
                      'standard_display_setting' => 0,
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27',
                      'color'=>'#D2B48C'
                      
                    ],
                    [ 
                      'name'    => 'Learning Experiences',
                      'standard_display_setting' => 0,
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27',
                      'color'=>'#ffff66'
                      
                    ],
                    [ 
                      'name'    => 'Performance Tasks',
                      'standard_display_setting' => 0,
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27',
                      'color'=>'#FFE4B5'  
                    ]
                ];

        $table = $this->table('assessment_types');
        $table->insert($data)->save();
    }
}
  