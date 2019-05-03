<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitCoursesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitCoursesTable Test Case
 */
class UnitCoursesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitCoursesTable
     */
    public $UnitCourses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_courses',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_standards',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.courses',
        'app.grades',
        'app.course',
        'app.division_grades',
        'app.divisions',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
        'app.campus_course_teachers',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.reset_password_hashes',
        'app.sections',
        'app.section_students',
        'app.students',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers',
        'app.terms',
        'app.academic_years',
        'app.course_strands',
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
        $config = TableRegistry::exists('UnitCourses') ? [] : ['className' => UnitCoursesTable::class];
        $this->UnitCourses = TableRegistry::get('UnitCourses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitCourses);

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
