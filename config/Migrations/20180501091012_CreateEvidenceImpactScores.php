<?php
use Migrations\AbstractMigration;

class CreateEvidenceImpactScores extends AbstractMigration
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
        $table = $this->table('evidence_impact_scores');
        $table->addColumn('evidence_impact_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('scale_value_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('comment', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('is_completed', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
