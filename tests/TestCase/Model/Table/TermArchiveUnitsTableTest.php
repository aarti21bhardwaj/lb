<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TermArchiveUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TermArchiveUnitsTable Test Case
 */
class TermArchiveUnitsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TermArchiveUnitsTable
     */
    public $TermArchiveUnits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.term_archive_units',
        'app.terms',
        'app.units'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TermArchiveUnits') ? [] : ['className' => TermArchiveUnitsTable::class];
        $this->TermArchiveUnits = TableRegistry::get('TermArchiveUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TermArchiveUnits);

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
