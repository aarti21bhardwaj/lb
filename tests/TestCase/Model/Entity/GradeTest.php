<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Grade;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Grade Test Case
 */
class GradeTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Grade
     */
    public $Grade;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Grade = new Grade();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Grade);

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
