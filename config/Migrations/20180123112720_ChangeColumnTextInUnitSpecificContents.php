<?php
use Migrations\AbstractMigration;

class ChangeColumnTextInUnitSpecificContents extends AbstractMigration
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
        $table = $this->table('unit_specific_contents');
        
        $table->changeColumn('text', 'text', [
            'default' => null,
            'null' => false,
        ]);

        $table->update();
    }
}
