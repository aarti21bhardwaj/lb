<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DivisionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DivisionsTable Test Case
 */
class DivisionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DivisionsTable
     */
    public $Divisions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.divisions',
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
        'app.terms',
        'app.academic_years',
        'app.section_students',
        'app.students',
        'app.campus_course_teachers',
        'app.school_users',
        'app.student_guardians',
        'app.guardians',
        'app.campus_teachers',
        'app.section_teachers',
        'app.assessment_types',
        'app.evaluations',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks',
        'app.division_grades'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Divisions') ? [] : ['className' => DivisionsTable::class];
        $this->Divisions = TableRegistry::get('Divisions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Divisions);

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
