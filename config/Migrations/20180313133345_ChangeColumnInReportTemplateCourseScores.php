<?php
use Migrations\AbstractMigration;

class ChangeColumnInReportTemplateCourseScores extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $conversations = $this->table('report_template_course_scores');
        $conversations->changeColumn('comment', 'string',array('null' => true))
                      ->save();
    }
    public function down(){
        $conversations = $this->table('report_template_course_scores');
        $conversations->changeColumn('comment', 'string',array('null' => false))
                      ->save(); 
    }
}
