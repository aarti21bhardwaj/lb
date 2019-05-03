<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolUsersTable Test Case
 */
class SchoolUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SchoolUsersTable
     */
    public $SchoolUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.school_users',
        'app.users',
        'app.roles',
        'app.schools',
        'app.campuses',
        'app.divisions',
        'app.division_grades',
        'app.grades',
        'app.course',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SchoolUsers') ? [] : ['className' => SchoolUsersTable::class];
        $this->SchoolUsers = TableRegistry::get('SchoolUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SchoolUsers);

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
