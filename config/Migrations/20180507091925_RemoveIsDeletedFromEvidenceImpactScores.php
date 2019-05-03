<?php
use Migrations\AbstractMigration;

class RemoveIsDeletedFromEvidenceImpactScores extends AbstractMigration
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
        $table = $this->table('evidence_impact_scores');
        $table->removeColumn('is_completed')
              ->save();
    }
}
