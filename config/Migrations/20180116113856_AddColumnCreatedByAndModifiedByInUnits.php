<?php
use Migrations\AbstractMigration;

class AddColumnCreatedByAndModifiedByInUnits extends AbstractMigration
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
