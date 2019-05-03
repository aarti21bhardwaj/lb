<?php
use Migrations\AbstractMigration;

class AddColumnInCourses extends AbstractMigration
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
        $table = $this->table('courses');

        $table->addColumn('learning_area_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);

        $table->addColumn('term_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);

        $table->update();

    }
}
