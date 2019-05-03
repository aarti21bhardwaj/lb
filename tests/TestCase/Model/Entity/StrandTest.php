<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Strand;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Strand Test Case
 */
class StrandTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Strand
     */
    public $Strand;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Strand = new Strand();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Strand);

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
