<?php
use Migrations\AbstractSeed;

/**
 * ReportTemplateTypes seed.
 */
class ReportTemplateTypesSeed extends AbstractSeed
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
                      'type'    => 'Course Template'
                      
                    ],
                    [ 
                      'type'    => 'Strand Template'
                      
                    ],
                    [ 
                      'type'    => 'Standard Template'
                      
                    ],
                    [ 
                      'type'    => 'Impact Template'
                      
                    ], 

                ];

        $table = $this->table('report_template_types');
        $table->insert($data)->save();
    }
}
