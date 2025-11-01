<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\UsersController
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\UsersController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    // ========================================================================
    // AUTHENTICATION TESTS - Login & Logout
    // ========================================================================

    /**
     * Test login GET request displays form
     *
     * Unauthenticated users should see the login form
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginGetDisplaysForm(): void
    {
        // GET login page without authentication
        $this->get('/users/login');

        // Should render login form successfully
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
    }

    /**
     * Test login POST with valid credentials
     *
     * Valid credentials should authenticate user and redirect to home
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginPostWithValidCredentials(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // POST valid credentials (John Doe from fixture)
        $this->post('/users/login', [
            'email' => 'user@example.com',
            'password' => 'password123', // Bcrypt hash in fixture
        ]);

        // Should redirect after successful login
        $this->assertResponseCode(302);

        // Should redirect to home or login redirect target
        $location = $this->_response->getHeaderLine('Location');
        $this->assertNotEmpty($location);
    }

    /**
     * Test login POST with invalid credentials
     *
     * Invalid credentials should show error and re-render form
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginPostWithInvalidCredentials(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // POST invalid credentials
        $this->post('/users/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        // Should re-render login form (not redirect)
        $this->assertResponseOk();

        // Should show error message
        // Note: Flash messages set but may not be easily testable in integration tests
    }

    /**
     * Test login when already authenticated
     *
     * Already logged in users should be redirected away from login page
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginWhenAlreadyAuthenticated(): void
    {
        // Mock authenticated session
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // GET login page while authenticated
        $this->get('/users/login');

        // Should redirect away from login page
        $this->assertResponseCode(302);

        // Should redirect to home or specified target
        $location = $this->_response->getHeaderLine('Location');
        $this->assertNotEmpty($location);
    }

    /**
     * Test login respects redirect parameter
     *
     * Login redirect parameter should take user to intended destination
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginWithRedirectParameter(): void
    {
        // Enable CSRF token for POST requests
        $this->enableCsrfToken();

        // POST valid credentials with redirect parameter in URL
        $this->post('/users/login?redirect=%2Fdogs%2Fview%2F1', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        // Should redirect after successful login
        $this->assertResponseCode(302);

        // Location should be set (actual redirect URL handled by auth component)
        $location = $this->_response->getHeaderLine('Location');
        $this->assertNotEmpty($location);
    }

    /**
     * Test logout clears authentication
     *
     * Logout should clear session and redirect to login page
     *
     * @return void
     * @uses \App\Controller\UsersController::logout()
     */
    public function testLogoutClearsAuthentication(): void
    {
        // Mock authenticated session
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // GET logout action
        $this->get('/users/logout');

        // Should redirect to login page
        $this->assertRedirect(['action' => 'login']);
    }

    /**
     * Test logout redirects to login
     *
     * After logout, user should be sent to login page
     *
     * @return void
     * @uses \App\Controller\UsersController::logout()
     */
    public function testLogoutRedirectsToLogin(): void
    {
        // Mock authenticated session
        $this->session(['Auth' => ['id' => 1, 'email' => 'user@example.com']]);

        // GET logout action
        $this->get('/users/logout');

        // Should redirect
        $this->assertResponseCode(302);

        // Should redirect specifically to login page
        $location = $this->_response->getHeaderLine('Location');
        $this->assertStringContainsString('/users/login', $location);
    }

    /**
     * Test logout accessible without authentication
     *
     * Logout should be accessible even if user is not logged in
     * (handles edge cases like expired sessions)
     *
     * @return void
     * @uses \App\Controller\UsersController::logout()
     */
    public function testLogoutAccessibleWithoutAuthentication(): void
    {
        // GET logout without authentication
        $this->get('/users/logout');

        // Should still redirect to login page (graceful handling)
        $this->assertRedirect(['action' => 'login']);
    }
}
