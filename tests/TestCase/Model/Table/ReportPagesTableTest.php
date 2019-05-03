<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportPagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportPagesTable Test Case
 */
class ReportPagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportPagesTable
     */
    public $ReportPages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_pages',
        'app.reports',
        'app.report_templates',
        'app.reporting_periods',
        'app.terms',
        'app.academic_years',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
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
        'app.reset_password_hashes',
        'app.sections',
        'app.evaluations',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.students',
        'app.campus_course_teachers',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks',
        'app.section_events',
        'app.section_students',
        'app.section_teachers',
        'app.assessment_types',
        'app.division_grades',
        'app.divisions',
        'app.report_settings',
        'app.report_templates_grades',
        'app.campus_settings',
        'app.setting_keys',
        'app.report_template_grades'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ReportPages') ? [] : ['className' => ReportPagesTable::class];
        $this->ReportPages = TableRegistry::get('ReportPages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportPages);

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
