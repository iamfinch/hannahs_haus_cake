<?php
declare(strict_types=1);

namespace App\Controller;

use App\Mailer\ApplicationMailer;
use Cake\Log\Log;

/**
 * DogApplication Controller
 *
 * @property \App\Model\Table\DogApplicationTable $DogApplication
 * @method \App\Model\Entity\DogApplication[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DogApplicationController extends AppController
{
    /**
     * Before filter callback
     *
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // Skip authorization for user-accessible actions
        $publicActions = ['apply', 'myApplications', 'view'];
        if (in_array($this->request->getParam('action'), $publicActions)) {
            $this->Authorization->skipAuthorization();
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $dogApplication = $this->paginate($this->DogApplication);

        $this->set(compact('dogApplication'));
    }

    /**
     * View method
     *
     * @param string|null $id Dog Application id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dogApplication = $this->DogApplication->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('dogApplication'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dogApplication = $this->DogApplication->newEmptyEntity();
        if ($this->request->is('post')) {
            $dogApplication = $this->DogApplication->patchEntity($dogApplication, $this->request->getData());
            if ($this->DogApplication->save($dogApplication)) {
                $this->Flash->success(__('The dog application has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dog application could not be saved. Please, try again.'));
        }
        $this->set(compact('dogApplication'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dog Application id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dogApplication = $this->DogApplication->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dogApplication = $this->DogApplication->patchEntity($dogApplication, $this->request->getData());
            if ($this->DogApplication->save($dogApplication)) {
                $this->Flash->success(__('The dog application has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dog application could not be saved. Please, try again.'));
        }
        $this->set(compact('dogApplication'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dog Application id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dogApplication = $this->DogApplication->get($id);
        if ($this->DogApplication->delete($dogApplication)) {
            $this->Flash->success(__('The dog application has been deleted.'));
        } else {
            $this->Flash->error(__('The dog application could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * My Applications method - View user's own applications
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function myApplications()
    {
        // Must be authenticated
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            $this->Flash->error(__('You must be logged in to view your applications.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $userId = $identity->getIdentifier();

        // Get all applications for this user with associated dog and pickup method data
        $applications = $this->DogApplication
            ->find('byUser', ['userId' => $userId])
            ->contain(['Dogs', 'PickupMethods'])
            ->order(['DogApplication.dateCreated' => 'DESC'])
            ->all();

        $this->set(compact('applications'));
    }

    /**
     * Apply method - Submit adoption application for a specific dog
     *
     * @param string|null $dogId Dog id.
     * @return \Cake\Http\Response|null|void Redirects on successful application, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When dog not found.
     */
    public function apply($dogId = null)
    {
        // Must be authenticated
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            $this->Flash->error(__('You must be logged in to apply for adoption.'));
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
                '?' => ['redirect' => $this->request->getRequestTarget()]
            ]);
        }

        $userId = $identity->getIdentifier();

        // Load the dog
        $dogsTable = $this->fetchTable('Dogs');
        try {
            $dog = $dogsTable->get($dogId);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->Flash->error(__('Dog not found.'));
            return $this->redirect(['controller' => 'Dogs', 'action' => 'index']);
        }

        // Check if dog is available
        if ($dog->adopted) {
            $this->Flash->error(__('This dog has already been adopted.'));
            return $this->redirect(['controller' => 'Dogs', 'action' => 'view', $dogId]);
        }

        if ($dog->retired) {
            $this->Flash->error(__('This dog is no longer available for adoption.'));
            return $this->redirect(['controller' => 'Dogs', 'action' => 'view', $dogId]);
        }

        // Check for existing pending application
        $existingApplication = $this->DogApplication
            ->find('pendingForDog', ['dogId' => $dogId])
            ->where(['DogApplication.userId' => $userId])
            ->first();

        if ($existingApplication) {
            $this->Flash->error(__('You already have a pending application for this dog.'));
            return $this->redirect(['action' => 'myApplications']);
        }

        // Create new application entity
        $application = $this->DogApplication->newEmptyEntity();

        if ($this->request->is('post')) {
            // Set required fields
            $data = $this->request->getData();
            $data['userId'] = $userId;
            $data['dogId'] = $dogId;
            $data['dateCreated'] = new \DateTime();
            $data['approved'] = '0'; // Pending status

            $application = $this->DogApplication->patchEntity($application, $data);

            if ($this->DogApplication->save($application)) {
                // Reload application with associations for email templates
                $application = $this->DogApplication->get($application->id, [
                    'contain' => ['Users', 'Dogs', 'PickupMethods']
                ]);

                // Send email notifications
                try {
                    // Send confirmation to applicant
                    $mailer = new ApplicationMailer();
                    $mailer->applicationSubmitted($application)->deliver();

                    // Send notification to admin
                    $mailer = new ApplicationMailer();
                    $mailer->adminNewApplication($application)->deliver();
                } catch (\Exception $e) {
                    // Log email error but don't fail the application
                    Log::error('Failed to send application emails: ' . $e->getMessage());
                }

                $this->Flash->success(__('Your adoption application has been submitted successfully! We will review it within 2-3 business days.'));
                return $this->redirect(['action' => 'myApplications']);
            }

            $this->Flash->error(__('Unable to submit your application. Please check the form and try again.'));
        }

        // Load pickup methods for the form
        $pickupMethodsTable = $this->fetchTable('PickupMethods');
        $pickupMethods = $pickupMethodsTable->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row->name . ' - ' . $row->description;
            }
        ])->toArray();

        $this->set(compact('application', 'dog', 'pickupMethods'));
    }

    /**
     * Approve method - Approve a dog adoption application
     *
     * IMPORTANT: DO NOT DEPLOY TO PRODUCTION WITHOUT PROPER REVIEW
     *
     * This action performs the following atomic operations:
     * 1. Approves the application (sets approved='1', approvedDate)
     * 2. Updates the dog as adopted (sets adopted=1, adoptedDate, userId)
     * 3. Rejects all other pending applications for the same dog
     * 4. Sends email notifications to the applicant and other applicants
     *
     * @param string|null $id Dog Application id.
     * @return \Cake\Http\Response|null Redirects to admin dashboard.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Authorization\Exception\ForbiddenException When user lacks permission.
     */
    public function approve($id = null)
    {
        $this->request->allowMethod(['post']);

        // Load application with related data
        $application = $this->DogApplication->get($id, [
            'contain' => ['Dogs', 'Users']
        ]);

        // Authorize the approve action (admin only per DogApplicationPolicy)
        $this->Authorization->authorize($application, 'approve');

        // Use database transaction for atomic operations
        $connection = $this->DogApplication->getConnection();

        try {
            $connection->begin();

            // 1. Update application status
            $application->approved = '1';
            $application->approvedDate = new \DateTime();

            if (!$this->DogApplication->save($application)) {
                throw new \RuntimeException('Failed to update application status');
            }

            // 2. Update dog as adopted
            $dogsTable = $this->fetchTable('Dogs');
            $dog = $dogsTable->get($application->dogId);
            $dog->adopted = 1;
            $dog->adoptedDate = new \DateTime();
            $dog->userId = $application->userId;

            if (!$dogsTable->save($dog)) {
                throw new \RuntimeException('Failed to update dog adoption status');
            }

            // 3. Reject all other pending applications for this dog
            $otherApplications = $this->DogApplication
                ->find('pendingForDog', ['dogId' => $application->dogId])
                ->where(['DogApplication.id !=' => $id])
                ->all();

            foreach ($otherApplications as $otherApp) {
                $otherApp->approved = '-1';
                $otherApp->approvedDate = new \DateTime();
                if (!$this->DogApplication->save($otherApp)) {
                    throw new \RuntimeException('Failed to reject competing application');
                }
            }

            // Commit transaction
            $connection->commit();

            // 4. Send email notifications
            try {
                // Reload application with all associations for email templates
                $application = $this->DogApplication->get($application->id, [
                    'contain' => ['Users', 'Dogs', 'PickupMethods']
                ]);

                // Send approval email to successful applicant
                $mailer = new ApplicationMailer();
                $mailer->applicationApproved($application)->deliver();

                // Send rejection emails to competing applicants
                foreach ($otherApplications as $otherApp) {
                    $otherApp = $this->DogApplication->get($otherApp->id, [
                        'contain' => ['Users', 'Dogs']
                    ]);
                    $mailer = new ApplicationMailer();
                    $mailer->applicationRejected($otherApp)->deliver();
                }
            } catch (\Exception $e) {
                // Log email error but don't fail the approval
                Log::error('Failed to send approval/rejection emails: ' . $e->getMessage());
            }

            $this->Flash->success(__(
                'Application approved successfully! {0} will be notified.',
                $application->user->fname . ' ' . $application->user->lname
            ));

        } catch (\Exception $e) {
            // Rollback transaction on any error
            $connection->rollback();

            $this->Flash->error(__(
                'Failed to approve application: {0}',
                $e->getMessage()
            ));

            return $this->redirect(['action' => 'adminDashboard']);
        }

        return $this->redirect(['action' => 'adminDashboard']);
    }

    /**
     * Reject method - Reject a dog adoption application
     *
     * IMPORTANT: DO NOT DEPLOY TO PRODUCTION WITHOUT PROPER REVIEW
     *
     * This action updates the application status to rejected and sends
     * an email notification to the applicant.
     *
     * @param string|null $id Dog Application id.
     * @return \Cake\Http\Response|null Redirects to admin dashboard.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Authorization\Exception\ForbiddenException When user lacks permission.
     */
    public function reject($id = null)
    {
        $this->request->allowMethod(['post']);

        // Load application with related data
        $application = $this->DogApplication->get($id, [
            'contain' => ['Dogs', 'Users']
        ]);

        // Authorize the reject action (admin only per DogApplicationPolicy)
        $this->Authorization->authorize($application, 'reject');

        // Check if application is already approved (cannot reject approved applications)
        if ($application->approved === '1') {
            $this->Flash->error(__('Cannot reject an already approved application.'));
            return $this->redirect(['action' => 'adminDashboard']);
        }

        // Update application status
        $application->approved = '-1';
        $application->approvedDate = new \DateTime();

        if ($this->DogApplication->save($application)) {
            // Send email notification
            try {
                // Reload application with associations for email template
                $application = $this->DogApplication->get($application->id, [
                    'contain' => ['Users', 'Dogs']
                ]);

                $mailer = new ApplicationMailer();
                $mailer->applicationRejected($application)->deliver();
            } catch (\Exception $e) {
                // Log email error but don't fail the rejection
                Log::error('Failed to send rejection email: ' . $e->getMessage());
            }

            $this->Flash->success(__(
                'Application rejected. {0} will be notified.',
                $application->user->fname . ' ' . $application->user->lname
            ));
        } else {
            $this->Flash->error(__('Failed to reject application. Please try again.'));
        }

        return $this->redirect(['action' => 'adminDashboard']);
    }

    /**
     * Admin Dashboard method - View and manage all adoption applications
     *
     * IMPORTANT: DO NOT DEPLOY TO PRODUCTION WITHOUT PROPER REVIEW
     *
     * Displays pending applications for review, with statistics on all applications.
     * Admin can approve or reject applications from this dashboard.
     *
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Authorization\Exception\ForbiddenException When user is not admin.
     */
    public function adminDashboard()
    {
        // Skip authorization check - we manually verify admin below
        $this->Authorization->skipAuthorization();

        // Must be authenticated as admin
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            $this->Flash->error(__('You must be logged in to access the admin dashboard.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Check if user is admin (authorization)
        if (!$identity->get('isAdmin')) {
            $this->Flash->error(__('You do not have permission to access the admin dashboard.'));
            return $this->redirect(['controller' => 'Dogs', 'action' => 'index']);
        }

        // Calculate statistics
        $totalPending = $this->DogApplication
            ->find()
            ->where(['approved' => '0'])
            ->count();

        $totalApproved = $this->DogApplication
            ->find()
            ->where(['approved' => '1'])
            ->count();

        $totalRejected = $this->DogApplication
            ->find()
            ->where(['approved' => '-1'])
            ->count();

        // Get pending applications with full related data
        $pendingApplications = $this->DogApplication
            ->find()
            ->where(['DogApplication.approved' => '0'])
            ->contain([
                'Dogs',
                'Users' => function ($q) {
                    return $q->select([
                        'Users.id',
                        'Users.email',
                        'Users.phoneNumber',
                        'Users.fname',
                        'Users.lname',
                        'Users.address1',
                        'Users.address2',
                        'Users.zipcode',
                        'Users.hasChildren',
                        'Users.everOwnedDogs',
                        'Users.primaryCareTaker',
                        'Users.housingTypeId',
                        'Users.stateId',
                        'Users.countryId',
                    ]);
                },
                'Users.HousingTypes',
                'Users.States',
                'Users.Countries',
                'PickupMethods',
            ])
            ->order(['DogApplication.dateCreated' => 'DESC'])
            ->all();

        $this->set(compact(
            'pendingApplications',
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }
}
