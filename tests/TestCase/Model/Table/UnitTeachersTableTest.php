<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitTeachersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitTeachersTable Test Case
 */
class UnitTeachersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitTeachersTable
     */
    public $UnitTeachers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_teachers',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_courses',
        'app.unit_impacts',
        'app.unit_other_goals',
        'app.unit_reflections',
        'app.unit_resources',
        'app.unit_standards',
        'app.teachers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UnitTeachers') ? [] : ['className' => UnitTeachersTable::class];
        $this->UnitTeachers = TableRegistry::get('UnitTeachers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitTeachers);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
