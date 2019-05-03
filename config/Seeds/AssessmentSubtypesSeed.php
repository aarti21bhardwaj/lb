<?php
use Migrations\AbstractSeed;

/**
 * AssessmentSubtypes seed.
 */
class AssessmentSubtypesSeed extends AbstractSeed
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
                      'name'    => 'Connections Tuning In' 
                    ],
                    [ 
                      'name'    => 'Invitations/Finding Out' 
                    ],
                    [ 
                      'name'    => 'Tension/Sorting Out' 
                    ],
                    [ 
                      'name'    => 'Investigations/Finding Out' 
                    ],
                    [ 
                      'name'    => 'Demonstration/Going Further' 
                    ],
                    [ 
                      'name'    => 'Revision/Sorting Out' 
                    ],
                    [ 
                      'name'    => 'Representation/Drawing Conclusions' 
                    ],
                    [ 
                      'name'    => 'Valuation/Drawing Calculations' 
                    ],
                    [ 
                      'name'    => 'Action/Taking Action' 
                    ],
                ];

        $table = $this->table('assessment_subtypes');
        $table->insert($data)->save();
    }
}
