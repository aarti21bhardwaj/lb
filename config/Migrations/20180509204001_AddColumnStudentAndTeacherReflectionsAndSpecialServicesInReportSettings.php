<?php
use Migrations\AbstractMigration;

class AddColumnStudentAndTeacherReflectionsAndSpecialServicesInReportSettings extends AbstractMigration
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
        $table = $this->table('report_settings');

        $table->addColumn('show_teacher_reflection', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->addColumn('show_student_reflection', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->addColumn('show_special_services', 'boolean', [
            'default' => 0,
            'null' => true,
        ]);
        $table->update();
    }
}
