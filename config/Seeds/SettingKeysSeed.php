<?php
use Migrations\AbstractSeed;

/**
 * SettingKeys seed.
 */
class SettingKeysSeed extends AbstractSeed
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
                      'name'    => 'Standard Scale',
                      'type' => 'integer',
                      'description' => 'Scale Id'
                    ],
                    [ 
                      'name'    => 'Impact Scale',
                      'type' => 'integer',
                      'description' => 'Scale Id'
                    ],
                    [ 
                      'name'    => 'Formative Assessments Weightage',
                      'type' => 'integer',
                      'description' => 'Weightage'
                    ],
                    [ 
                      'name'    => 'Summative Assessments Weightage',
                      'type' => 'integer',
                      'description' => 'Weightage'
                    ],
                    [ 
                      'name'    => 'Learning Activities Weightage',
                      'type' => 'integer',
                      'description' => 'Weightage'
                    ],
                    [ 
                      'name'    => 'Learning Experiences Weightage',
                      'type' => 'integer',
                      'description' => 'Weightage'
                    ],
                    [ 
                      'name'    => 'Performance Tasks Weightage',
                      'type' => 'integer',
                      'description' => 'Weightage'
                    ],
                    [ 
                      'name'    => 'Evidence reflection',
                      'type' => 'string',
                      'description' => 'File or Description'
                    ],
                    [ 
                      'name'    => 'Multiple Courses per Evidence',
                      'type' => 'integer',
                      'description' => 'Add evidence for multiple courses or a single course.'
                    ]
                ];

        $table = $this->table('setting_keys');
        $table->insert($data)->save();
    }
}
