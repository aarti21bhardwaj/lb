<?php
use Migrations\AbstractMigration;

class ChangeIsCompletedFieldInEvaluationFeedbacks extends AbstractMigration
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
        $conversations = $this->table('evaluation_feedbacks');
        $conversations->changeColumn('is_completed', 'boolean',array('null' => true))
                      ->save();
    }
    public function down(){
        $conversations = $this->table('evaluation_feedbacks');
        $conversations->changeColumn('is_completed', 'boolean',array('null' => false))
                      ->save(); 
    }
}
