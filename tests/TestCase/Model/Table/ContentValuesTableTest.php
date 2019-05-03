<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContentValuesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContentValuesTable Test Case
 */
class ContentValuesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ContentValuesTable
     */
    public $ContentValues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.content_values',
        'app.content_categories',
        'app.course_content_categories',
        'app.courses',
        'app.grades',
        'app.course',
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
        'app.section_teachers',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.course_strands',
        'app.unit_standards',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_contents',
        'app.unit_courses',
        'app.unit_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_other_goals',
        'app.other_goal_categories',
        'app.unit_reflections',
        'app.reflection_categories',
        'app.unit_resources',
        'app.unit_specfic_contents',
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
        $config = TableRegistry::exists('ContentValues') ? [] : ['className' => ContentValuesTable::class];
        $this->ContentValues = TableRegistry::get('ContentValues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ContentValues);

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
