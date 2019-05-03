<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportTemplateTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportTemplateTypesTable Test Case
 */
class ReportTemplateTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportTemplateTypesTable
     */
    public $ReportTemplateTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_template_types',
        'app.report_template_pages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ReportTemplateTypes') ? [] : ['className' => ReportTemplateTypesTable::class];
        $this->ReportTemplateTypes = TableRegistry::get('ReportTemplateTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportTemplateTypes);

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
