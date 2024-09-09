<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DogApplicationTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DogApplicationTable Test Case
 */
class DogApplicationTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DogApplicationTable
     */
    protected $DogApplication;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.DogApplication',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DogApplication') ? [] : ['className' => DogApplicationTable::class];
        $this->DogApplication = $this->getTableLocator()->get('DogApplication', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->DogApplication);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DogApplicationTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
