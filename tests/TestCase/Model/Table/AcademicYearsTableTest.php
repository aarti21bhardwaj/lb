<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AcademicYearsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AcademicYearsTable Test Case
 */
class AcademicYearsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AcademicYearsTable
     */
    public $AcademicYears;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.academic_years',
        'app.terms'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AcademicYears') ? [] : ['className' => AcademicYearsTable::class];
        $this->AcademicYears = TableRegistry::get('AcademicYears', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AcademicYears);

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
