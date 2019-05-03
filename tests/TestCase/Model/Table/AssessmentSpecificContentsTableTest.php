<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssessmentSpecificContentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssessmentSpecificContentsTable Test Case
 */
class AssessmentSpecificContentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AssessmentSpecificContentsTable
     */
    public $AssessmentSpecificContents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.assessment_specific_contents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AssessmentSpecificContents') ? [] : ['className' => AssessmentSpecificContentsTable::class];
        $this->AssessmentSpecificContents = TableRegistry::get('AssessmentSpecificContents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AssessmentSpecificContents);

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
