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
     * Loaded in dependency order to satisfy foreign key constraints
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Countries',
        'app.HousingTypes',
        'app.States',
        'app.Users',
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
     * Test validationDefault method with valid data
     *
     * @return void
     * @uses \App\Model\Table\DogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $dog = $this->Dogs->newEntity([
            'name' => 'Rocky',
            'dateBorn' => '2022-01-15 00:00:00',
            'color' => 'Brown',
            'retired' => false,
            'retiredDate' => null,
            'adopted' => false,
            'adoptedDate' => null,
            'userId' => null, // Nullable FK
        ]);

        $this->assertNotFalse($this->Dogs->save($dog), 'Dog with valid data should save successfully');
        $this->assertEmpty($dog->getErrors(), 'Dog should have no validation errors');
    }

    /**
     * Test that name is required
     *
     * @return void
     */
    public function testValidationNameRequired(): void
    {
        $dog = $this->Dogs->newEntity([
            'dateBorn' => '2022-01-15 00:00:00',
            'color' => 'Brown',
            'retired' => false,
            'adopted' => false,
            // name intentionally omitted
        ]);

        $this->assertFalse($this->Dogs->save($dog), 'Dog without name should not save');
        $this->assertNotEmpty($dog->getError('name'), 'name field should have validation error');
    }

    /**
     * Test that dateBorn is required
     *
     * @return void
     */
    public function testValidationDateBornRequired(): void
    {
        $dog = $this->Dogs->newEntity([
            'name' => 'Rocky',
            'color' => 'Brown',
            'retired' => false,
            'adopted' => false,
            // dateBorn intentionally omitted
        ]);

        $this->assertFalse($this->Dogs->save($dog), 'Dog without dateBorn should not save');
        $this->assertNotEmpty($dog->getError('dateBorn'), 'dateBorn field should have validation error');
    }

    /**
     * Test that color is required
     *
     * @return void
     */
    public function testValidationColorRequired(): void
    {
        $dog = $this->Dogs->newEntity([
            'name' => 'Rocky',
            'dateBorn' => '2022-01-15 00:00:00',
            'retired' => false,
            'adopted' => false,
            // color intentionally omitted
        ]);

        $this->assertFalse($this->Dogs->save($dog), 'Dog without color should not save');
        $this->assertNotEmpty($dog->getError('color'), 'color field should have validation error');
    }

    /**
     * Test that userId is nullable (can be null)
     *
     * Validates nullable foreign key behavior
     *
     * @return void
     */
    public function testValidationUserIdNullable(): void
    {
        // Dog without owner (userId = null) should save successfully
        $dogWithoutOwner = $this->Dogs->newEntity([
            'name' => 'Stray',
            'dateBorn' => '2021-05-20 00:00:00',
            'color' => 'Mixed',
            'retired' => false,
            'adopted' => false,
            'userId' => null, // Explicitly null
        ]);

        $this->assertNotFalse(
            $this->Dogs->save($dogWithoutOwner),
            'Dog with null userId should save successfully (nullable FK)'
        );

        // Dog with valid userId should also save
        $dogWithOwner = $this->Dogs->newEntity([
            'name' => 'Owned Dog',
            'dateBorn' => '2020-08-10 00:00:00',
            'color' => 'Black',
            'retired' => false,
            'adopted' => true,
            'adoptedDate' => '2023-01-15 00:00:00',
            'userId' => 1, // Valid user from fixture
        ]);

        $this->assertNotFalse(
            $this->Dogs->save($dogWithOwner),
            'Dog with valid userId should save successfully'
        );
    }

    /**
     * Test that retired field accepts boolean values
     *
     * @return void
     */
    public function testValidationRetiredBoolean(): void
    {
        // Test retired = true
        $retiredDog = $this->Dogs->newEntity([
            'name' => 'Old Timer',
            'dateBorn' => '2015-03-10 00:00:00',
            'color' => 'Gray',
            'retired' => true,
            'retiredDate' => '2023-06-01 00:00:00',
            'adopted' => false,
        ]);

        $this->assertNotFalse($this->Dogs->save($retiredDog), 'Dog with retired=true should save');

        // Test retired = false
        $activeDog = $this->Dogs->newEntity([
            'name' => 'Young Pup',
            'dateBorn' => '2023-01-01 00:00:00',
            'color' => 'White',
            'retired' => false,
            'adopted' => false,
        ]);

        $this->assertNotFalse($this->Dogs->save($activeDog), 'Dog with retired=false should save');
    }

    /**
     * Test that adopted field accepts boolean values
     *
     * @return void
     */
    public function testValidationAdoptedBoolean(): void
    {
        // Test adopted = true
        $adoptedDog = $this->Dogs->newEntity([
            'name' => 'Happy Home',
            'dateBorn' => '2021-07-15 00:00:00',
            'color' => 'Golden',
            'retired' => false,
            'adopted' => true,
            'adoptedDate' => '2022-12-25 00:00:00',
            'userId' => 1, // Adopted by user 1
        ]);

        $this->assertNotFalse($this->Dogs->save($adoptedDog), 'Dog with adopted=true should save');

        // Test adopted = false
        $availableDog = $this->Dogs->newEntity([
            'name' => 'Still Waiting',
            'dateBorn' => '2022-04-20 00:00:00',
            'color' => 'Brown',
            'retired' => false,
            'adopted' => false,
        ]);

        $this->assertNotFalse($this->Dogs->save($availableDog), 'Dog with adopted=false should save');
    }

    /**
     * Test belongsTo Owner association
     *
     * Verifies dog can load associated owner (user) data
     * Uses LEFT JOIN due to nullable userId
     *
     * @return void
     */
    public function testBelongsToOwnerAssociation(): void
    {
        // Load dog with owner (Dog 2 - Luna, userId=3 from fixture)
        $dogWithOwner = $this->Dogs->find()
            ->where(['Dogs.id' => 2])
            ->contain(['Owners'])
            ->first();

        $this->assertNotNull($dogWithOwner, 'Dog should be found');
        $this->assertNotNull($dogWithOwner->owner, 'Owner association should be loaded');
        $this->assertEquals(3, $dogWithOwner->owner->id, 'Correct owner should be associated');

        // Load dog without owner (Dog 1 - Buddy, userId=null from fixture)
        $dogWithoutOwner = $this->Dogs->find()
            ->where(['Dogs.id' => 1])
            ->contain(['Owners'])
            ->first();

        $this->assertNotNull($dogWithoutOwner, 'Dog without owner should be found');
        $this->assertNull($dogWithoutOwner->owner, 'Owner should be null for dog without userId');
    }

    /**
     * Test findAvailable custom finder
     *
     * Should return only dogs that are not retired and not adopted
     *
     * @return void
     * @uses \App\Model\Table\DogsTable::findAvailable()
     */
    public function testFindAvailableMethod(): void
    {
        // Find available dogs (not retired, not adopted)
        $availableDogs = $this->Dogs->find('available')->all();

        $this->assertNotEmpty($availableDogs, 'Should find available dogs');

        // Fixture has Dog 1 (Buddy) as available (retired=0, adopted=0)
        $this->assertCount(1, $availableDogs, 'Should find exactly 1 available dog from fixture');

        foreach ($availableDogs as $dog) {
            $this->assertFalse($dog->retired, 'Available dog should not be retired');
            $this->assertFalse($dog->adopted, 'Available dog should not be adopted');
        }
    }

    /**
     * Test findAdoptedByUser custom finder
     *
     * Should return only dogs adopted by specific user
     *
     * @return void
     * @uses \App\Model\Table\DogsTable::findAdoptedByUser()
     */
    public function testFindAdoptedByUserMethod(): void
    {
        // Find dogs adopted by user 3 (from fixture: Dog 2 - Luna)
        $adoptedByUser3 = $this->Dogs->find('adoptedByUser', ['userId' => 3])->all();

        $this->assertNotEmpty($adoptedByUser3, 'Should find dogs adopted by user 3');
        $this->assertCount(1, $adoptedByUser3, 'Should find exactly 1 dog adopted by user 3 from fixture');

        foreach ($adoptedByUser3 as $dog) {
            $this->assertEquals(3, $dog->userId, 'Dog should belong to user 3');
            $this->assertTrue($dog->adopted, 'Dog should be marked as adopted');
        }

        // Find dogs adopted by user 1 (should be empty - no dogs in fixture)
        $adoptedByUser1 = $this->Dogs->find('adoptedByUser', ['userId' => 1])->all();
        $this->assertEmpty($adoptedByUser1, 'User 1 should have no adopted dogs in fixture');
    }
}
