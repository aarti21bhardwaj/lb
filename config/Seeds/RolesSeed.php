<?php
use Migrations\AbstractSeed;

/**
 * Roles seed.
 */
class RolesSeed extends AbstractSeed
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
                      'name'    => 'admin',
                      'label'   =>'Admin',
                      
                    ],
                    [ 
                      'name'    => 'school',
                      'label'   =>'School Admin',
                      
                    ],
                    [ 
                      'name'    => 'teacher',
                      'label'   =>'Teacher',
                      
                    ],
                    [ 
                      'name'    => 'student',
                      'label'   =>'Student',
                      
                    ],
                    [ 
                      'name'    => 'guardian',
                      'label'   =>'Guardian',
                      
                    ],                    
                     
        ];

        $table = $this->table('roles');
        $table->insert($data)->save();
    }
}
