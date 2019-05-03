<?php
use Migrations\AbstractMigration;

class CreateReportSettings extends AbstractMigration
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
        $table = $this->table('report_settings');
        $table->addColumn('report_template_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('grade_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('course_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('course_status', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('course_comment_status', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('strand_status', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('strand_comment_status', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('standard_status', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('standard_comment_status', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('impact_status', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('impact_comment_status', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
