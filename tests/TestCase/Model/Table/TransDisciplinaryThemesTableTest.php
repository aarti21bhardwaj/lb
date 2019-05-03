<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransDisciplinaryThemesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransDisciplinaryThemesTable Test Case
 */
class TransDisciplinaryThemesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TransDisciplinaryThemesTable
     */
    public $TransDisciplinaryThemes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.trans_disciplinary_themes',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.types',
        'app.unit_contents',
        'app.content_categories',
        'app.content_values',
        'app.course_content_categories',
        'app.courses',
        'app.grades',
        'app.assessment_strands',
        'app.assessments',
        'app.assessment_types',
        'app.assessment_contents',
        'app.unit_specific_contents',
        'app.assessment_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_impacts',
        'app.grade_impacts',
        'app.report_template_impacts',
        'app.report_templates',
        'app.reporting_periods',
        'app.terms',
        'app.academic_years',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
        'app.campus_course_teachers',
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
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers',
        'app.evaluation_standard_scores',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.report_template_course_strands',
        'app.report_template_strands',
        'app.report_template_strand_scores',
        'app.unit_strands',
        'app.course_strands',
        'app.standard_grades',
        'app.assessment_standards',
        'app.unit_standards',
        'app.report_template_standards',
        'app.report_template_standard_scores',
        'app.evaluation_feedbacks',
        'app.section_events',
        'app.section_students',
        'app.section_teachers',
        'app.campus_settings',
        'app.setting_keys',
        'app.divisions',
        'app.division_grades',
        'app.report_settings',
        'app.report_template_pages',
        'app.report_template_types',
        'app.report_template_course_scores',
        'app.report_template_grades',
        'app.reports',
        'app.report_pages',
        'app.report_template_impact_scores',
        'app.assessment_specific_contents',
        'app.unit_courses',
        'app.course_grades',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
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
        $config = TableRegistry::exists('TransDisciplinaryThemes') ? [] : ['className' => TransDisciplinaryThemesTable::class];
        $this->TransDisciplinaryThemes = TableRegistry::get('TransDisciplinaryThemes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TransDisciplinaryThemes);

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
