<?php
use Migrations\AbstractMigration;

class AddcolorColumnInCourses extends AbstractMigration
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
        $table->addColumn('color', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
