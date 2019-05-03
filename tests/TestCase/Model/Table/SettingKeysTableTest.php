<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SettingKeysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SettingKeysTable Test Case
 */
class SettingKeysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SettingKeysTable
     */
    public $SettingKeys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.setting_keys',
        'app.campus_settings',
        'app.campuses',
        'app.schools',
        'app.divisions',
        'app.templates',
        'app.units',
        'app.unit_types',
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
        'app.reset_password_hashes',
        'app.campus_course_teachers',
        'app.campus_courses',
        'app.school_users',
        'app.student_guardians',
        'app.students',
        'app.campus_teachers',
        'app.guardians',
        'app.terms',
        'app.academic_years',
        'app.section_students',
        'app.section_teachers',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks',
        'app.section_events',
        'app.division_grades',
        'app.unit_courses',
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
        $config = TableRegistry::exists('SettingKeys') ? [] : ['className' => SettingKeysTable::class];
        $this->SettingKeys = TableRegistry::get('SettingKeys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SettingKeys);

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
