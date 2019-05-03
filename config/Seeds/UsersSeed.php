<?php
use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Text;
/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
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
        $hasher = new DefaultPasswordHasher();
        $data = [
                    [
                      'first_name'    => 'admin',
                      'middle_name'    => 'admin',
                      'last_name'    => 'admin',
                      'email'   =>'admin@admin.com',
                      'password'   =>$hasher->hash('12345678'),
                      'role_id'=>'1',
                      'school_id' => 0,
                      'dob' => '20/04/1993',
                      'created' => '2016-06-15 10:01:27',
                      'modified'=> '2016-06-15 10:01:27'
                      ]
                ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
