<?php
use Migrations\AbstractMigration;

class AddColumnInImpacts extends AbstractMigration
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
        $table = $this->table('impacts');

        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);

        $table->addColumn('lft', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);

        $table->addColumn('rght', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);

        $table->update();
    }
}
