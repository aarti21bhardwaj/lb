<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SpecialServiceTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SpecialServiceTypesTable Test Case
 */
class SpecialServiceTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SpecialServiceTypesTable
     */
    public $SpecialServiceTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.special_service_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SpecialServiceTypes') ? [] : ['className' => SpecialServiceTypesTable::class];
        $this->SpecialServiceTypes = TableRegistry::get('SpecialServiceTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SpecialServiceTypes);

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
}
