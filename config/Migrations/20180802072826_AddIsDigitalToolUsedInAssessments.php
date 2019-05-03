<?php
use Migrations\AbstractMigration;

class AddIsDigitalToolUsedInAssessments extends AbstractMigration
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
        $table = $this->table('assessments');
        $table->addColumn('is_digital_tool_used', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
