<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImpactsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImpactsTable Test Case
 */
class ImpactsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ImpactsTable
     */
    public $Impacts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.impacts',
        'app.impact_categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Impacts') ? [] : ['className' => ImpactsTable::class];
        $this->Impacts = TableRegistry::get('Impacts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Impacts);

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
