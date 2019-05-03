<?php
use Migrations\AbstractMigration;

class ChangeStartEndDateInUnits extends AbstractMigration
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
        $table->changeColumn('start_date', 'date', [
            'default' => null,
            'null' => true
        ]);
        $table->changeColumn('end_date', 'date', [
            'default' => null,
            'null' => true
        ]);

        $table->save();

    }
}
