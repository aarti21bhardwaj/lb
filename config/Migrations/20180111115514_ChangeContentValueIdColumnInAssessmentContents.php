<?php
use Migrations\AbstractMigration;

class ChangeContentValueIdColumnInAssessmentContents extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
   public function up()
    {
        $table = $this->table('assessment_contents');
        $table->changeColumn('content_value_id', 'string', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
       
        $table->update();
    }

     public function down()
    {
        $table = $this->table('assessment_contents');
        $table->changeColumn('content_value_id', 'string', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
       
        $table->update();
    }
}
