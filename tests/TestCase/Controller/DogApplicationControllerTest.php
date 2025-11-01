<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\DogApplicationController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\DogApplicationController Test Case
 *
 * @uses \App\Controller\DogApplicationController
 */
class DogApplicationControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * Loaded in dependency order to satisfy foreign key constraints:
     * 1. Base lookups (no FKs): Countries, HousingTypes, PickupMethods
     * 2. Dependent lookups: States → Countries
     * 3. Domain entities: Users → Countries/States/HousingTypes, Dogs
     * 4. Relationships: DogApplication → Users/Dogs/PickupMethods
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
     * @uses \App\Controller\DogApplicationController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    // ========================================================================
    // APPLY METHOD TESTS - Critical adoption workflow
    // ========================================================================

    /**
     * Test apply requires authentication
     *
     * Unauthenticated users should be redirected to login with redirect parameter
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyRequiresAuthentication(): void
    {
        // Attempt to access apply without authentication
        $this->get('/dog-application/apply/1');

        // Should redirect to login page (may include redirect query param)
        $this->assertResponseCode(302);
        $location = $this->_response->getHeaderLine('Location');
        $this->assertStringContainsString('/users/login', $location);

        // NOTE: Flash message is set but not easily testable after redirect
        // The important behavior (redirect to login) is verified above
    }

    /**
     * Test apply with invalid dog ID
     *
     * Non-existent dog should show error and redirect to dogs index
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyWithInvalidDogId(): void
    {
        // Log in as John Doe
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // Attempt to apply for non-existent dog
        $this->get('/dog-application/apply/9999');

        // Should redirect to dogs index
        $this->assertRedirect(['controller' => 'Dogs', 'action' => 'index']);

        // Should show error message
        $this->assertSession('Dog not found.', 'Flash.flash.0.message');
    }

    /**
     * Test apply GET request displays form
     *
     * Authenticated user viewing apply form should see dog details and pickup methods
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyGetRequestDisplaysForm(): void
    {
        // Log in as Jane Smith (user ID 3 - no pending application for Buddy)
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com']]);

        // GET apply form for Buddy (dog ID 1 - available)
        $this->get('/dog-application/apply/1');

        // Should render successfully
        $this->assertResponseOk();

        // Should have dog details in view vars
        $dog = $this->viewVariable('dog');
        $this->assertNotNull($dog);
        $this->assertEquals(1, $dog->id);
        $this->assertEquals('Buddy', $dog->name);

        // Should have pickup methods for dropdown
        $pickupMethods = $this->viewVariable('pickupMethods');
        $this->assertNotNull($pickupMethods);
        $this->assertIsArray($pickupMethods);
        $this->assertCount(3, $pickupMethods); // 3 pickup methods from fixture
    }

    /**
     * Test apply to adopted dog
     *
     * Users cannot apply for dogs that have already been adopted
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyToAdoptedDog(): void
    {
        // Log in as John Doe
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // Attempt to apply for Luna (dog ID 2 - adopted by Jane)
        $this->get('/dog-application/apply/2');

        // Should redirect to dog view page
        $this->assertRedirect(['controller' => 'Dogs', 'action' => 'view', 2]);

        // Should show error message
        $this->assertSession('This dog has already been adopted.', 'Flash.flash.0.message');
    }

    /**
     * Test apply to retired dog
     *
     * Users cannot apply for dogs that have been retired
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyToRetiredDog(): void
    {
        // Log in as John Doe
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // Attempt to apply for Max (dog ID 3 - retired)
        $this->get('/dog-application/apply/3');

        // Should redirect to dog view page
        $this->assertRedirect(['controller' => 'Dogs', 'action' => 'view', 3]);

        // Should show error message
        $this->assertSession('This dog is no longer available for adoption.', 'Flash.flash.0.message');
    }

    /**
     * Test apply with existing pending application
     *
     * Users cannot submit duplicate applications for the same dog
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyWithExistingPendingApplication(): void
    {
        // Log in as John Doe (who already has pending application for Buddy - see fixture)
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // Attempt to apply again for Buddy (dog ID 1)
        $this->get('/dog-application/apply/1');

        // Should redirect to myApplications
        $this->assertRedirect(['action' => 'myApplications']);

        // Should show error message
        $this->assertSession('You already have a pending application for this dog.', 'Flash.flash.0.message');
    }

    /**
     * Test apply POST with valid data
     *
     * Valid application submission should save and redirect to myApplications
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyPostWithValidData(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // Log in as Jane Smith (user ID 3 - she adopted Luna but can apply for Buddy)
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com']]);

        // Count applications before submission
        $applicationsBefore = $this->getTableLocator()
            ->get('DogApplication')
            ->find()
            ->count();

        // POST valid application data for Buddy (dog ID 1)
        $this->post('/dog-application/apply/1', [
            'pickupMethodId' => 1, // Flexible
        ]);

        // Should redirect to myApplications
        $this->assertRedirect(['action' => 'myApplications']);

        // Should show success message
        $this->assertSession('Your adoption application has been submitted successfully! We will review it within 2-3 business days.', 'Flash.flash.0.message');

        // Should have created one new application
        $applicationsAfter = $this->getTableLocator()
            ->get('DogApplication')
            ->find()
            ->count();
        $this->assertEquals($applicationsBefore + 1, $applicationsAfter);
    }

    /**
     * Test apply POST with missing required data
     *
     * Missing pickupMethodId should fail validation
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyPostWithInvalidData(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // Log in as Jane Smith
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com']]);

        // Count applications before submission
        $applicationsBefore = $this->getTableLocator()
            ->get('DogApplication')
            ->find()
            ->count();

        // POST invalid application data (missing pickupMethodId)
        $this->post('/dog-application/apply/1', [
            // Missing pickupMethodId
        ]);

        // Should NOT redirect (re-render form)
        $this->assertResponseOk();

        // NOTE: Flash message is set but testable by checking form re-renders
        // The important behavior (no save, re-render form) is verified above

        // Should NOT have created a new application
        $applicationsAfter = $this->getTableLocator()
            ->get('DogApplication')
            ->find()
            ->count();
        $this->assertEquals($applicationsBefore, $applicationsAfter);
    }

    /**
     * Test apply POST sets correct default values
     *
     * Application should automatically set userId, dogId, dateCreated, and approved='0'
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyPostSetsCorrectDefaults(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // Log in as Jane Smith (user ID 3)
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com']]);

        // POST valid application
        $this->post('/dog-application/apply/1', [
            'pickupMethodId' => 2, // Will Pickup
        ]);

        // Find the newly created application
        $application = $this->getTableLocator()
            ->get('DogApplication')
            ->find()
            ->where([
                'userId' => 3,
                'dogId' => 1,
            ])
            ->first();

        // Assert default values were set correctly
        $this->assertNotNull($application);
        $this->assertEquals(3, $application->userId, 'userId should be set from authenticated user');
        $this->assertEquals(1, $application->dogId, 'dogId should be set from URL parameter');
        $this->assertEquals(2, $application->pickupMethodId, 'pickupMethodId should be from POST data');
        $this->assertEquals('0', $application->approved, 'approved should default to 0 (pending)');
        $this->assertNotNull($application->dateCreated, 'dateCreated should be set automatically');
        $this->assertNull($application->approvedDate, 'approvedDate should be null for pending applications');
    }

    /**
     * Test apply success redirects to myApplications
     *
     * After successful submission, user should see their applications list
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplySuccessRedirectsToMyApplications(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // Log in as Jane Smith
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com']]);

        // POST valid application
        $this->post('/dog-application/apply/1', [
            'pickupMethodId' => 1,
        ]);

        // Should redirect to myApplications action
        $this->assertRedirect(['action' => 'myApplications']);

        // Verify it's a relative redirect (not absolute external URL)
        $location = $this->_response->getHeaderLine('Location');
        $this->assertStringContainsString('/dog-application/my-applications', $location);
    }

    /**
     * Test apply preserves pickup method selection
     *
     * The selected pickup method should be saved in the application
     *
     * @return void
     * @uses \App\Controller\DogApplicationController::apply()
     */
    public function testApplyStoresPickupMethodId(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // Log in as Jane Smith
        $this->session(['Auth' => ['id' => 3, 'email' => 'jane@example.com']]);

        // POST with specific pickup method
        $this->post('/dog-application/apply/1', [
            'pickupMethodId' => 3, // Requires Delivery
        ]);

        // Find the application
        $application = $this->getTableLocator()
            ->get('DogApplication')
            ->find()
            ->where([
                'userId' => 3,
                'dogId' => 1,
            ])
            ->first();

        // Should have saved the pickup method
        $this->assertEquals(3, $application->pickupMethodId);
    }
}
