<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\DogsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\DogsController Test Case
 *
 * @uses \App\Controller\DogsController
 */
class DogsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * Loaded in dependency order for dog viewing and application checking
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

    // ========================================================================
    // INDEX METHOD TESTS - Public vs Admin dog listing
    // ========================================================================

    /**
     * Test index as unauthenticated user
     *
     * Public users should only see available dogs (not adopted or retired)
     *
     * @return void
     * @uses \App\Controller\DogsController::index()
     */
    public function testIndexAsUnauthenticatedUser(): void
    {
        // GET dog index without authentication
        $this->get('/dogs/index');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dogs in view vars
        $dogs = $this->viewVariable('dogs');
        $this->assertNotNull($dogs);

        // Public should only see available dogs (Buddy - ID 1)
        // Should NOT see Luna (adopted) or Max (retired)
        $dogIds = [];
        foreach ($dogs as $dog) {
            $dogIds[] = $dog->id;
        }
        $this->assertContains(1, $dogIds, 'Should see Buddy (available)');
        $this->assertNotContains(2, $dogIds, 'Should NOT see Luna (adopted)');
        $this->assertNotContains(3, $dogIds, 'Should NOT see Max (retired)');

        // isAdmin flag should be false
        $isAdmin = $this->viewVariable('isAdmin');
        $this->assertFalse($isAdmin);
    }

    /**
     * Test index as authenticated regular user
     *
     * Authenticated non-admin users see same as public (only available dogs)
     *
     * @return void
     * @uses \App\Controller\DogsController::index()
     */
    public function testIndexAsAuthenticatedRegularUser(): void
    {
        // Log in as John Doe (regular user, not admin)
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // GET dog index
        $this->get('/dogs/index');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dogs in view vars
        $dogs = $this->viewVariable('dogs');
        $this->assertNotNull($dogs);

        // Regular users should only see available dogs
        $dogIds = [];
        foreach ($dogs as $dog) {
            $dogIds[] = $dog->id;
        }
        $this->assertContains(1, $dogIds, 'Should see Buddy (available)');
        $this->assertNotContains(2, $dogIds, 'Should NOT see Luna (adopted)');
        $this->assertNotContains(3, $dogIds, 'Should NOT see Max (retired)');

        // isAdmin flag should be false
        $isAdmin = $this->viewVariable('isAdmin');
        $this->assertFalse($isAdmin);
    }

    /**
     * Test index as admin user
     *
     * Admins see ALL dogs (available, adopted, and retired)
     *
     * @return void
     * @uses \App\Controller\DogsController::index()
     */
    public function testIndexAsAdmin(): void
    {
        // Log in as Admin (user ID 2)
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // GET dog index
        $this->get('/dogs/index');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dogs in view vars
        $dogs = $this->viewVariable('dogs');
        $this->assertNotNull($dogs);

        // Admin should see ALL dogs
        $dogIds = [];
        foreach ($dogs as $dog) {
            $dogIds[] = $dog->id;
        }
        $this->assertContains(1, $dogIds, 'Should see Buddy (available)');
        $this->assertContains(2, $dogIds, 'Should see Luna (adopted)');
        $this->assertContains(3, $dogIds, 'Should see Max (retired)');

        // isAdmin flag should be true
        $isAdmin = $this->viewVariable('isAdmin');
        $this->assertTrue($isAdmin);
    }

    /**
     * Test index pagination
     *
     * Verifies that pagination works correctly
     *
     * @return void
     * @uses \App\Controller\DogsController::index()
     */
    public function testIndexPagination(): void
    {
        // GET dog index with pagination parameters
        $this->get('/dogs/index?page=1&limit=2');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dogs in view vars
        $dogs = $this->viewVariable('dogs');
        $this->assertNotNull($dogs);

        // With only 1 available dog (Buddy), pagination should work
        // but only return 1 result
        $count = 0;
        foreach ($dogs as $dog) {
            $count++;
        }
        $this->assertEquals(1, $count, 'Should return 1 available dog');
    }

    /**
     * Test index with no authentication shows available dogs only
     *
     * Verifies public view excludes non-available dogs
     *
     * @return void
     * @uses \App\Controller\DogsController::index()
     */
    public function testIndexPublicViewExcludesNonAvailable(): void
    {
        // GET dog index without authentication
        $this->get('/dogs/index');

        // Should render successfully
        $this->assertResponseOk();

        // Iterate through returned dogs and verify none are adopted or retired
        $dogs = $this->viewVariable('dogs');
        foreach ($dogs as $dog) {
            $this->assertEquals(0, $dog->adopted, "Dog {$dog->name} should not be adopted in public view");
            $this->assertEquals(0, $dog->retired, "Dog {$dog->name} should not be retired in public view");
        }
    }

    // ========================================================================
    // VIEW METHOD TESTS - Context-aware dog viewing
    // ========================================================================

    /**
     * Test view as unauthenticated user
     *
     * Public users can view dog details without authentication
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewAsUnauthenticatedUser(): void
    {
        // GET dog view page without authentication (Buddy - ID 1)
        $this->get('/dogs/view/1');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(1, $dog->id);
        $this->assertEquals('Buddy', $dog->name);

        // Should indicate user is not authenticated
        $isAuthenticated = $this->viewVariable('isAuthenticated');
        $this->assertFalse($isAuthenticated);

        // Should not have pending application (not authenticated)
        $hasPendingApplication = $this->viewVariable('hasPendingApplication');
        $this->assertFalse($hasPendingApplication);
    }

    /**
     * Test view as authenticated user without pending application
     *
     * Authenticated users see if they can apply for the dog
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewAsAuthenticatedUserWithoutPendingApplication(): void
    {
        // Log in as Jane Smith (user ID 3 - no pending application for Buddy)
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com', 'isAdmin' => 0]]);

        // GET dog view page for Buddy (ID 1 - available)
        $this->get('/dogs/view/1');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(1, $dog->id);

        // Should indicate user is authenticated
        $isAuthenticated = $this->viewVariable('isAuthenticated');
        $this->assertTrue($isAuthenticated);

        // Should NOT have pending application for this dog
        $hasPendingApplication = $this->viewVariable('hasPendingApplication');
        $this->assertFalse($hasPendingApplication);
    }

    /**
     * Test view as authenticated user WITH pending application
     *
     * Users with pending applications see that they've already applied
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewAsAuthenticatedUserWithPendingApplication(): void
    {
        // Log in as John Doe (user ID 1 - HAS pending application for Buddy)
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // GET dog view page for Buddy (ID 1)
        $this->get('/dogs/view/1');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(1, $dog->id);

        // Should indicate user is authenticated
        $isAuthenticated = $this->viewVariable('isAuthenticated');
        $this->assertTrue($isAuthenticated);

        // Should HAVE pending application for this dog (from fixture)
        $hasPendingApplication = $this->viewVariable('hasPendingApplication');
        $this->assertTrue($hasPendingApplication);
    }

    /**
     * Test view as admin user
     *
     * Admins can view dog details without pending application checks
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewAsAdminUser(): void
    {
        // Log in as Admin (user ID 2)
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // GET dog view page
        $this->get('/dogs/view/1');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);

        // Should indicate user is authenticated
        $isAuthenticated = $this->viewVariable('isAuthenticated');
        $this->assertTrue($isAuthenticated);

        // Admin should not see pending application flag (not checked for admins)
        $hasPendingApplication = $this->viewVariable('hasPendingApplication');
        $this->assertFalse($hasPendingApplication);
    }

    /**
     * Test view with invalid dog ID
     *
     * Non-existent dogs should return 404 error
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewWithInvalidDogId(): void
    {
        // GET non-existent dog (error handler catches RecordNotFoundException)
        $this->get('/dogs/view/9999');

        // Should return 404 Not Found
        $this->assertResponseCode(404);
    }

    /**
     * Test view adopted dog
     *
     * Adopted dogs can still be viewed (shows adoption status)
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewAdoptedDog(): void
    {
        // GET adopted dog (Luna - ID 2)
        $this->get('/dogs/view/2');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(2, $dog->id);
        $this->assertEquals('Luna', $dog->name);
        $this->assertEquals(1, $dog->adopted, 'Dog should be marked as adopted');
    }

    /**
     * Test view retired dog
     *
     * Retired dogs can still be viewed (shows retirement status)
     *
     * @return void
     * @uses \App\Controller\DogsController::view()
     */
    public function testViewRetiredDog(): void
    {
        // GET retired dog (Max - ID 3)
        $this->get('/dogs/view/3');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(3, $dog->id);
        $this->assertEquals('Max', $dog->name);
        $this->assertEquals(1, $dog->retired, 'Dog should be marked as retired');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\DogsController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\DogsController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
