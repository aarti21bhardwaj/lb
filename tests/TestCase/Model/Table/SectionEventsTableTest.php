<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SectionEventsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SectionEventsTable Test Case
 */
class SectionEventsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SectionEventsTable
     */
    public $SectionEvents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.section_events',
        'app.sections',
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
        'app.school_users',
        'app.student_guardians',
        'app.students',
        'app.campus_teachers',
        'app.guardians',
        'app.academic_years',
        'app.terms',
        'app.templates',
        'app.units',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_contents',
        'app.content_categories',
        'app.content_values',
        'app.course_content_categories',
        'app.unit_specific_contents',
        'app.assessment_specific_contents',
        'app.assessments',
        'app.assessment_types',
        'app.assessment_contents',
        'app.assessment_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_impacts',
        'app.assessment_reflections',
        'app.assessment_resources',
        'app.assessment_standards',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.course_strands',
        'app.standard_grades',
        'app.unit_standards',
        'app.unit_courses',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_teachers',
        'app.section_students'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SectionEvents') ? [] : ['className' => SectionEventsTable::class];
        $this->SectionEvents = TableRegistry::get('SectionEvents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SectionEvents);

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
