<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StandardGradesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StandardGradesTable Test Case
 */
class StandardGradesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StandardGradesTable
     */
    public $StandardGrades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.standard_grades',
        'app.grades',
        'app.course',
        'app.courses',
        'app.learning_areas',
        'app.curriculums',
        'app.strands',
        'app.standards',
        'app.unit_standards',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_courses',
        'app.unit_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_teachers',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
        'app.campus_course_teachers',
        'app.divisions',
        'app.division_grades',
        'app.terms',
        'app.academic_years',
        'app.sections',
        'app.section_students',
        'app.students',
        'app.reset_password_hashes',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers',
        'app.course_strands'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('StandardGrades') ? [] : ['className' => StandardGradesTable::class];
        $this->StandardGrades = TableRegistry::get('StandardGrades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StandardGrades);

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
