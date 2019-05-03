<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PowerSchoolHashTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PowerSchoolHashTable Test Case
 */
class PowerSchoolHashTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PowerSchoolHashTable
     */
    public $PowerSchoolHash;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.power_school_hash',
        'app.olds',
        'app.news'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PowerSchoolHash') ? [] : ['className' => PowerSchoolHashTable::class];
        $this->PowerSchoolHash = TableRegistry::get('PowerSchoolHash', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PowerSchoolHash);

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
