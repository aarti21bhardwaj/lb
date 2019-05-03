<?php
use Migrations\AbstractMigration;

class AddColumnCreatedByAndModifiedByInReportTemplateCourseScores extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('report_template_course_scores');
        $table->addColumn('created_by', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
         ]);

        $table->addColumn('modified_by', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
         ]);

        $table->update();
    }
}
