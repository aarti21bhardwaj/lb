<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Courses;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Courses Test Case
 */
class CoursesTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Courses
     */
    public $Courses;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Courses = new Courses();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Courses);

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
