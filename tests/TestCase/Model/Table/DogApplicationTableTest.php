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
        'app.Countries',
        'app.HousingTypes',
        'app.PickupMethods',
        'app.States',
        'app.Users',
        'app.Dogs',
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
     * Test validationDefault method with valid data
     *
     * @return void
     * @uses \App\Model\Table\DogApplicationTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // Use User 2 (admin) → Dog 1 - no existing application for this combination
        $application = $this->DogApplication->newEntity([
            'userId' => 2, // Admin user - no existing application
            'dogId' => 1,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0', // Pending
        ]);

        $this->assertNotFalse($this->DogApplication->save($application), 'Application with valid data should save');
        $this->assertEmpty($application->getErrors(), 'Application should have no validation errors');
    }

    /**
     * Test that userId is required
     *
     * @return void
     */
    public function testValidationUserIdRequired(): void
    {
        $application = $this->DogApplication->newEntity([
            'dogId' => 1,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0',
            // userId intentionally omitted
        ]);

        $this->assertFalse($this->DogApplication->save($application), 'Application without userId should not save');
        $this->assertNotEmpty($application->getError('userId'), 'userId field should have validation error');
    }

    /**
     * Test that dogId is required
     *
     * @return void
     */
    public function testValidationDogIdRequired(): void
    {
        $application = $this->DogApplication->newEntity([
            'userId' => 1,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0',
            // dogId intentionally omitted
        ]);

        $this->assertFalse($this->DogApplication->save($application), 'Application without dogId should not save');
        $this->assertNotEmpty($application->getError('dogId'), 'dogId field should have validation error');
    }

    /**
     * Test that pickupMethodId is required
     *
     * @return void
     */
    public function testValidationPickupMethodIdRequired(): void
    {
        $application = $this->DogApplication->newEntity([
            'userId' => 1,
            'dogId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0',
            // pickupMethodId intentionally omitted
        ]);

        $this->assertFalse($this->DogApplication->save($application), 'Application without pickupMethodId should not save');
        $this->assertNotEmpty($application->getError('pickupMethodId'), 'pickupMethodId field should have validation error');
    }

    /**
     * Test foreign key validation for userId
     *
     * @return void
     * @uses \App\Model\Table\DogApplicationTable::buildRules()
     */
    public function testBuildRulesUserIdExistsIn(): void
    {
        $application = $this->DogApplication->newEntity([
            'userId' => 9999, // Non-existent user
            'dogId' => 1,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0',
        ]);

        $this->assertFalse($this->DogApplication->save($application), 'Application with invalid userId should not save');
        $this->assertNotEmpty($application->getError('userId'), 'userId should have foreign key error');
    }

    /**
     * Test tri-state approved values
     *
     * Tests that approved field accepts valid tri-state values
     *
     * @return void
     */
    public function testValidationApprovedTriState(): void
    {
        // Test rejected status ("-1")
        $rejected = $this->DogApplication->newEntity([
            'userId' => 2,
            'dogId' => 1,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '-1', // Rejected
        ]);
        $this->assertNotFalse(
            $this->DogApplication->save($rejected),
            "Application with approved='-1' should save successfully"
        );

        // Test pending status ("0")
        $pending = $this->DogApplication->newEntity([
            'userId' => 3,
            'dogId' => 1,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0', // Pending
        ]);
        $this->assertNotFalse(
            $this->DogApplication->save($pending),
            "Application with approved='0' should save successfully"
        );

        // Test approved status ("1")
        $approved = $this->DogApplication->newEntity([
            'userId' => 1,
            'dogId' => 2,
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '1', // Approved
        ]);
        $this->assertNotFalse(
            $this->DogApplication->save($approved),
            "Application with approved='1' should save successfully"
        );
    }

    /**
     * Test unique pending application rule
     *
     * Prevents duplicate pending applications for same user + dog
     *
     * @return void
     * @uses \App\Model\Table\DogApplicationTable::buildRules()
     */
    public function testBuildRulesUniquePendingApplication(): void
    {
        // Fixture already has pending application: user 1 → dog 1 (approved = "0")

        // Attempt to create duplicate pending application
        $duplicate = $this->DogApplication->newEntity([
            'userId' => 1,
            'dogId' => 1,
            'pickupMethodId' => 2,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '0', // Pending
        ]);

        $this->assertFalse($this->DogApplication->save($duplicate), 'Duplicate pending application should not save');
        $this->assertNotEmpty($duplicate->getError('dogId'), 'dogId should have unique rule error');

        // Verify approved applications don't trigger the rule
        $approved = $this->DogApplication->newEntity([
            'userId' => 1,
            'dogId' => 3, // Different dog
            'pickupMethodId' => 1,
            'dateCreated' => date('Y-m-d H:i:s'),
            'approved' => '1', // Approved (not pending)
        ]);

        $this->assertNotFalse($this->DogApplication->save($approved), 'Approved application should save even if previous pending exists');
    }

    /**
     * Test belongsTo User association
     *
     * Verifies application can load associated user data
     *
     * @return void
     */
    public function testBelongsToUserAssociation(): void
    {
        // Load application with user data
        $application = $this->DogApplication->find()
            ->where(['DogApplication.id' => 1])
            ->contain(['Users'])
            ->first();

        $this->assertNotNull($application, 'Application should be found');
        $this->assertNotNull($application->user, 'User association should be loaded');
        $this->assertEquals(1, $application->user->id, 'Correct user should be associated');
    }
}
