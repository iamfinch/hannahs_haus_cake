<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * UserContactPreferences Controller
 *
 * @method \App\Model\Entity\UserContactPreference[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserContactPreferencesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $userContactPreferences = $this->paginate($this->UserContactPreferences);

        $this->set(compact('userContactPreferences'));
    }

    /**
     * View method
     *
     * @param string|null $id User Contact Preference id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userContactPreference = $this->UserContactPreferences->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('userContactPreference'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userContactPreference = $this->UserContactPreferences->newEmptyEntity();
        if ($this->request->is('post')) {
            $userContactPreference = $this->UserContactPreferences->patchEntity($userContactPreference, $this->request->getData());
            if ($this->UserContactPreferences->save($userContactPreference)) {
                $this->Flash->success(__('The user contact preference has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user contact preference could not be saved. Please, try again.'));
        }
        $this->set(compact('userContactPreference'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User Contact Preference id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userContactPreference = $this->UserContactPreferences->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userContactPreference = $this->UserContactPreferences->patchEntity($userContactPreference, $this->request->getData());
            if ($this->UserContactPreferences->save($userContactPreference)) {
                $this->Flash->success(__('The user contact preference has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user contact preference could not be saved. Please, try again.'));
        }
        $this->set(compact('userContactPreference'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User Contact Preference id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userContactPreference = $this->UserContactPreferences->get($id);
        if ($this->UserContactPreferences->delete($userContactPreference)) {
            $this->Flash->success(__('The user contact preference has been deleted.'));
        } else {
            $this->Flash->error(__('The user contact preference could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
