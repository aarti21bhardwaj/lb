<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssessmentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssessmentsTable Test Case
 */
class AssessmentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AssessmentsTable
     */
    public $Assessments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.assessments',
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
        'app.assessment_strands',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.course_strands',
        'app.standards',
        'app.standard_grades',
        'app.assessment_standards',
        'app.unit_standards',
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
        'app.unit_courses',
        'app.unit_specific_contents',
        'app.assessment_specific_contents',
        'app.unit_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.assessment_impacts',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_teachers',
        'app.assessment_types',
        'app.assessment_contents',
        'app.assessment_reflections',
        'app.assessment_resources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Assessments') ? [] : ['className' => AssessmentsTable::class];
        $this->Assessments = TableRegistry::get('Assessments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Assessments);

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

    /**
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
