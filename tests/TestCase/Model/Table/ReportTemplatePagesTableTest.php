<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportTemplatePagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportTemplatePagesTable Test Case
 */
class ReportTemplatePagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportTemplatePagesTable
     */
    public $ReportTemplatePages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_template_pages',
        'app.report_template_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ReportTemplatePages') ? [] : ['className' => ReportTemplatePagesTable::class];
        $this->ReportTemplatePages = TableRegistry::get('ReportTemplatePages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportTemplatePages);

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
