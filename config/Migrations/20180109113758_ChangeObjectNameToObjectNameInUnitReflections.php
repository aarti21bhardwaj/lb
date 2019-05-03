<?php
use Migrations\AbstractMigration;

class ChangeObjectNameToObjectNameInUnitReflections extends AbstractMigration
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
        $conversations = $this->table('unit_reflections');
        $conversations->changeColumn('object_name', 'string',array('null' => true))
                      ->save();
    }
    public function down(){
        $conversations = $this->table('unit_reflections');
        $conversations->changeColumn('object_name', 'integer',array('null' => true))
                      ->save(); 
    }
}
