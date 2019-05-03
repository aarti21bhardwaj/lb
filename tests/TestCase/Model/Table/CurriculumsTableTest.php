<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CurriculumsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CurriculumsTable Test Case
 */
class CurriculumsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CurriculumsTable
     */
    public $Curriculums;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.curriculums',
        'app.learning_areas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Curriculums') ? [] : ['className' => CurriculumsTable::class];
        $this->Curriculums = TableRegistry::get('Curriculums', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Curriculums);

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
