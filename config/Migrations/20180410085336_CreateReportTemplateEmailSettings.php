<?php
use Migrations\AbstractMigration;

class CreateReportTemplateEmailSettings extends AbstractMigration
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
        $table = $this->table('report_template_email_settings');
        $table->addColumn('report_template_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('sender_email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('body', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('live_mode', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('test_receiver_email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->create();
    }
}
