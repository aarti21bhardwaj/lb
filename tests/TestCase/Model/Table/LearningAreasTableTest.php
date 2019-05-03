<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LearningAreasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LearningAreasTable Test Case
 */
class LearningAreasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LearningAreasTable
     */
    public $LearningAreas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.learning_areas',
        'app.curriculums',
        'app.strands',
        'app.grades',
        'app.course',
        'app.courses',
        'app.terms',
        'app.academic_years',
        'app.campus_courses',
        'app.sections',
        'app.teachers',
        'app.roles',
        'app.users',
        'app.school_users',
        'app.schools',
        'app.campuses',
        'app.divisions',
        'app.division_grades',
        'app.reset_password_hashes',
        'app.standards'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LearningAreas') ? [] : ['className' => LearningAreasTable::class];
        $this->LearningAreas = TableRegistry::get('LearningAreas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LearningAreas);

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
