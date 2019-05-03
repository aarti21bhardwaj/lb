<?php
use Migrations\AbstractMigration;

class ChangeMiddleNameColumnInUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->changeColumn('middle_name', 'string',array('null' => true))
                         ->save();
    }
    public function down(){
        $table = $this->table('users');
        $table->changeColumn('middle_name', 'string',array('null' => false))
                         ->save(); 
    }
}
