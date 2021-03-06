<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitTypesTable Test Case
 */
class UnitTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitTypesTable
     */
    public $UnitTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_types',
        'app.units',
        'app.templates',
        'app.trans_disciplinary_themes',
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
        'app.assessment_specific_contents',
        'app.assessment_standards',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.unit_strands',
        'app.course_strands',
        'app.standard_grades',
        'app.unit_standards',
        'app.evaluations',
        'app.sections',
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
        'app.school_users',
        'app.reset_password_hashes',
        'app.student_guardians',
        'app.students',
        'app.guardians',
        'app.section_events',
        'app.section_students',
        'app.section_teachers',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks',
        'app.unit_courses',
        'app.course_grades',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_teachers',
        'app.types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UnitTypes') ? [] : ['className' => UnitTypesTable::class];
        $this->UnitTypes = TableRegistry::get('UnitTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitTypes);

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
