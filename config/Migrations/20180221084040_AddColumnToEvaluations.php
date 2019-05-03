<?php
use Migrations\AbstractMigration;

class AddColumnToEvaluations extends AbstractMigration
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
        $table = $this->table('evaluations');
        $table->addColumn('is_archived', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
