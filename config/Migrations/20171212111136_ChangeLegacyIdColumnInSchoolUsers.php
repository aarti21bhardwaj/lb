<?php
use Migrations\AbstractMigration;

class ChangeLegacyIdColumnInSchoolUsers extends AbstractMigration
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
        $table = $this->table('school_users');
        $table->changeColumn('legacy_id', 'string',array('null' => true))
                         ->save();
    }
    public function down(){
        $table = $this->table('school_users');
        $table->changeColumn('legacy_id', 'string',array('null' => false))
                         ->save(); 
    }
}
