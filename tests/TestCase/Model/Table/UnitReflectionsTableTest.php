<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitReflectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitReflectionsTable Test Case
 */
class UnitReflectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitReflectionsTable
     */
    public $UnitReflections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_reflections',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_contents',
        'app.content_categories',
        'app.content_values',
        'app.course_content_categories',
        'app.courses',
        'app.grades',
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
        'app.terms',
        'app.academic_years',
        'app.section_students',
        'app.students',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers',
        'app.standard_grades',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.course_strands',
        'app.assessment_standards',
        'app.assessments',
        'app.assessment_types',
        'app.assessment_contents',
        'app.assessment_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_impacts',
        'app.assessment_reflections',
        'app.assessment_resources',
        'app.assessment_specfic_contents',
        'app.unit_standards',
        'app.unit_courses',
        'app.unit_specific_contents',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_resources',
        'app.unit_teachers',
        'app.reflection_categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UnitReflections') ? [] : ['className' => UnitReflectionsTable::class];
        $this->UnitReflections = TableRegistry::get('UnitReflections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitReflections);

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
