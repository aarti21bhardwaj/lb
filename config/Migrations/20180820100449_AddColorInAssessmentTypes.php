<?php
use Migrations\AbstractMigration;

class AddColorInAssessmentTypes extends AbstractMigration
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
        $table = $this->table('assessment_types');
        $table->addColumn('color', 'string', [
            'default' => 'white',
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
