<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Dogs Controller
 *
 * @property \App\Model\Table\DogsTable $Dogs
 * @method \App\Model\Entity\Dog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DogsController extends AppController
{
    /**
     * Before filter callback
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // Allow unauthenticated access to public dog browsing
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);

        // Skip authorization for public browsing actions
        // Admin actions (add, edit, delete) will use authorization checks
        if (in_array($this->request->getParam('action'), ['index', 'view'])) {
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
        // Show only available dogs to public, all dogs to admins
        $identity = $this->Authentication->getIdentity();
        $isAdmin = $identity && $identity->get('isAdmin');

        if ($isAdmin) {
            $dogs = $this->paginate($this->Dogs);
        } else {
            $query = $this->Dogs->find('available');
            $dogs = $this->paginate($query);
        }

        $this->set(compact('dogs', 'isAdmin'));
    }

    /**
     * View method
     *
     * @param string|null $id Dog id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dog = $this->Dogs->get($id, [
            'contain' => [],
        ]);

        // Check authentication status
        $identity = $this->Authentication->getIdentity();
        $isAuthenticated = !is_null($identity);

        // Initialize pending application check
        $hasPendingApplication = false;

        // If user is authenticated (not admin), check for pending application
        if ($isAuthenticated && !$identity->get('isAdmin')) {
            $userId = $identity->getIdentifier();

            $pendingApp = $this->fetchTable('DogApplication')
                ->find('pendingForDog', ['dogId' => $dog->id])
                ->where(['DogApplication.userId' => $userId])
                ->first();

            $hasPendingApplication = !is_null($pendingApp);
        }

        $this->set(compact('dog', 'hasPendingApplication', 'isAuthenticated'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dog = $this->Dogs->newEmptyEntity();
        if ($this->request->is('post')) {
            $dog = $this->Dogs->patchEntity($dog, $this->request->getData());
            if ($this->Dogs->save($dog)) {
                $this->Flash->success(__('The dog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dog could not be saved. Please, try again.'));
        }
        $this->set(compact('dog'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dog id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dog = $this->Dogs->get($id, [
            'contain' => [],
        ]);

        // Authorization check - only admins can edit dogs
        $this->Authorization->authorize($dog);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $dog = $this->Dogs->patchEntity($dog, $this->request->getData());
            if ($this->Dogs->save($dog)) {
                $this->Flash->success(__('The dog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dog could not be saved. Please, try again.'));
        }
        $this->set(compact('dog'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dog id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dog = $this->Dogs->get($id);
        if ($this->Dogs->delete($dog)) {
            $this->Flash->success(__('The dog has been deleted.'));
        } else {
            $this->Flash->error(__('The dog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
