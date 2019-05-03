<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitStandardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitStandardsTable Test Case
 */
class UnitStandardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitStandardsTable
     */
    public $UnitStandards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_standards',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_courses',
        'app.unit_impacts',
        'app.unit_other_goals',
        'app.unit_reflections',
        'app.unit_resources',
        'app.unit_teachers',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
        'app.courses',
        'app.grades',
        'app.course',
        'app.division_grades',
        'app.divisions',
        'app.terms',
        'app.academic_years',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.course_strands',
        'app.sections',
        'app.section_students',
        'app.students',
        'app.reset_password_hashes',
        'app.campus_course_teachers',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UnitStandards') ? [] : ['className' => UnitStandardsTable::class];
        $this->UnitStandards = TableRegistry::get('UnitStandards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitStandards);

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
