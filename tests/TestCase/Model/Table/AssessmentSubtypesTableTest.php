<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssessmentSubtypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssessmentSubtypesTable Test Case
 */
class AssessmentSubtypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AssessmentSubtypesTable
     */
    public $AssessmentSubtypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.assessment_subtypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AssessmentSubtypes') ? [] : ['className' => AssessmentSubtypesTable::class];
        $this->AssessmentSubtypes = TableRegistry::get('AssessmentSubtypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AssessmentSubtypes);

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
