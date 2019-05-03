<?php
use Migrations\AbstractMigration;

class AddIsArchivedColumnInUnits extends AbstractMigration
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
        $table = $this->table('units');
        $table->addColumn('is_archived', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
