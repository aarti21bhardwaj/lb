<?php
use Migrations\AbstractMigration;

class ChangeDOBToDOBInUsers extends AbstractMigration
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
        $conversations = $this->table('users');
        $conversations->changeColumn('dob', 'string',array('null' => true))
                      ->save();
    }
    public function down(){
        $conversations = $this->table('users');
        $conversations->changeColumn('dob', 'string',array('null' => false))
                      ->save(); 
    }
}
