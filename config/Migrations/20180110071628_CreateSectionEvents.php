<?php
use Migrations\AbstractMigration;

class CreateSectionEvents extends AbstractMigration
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
        $table = $this->table('section_events');
        $table->addColumn('section_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('start_date', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('end_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('object_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('object_identifier', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->create();
    }
}
