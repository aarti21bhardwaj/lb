<?php
namespace App\Test\TestCase\Shell;

use App\Shell\MigrateUnitsFromDbShell;
use Cake\TestSuite\TestCase;

/**
 * App\Shell\MigrateUnitsFromDbShell Test Case
 */
class MigrateUnitsFromDbShellTest extends TestCase
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
     * @var \App\Shell\MigrateUnitsFromDbShell
     */
    public $MigrateUnitsFromDb;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')->getMock();
        $this->MigrateUnitsFromDb = new MigrateUnitsFromDbShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MigrateUnitsFromDb);

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
