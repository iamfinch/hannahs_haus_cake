<?php
declare(strict_types=1);

namespace App\Test\TestCase\Policy;

use App\Model\Entity\DogApplication;
use App\Policy\DogApplicationPolicy;
use Authorization\IdentityInterface;
use Authorization\Policy\ResultInterface;
use Cake\TestSuite\TestCase;

/**
 * Mock admin identity for testing
 */
class MockAdminIdentity implements IdentityInterface
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
class MockUserIdentity implements IdentityInterface
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
 * App\Policy\DogApplicationPolicy Test Case
 *
 * Tests authorization rules for dog adoption applications
 */
class DogApplicationPolicyTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Policy\DogApplicationPolicy
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
        $this->policy = new DogApplicationPolicy();
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
    // HELPER METHODS - Create mock identities and applications
    // ========================================================================

    /**
     * Create a mock admin identity
     *
     * @param int $userId User ID
     * @return \Authorization\IdentityInterface
     */
    protected function createAdminIdentity(int $userId = 2): IdentityInterface
    {
        return new MockAdminIdentity($userId);
    }

    /**
     * Create a mock regular user identity
     *
     * @param int $userId User ID
     * @return \Authorization\IdentityInterface
     */
    protected function createUserIdentity(int $userId = 1): IdentityInterface
    {
        return new MockUserIdentity($userId);
    }

    /**
     * Create a mock dog application entity
     *
     * @param int $userId Owner user ID
     * @param string $approved Application status ('0' = pending, '1' = approved, '-1' = rejected)
     * @return \App\Model\Entity\DogApplication
     */
    protected function createApplication(int $userId = 1, string $approved = '0'): DogApplication
    {
        $application = new DogApplication();
        $application->id = 1;
        $application->userId = $userId;
        $application->dogId = 1;
        $application->pickupMethodId = 1;
        $application->approved = $approved;
        $application->dateCreated = new \DateTime();

        return $application;
    }

    // ========================================================================
    // CAN VIEW TESTS
    // ========================================================================

    /**
     * Test canView as admin
     *
     * Admins can view all applications regardless of ownership
     *
     * @return void
     */
    public function testCanViewAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $application = $this->createApplication(1); // Different user's application

        $result = $this->policy->canView($admin, $application);

        $this->assertTrue($result, 'Admins should be able to view all applications');
    }

    /**
     * Test canView own application
     *
     * Users can view their own applications
     *
     * @return void
     */
    public function testCanViewOwnApplication(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1); // User's own application

        $result = $this->policy->canView($user, $application);

        $this->assertTrue($result, 'Users should be able to view their own applications');
    }

    /**
     * Test canView other user's application
     *
     * Regular users cannot view other users' applications
     *
     * @return void
     */
    public function testCannotViewOtherUsersApplication(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(3); // Different user's application

        $result = $this->policy->canView($user, $application);

        $this->assertFalse($result, 'Users should not be able to view other users\' applications');
    }

    /**
     * Test canView as guest
     *
     * Unauthenticated users cannot view any applications
     *
     * @return void
     */
    public function testCannotViewAsGuest(): void
    {
        $application = $this->createApplication(1);

        $result = $this->policy->canView(null, $application);

        $this->assertFalse($result, 'Guests should not be able to view applications');
    }

    // ========================================================================
    // CAN ADD TESTS
    // ========================================================================

    /**
     * Test canAdd as authenticated user
     *
     * Any authenticated user can submit applications
     *
     * @return void
     */
    public function testCanAddAsAuthenticatedUser(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1);

        $result = $this->policy->canAdd($user, $application);

        $this->assertTrue($result, 'Authenticated users should be able to submit applications');
    }

    /**
     * Test canAdd as admin
     *
     * Admins can also submit applications (same as regular users)
     *
     * @return void
     */
    public function testCanAddAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $application = $this->createApplication(2);

        $result = $this->policy->canAdd($admin, $application);

        $this->assertTrue($result, 'Admins should be able to submit applications');
    }

    /**
     * Test canAdd as guest
     *
     * Unauthenticated users cannot submit applications
     *
     * @return void
     */
    public function testCannotAddAsGuest(): void
    {
        $application = $this->createApplication(1);

        $result = $this->policy->canAdd(null, $application);

        $this->assertFalse($result, 'Guests should not be able to submit applications');
    }

    // ========================================================================
    // CAN EDIT TESTS
    // ========================================================================

    /**
     * Test canEdit as admin
     *
     * Admins can edit any application regardless of status
     *
     * @return void
     */
    public function testCanEditAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $application = $this->createApplication(1, '0'); // Pending application

        $result = $this->policy->canEdit($admin, $application);

        $this->assertTrue($result, 'Admins should be able to edit any application');
    }

    /**
     * Test canEdit own pending application
     *
     * Users can edit their own pending applications
     *
     * @return void
     */
    public function testCanEditOwnPendingApplication(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1, '0'); // Pending

        $result = $this->policy->canEdit($user, $application);

        $this->assertTrue($result, 'Users should be able to edit their own pending applications');
    }

    /**
     * Test canEdit own approved application
     *
     * Users cannot edit their own approved applications
     *
     * @return void
     */
    public function testCannotEditOwnApprovedApplication(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1, '1'); // Approved

        $result = $this->policy->canEdit($user, $application);

        $this->assertFalse($result, 'Users should not be able to edit approved applications');
    }

    /**
     * Test canEdit own rejected application
     *
     * Users cannot edit their own rejected applications
     *
     * @return void
     */
    public function testCannotEditOwnRejectedApplication(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1, '-1'); // Rejected

        $result = $this->policy->canEdit($user, $application);

        $this->assertFalse($result, 'Users should not be able to edit rejected applications');
    }

    /**
     * Test canEdit other user's application
     *
     * Regular users cannot edit other users' applications
     *
     * @return void
     */
    public function testCannotEditOtherUsersApplication(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(3, '0'); // Different user's pending app

        $result = $this->policy->canEdit($user, $application);

        $this->assertFalse($result, 'Users should not be able to edit other users\' applications');
    }

    /**
     * Test canEdit as guest
     *
     * Unauthenticated users cannot edit any applications
     *
     * @return void
     */
    public function testCannotEditAsGuest(): void
    {
        $application = $this->createApplication(1, '0');

        $result = $this->policy->canEdit(null, $application);

        $this->assertFalse($result, 'Guests should not be able to edit applications');
    }

    // ========================================================================
    // CAN DELETE TESTS
    // ========================================================================

    /**
     * Test canDelete as admin
     *
     * Only admins can delete applications
     *
     * @return void
     */
    public function testCanDeleteAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $application = $this->createApplication(1);

        $result = $this->policy->canDelete($admin, $application);

        $this->assertTrue($result, 'Admins should be able to delete applications');
    }

    /**
     * Test canDelete as regular user
     *
     * Regular users cannot delete applications (even their own)
     *
     * @return void
     */
    public function testCannotDeleteAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1); // User's own application

        $result = $this->policy->canDelete($user, $application);

        $this->assertFalse($result, 'Regular users should not be able to delete applications');
    }

    /**
     * Test canDelete as guest
     *
     * Unauthenticated users cannot delete applications
     *
     * @return void
     */
    public function testCannotDeleteAsGuest(): void
    {
        $application = $this->createApplication(1);

        $result = $this->policy->canDelete(null, $application);

        $this->assertFalse($result, 'Guests should not be able to delete applications');
    }

    // ========================================================================
    // CAN APPROVE TESTS
    // ========================================================================

    /**
     * Test canApprove as admin
     *
     * Only admins can approve applications
     *
     * @return void
     */
    public function testCanApproveAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $application = $this->createApplication(1, '0'); // Pending

        $result = $this->policy->canApprove($admin, $application);

        $this->assertTrue($result, 'Admins should be able to approve applications');
    }

    /**
     * Test canApprove as regular user
     *
     * Regular users cannot approve applications (even their own)
     *
     * @return void
     */
    public function testCannotApproveAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1, '0');

        $result = $this->policy->canApprove($user, $application);

        $this->assertFalse($result, 'Regular users should not be able to approve applications');
    }

    /**
     * Test canApprove as guest
     *
     * Unauthenticated users cannot approve applications
     *
     * @return void
     */
    public function testCannotApproveAsGuest(): void
    {
        $application = $this->createApplication(1, '0');

        $result = $this->policy->canApprove(null, $application);

        $this->assertFalse($result, 'Guests should not be able to approve applications');
    }

    // ========================================================================
    // CAN REJECT TESTS
    // ========================================================================

    /**
     * Test canReject as admin
     *
     * Only admins can reject applications
     *
     * @return void
     */
    public function testCanRejectAsAdmin(): void
    {
        $admin = $this->createAdminIdentity(2);
        $application = $this->createApplication(1, '0'); // Pending

        $result = $this->policy->canReject($admin, $application);

        $this->assertTrue($result, 'Admins should be able to reject applications');
    }

    /**
     * Test canReject as regular user
     *
     * Regular users cannot reject applications
     *
     * @return void
     */
    public function testCannotRejectAsUser(): void
    {
        $user = $this->createUserIdentity(1);
        $application = $this->createApplication(1, '0');

        $result = $this->policy->canReject($user, $application);

        $this->assertFalse($result, 'Regular users should not be able to reject applications');
    }

    /**
     * Test canReject as guest
     *
     * Unauthenticated users cannot reject applications
     *
     * @return void
     */
    public function testCannotRejectAsGuest(): void
    {
        $application = $this->createApplication(1, '0');

        $result = $this->policy->canReject(null, $application);

        $this->assertFalse($result, 'Guests should not be able to reject applications');
    }
}
