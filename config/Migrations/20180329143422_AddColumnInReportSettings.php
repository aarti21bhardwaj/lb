<?php
use Migrations\AbstractMigration;

class AddColumnInReportSettings extends AbstractMigration
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
        $table->addColumn('course_scale_status', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
