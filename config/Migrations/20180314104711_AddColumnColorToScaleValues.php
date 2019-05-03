<?php
use Migrations\AbstractMigration;

class AddColumnColorToScaleValues extends AbstractMigration
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
        $table = $this->table('scale_values');
        $table->addColumn('color', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }
}
