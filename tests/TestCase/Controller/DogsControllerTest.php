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

    // ========================================================================
    // ADD METHOD TESTS - Admin-only dog creation
    // ========================================================================

    /**
     * Test add GET request renders form (admin only)
     *
     * @return void
     * @uses \App\Controller\DogsController::add()
     */
    public function testAddGetAsAdmin(): void
    {
        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // GET add form
        $this->get('/dogs/add');

        // Should render successfully
        $this->assertResponseOk();

        // Verify dog entity is set
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog, 'Dog entity should be available in view');
    }

    /**
     * Test add GET as non-admin throws ForbiddenException
     *
     * @return void
     * @uses \App\Controller\DogsController::add()
     */
    public function testAddGetAsRegularUser(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Expect ForbiddenException
        $this->expectException(\Authorization\Exception\ForbiddenException::class);

        // Login as regular user
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // Attempt to access add form
        $this->get('/dogs/add');
    }

    /**
     * Test add POST with valid data creates dog
     *
     * @return void
     * @uses \App\Controller\DogsController::add()
     */
    public function testAddPostWithValidData(): void
    {
        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Get initial dog count
        $dogs = $this->getTableLocator()->get('Dogs');
        $countBefore = $dogs->find()->count();

        // Submit valid dog data
        $this->post('/dogs/add', [
            'name' => 'Rex',
            'dateBorn' => '2020-01-15 00:00:00',
            'color' => 'Black and Tan',
            'retired' => 0,
            'adopted' => 0,
        ]);

        // Should redirect to index on success
        $this->assertRedirect(['action' => 'index']);

        // Should show success message
        $this->assertSession('The dog has been saved.', 'Flash.flash.0.message');

        // Verify dog was created in database
        $countAfter = $dogs->find()->count();
        $this->assertEquals($countBefore + 1, $countAfter, 'Dog count should increase by 1');

        // Verify new dog exists with correct name
        $newDog = $dogs->findByName('Rex')->first();
        $this->assertNotNull($newDog, 'New dog should exist in database');
        $this->assertEquals('Black and Tan', $newDog->color);
    }

    /**
     * Test add POST with missing required fields shows validation errors
     *
     * @return void
     * @uses \App\Controller\DogsController::add()
     */
    public function testAddPostWithMissingRequiredFields(): void
    {
        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Get initial dog count
        $dogs = $this->getTableLocator()->get('Dogs');
        $countBefore = $dogs->find()->count();

        // Submit with missing required fields (name)
        $this->post('/dogs/add', [
            'color' => 'Brown',
            // Missing: name, dateBorn
        ]);

        // Should re-render form with errors
        $this->assertResponseOk();

        // Verify dog was NOT created
        $countAfter = $dogs->find()->count();
        $this->assertEquals($countBefore, $countAfter, 'Dog count should not change');
    }

    /**
     * Test add POST as regular user throws ForbiddenException
     *
     * @return void
     * @uses \App\Controller\DogsController::add()
     */
    public function testAddPostAsRegularUser(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Expect ForbiddenException
        $this->expectException(\Authorization\Exception\ForbiddenException::class);

        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as regular user
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // Attempt to create dog
        $this->post('/dogs/add', [
            'name' => 'Unauthorized Dog',
            'dateBorn' => '2020-01-01 00:00:00',
            'color' => 'Mixed',
        ]);
    }

    // ========================================================================
    // EDIT METHOD TESTS - Admin-only dog editing
    // ========================================================================

    /**
     * Test edit GET as admin renders form
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEditGetAsAdmin(): void
    {
        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // GET edit form for Buddy (ID 1)
        $this->get('/dogs/edit/1');

        // Should render successfully
        $this->assertResponseOk();

        // Verify correct dog loaded
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(1, $dog->id);
        $this->assertEquals('Buddy', $dog->name);
    }

    /**
     * Test edit GET as regular user throws ForbiddenException
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEditGetAsRegularUser(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Expect ForbiddenException from authorization failure
        $this->expectException(\Authorization\Exception\ForbiddenException::class);

        // Login as regular user
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // Attempt to edit dog
        $this->get('/dogs/edit/1');
    }

    /**
     * Test edit POST with valid data updates dog
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEditPostWithValidData(): void
    {
        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Get current dog data
        $dogs = $this->getTableLocator()->get('Dogs');
        $dogBefore = $dogs->get(1);
        $this->assertEquals('Buddy', $dogBefore->name);

        // POST updated data
        $this->post('/dogs/edit/1', [
            'name' => 'Buddy Jr.',  // Changed
            'dateBorn' => '2021-03-15 00:00:00',
            'color' => 'Golden',
            'retired' => 0,
            'adopted' => 0,
        ]);

        // Should redirect to index
        $this->assertRedirect(['action' => 'index']);

        // Should show success message
        $this->assertSession('The dog has been saved.', 'Flash.flash.0.message');

        // Verify database updated
        $dogAfter = $dogs->get(1);
        $this->assertEquals('Buddy Jr.', $dogAfter->name, 'Name should be updated');
    }

    /**
     * Test edit POST with invalid data shows validation errors
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEditPostWithInvalidData(): void
    {
        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Get original name
        $dogs = $this->getTableLocator()->get('Dogs');
        $dogBefore = $dogs->get(1);
        $originalName = $dogBefore->name;

        // POST with empty name (invalid)
        $this->post('/dogs/edit/1', [
            'name' => '',  // Invalid - required field
            'dateBorn' => '2021-03-15 00:00:00',
            'color' => 'Golden',
        ]);

        // Should re-render form (not redirect)
        $this->assertResponseOk();

        // Verify database NOT updated
        $dogAfter = $dogs->get(1);
        $this->assertEquals($originalName, $dogAfter->name, 'Name should not change');
    }

    /**
     * Test edit POST as regular user throws ForbiddenException
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEditPostAsRegularUser(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Expect ForbiddenException from authorization failure
        $this->expectException(\Authorization\Exception\ForbiddenException::class);

        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as regular user
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // Attempt to edit dog
        $this->post('/dogs/edit/1', [
            'name' => 'Hacked Name',
        ]);
    }

    /**
     * Test edit GET with non-existent dog throws RecordNotFoundException
     *
     * @return void
     * @uses \App\Controller\DogsController::edit()
     */
    public function testEditGetNonExistentDog(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Expect RecordNotFoundException
        $this->expectException(\Cake\Datasource\Exception\RecordNotFoundException::class);

        // Attempt to edit non-existent dog
        $this->get('/dogs/edit/9999');
    }

    // ========================================================================
    // DELETE METHOD TESTS - Admin-only dog deletion
    // ========================================================================

    /**
     * Test delete as admin successfully deletes dog
     *
     * @return void
     * @uses \App\Controller\DogsController::delete()
     */
    public function testDeleteAsAdmin(): void
    {
        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Get initial dog count
        $dogs = $this->getTableLocator()->get('Dogs');
        $countBefore = $dogs->find()->count();
        $this->assertEquals(3, $countBefore, 'Should start with 3 fixture dogs');

        // Delete dog ID 3 (Max) as admin
        $this->post('/dogs/delete/3');

        // Should redirect to index
        $this->assertRedirect(['action' => 'index']);

        // Should show success message
        $this->assertSession('The dog has been deleted.', 'Flash.flash.0.message');

        // Verify dog deleted
        $countAfter = $dogs->find()->count();
        $this->assertEquals(2, $countAfter, 'Dog count should decrease by 1');

        // Verify specific dog no longer exists
        $this->assertFalse($dogs->exists(['id' => 3]), 'Dog ID 3 should be deleted');
    }

    /**
     * Test delete as regular user throws ForbiddenException
     *
     * @return void
     * @uses \App\Controller\DogsController::delete()
     */
    public function testDeleteAsRegularUser(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Expect ForbiddenException from authorization failure
        $this->expectException(\Authorization\Exception\ForbiddenException::class);

        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as regular user
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com', 'isAdmin' => 0]]);

        // Attempt to delete dog
        $this->post('/dogs/delete/1');
    }

    /**
     * Test delete non-existent dog throws RecordNotFoundException
     *
     * @return void
     * @uses \App\Controller\DogsController::delete()
     */
    public function testDeleteNonExistentDog(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Expect RecordNotFoundException
        $this->expectException(\Cake\Datasource\Exception\RecordNotFoundException::class);

        // Attempt to delete non-existent dog
        $this->post('/dogs/delete/9999');
    }

    /**
     * Test delete requires POST or DELETE method
     *
     * @return void
     * @uses \App\Controller\DogsController::delete()
     */
    public function testDeleteRequiresPostMethod(): void
    {
        // Disable error handler to let exception propagate
        $this->disableErrorHandlerMiddleware();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Expect MethodNotAllowedException for GET request
        $this->expectException(\Cake\Http\Exception\MethodNotAllowedException::class);

        // Attempt to delete via GET (should fail)
        $this->get('/dogs/delete/1');
    }

    /**
     * Test delete using DELETE HTTP method
     *
     * @return void
     * @uses \App\Controller\DogsController::delete()
     */
    public function testDeleteViaDeleteMethod(): void
    {
        // Enable CSRF token
        $this->enableCsrfToken();

        // Login as admin
        $this->session(['Auth' => ['id' => 2, 'email' => 'admin@hannahshaus.com', 'isAdmin' => 1]]);

        // Get initial dog count
        $dogs = $this->getTableLocator()->get('Dogs');
        $countBefore = $dogs->find()->count();

        // Delete using DELETE HTTP method
        $this->delete('/dogs/delete/3');

        // Should redirect to index
        $this->assertRedirect(['action' => 'index']);

        // Should show success message
        $this->assertSession('The dog has been deleted.', 'Flash.flash.0.message');

        // Verify dog deleted
        $countAfter = $dogs->find()->count();
        $this->assertEquals($countBefore - 1, $countAfter);
    }
}
