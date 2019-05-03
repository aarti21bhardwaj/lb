<?php
namespace App\Test\TestCase\Shell;

use App\Shell\FixBlackBaudSyncShell;
use Cake\TestSuite\ConsoleIntegrationTestCase;

/**
 * App\Shell\FixBlackBaudSyncShell Test Case
 */
class FixBlackBaudSyncShellTest extends ConsoleIntegrationTestCase
{

    /**
     * ConsoleIo mock
     *
     * @var \Cake\Console\ConsoleIo|\PHPUnit_Framework_MockObject_MockObject
     */
    public $io;

    /**
     * Test subject
     *
     * @var \App\Shell\FixBlackBaudSyncShell
     */
    public $FixBlackBaudSync;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')->getMock();
        $this->FixBlackBaudSync = new FixBlackBaudSyncShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FixBlackBaudSync);

        parent::tearDown();
    }

    /**
     * Test getOptionParser method
     *
     * @return void
     */
    public function testGetOptionParser()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
