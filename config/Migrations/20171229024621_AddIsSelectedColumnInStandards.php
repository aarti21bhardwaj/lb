<?php
use Migrations\AbstractMigration;

class AddIsSelectedColumnInStandards extends AbstractMigration
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
        $table->addColumn('is_selected', 'boolean', [
            'default' => false,
            'null' => true,
        ]);

        $table->update();
    }
}
