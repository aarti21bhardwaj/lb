<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CampusTeachersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CampusTeachersTable Test Case
 */
class CampusTeachersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CampusTeachersTable
     */
    public $CampusTeachers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.campus_teachers',
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
        'app.campus_courses',
        'app.campus_course_teachers',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.school_users',
        'app.reset_password_hashes',
        'app.sections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CampusTeachers') ? [] : ['className' => CampusTeachersTable::class];
        $this->CampusTeachers = TableRegistry::get('CampusTeachers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CampusTeachers);

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
