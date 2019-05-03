<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImapctCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImapctCategoriesTable Test Case
 */
class ImapctCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ImapctCategoriesTable
     */
    public $ImapctCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.imapct_categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ImapctCategories') ? [] : ['className' => ImapctCategoriesTable::class];
        $this->ImapctCategories = TableRegistry::get('ImapctCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ImapctCategories);

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
