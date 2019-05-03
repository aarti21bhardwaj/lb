<?php
use Migrations\AbstractMigration;

class AddColumnIsDefaultToScaleValues extends AbstractMigration
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
        $table->addColumn('is_default', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();

        $table = $this->table('scales');
        $table->removeColumn('is_default');
        $table->update();
    }
}
