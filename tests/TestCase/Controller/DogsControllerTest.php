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

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\DogsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
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
