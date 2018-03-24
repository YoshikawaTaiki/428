<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use RuntimeException;
use Cake\ORM\TableRegistry;
use \Symfony\Component\Finder\Finder;

/**
* Articles Controller
*
* @property \App\Model\Table\ArticlesTable $Articles
*
* @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class ArticlesController extends AppController
{

    /**
    * Index method
    *
    * @return \Cake\Http\Response|void
    */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));
    }

    /**
    * View method
    *
    * @param string|null $id Article id.
    * @return \Cake\Http\Response|void
    * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
    public function view($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => ['Users']
        ]);

        // Usersの'id'とMachinesの'user_id'が等しいか
        if($this->Auth->user('id') === $article['user_id']){
            $this->set('article', $article);
            $this->set('_serialize', ['article']);
        } else {// ユーザーIDが違う場合
            $this->Flash->error(__('ユーザーIDが違います。同じユーザーIDのみ視聴できます。'));
            return $this->redirect(['action' => 'index']);
        }
    }

        /**
        * Add method
        *
        * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
        */
        public function add()
        {
            $article = $this->Articles->newEntity();
            if ($this->request->is('post')) {
                $article = $this->Articles->patchEntity($article, $this->request->getData());
                $article['user_id'] = $this->Auth->user('id');
                if ($this->Articles->save($article)) {
                    $this->Flash->success(__('The article has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The article could not be saved. Please, try again.'));
            }
            $users = $this->Articles->Users->find('list', ['limit' => 200]);
            $this->set(compact('article', 'users'));
        }

        /**
        * Edit method
        *
        * @param string|null $id Article id.
        * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
        * @throws \Cake\Network\Exception\NotFoundException When record not found.
        */
        public function edit($id = null)
        {
            $article = $this->Articles->get($id, [
                'contain' => []
                ]);
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $article = $this->Articles->patchEntity($article, $this->request->getData());
                    if ($this->Articles->save($article)) {
                        $this->Flash->success(__('The article has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The article could not be saved. Please, try again.'));
                }
                $users = $this->Articles->Users->find('list', ['limit' => 200]);
                $this->set(compact('article', 'users'));
            }

            /**
            * Delete method
            *
            * @param string|null $id Article id.
            * @return \Cake\Http\Response|null Redirects to index.
            * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
            */
            public function delete($id = null)
            {
                $this->request->allowMethod(['post', 'delete']);
                $article = $this->Articles->get($id);
                if ($this->Articles->delete($article)) {
                    $this->Flash->success(__('The article has been deleted.'));
                } else {
                    $this->Flash->error(__('The article could not be deleted. Please, try again.'));
                }

                return $this->redirect(['action' => 'index']);
            }
        }
