<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StrandsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StrandsTable Test Case
 */
class StrandsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StrandsTable
     */
    public $StrandsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
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
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_standards',
        'app.standards',
        'app.standard_grades',
        'app.assessment_standards',
        'app.unit_strands',
        'app.unit_teachers',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.schools',
        'app.campuses',
        'app.campus_courses',
        'app.campus_course_teachers',
        'app.campus_teachers',
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
        'app.section_teachers',
        'app.assessment_types',
        'app.evaluations',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks',
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
        $config = TableRegistry::exists('Strands') ? [] : ['className' => StrandsTable::class];
        $this->StrandsTable = TableRegistry::get('Strands', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StrandsTable);

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
