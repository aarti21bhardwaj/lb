<?php
use Migrations\AbstractSeed;

/**
 * SpecialServiceTypes seed.
 */
class SpecialServiceTypesSeed extends AbstractSeed
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
                        'name' => 'EAL'
                    ],
                    [ 
                        'name' => 'Learning Support'
                    ],
                    [ 
                        'name' => 'Occupational Therapy'
                    ],
                    [
                        'name' => 'Speech Pathology'
                    ],
                    [
                        'name' => 'Gifted & Talented'
                    ],
                ];

        $table = $this->table('special_service_types');
        $table->insert($data)->save();
    }
}
