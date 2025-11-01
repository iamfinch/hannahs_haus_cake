<?php
declare(strict_types=1);

namespace App\Test\TestCase\Policy;

use App\Model\Entity\User;
use App\Policy\UserPolicy;
use Authorization\IdentityInterface;
use Authorization\Policy\ResultInterface;
use Cake\TestSuite\TestCase;

/**
 * Mock admin identity for testing
 */
class MockAdminIdentity_UserPolicy implements IdentityInterface
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
class MockUserIdentity_UserPolicy implements IdentityInterface
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
 * App\Policy\UserPolicy Test Case
 *
 * Tests authorization rules for user entities
 */
class UserPolicyTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Policy\UserPolicy
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
        $this->policy = new UserPolicy();
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
    // HELPER METHODS - Create mock identities and users
    // ========================================================================

    /**
     * Create a mock admin identity
     *
     * @param int $userId User ID
     * @return \Authorization\IdentityInterface
     */
    protected function createAdminIdentity(int $userId = 2): IdentityInterface
    {
        return new MockAdminIdentity_UserPolicy($userId);
    }

    /**
     * Create a mock regular user identity
     *
     * @param int $userId User ID
     * @return \Authorization\IdentityInterface
     */
    protected function createUserIdentity(int $userId = 1): IdentityInterface
    {
        return new MockUserIdentity_UserPolicy($userId);
    }

    /**
     * Create a mock user entity
     *
     * @param int $userId User ID
     * @return \App\Model\Entity\User
     */
    protected function createUser(int $userId = 1): User
    {
        $user = new User();
        $user->id = $userId;
        $user->email = "user{$userId}@example.com";
        $user->fname = 'Test';
        $user->lname = 'User';
        $user->isAdmin = ($userId === 2) ? 1 : 0;

        return $user;
    }

    // ========================================================================
    // CAN VIEW TESTS
    // ========================================================================

    /**
     * Test canView own profile
     *
     * Users can view their own profile
     *
     * @return void
     */
    public function testCanViewOwnProfile(): void
    {
        $user = $this->createUserIdentity(1);
        $profile = $this->createUser(1); // Same user

        $result = $this->policy->canView($user, $profile);

        $this->assertTrue($result, 'Users should be able to view their own profile');
    }

    /**
     * Test cannot view other user's profile
     *
     * Regular users cannot view other users' profiles
     *
     * @return void
     */
    public function testCannotViewOtherProfile(): void
    {
        $user = $this->createUserIdentity(1);
        $profile = $this->createUser(3); // Different user

        $result = $this->policy->canView($user, $profile);

        $this->assertFalse($result, 'Users should not be able to view other users\' profiles');
    }

    /**
     * Test admin can view any profile
     *
     * Admins can view any user's profile
     *
     * @return void
     */
    public function testAdminCanViewAnyProfile(): void
    {
        $admin = $this->createAdminIdentity(2);
        $profile = $this->createUser(1); // Different user

        $result = $this->policy->canView($admin, $profile);

        $this->assertTrue($result, 'Admins should be able to view any profile');
    }

    /**
     * Test guest cannot view profiles
     *
     * Unauthenticated users cannot view user profiles
     *
     * @return void
     */
    public function testGuestCannotView(): void
    {
        $profile = $this->createUser(1);

        $result = $this->policy->canView(null, $profile);

        $this->assertFalse($result, 'Guests should not be able to view profiles');
    }

    // ========================================================================
    // CAN EDIT TESTS
    // ========================================================================

    /**
     * Test canEdit own profile
     *
     * Users can edit their own profile
     *
     * @return void
     */
    public function testCanEditOwnProfile(): void
    {
        $user = $this->createUserIdentity(1);
        $profile = $this->createUser(1); // Same user

        $result = $this->policy->canEdit($user, $profile);

        $this->assertTrue($result, 'Users should be able to edit their own profile');
    }

    /**
     * Test cannot edit other user's profile
     *
     * Regular users cannot edit other users' profiles
     *
     * @return void
     */
    public function testCannotEditOtherProfile(): void
    {
        $user = $this->createUserIdentity(1);
        $profile = $this->createUser(3); // Different user

        $result = $this->policy->canEdit($user, $profile);

        $this->assertFalse($result, 'Users should not be able to edit other users\' profiles');
    }

    /**
     * Test admin can edit any profile
     *
     * Admins can edit any user's profile
     *
     * @return void
     */
    public function testAdminCanEditAnyProfile(): void
    {
        $admin = $this->createAdminIdentity(2);
        $profile = $this->createUser(1); // Different user

        $result = $this->policy->canEdit($admin, $profile);

        $this->assertTrue($result, 'Admins should be able to edit any profile');
    }

    /**
     * Test guest cannot edit profiles
     *
     * Unauthenticated users cannot edit user profiles
     *
     * @return void
     */
    public function testGuestCannotEdit(): void
    {
        $profile = $this->createUser(1);

        $result = $this->policy->canEdit(null, $profile);

        $this->assertFalse($result, 'Guests should not be able to edit profiles');
    }

    // ========================================================================
    // CAN DELETE TESTS
    // ========================================================================

    /**
     * Test canDelete as admin
     *
     * Only admins can delete users
     *
     * @return void
     */
    public function testCanDeleteAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $profile = $this->createUser(1);

        $result = $this->policy->canDelete($admin, $profile);

        $this->assertTrue($result, 'Admins should be able to delete users');
    }

    /**
     * Test cannot delete as user
     *
     * Regular users cannot delete users (even themselves)
     *
     * @return void
     */
    public function testCannotDeleteAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $profile = $this->createUser(1); // Even their own profile

        $result = $this->policy->canDelete($user, $profile);

        $this->assertFalse($result, 'Regular users should not be able to delete users (even themselves)');
    }

    // ========================================================================
    // CAN ADD TESTS
    // ========================================================================

    /**
     * Test canAdd as guest
     *
     * Anyone can register (public registration)
     *
     * @return void
     */
    public function testCanAddAsGuest(): void
    {
        $profile = $this->createUser(1);

        $result = $this->policy->canAdd(null, $profile);

        $this->assertTrue($result, 'Guests should be able to register (add new users)');
    }

    /**
     * Test canAdd as authenticated user
     *
     * Authenticated users can also add users (registration)
     *
     * @return void
     */
    public function testCanAddAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $profile = $this->createUser(3);

        $result = $this->policy->canAdd($user, $profile);

        $this->assertTrue($result, 'Authenticated users can add users');
    }
}
