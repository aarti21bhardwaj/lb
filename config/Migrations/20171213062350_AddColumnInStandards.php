<?php
use Migrations\AbstractMigration;

class AddColumnInStandards extends AbstractMigration
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
        $table = $this->table('standards');
        $table->addColumn('code', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);

        $table->addColumn('lft', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);

        $table->addColumn('rght', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);

        $table->update();
    }
}
