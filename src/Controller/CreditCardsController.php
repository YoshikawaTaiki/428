<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CreditCards Controller
 *
 * @property \App\Model\Table\CreditCardsTable $CreditCards
 *
 * @method \App\Model\Entity\CreditCard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CreditCardsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $creditCards = $this->paginate($this->CreditCards);

        $this->set(compact('creditCards'));
    }

    /**
     * View method
     *
     * @param string|null $id Credit Card id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $creditCard = $this->CreditCards->get($id, [
            'contain' => ['Restaurants']
        ]);

        $this->set('creditCard', $creditCard);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $creditCard = $this->CreditCards->newEntity();
        if ($this->request->is('post')) {
            $creditCard = $this->CreditCards->patchEntity($creditCard, $this->request->getData());
            if ($this->CreditCards->save($creditCard)) {
                $this->Flash->success(__('The credit card has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The credit card could not be saved. Please, try again.'));
        }
        $this->set(compact('creditCard'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Credit Card id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $creditCard = $this->CreditCards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $creditCard = $this->CreditCards->patchEntity($creditCard, $this->request->getData());
            if ($this->CreditCards->save($creditCard)) {
                $this->Flash->success(__('The credit card has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The credit card could not be saved. Please, try again.'));
        }
        $this->set(compact('creditCard'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Credit Card id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $creditCard = $this->CreditCards->get($id);
        if ($this->CreditCards->delete($creditCard)) {
            $this->Flash->success(__('The credit card has been deleted.'));
        } else {
            $this->Flash->error(__('The credit card could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
