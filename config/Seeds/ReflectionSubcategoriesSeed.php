<?php
use Migrations\AbstractSeed;

/**
 * ReflectionSubcategories seed.
 */
class ReflectionSubcategoriesSeed extends AbstractSeed
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
                      'name'    => 'Improve assessment task' ,
                      'description'=> "How could you improve on the assessment task(s) so that you would have a more accurate picture of each student's understanding of the central idea?"
                    ],
                    [ 
                      'name'    => 'Evidence connections',
                      'description'=> 'What was the evidence that connections were made between the central idea and the transdisciplinary theme?' 
                    ],
                    [ 
                      'name'    => 'Concept understanding',
                      'description'=> "develop an understanding of the concepts identified in 'What do we want to learn?'"
                    ],
                    [ 
                      'name'    => 'transdisciplinary skills',
                      'description'=> 'demonstrate the learning and application of particular transdisciplinary skills?' 
                    ],
                    [ 
                      'name'    => ' Particular Attribute',
                      'description'=> 'develop particular attributes of the learner profile and/or attitudes?' 
                    ],
                    [ 
                      'name'    => 'Teachers notes',
                      'description'=> 'Teachers Notes'
                    ],
                    [ 
                      'name'    => 'Initiated Inquiries',
                      'description'=> 'Initiated Inquiries'
                    ],
                    [ 
                      'name'    => 'Initiated Actions',
                      'description'=> 'Initiated Actions'
                    ]
                ];

        $table = $this->table('reflection_subcategories');
        $table->insert($data)->save();
    }
}
