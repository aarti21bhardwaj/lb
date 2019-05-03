<?php
use Migrations\AbstractMigration;

class AddColumnStandardDisplaySettingInAssessmentTypes extends AbstractMigration
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
        $table = $this->table('assessment_types');
        $table->addColumn('standard_display_setting', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
