<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EvidenceContentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EvidenceContentsTable Test Case
 */
class EvidenceContentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EvidenceContentsTable
     */
    public $EvidenceContents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.evidence_contents',
        'app.evidences',
        'app.content_categories',
        'app.content_values'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EvidenceContents') ? [] : ['className' => EvidenceContentsTable::class];
        $this->EvidenceContents = TableRegistry::get('EvidenceContents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EvidenceContents);

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
