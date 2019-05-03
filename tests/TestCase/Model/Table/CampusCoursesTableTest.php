<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CampusCoursesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CampusCoursesTable Test Case
 */
class CampusCoursesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CampusCoursesTable
     */
    public $CampusCourses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.campus_courses',
        'app.campuses',
        'app.schools',
        'app.divisions',
        'app.division_grades',
        'app.grades',
        'app.course',
        'app.courses',
        'app.learning_areas',
        'app.curriculums',
        'app.strands',
        'app.standards',
        'app.terms',
        'app.academic_years',
        'app.sections',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.school_users',
        'app.reset_password_hashes',
        'app.campus_course_teachers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CampusCourses') ? [] : ['className' => CampusCoursesTable::class];
        $this->CampusCourses = TableRegistry::get('CampusCourses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CampusCourses);

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
