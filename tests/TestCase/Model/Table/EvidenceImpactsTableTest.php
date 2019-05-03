<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EvidenceImpactsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EvidenceImpactsTable Test Case
 */
class EvidenceImpactsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EvidenceImpactsTable
     */
    public $EvidenceImpacts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.evidence_impacts',
        'app.evidences',
        'app.students',
        'app.roles',
        'app.users',
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
        'app.types',
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
        'app.report_template_impacts',
        'app.report_templates',
        'app.reporting_periods',
        'app.terms',
        'app.academic_years',
        'app.divisions',
        'app.division_grades',
        'app.sections',
        'app.teachers',
        'app.reset_password_hashes',
        'app.campus_course_teachers',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.section_students',
        'app.campus_teachers',
        'app.report_template_course_scores',
        'app.scale_values',
        'app.scales',
        'app.evaluations',
        'app.evaluation_feedbacks',
        'app.evaluation_impact_scores',
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
        'app.section_events',
        'app.section_teachers',
        'app.report_settings',
        'app.report_template_pages',
        'app.report_template_types',
        'app.report_template_grades',
        'app.reports',
        'app.report_pages',
        'app.report_template_email_settings',
        'app.report_template_impact_scores',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.reflection_subcategories',
        'app.unit_resources',
        'app.unit_teachers',
        'app.assessment_types',
        'app.assessment_subtypes',
        'app.grade_contexts',
        'app.contexts',
        'app.course_grades',
        'app.campus_settings',
        'app.setting_keys',
        'app.evidence_sections',
        'app.evidence_contexts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EvidenceImpacts') ? [] : ['className' => EvidenceImpactsTable::class];
        $this->EvidenceImpacts = TableRegistry::get('EvidenceImpacts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EvidenceImpacts);

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
