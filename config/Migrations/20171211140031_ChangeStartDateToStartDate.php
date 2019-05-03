<?php
use Migrations\AbstractMigration;

class ChangeStartDateToStartDate extends AbstractMigration
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
        $conversations = $this->table('academic_years');
        $conversations->changeColumn('start_date', 'date',array('null' => true))
                         ->save();
    }
    public function down(){
        $conversations = $this->table('academic_years');
        $conversations->changeColumn('start_date', 'datetime',array('null' => false))
                         ->save(); 
    }
}
