<?php
use Migrations\AbstractSeed;

/**
 * Scales seed.
 */
class ScalesSeed extends AbstractSeed
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
                      'name'    => 'General' 
                    ],
                    [ 
                      'name'    => 'Standard' 
                    ],
                    [
                      'name' => 'ISK Academic Standards'
                    ],
                    [
                      'name' => 'ISK Aims'
                    ],
                    [
                      'name' => 'Middle School Academic Scale'
                    ],
                    [
                      'name' => 'Middle School Impact Scale'
                    ],
                    [
                      'name' => 'ISC Academic Scale'
                    ],
                    [
                      'name' => 'ISC Impact Scale'
                    ], 
                ];

        $table = $this->table('scales');
        $table->insert($data)->save();
    }
}
