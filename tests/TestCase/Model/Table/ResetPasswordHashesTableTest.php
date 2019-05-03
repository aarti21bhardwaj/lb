<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResetPasswordHashesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResetPasswordHashesTable Test Case
 */
class ResetPasswordHashesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResetPasswordHashesTable
     */
    public $ResetPasswordHashes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.reset_password_hashes',
        'app.users',
        'app.roles',
        'app.school_users',
        'app.schools',
        'app.campuses',
        'app.divisions',
        'app.division_grades',
        'app.grades',
        'app.course',
        'app.courses',
        'app.sections',
        'app.teachers',
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
        $config = TableRegistry::exists('ResetPasswordHashes') ? [] : ['className' => ResetPasswordHashesTable::class];
        $this->ResetPasswordHashes = TableRegistry::get('ResetPasswordHashes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ResetPasswordHashes);

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
