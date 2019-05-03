<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Unit;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Unit Test Case
 */
class UnitTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Unit
     */
    public $Unit;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Unit = new Unit();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Unit);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
