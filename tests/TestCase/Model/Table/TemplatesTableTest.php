<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplatesTable Test Case
 */
class TemplatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplatesTable
     */
    public $Templates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.templates',
        'app.units',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_courses',
        'app.unit_impacts',
        'app.unit_other_goals',
        'app.unit_reflections',
        'app.unit_resources',
        'app.unit_standards',
        'app.unit_teachers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Templates') ? [] : ['className' => TemplatesTable::class];
        $this->Templates = TableRegistry::get('Templates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Templates);

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
