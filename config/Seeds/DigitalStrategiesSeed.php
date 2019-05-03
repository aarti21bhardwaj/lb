<?php
use Migrations\AbstractSeed;

/**
 * DigitalStrategies seed.
 */
class DigitalStrategiesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {   
        $table = $this->table('content_categories');
        $data = [
            [
              'name' => 'Digital Strategies',
              'type' => 'digital_strategies'
            ]
        ];

        $table->insert($data)->save();
    }
}
