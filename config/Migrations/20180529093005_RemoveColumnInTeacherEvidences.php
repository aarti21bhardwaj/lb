<?php
use Migrations\AbstractMigration;

class RemoveColumnInTeacherEvidences extends AbstractMigration
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
        $table = $this->table('teacher_evidences');
        $table->removeColumn('digital_tool_used', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
