<?php
use Migrations\AbstractMigration;

class ChangeNumericValueInScaleValues extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('scale_values');
        $table->changeColumn('numeric_value', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
       
        $table->update();
    }

     public function down()
    {
        $table = $this->table('scale_values');
        $table->changeColumn('numeric_value', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
       
        $table->update();
    }
}
