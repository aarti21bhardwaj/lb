<?php
use Migrations\AbstractMigration;

class AddColumnInUnitReflections extends AbstractMigration
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
        $table = $this->table('unit_reflections');
        $table->addColumn('object_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('object_name', 'integer', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
