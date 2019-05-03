<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GradesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GradesTable Test Case
 */
class GradesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GradesTable
     */
    public $Grades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        'app.courses',
        'app.learning_areas',
        'app.curriculums',
        'app.strands',
        'app.unit_strands',
        'app.course_strands',
        'app.standards',
        'app.standard_grades',
        'app.assessment_standards',
        'app.unit_standards',
        'app.campus_courses',
        'app.campuses',
        'app.schools',
        'app.divisions',
        'app.division_grades',
        'app.terms',
        'app.academic_years',
        'app.sections',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.reset_password_hashes',
        'app.campus_course_teachers',
        'app.school_users',
        'app.student_guardians',
        'app.students',
        'app.campus_teachers',
        'app.guardians',
        'app.section_students',
        'app.section_teachers',
        'app.unit_courses',
        'app.unit_specific_contents',
        'app.assessment_contents',
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
        'app.evaluations',
        'app.scales',
        'app.scale_values',
        'app.evaluation_impact_scores',
        'app.evaluation_standard_scores',
        'app.evaluation_feedbacks'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Grades') ? [] : ['className' => GradesTable::class];
        $this->Grades = TableRegistry::get('Grades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Grades);

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
