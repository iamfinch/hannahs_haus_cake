<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\UserContactPreferencesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UserContactPreferencesController Test Case
 *
 * @uses \App\Controller\UserContactPreferencesController
 */
class UserContactPreferencesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * NOTE: ContactMethods and UserContactPreferences tables don't exist in test database yet.
     * These fixtures are commented out until the tables are created or migrations are run.
     * Re-enable when implementing these tests.
     *
     * @var array<string>
     */
    protected $fixtures = [
        // 'app.Countries',
        // 'app.HousingTypes',
        // 'app.States',
        // 'app.Users',
        // 'app.ContactMethods',
        // 'app.UserContactPreferences',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UserContactPreferencesController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UserContactPreferencesController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UserContactPreferencesController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UserContactPreferencesController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\UserContactPreferencesController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
