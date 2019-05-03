<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReflectionCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReflectionCategoriesTable Test Case
 */
class ReflectionCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReflectionCategoriesTable
     */
    public $ReflectionCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.reflection_categories',
        'app.unit_reflections',
        'app.units',
        'app.templates',
        'app.unit_types',
        'app.trans_disciplinary_themes',
        'app.unit_courses',
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
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.course_strands',
        'app.unit_standards',
        'app.unit_impacts',
        'app.impacts',
        'app.impact_categories',
        'app.unit_other_goals',
        'app.other_goal_categories',
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
        $config = TableRegistry::exists('ReflectionCategories') ? [] : ['className' => ReflectionCategoriesTable::class];
        $this->ReflectionCategories = TableRegistry::get('ReflectionCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReflectionCategories);

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
