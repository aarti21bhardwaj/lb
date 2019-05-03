<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BlackBaudHashTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BlackBaudHashTable Test Case
 */
class BlackBaudHashTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BlackBaudHashTable
     */
    public $BlackBaudHash;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.black_baud_hash',
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
        $config = TableRegistry::exists('BlackBaudHash') ? [] : ['className' => BlackBaudHashTable::class];
        $this->BlackBaudHash = TableRegistry::get('BlackBaudHash', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BlackBaudHash);

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
