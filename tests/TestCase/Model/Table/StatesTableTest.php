<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StatesTable Test Case
 */
class StatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StatesTable
     */
    protected $States;

    /**
     * Fixtures
     *
     * Loaded in dependency order to satisfy foreign key constraints
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Countries',
        'app.States',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('States') ? [] : ['className' => StatesTable::class];
        $this->States = $this->getTableLocator()->get('States', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->States);

        parent::tearDown();
    }

    /**
     * Test validationDefault method with valid data
     *
     * @return void
     * @uses \App\Model\Table\StatesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $state = $this->States->newEntity([
            'countryId' => 1, // USA from fixture
            'name' => 'California',
            'isProvince' => false,
        ]);

        $this->assertNotFalse($this->States->save($state), 'State with valid data should save successfully');
        $this->assertEmpty($state->getErrors(), 'State should have no validation errors');
    }

    /**
     * Test that countryId is required
     *
     * @return void
     */
    public function testValidationCountryIdRequired(): void
    {
        $state = $this->States->newEntity([
            'name' => 'California',
            'isProvince' => false,
            // countryId intentionally omitted
        ]);

        $this->assertFalse($this->States->save($state), 'State without countryId should not save');
        $this->assertNotEmpty($state->getError('countryId'), 'countryId field should have validation error');
    }

    /**
     * Test that name is required
     *
     * @return void
     */
    public function testValidationNameRequired(): void
    {
        $state = $this->States->newEntity([
            'countryId' => 1,
            'isProvince' => false,
            // name intentionally omitted
        ]);

        $this->assertFalse($this->States->save($state), 'State without name should not save');
        $this->assertNotEmpty($state->getError('name'), 'name field should have validation error');
    }

    /**
     * Test foreign key validation for countryId
     *
     * SQLite enforces foreign key constraints at database level,
     * which may throw PDOException instead of validation errors
     *
     * @return void
     * @uses \App\Model\Table\StatesTable::buildRules()
     */
    public function testBuildRulesCountryIdExistsIn(): void
    {
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('FOREIGN KEY constraint failed');

        $state = $this->States->newEntity([
            'countryId' => 9999, // Non-existent country
            'name' => 'Invalid State',
            'isProvince' => false,
        ]);

        // This should throw PDOException due to foreign key constraint
        $this->States->save($state);
    }

    /**
     * Test belongsTo Country association
     *
     * Verifies state can load associated country data
     *
     * @return void
     */
    public function testBelongsToCountryAssociation(): void
    {
        // Load state with country data
        $state = $this->States->find()
            ->where(['States.id' => 1])
            ->contain(['Countries'])
            ->first();

        $this->assertNotNull($state, 'State should be found');
        $this->assertNotNull($state->country, 'Country association should be loaded');
        $this->assertEquals(1, $state->country->id, 'Correct country should be associated');
    }

    /**
     * Test getStates custom method
     *
     * Verifies custom method returns states filtered by country
     * Returns array of State entity objects with id and name fields
     * (note: only id and name are selected, countryId is not hydrated)
     *
     * @return void
     * @uses \App\Model\Table\StatesTable::getStates()
     */
    public function testGetStatesMethod(): void
    {
        // Get US states (countryId = 1)
        $usStates = $this->States->getStates(1);
        $this->assertNotEmpty($usStates, 'US states should be returned');
        $this->assertIsArray($usStates, 'Should return an array');
        $this->assertCount(3, $usStates, 'Should return 3 US states from fixture');

        // Verify returned states have proper structure
        $stateNames = [];
        foreach ($usStates as $state) {
            $this->assertInstanceOf(\App\Model\Entity\State::class, $state, 'Each item should be a State entity');
            $this->assertNotNull($state->id, 'State should have an id');
            $this->assertNotEmpty($state->name, 'State should have a name');
            $stateNames[] = $state->name;
        }

        // Verify states are ordered by name (ASC)
        $sortedNames = $stateNames;
        sort($sortedNames);
        $this->assertEquals($sortedNames, $stateNames, 'States should be ordered by name ascending');

        // Get Canadian provinces (countryId = 2)
        $canadianProvinces = $this->States->getStates(2);
        $this->assertNotEmpty($canadianProvinces, 'Canadian provinces should be returned');
        $this->assertCount(2, $canadianProvinces, 'Should return 2 Canadian provinces from fixture');

        // Verify provinces have proper structure
        foreach ($canadianProvinces as $province) {
            $this->assertNotNull($province->id, 'Province should have an id');
            $this->assertNotEmpty($province->name, 'Province should have a name');
        }
    }
}
