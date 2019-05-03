<?php
use Migrations\AbstractMigration;

class ChangeDesriptionInAssessments extends AbstractMigration
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
        $conversations = $this->table('assessments');
        $conversations->changeColumn('description', 'string',array('null' => true))
                      ->save();
    }
    public function down(){
        $conversations = $this->table('assessments');
        $conversations->changeColumn('description', 'integer',array('null' => false))
                      ->save(); 
    }
}
