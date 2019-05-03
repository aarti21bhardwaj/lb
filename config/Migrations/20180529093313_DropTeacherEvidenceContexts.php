<?php
use Migrations\AbstractMigration;

class DropTeacherEvidenceContexts extends AbstractMigration
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
        $table = $this->table('teacher_evidence_contexts');
        $table->drop();
    }
}
