<?php
use Migrations\AbstractSeed;

/**
 * ReportTemplatePages seed.
 */
class ReportTemplatePagesSeed extends AbstractSeed
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
                    'report_template_type_id' => 2,
                    'title' => 'Strand Template',
                    'body'=> '{{strand_name}}<br>{{strand_scale_value_image}}<br/><h6>{{strand_scale_name}}</h6><br/>{{strand_comment}}',
                    'created' => date('Y-m-d H:i:s'),
                    'modified'=> date('Y-m-d H:i:s')
                ],
                [
                    'report_template_type_id' => 3,
                    'title' => 'Standard Template',
                    'body'=> '{{standard_name}}<br>{{standard_scale_value_image}}<br/><h6>{{standard_scale_name}}</h6><br/>{{standard_comment}}',
                    'created' => date('Y-m-d H:i:s'),
                    'modified'=> date('Y-m-d H:i:s')
                ],
                [
                    'report_template_type_id' => 4,
                    'title' => 'Impact Template',
                    'body'=> '{{impact_name}}<br>{{impact_scale_value_image}}<br/><h6>{{impact_scale_name}}</h6><br/>{{impact_comment}}',
                    'created' => date('Y-m-d H:i:s'),
                    'modified'=> date('Y-m-d H:i:s')
                ]
            ];

        $table = $this->table('report_template_pages');
        $table->insert($data)->save();
    }
}
