<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportTemplateVariablesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportTemplateVariablesTable Test Case
 */
class ReportTemplateVariablesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportTemplateVariablesTable
     */
    public $ReportTemplateVariables;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_template_variables',
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
        $config = TableRegistry::exists('ReportTemplateVariables') ? [] : ['className' => ReportTemplateVariablesTable::class];
        $this->ReportTemplateVariables = TableRegistry::get('ReportTemplateVariables', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportTemplateVariables);

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
