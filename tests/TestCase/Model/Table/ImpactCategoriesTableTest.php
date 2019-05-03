<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImpactCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImpactCategoriesTable Test Case
 */
class ImpactCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ImpactCategoriesTable
     */
    public $ImpactCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.impact_categories',
        'app.impacts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ImpactCategories') ? [] : ['className' => ImpactCategoriesTable::class];
        $this->ImpactCategories = TableRegistry::get('ImpactCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ImpactCategories);

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
