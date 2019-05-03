<?php
use Migrations\AbstractMigration;

class UpdateCommentinEvaluationFeedbacks extends AbstractMigration
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
        $table = $this->table('evaluation_feedbacks');
        $table->changeColumn('comment', 'text', [
            'default' => null,
            'null' => true
        ]);

        $table->save();
    }
}
