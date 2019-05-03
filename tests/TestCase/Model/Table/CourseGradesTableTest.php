<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CourseGradesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourseGradesTable Test Case
 */
class CourseGradesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CourseGradesTable
     */
    public $CourseGrades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.course_grades',
        'app.courses',
        'app.grades',
        'app.assessment_strands',
        'app.assessments',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_contents',
        'app.content_categories',
        'app.content_values',
        'app.course_content_categories',
        'app.unit_specific_contents',
        'app.assessment_contents',
        'app.assessment_specific_contents',
        'app.unit_courses',
        'app.unit_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.assessment_impacts',
        'app.grade_impacts',
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
        'app.unit_strands',
        'app.course_strands',
        'app.standard_grades',
        'app.assessment_standards',
        'app.unit_teachers',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
        'app.campus_course_teachers',
        'app.campus_settings',
        'app.setting_keys',
        'app.campus_teachers',
        'app.divisions',
        'app.division_grades',
        'app.terms',
        'app.academic_years',
        'app.reporting_periods',
        'app.report_templates',
        'app.report_settings',
        'app.report_template_grades',
        'app.reports',
        'app.report_pages',
        'app.sections',
        'app.evaluations',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.students',
        'app.reset_password_hashes',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks',
        'app.section_events',
        'app.section_students',
        'app.section_teachers',
        'app.assessment_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CourseGrades') ? [] : ['className' => CourseGradesTable::class];
        $this->CourseGrades = TableRegistry::get('CourseGrades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CourseGrades);

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
