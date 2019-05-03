<?php
use Migrations\AbstractSeed;

/**
 * UnitTypes seed.
 */
class TypesSeed extends AbstractSeed
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
                      'name'    => 'Unit of Inquiry' 
                    ],
                    [ 
                      'name'    => 'Math' 
                    ],
                    [ 
                      'name'    => 'Writing' 
                    ],
                    [ 
                      'name'    => 'Reading' 
                    ],
                    [ 
                      'name'    => ' Art' 
                    ],
                    [ 
                      'name'    => 'Music' 
                    ],
                    [ 
                      'name'    => 'PE' 
                    ],
                    [ 
                      'name'    => 'World Languages' 
                    ],
                    [ 
                      'name'    => 'PSPE' 
                    ],
                ];


        $table = $this->table('types');
        $table->insert($data)->save();
    }
}
