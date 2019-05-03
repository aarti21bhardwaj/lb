<?php
use Migrations\AbstractMigration;

class ChangeCourses extends AbstractMigration
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
        
        $table->changeColumn('grade_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);

        $table->update();
    }
}
