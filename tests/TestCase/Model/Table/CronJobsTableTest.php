<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CronJobsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CronJobsTable Test Case
 */
class CronJobsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CronJobsTable
     */
    public $CronJobs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cron_jobs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CronJobs') ? [] : ['className' => CronJobsTable::class];
        $this->CronJobs = TableRegistry::get('CronJobs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CronJobs);

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
