<?php
use Migrations\AbstractSeed;

/**
 * Contexts seed.
 */
class ContextsSeed extends AbstractSeed
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
                      'name'    => 'After-School Program' 
                    ],
                    [ 
                      'name'    => 'Sports' 
                    ],
                    [ 
                      'name'    => 'Leadership Club' 
                    ],
                    [ 
                      'name'    => 'Scouts' 
                    ],
                    [ 
                      'name'    => 'Home' 
                    ]
                ];

        $table = $this->table('contexts');
        $table->insert($data)->save();
    }
}
