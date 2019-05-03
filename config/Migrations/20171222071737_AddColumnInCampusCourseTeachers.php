<?php
use Migrations\AbstractMigration;

class AddColumnInCampusCourseTeachers extends AbstractMigration
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
        $table = $this->table('campus_course_teachers');
        $table->addColumn('is_leader', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
