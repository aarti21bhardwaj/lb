<?php
use Migrations\AbstractMigration;

class AddColumnToEvidences extends AbstractMigration
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
        $table = $this->table('evidences');
        $table->addColumn('digital_tool_used', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('reflection_url', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
