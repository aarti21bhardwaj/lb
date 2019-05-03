<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserPermissionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserPermissionsTable Test Case
 */
class UserPermissionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserPermissionsTable
     */
    public $UserPermissions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_permissions',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserPermissions') ? [] : ['className' => UserPermissionsTable::class];
        $this->UserPermissions = TableRegistry::get('UserPermissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserPermissions);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
