<?php
declare(strict_types=1);

namespace App\Controller;

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
}
