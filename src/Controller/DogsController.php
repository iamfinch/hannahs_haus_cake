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

        $this->set(compact('dog'));
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
