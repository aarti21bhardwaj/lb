<?php
use Migrations\AbstractMigration;

class AddColumnInStrands extends AbstractMigration
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
        $table = $this->table('strands');
        $table->addColumn('code', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('grade_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);

        $table->update();
    }
}
