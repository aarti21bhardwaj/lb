<?php
use Migrations\AbstractMigration;

class CreateEvaluationFeedbacks extends AbstractMigration
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
        $table->addColumn('evaluation_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('student_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('comment', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('numeric_grade', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->create();
    }
}
