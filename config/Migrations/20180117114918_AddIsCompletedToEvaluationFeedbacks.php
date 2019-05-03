<?php
use Migrations\AbstractMigration;

class AddIsCompletedToEvaluationFeedbacks extends AbstractMigration
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
        $table->addColumn('is_completed', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
