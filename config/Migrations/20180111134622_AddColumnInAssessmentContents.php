<?php
use Migrations\AbstractMigration;

class AddColumnInAssessmentContents extends AbstractMigration
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
        $table = $this->table('assessment_contents');
         $table->addColumn('unit_specific_content_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);

        $table->update();
    }
}
