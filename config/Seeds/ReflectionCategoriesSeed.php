<?php
use Migrations\AbstractSeed;

/**
 * ReflectionCategories seed.
 */
class ReflectionCategoriesSeed extends AbstractSeed
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
        $data = [
            [
              'name' => 'General',
              'created' => '2016-06-15 10:01:27',
              'modified'=> '2016-06-15 10:01:27'
            ]
        ];

        $table = $this->table('reflection_categories');
        $table->insert($data)->save();
    }
}
