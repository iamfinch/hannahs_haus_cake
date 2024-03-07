<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DogsTable Test Case
 */
class DogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DogsTable
     */
    protected $Dogs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Dogs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Dogs') ? [] : ['className' => DogsTable::class];
        $this->Dogs = $this->getTableLocator()->get('Dogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Dogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
