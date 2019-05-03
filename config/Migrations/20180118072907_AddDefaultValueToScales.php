<?php
use Migrations\AbstractMigration;

class AddDefaultValueToScales extends AbstractMigration
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
        $table = $this->table('scales');
        $table->addColumn('is_default', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
