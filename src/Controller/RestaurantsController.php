<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
* Restaurants Controller
*
* @property \App\Model\Table\RestaurantsTable $Restaurants
*
* @method \App\Model\Entity\Restaurant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class RestaurantsController extends AppController
{


  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('Search.Prg', [
      'actions' => ['find']
    ]);
  }

  /**
  * Index method
  *
  * @return \Cake\Http\Response|void
  */
  public function index()
  {
    $this->paginate = [
      'contain' => ['CreditCards']
    ];
    $restaurants = $this->paginate($this->Restaurants);

    $this->set(compact('restaurants'));
  }

  /**
  * View method
  *
  * @param string|null $id Restaurant id.
  * @return \Cake\Http\Response|void
  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
  */
  public function view($id = null)
  {
    $restaurant = $this->Restaurants->get($id, [
      'contain' => ['CreditCards']
    ]);

    $this->set('restaurant', $restaurant);
  }

  /**
  * Add method
  *
  * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
  */
  public function add()
  {
    $restaurant = $this->Restaurants->newEntity();
    if ($this->request->is('post')) {
      $restaurant = $this->Restaurants->patchEntity($restaurant, $this->request->getData());
      if ($this->Restaurants->save($restaurant)) {
        $this->Flash->success(__('The restaurant has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The restaurant could not be saved. Please, try again.'));
    }
    $creditCards = $this->Restaurants->CreditCards->find('list', ['limit' => 200]);
    $this->set(compact('restaurant', 'creditCards'));
  }

  /**
  * Edit method
  *
  * @param string|null $id Restaurant id.
  * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
  * @throws \Cake\Network\Exception\NotFoundException When record not found.
  */
  public function edit($id = null)
  {
    $restaurant = $this->Restaurants->get($id, [
      'contain' => []
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $restaurant = $this->Restaurants->patchEntity($restaurant, $this->request->getData());
      if ($this->Restaurants->save($restaurant)) {
        $this->Flash->success(__('The restaurant has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The restaurant could not be saved. Please, try again.'));
    }
    $creditCards = $this->Restaurants->CreditCards->find('list', ['limit' => 200]);
    $this->set(compact('restaurant', 'creditCards'));
  }

  /**
  * Delete method
  *
  * @param string|null $id Restaurant id.
  * @return \Cake\Http\Response|null Redirects to index.
  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
  */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $restaurant = $this->Restaurants->get($id);
    if ($this->Restaurants->delete($restaurant)) {
      $this->Flash->success(__('The restaurant has been deleted.'));
    } else {
      $this->Flash->error(__('The restaurant could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  /**
  * Search method
  */
  public function search()
  {
    // $restaurant = $this->Restaurants->newEntity();
    // if ($this->request->is('post')) {
    //   $restaurant = $this->Restaurants->patchEntity($restaurant, $this->request->getData());
    // }
    // $creditCards = $this->Restaurants->CreditCards->find('list', ['limit' => 200]);
    // $this->set(compact('restaurant', 'creditCards'));
    $restaurants = [];
    if ($this->request->is('post')) {
      $find = $this->request->data['find'];
      $restaurants = $this->Restaurants->find()
      ->where(["name like " => '%' . $find . '%'])
      ->orwhere(["credit_card_id like " => '%' . $find . '%'])
      ->orwhere(["halal " => '%' . $find . '%']);
    } 
    $this->set('restaurants', $restaurants);
  }
}
