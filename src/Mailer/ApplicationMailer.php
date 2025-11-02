<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\DogApplication;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

/**
 * ApplicationMailer
 *
 * Handles all email notifications related to dog adoption applications.
 *
 * IMPORTANT: DO NOT DEPLOY TO PRODUCTION WITHOUT PROPER REVIEW
 * - Configure AWS SES credentials in config/app.php
 * - Update email transport from 'mailhog' to 'ses'
 * - Verify sender email addresses in AWS SES
 * - Test email deliverability
 * - Add unsubscribe functionality (CAN-SPAM compliance)
 */
class ApplicationMailer extends Mailer
{
    /**
     * Send confirmation email to applicant after application submission
     *
     * This email confirms receipt of the adoption application and provides
     * information about the review process timeline.
     *
     * @param \App\Model\Entity\DogApplication $application The submitted application
     * @return $this
     */
    public function applicationSubmitted(DogApplication $application): self
    {
        // Ensure related data is loaded
        if (!isset($application->user)) {
            $table = TableRegistry::getTableLocator()->get('DogApplication');
            $table->loadInto($application, ['Users', 'Dogs', 'PickupMethods']);
        }

        $this->setTo($application->user->email)
            ->setSubject("Adoption Application Received for {$application->dog->name}")
            ->setViewVars([
                'application' => $application,
                'applicantName' => $application->user->fname . ' ' . $application->user->lname,
                'dogName' => $application->dog->name,
            ]);

        $this->viewBuilder()->setTemplate('application_submitted');

        return $this;
    }

    /**
     * Send approval notification to applicant
     *
     * This email congratulates the applicant on their approval and provides
     * next steps for completing the adoption process.
     *
     * @param \App\Model\Entity\DogApplication $application The approved application
     * @return $this
     */
    public function applicationApproved(DogApplication $application): self
    {
        // Ensure related data is loaded
        if (!isset($application->user)) {
            $table = TableRegistry::getTableLocator()->get('DogApplication');
            $table->loadInto($application, ['Users', 'Dogs', 'PickupMethods']);
        }

        $this->setTo($application->user->email)
            ->setSubject("Your Adoption Application Has Been Approved!")
            ->setViewVars([
                'application' => $application,
                'applicantName' => $application->user->fname . ' ' . $application->user->lname,
                'dogName' => $application->dog->name,
                'pickupMethod' => $application->pickup_method->name,
            ]);

        $this->viewBuilder()->setTemplate('application_approved');

        return $this;
    }

    /**
     * Send rejection notification to applicant
     *
     * This email informs the applicant that their application was not approved,
     * with empathetic messaging and encouragement to apply for other dogs.
     *
     * @param \App\Model\Entity\DogApplication $application The rejected application
     * @return $this
     */
    public function applicationRejected(DogApplication $application): self
    {
        // Ensure related data is loaded
        if (!isset($application->user)) {
            $table = TableRegistry::getTableLocator()->get('DogApplication');
            $table->loadInto($application, ['Users', 'Dogs']);
        }

        $this->setTo($application->user->email)
            ->setSubject("Adoption Application Update for {$application->dog->name}")
            ->setViewVars([
                'application' => $application,
                'applicantName' => $application->user->fname . ' ' . $application->user->lname,
                'dogName' => $application->dog->name,
            ]);

        $this->viewBuilder()->setTemplate('application_rejected');

        return $this;
    }

    /**
     * Send notification to admin about new application requiring review
     *
     * This email alerts the admin team that a new adoption application has been
     * submitted and provides a summary of the applicant's information.
     *
     * @param \App\Model\Entity\DogApplication $application The new application
     * @return $this
     */
    public function adminNewApplication(DogApplication $application): self
    {
        // Ensure related data is loaded with housing info
        if (!isset($application->user)) {
            $table = TableRegistry::getTableLocator()->get('DogApplication');
            $table->loadInto($application, [
                'Users',
                'Users.HousingTypes',
                'Users.States',
                'Users.Countries',
                'Dogs',
                'PickupMethods'
            ]);
        }

        // Admin email - TODO: Move to configuration
        $adminEmail = 'admin@hannahshaus.com';

        $this->setTo($adminEmail)
            ->setSubject("New Adoption Application for Review - {$application->dog->name}")
            ->setViewVars([
                'application' => $application,
                'applicantName' => $application->user->fname . ' ' . $application->user->lname,
                'dogName' => $application->dog->name,
                'applicantEmail' => $application->user->email,
                'applicantPhone' => $application->user->phoneNumber ?? 'Not provided',
                'housingType' => $application->user->housing_type->name ?? 'Not specified',
                'hasChildren' => $application->user->hasChildren,
                'everOwnedDogs' => $application->user->everOwnedDogs,
                'primaryCareTaker' => $application->user->primaryCareTaker,
            ]);

        $this->viewBuilder()->setTemplate('admin_new_application');

        return $this;
    }
}
