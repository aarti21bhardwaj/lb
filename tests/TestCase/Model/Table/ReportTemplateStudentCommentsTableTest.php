<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportTemplateStudentCommentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportTemplateStudentCommentsTable Test Case
 */
class ReportTemplateStudentCommentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportTemplateStudentCommentsTable
     */
    public $ReportTemplateStudentComments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_template_student_comments',
        'app.report_templates',
        'app.students',
        'app.teachers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ReportTemplateStudentComments') ? [] : ['className' => ReportTemplateStudentCommentsTable::class];
        $this->ReportTemplateStudentComments = TableRegistry::get('ReportTemplateStudentComments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportTemplateStudentComments);

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
