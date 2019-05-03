<?php
use Migrations\AbstractSeed;

/**
 * TransDisciplinaryThemes seed.
 */
class TransDisciplinaryThemesSeed extends AbstractSeed
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
                      'name'    => 'Who we are',
                      'description' => 'Who we are?'
                    ],
                    [ 
                      'name'    => 'Where we are in place and time',
                      'description' => 'Where we are in place and time?'
                    ],
                    [ 
                      'name'    => 'How we expess ourselves',
                      'description' => 'How we expess ourselves?'
                    ],
                    [ 
                      'name'    => 'How the world works',
                      'description' => 'How the world works?'
                    ],
                    [ 
                      'name'    => 'How we organize ourselves',
                      'description' => 'How we organize ourselves?'
                    ],
                    [ 
                      'name'    => 'Sharing the planet',
                      'description' => 'Sharing the planet?'
                    ],
                ];
        
        $table = $this->table('trans_disciplinary_themes');
        $table->insert($data)->save();
    }
}
