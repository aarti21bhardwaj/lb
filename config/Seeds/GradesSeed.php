<?php
use Migrations\AbstractSeed;

/**
 * Grades seed.
 */
class GradesSeed extends AbstractSeed
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
                      'name'    => 'Pre K',
                      'sort_order'   => 1,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => 'K',
                      'sort_order'   => 2,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '1',
                      'sort_order'   => 3,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '2',
                      'sort_order'   => 4,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '3',
                      'sort_order'   => 5,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '4',
                      'sort_order'   => 6,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],[ 
                      'name'    => '5',
                      'sort_order'   => 7,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '6',
                      'sort_order'   => 8,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '7',
                      'sort_order'   => 9,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '8',
                      'sort_order'   => 10,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '9',
                      'sort_order'   => 11,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '10',
                      'sort_order'   => 12,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],
                    [ 
                      'name'    => '11',
                      'sort_order'   => 13,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],[ 
                      'name'    => '12',
                      'sort_order'   => 14,
                      'created' => date('Y-m-d H:i:s'),
                      'modified' => date('Y-m-d H:i:s'),
                    ],

        ];

        $table = $this->table('grades');
        $table->insert($data)->save();
    }
}
