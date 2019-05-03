<?php
use Migrations\AbstractSeed;

/**
 * Templates seed.
 */
class TemplatesSeed extends AbstractSeed
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
                      'name'    => 'UbD',
                      'slug' => 'ubd'
                      
                    ],
                    [ 
                      'name'    => 'Transfer',
                      'slug' => 'transfer'
                      
                    ],
                    [ 
                      'name'    => 'PyP',
                      'slug' => 'pyp'
                      
                    ],
                    [ 
                      'name'    => 'Transfer-UbD',
                      'slug' => 'tUbd'
                      
                    ]
                ];

        $table = $this->table('templates');
        $table->insert($data)->save();
    }
}
