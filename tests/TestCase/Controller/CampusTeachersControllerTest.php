<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CampusTeachersController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\CampusTeachersController Test Case
 */
class CampusTeachersControllerTest extends IntegrationTestCase
{

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
