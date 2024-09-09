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
}
