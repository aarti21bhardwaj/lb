<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SectionStudentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SectionStudentsTable Test Case
 */
class SectionStudentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SectionStudentsTable
     */
    public $SectionStudents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.section_students',
        'app.sections',
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
        'app.school_users',
        'app.reset_password_hashes',
        'app.standards',
        'app.strands',
        'app.learning_areas',
        'app.curriculums',
        'app.terms',
        'app.academic_years',
        'app.students'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SectionStudents') ? [] : ['className' => SectionStudentsTable::class];
        $this->SectionStudents = TableRegistry::get('SectionStudents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SectionStudents);

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
