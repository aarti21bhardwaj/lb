<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CroneJobsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CroneJobsTable Test Case
 */
class CroneJobsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CroneJobsTable
     */
    public $CroneJobs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.crone_jobs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CroneJobs') ? [] : ['className' => CroneJobsTable::class];
        $this->CroneJobs = TableRegistry::get('CroneJobs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CroneJobs);

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
