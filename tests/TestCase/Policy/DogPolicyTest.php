<?php
declare(strict_types=1);

namespace App\Test\TestCase\Policy;

use App\Model\Entity\Dog;
use App\Policy\DogPolicy;
use Authorization\IdentityInterface;
use Authorization\Policy\ResultInterface;
use Cake\TestSuite\TestCase;

/**
 * Mock admin identity for testing
 */
class MockAdminIdentity_DogPolicy implements IdentityInterface
{
    private $userId;

    public function __construct(int $userId = 2)
    {
        $this->userId = $userId;
    }

    public function getIdentifier()
    {
        return $this->userId;
    }

    public function get(string $field)
    {
        if ($field === 'isAdmin') {
            return true;
        }
        return null;
    }

    public function getOriginalData()
    {
        return ['id' => $this->userId, 'isAdmin' => true];
    }

    public function can(string $action, $resource): bool
    {
        return true;
    }

    public function canResult(string $action, $resource): ResultInterface
    {
        return new \Authorization\Policy\Result(true);
    }

    public function applyScope(string $action, $target)
    {
        return $target;
    }

    // ArrayAccess methods
    public function offsetExists($offset): bool { return isset($this->getOriginalData()[$offset]); }
    public function offsetGet($offset) { return $this->getOriginalData()[$offset] ?? null; }
    public function offsetSet($offset, $value): void {}
    public function offsetUnset($offset): void {}
}

/**
 * Mock regular user identity for testing
 */
class MockUserIdentity_DogPolicy implements IdentityInterface
{
    private $userId;

    public function __construct(int $userId = 1)
    {
        $this->userId = $userId;
    }

    public function getIdentifier()
    {
        return $this->userId;
    }

    public function get(string $field)
    {
        if ($field === 'isAdmin') {
            return false;
        }
        return null;
    }

    public function getOriginalData()
    {
        return ['id' => $this->userId, 'isAdmin' => false];
    }

    public function can(string $action, $resource): bool
    {
        return false;
    }

    public function canResult(string $action, $resource): ResultInterface
    {
        return new \Authorization\Policy\Result(false);
    }

    public function applyScope(string $action, $target)
    {
        return $target;
    }

    // ArrayAccess methods
    public function offsetExists($offset): bool { return isset($this->getOriginalData()[$offset]); }
    public function offsetGet($offset) { return $this->getOriginalData()[$offset] ?? null; }
    public function offsetSet($offset, $value): void {}
    public function offsetUnset($offset): void {}
}

/**
 * App\Policy\DogPolicy Test Case
 *
 * Tests authorization rules for dog entities
 */
class DogPolicyTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Policy\DogPolicy
     */
    protected $policy;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new DogPolicy();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->policy);
        parent::tearDown();
    }

    // ========================================================================
    // HELPER METHODS - Create mock identities and dogs
    // ========================================================================

    /**
     * Create a mock admin identity
     *
     * @param int $userId User ID
     * @return \Authorization\IdentityInterface
     */
    protected function createAdminIdentity(int $userId = 2): IdentityInterface
    {
        return new MockAdminIdentity_DogPolicy($userId);
    }

    /**
     * Create a mock regular user identity
     *
     * @param int $userId User ID
     * @return \Authorization\IdentityInterface
     */
    protected function createUserIdentity(int $userId = 1): IdentityInterface
    {
        return new MockUserIdentity_DogPolicy($userId);
    }

    /**
     * Create a mock dog entity
     *
     * @param int $dogId Dog ID
     * @param int|null $userId Owner user ID (null if not adopted)
     * @return \App\Model\Entity\Dog
     */
    protected function createDog(int $dogId = 1, ?int $userId = null): Dog
    {
        $dog = new Dog();
        $dog->id = $dogId;
        $dog->name = 'Test Dog';
        $dog->dateBorn = new \DateTime('2020-01-01');
        $dog->color = 'Brown';
        $dog->retired = 0;
        $dog->adopted = $userId ? 1 : 0;
        $dog->userId = $userId;

        return $dog;
    }

    // ========================================================================
    // CAN VIEW TESTS
    // ========================================================================

    /**
     * Test canView as guest
     *
     * Anyone can view dogs (public listing)
     *
     * @return void
     */
    public function testCanViewAsGuest(): void
    {
        $dog = $this->createDog(1);

        $result = $this->policy->canView(null, $dog);

        $this->assertTrue($result, 'Guests should be able to view dogs');
    }

    /**
     * Test canView as authenticated user
     *
     * Authenticated users can view dogs
     *
     * @return void
     */
    public function testCanViewAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $dog = $this->createDog(1);

        $result = $this->policy->canView($user, $dog);

        $this->assertTrue($result, 'Authenticated users should be able to view dogs');
    }

    /**
     * Test canView as admin
     *
     * Admins can view dogs
     *
     * @return void
     */
    public function testCanViewAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $dog = $this->createDog(1);

        $result = $this->policy->canView($admin, $dog);

        $this->assertTrue($result, 'Admins should be able to view dogs');
    }

    // ========================================================================
    // CAN ADD TESTS
    // ========================================================================

    /**
     * Test canAdd as guest
     *
     * Guests cannot add dogs
     *
     * @return void
     */
    public function testCanAddAsGuest(): void
    {
        $dog = $this->createDog(1);

        $result = $this->policy->canAdd(null, $dog);

        $this->assertFalse($result, 'Guests should not be able to add dogs');
    }

    /**
     * Test canAdd as regular user
     *
     * Regular users cannot add dogs
     *
     * @return void
     */
    public function testCanAddAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $dog = $this->createDog(1);

        $result = $this->policy->canAdd($user, $dog);

        $this->assertFalse($result, 'Regular users should not be able to add dogs');
    }

    /**
     * Test canAdd as admin
     *
     * Only admins can add dogs
     *
     * @return void
     */
    public function testCanAddAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $dog = $this->createDog(1);

        $result = $this->policy->canAdd($admin, $dog);

        $this->assertTrue($result, 'Admins should be able to add dogs');
    }

    // ========================================================================
    // CAN EDIT TESTS
    // ========================================================================

    /**
     * Test canEdit as guest
     *
     * Guests cannot edit dogs
     *
     * @return void
     */
    public function testCanEditAsGuest(): void
    {
        $dog = $this->createDog(1);

        $result = $this->policy->canEdit(null, $dog);

        $this->assertFalse($result, 'Guests should not be able to edit dogs');
    }

    /**
     * Test canEdit as regular user
     *
     * Regular users cannot edit dogs (even their own adopted dogs)
     * This prevents manipulation of dog records
     *
     * @return void
     */
    public function testCanEditAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $dog = $this->createDog(1, 1); // Dog adopted by this user

        $result = $this->policy->canEdit($user, $dog);

        $this->assertFalse($result, 'Regular users should not be able to edit dogs (even their own)');
    }

    /**
     * Test canEdit as admin
     *
     * Only admins can edit dogs
     *
     * @return void
     */
    public function testCanEditAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $dog = $this->createDog(1);

        $result = $this->policy->canEdit($admin, $dog);

        $this->assertTrue($result, 'Admins should be able to edit dogs');
    }

    // ========================================================================
    // CAN DELETE TESTS
    // ========================================================================

    /**
     * Test canDelete as guest
     *
     * Guests cannot delete dogs
     *
     * @return void
     */
    public function testCanDeleteAsGuest(): void
    {
        $dog = $this->createDog(1);

        $result = $this->policy->canDelete(null, $dog);

        $this->assertFalse($result, 'Guests should not be able to delete dogs');
    }

    /**
     * Test canDelete as regular user
     *
     * Regular users cannot delete dogs
     *
     * @return void
     */
    public function testCanDeleteAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $dog = $this->createDog(1);

        $result = $this->policy->canDelete($user, $dog);

        $this->assertFalse($result, 'Regular users should not be able to delete dogs');
    }

    /**
     * Test canDelete as admin
     *
     * Only admins can delete dogs
     *
     * @return void
     */
    public function testCanDeleteAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $dog = $this->createDog(1);

        $result = $this->policy->canDelete($admin, $dog);

        $this->assertTrue($result, 'Admins should be able to delete dogs');
    }

    // ========================================================================
    // IS OWNER TESTS
    // ========================================================================

    /**
     * Test isOwner for non-owner
     *
     * User does not own the dog
     *
     * @return void
     */
    public function testIsOwnerForNonOwner(): void
    {
        $user = $this->createUserIdentity(1);
        $dog = $this->createDog(1, 3); // Dog owned by user ID 3

        $result = $this->policy->isOwner($user, $dog);

        $this->assertFalse($result, 'User should not be owner of dog owned by someone else');
    }

    /**
     * Test isOwner for owner
     *
     * User owns the dog (userId matches)
     *
     * @return void
     */
    public function testIsOwnerForOwner(): void
    {
        $user = $this->createUserIdentity(1);
        $dog = $this->createDog(1, 1); // Dog owned by user ID 1

        $result = $this->policy->isOwner($user, $dog);

        $this->assertTrue($result, 'User should be owner of dog they adopted');
    }

    /**
     * Test isOwner as guest
     *
     * Guest is never an owner
     *
     * @return void
     */
    public function testIsOwnerAsGuest(): void
    {
        $dog = $this->createDog(1, 1); // Dog owned by user ID 1

        $result = $this->policy->isOwner(null, $dog);

        $this->assertFalse($result, 'Guests should not be owners');
    }
}
