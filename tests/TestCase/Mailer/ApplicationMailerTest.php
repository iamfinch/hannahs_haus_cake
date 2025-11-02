<?php
declare(strict_types=1);

namespace App\Test\TestCase\Mailer;

use App\Mailer\ApplicationMailer;
use Cake\TestSuite\TestCase;

/**
 * App\Mailer\ApplicationMailer Test Case
 *
 * @uses \App\Mailer\ApplicationMailer
 */
class ApplicationMailerTest extends TestCase
{
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
     * Test applicationSubmitted email configuration
     *
     * Verifies that confirmation email is properly configured with correct
     * recipient, subject, template, and view variables
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::applicationSubmitted()
     */
    public function testApplicationSubmitted(): void
    {
        // Load application with associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1, [
            'contain' => ['Users', 'Dogs', 'PickupMethods']
        ]);

        // Create mailer and configure email
        $mailer = new ApplicationMailer();
        $result = $mailer->applicationSubmitted($application);

        // Verify mailer returned for method chaining
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Get email message for assertions
        $message = $mailer->getMessage();

        // Verify recipient is the applicant
        $to = $message->getTo();
        $this->assertArrayHasKey('user@example.com', $to, 'Email should be sent to applicant');

        // Verify subject includes dog name
        $subject = $message->getSubject();
        $this->assertStringContainsString('Buddy', $subject, 'Subject should include dog name');
        $this->assertStringContainsString('Adoption Application Received', $subject);

        // Verify view variables are set
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('application', $viewVars);
        $this->assertArrayHasKey('applicantName', $viewVars);
        $this->assertArrayHasKey('dogName', $viewVars);
        $this->assertEquals('John Doe', $viewVars['applicantName']);
        $this->assertEquals('Buddy', $viewVars['dogName']);

        // Verify template is set
        $template = $mailer->viewBuilder()->getTemplate();
        $this->assertEquals('application_submitted', $template);
    }

    /**
     * Test applicationApproved email configuration
     *
     * Verifies that approval email is properly configured with correct
     * recipient, subject, template, and view variables including pickup method
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::applicationApproved()
     */
    public function testApplicationApproved(): void
    {
        // Load application with associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1, [
            'contain' => ['Users', 'Dogs', 'PickupMethods']
        ]);

        // Create mailer and configure email
        $mailer = new ApplicationMailer();
        $result = $mailer->applicationApproved($application);

        // Verify mailer returned for method chaining
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Get email message for assertions
        $message = $mailer->getMessage();

        // Verify recipient is the applicant
        $to = $message->getTo();
        $this->assertArrayHasKey('user@example.com', $to, 'Email should be sent to approved applicant');

        // Verify subject is celebratory
        $subject = $message->getSubject();
        $this->assertStringContainsString('Approved', $subject, 'Subject should indicate approval');
        $this->assertStringContainsString('!', $subject, 'Subject should be celebratory with exclamation');

        // Verify view variables are set including pickup method
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('application', $viewVars);
        $this->assertArrayHasKey('applicantName', $viewVars);
        $this->assertArrayHasKey('dogName', $viewVars);
        $this->assertArrayHasKey('pickupMethod', $viewVars);
        $this->assertEquals('John Doe', $viewVars['applicantName']);
        $this->assertEquals('Buddy', $viewVars['dogName']);
        $this->assertEquals('Flexible', $viewVars['pickupMethod'], 'Pickup method should be included');

        // Verify template is set
        $template = $mailer->viewBuilder()->getTemplate();
        $this->assertEquals('application_approved', $template);
    }

    /**
     * Test applicationRejected email configuration
     *
     * Verifies that rejection email is properly configured with empathetic
     * messaging and correct recipient, subject, template, and view variables
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::applicationRejected()
     */
    public function testApplicationRejected(): void
    {
        // Load application with associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1, [
            'contain' => ['Users', 'Dogs']
        ]);

        // Create mailer and configure email
        $mailer = new ApplicationMailer();
        $result = $mailer->applicationRejected($application);

        // Verify mailer returned for method chaining
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Get email message for assertions
        $message = $mailer->getMessage();

        // Verify recipient is the applicant
        $to = $message->getTo();
        $this->assertArrayHasKey('user@example.com', $to, 'Email should be sent to rejected applicant');

        // Verify subject is neutral and includes dog name
        $subject = $message->getSubject();
        $this->assertStringContainsString('Buddy', $subject, 'Subject should include dog name');
        $this->assertStringContainsString('Update', $subject, 'Subject should use neutral language');
        $this->assertStringNotContainsString('Rejected', $subject, 'Subject should avoid harsh "rejected" word');

        // Verify view variables are set
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('application', $viewVars);
        $this->assertArrayHasKey('applicantName', $viewVars);
        $this->assertArrayHasKey('dogName', $viewVars);
        $this->assertEquals('John Doe', $viewVars['applicantName']);
        $this->assertEquals('Buddy', $viewVars['dogName']);

        // Verify template is set
        $template = $mailer->viewBuilder()->getTemplate();
        $this->assertEquals('application_rejected', $template);
    }

    /**
     * Test adminNewApplication email configuration
     *
     * Verifies that admin notification email is properly configured with correct
     * recipient, subject, template, and comprehensive applicant details
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::adminNewApplication()
     */
    public function testAdminNewApplication(): void
    {
        // Load application with all associations for admin review
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1, [
            'contain' => [
                'Users',
                'Users.HousingTypes',
                'Users.States',
                'Users.Countries',
                'Dogs',
                'PickupMethods'
            ]
        ]);

        // Create mailer and configure email
        $mailer = new ApplicationMailer();
        $result = $mailer->adminNewApplication($application);

        // Verify mailer returned for method chaining
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Get email message for assertions
        $message = $mailer->getMessage();

        // Verify recipient is the admin email
        $to = $message->getTo();
        $this->assertArrayHasKey('admin@hannahshaus.com', $to, 'Email should be sent to admin');

        // Verify subject indicates new application and includes dog name
        $subject = $message->getSubject();
        $this->assertStringContainsString('New', $subject, 'Subject should indicate new application');
        $this->assertStringContainsString('Buddy', $subject, 'Subject should include dog name');
        $this->assertStringContainsString('Review', $subject, 'Subject should indicate review needed');

        // Verify comprehensive view variables are set for admin
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('application', $viewVars);
        $this->assertArrayHasKey('applicantName', $viewVars);
        $this->assertArrayHasKey('dogName', $viewVars);
        $this->assertArrayHasKey('applicantEmail', $viewVars);
        $this->assertArrayHasKey('applicantPhone', $viewVars);
        $this->assertArrayHasKey('housingType', $viewVars);
        $this->assertArrayHasKey('hasChildren', $viewVars);
        $this->assertArrayHasKey('everOwnedDogs', $viewVars);
        $this->assertArrayHasKey('primaryCareTaker', $viewVars);

        // Verify applicant details
        $this->assertEquals('John Doe', $viewVars['applicantName']);
        $this->assertEquals('Buddy', $viewVars['dogName']);
        $this->assertEquals('user@example.com', $viewVars['applicantEmail']);
        $this->assertIsString($viewVars['applicantPhone']);

        // Verify qualification fields are boolean
        $this->assertIsBool($viewVars['hasChildren']);
        $this->assertIsBool($viewVars['everOwnedDogs']);
        $this->assertIsBool($viewVars['primaryCareTaker']);

        // Verify template is set
        $template = $mailer->viewBuilder()->getTemplate();
        $this->assertEquals('admin_new_application', $template);
    }

    /**
     * Test applicationSubmitted handles missing associations gracefully
     *
     * Mailer should lazy-load associations if they're not already loaded
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::applicationSubmitted()
     */
    public function testApplicationSubmittedLazyLoadsAssociations(): void
    {
        // Load application WITHOUT associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1);

        // Verify associations not loaded yet
        $this->assertFalse(isset($application->user), 'User association should not be loaded yet');

        // Create mailer - should handle lazy loading
        $mailer = new ApplicationMailer();
        $result = $mailer->applicationSubmitted($application);

        // Should succeed without throwing errors
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Verify view variables are still set correctly
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('applicantName', $viewVars);
        $this->assertEquals('John Doe', $viewVars['applicantName']);
    }

    /**
     * Test applicationApproved handles missing associations gracefully
     *
     * Mailer should lazy-load associations if they're not already loaded
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::applicationApproved()
     */
    public function testApplicationApprovedLazyLoadsAssociations(): void
    {
        // Load application WITHOUT associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1);

        // Create mailer - should handle lazy loading
        $mailer = new ApplicationMailer();
        $result = $mailer->applicationApproved($application);

        // Should succeed without throwing errors
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Verify view variables are still set correctly
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('pickupMethod', $viewVars);
        $this->assertEquals('Flexible', $viewVars['pickupMethod']);
    }

    /**
     * Test applicationRejected handles missing associations gracefully
     *
     * Mailer should lazy-load associations if they're not already loaded
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::applicationRejected()
     */
    public function testApplicationRejectedLazyLoadsAssociations(): void
    {
        // Load application WITHOUT associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1);

        // Create mailer - should handle lazy loading
        $mailer = new ApplicationMailer();
        $result = $mailer->applicationRejected($application);

        // Should succeed without throwing errors
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Verify view variables are still set correctly
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('dogName', $viewVars);
        $this->assertEquals('Buddy', $viewVars['dogName']);
    }

    /**
     * Test adminNewApplication handles missing associations gracefully
     *
     * Mailer should lazy-load comprehensive associations if not already loaded
     *
     * @return void
     * @uses \App\Mailer\ApplicationMailer::adminNewApplication()
     */
    public function testAdminNewApplicationLazyLoadsAssociations(): void
    {
        // Load application WITHOUT associations
        $applications = $this->getTableLocator()->get('DogApplication');
        $application = $applications->get(1);

        // Create mailer - should handle lazy loading
        $mailer = new ApplicationMailer();
        $result = $mailer->adminNewApplication($application);

        // Should succeed without throwing errors
        $this->assertInstanceOf(ApplicationMailer::class, $result);

        // Verify comprehensive view variables are still set correctly
        $viewVars = $mailer->viewBuilder()->getVars();
        $this->assertArrayHasKey('housingType', $viewVars);
        $this->assertArrayHasKey('hasChildren', $viewVars);
        $this->assertArrayHasKey('everOwnedDogs', $viewVars);
    }
}
