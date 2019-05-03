<?php
use Migrations\AbstractMigration;

class ChangeStartDatetimeToStartDate extends AbstractMigration
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
        $terms = $this->table('terms');
        $terms->changeColumn('start_date', 'date')->save();
        $terms->changeColumn('end_date', 'date')->save();
    }
    public function down(){
        $terms = $this->table('terms');
        $terms->changeColumn('start_date', 'datetime')->save();
        $terms->changeColumn('end_date', 'datetime')->save(); 
    }
}
