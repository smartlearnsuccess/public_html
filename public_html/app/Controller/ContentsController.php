<?php

class ContentsController extends AppController
{
    public $components = array('RequestHandler');

    public function pages($id = null)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->layout = 'rest';
        }
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        $checkPost = $this->Content->findByPageUrl($id);
        if (!$checkPost) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        $this->set('contentPost', $checkPost);
        $views = $checkPost['Content']['views'];
        $views++;
        $userResult = array('id' => $checkPost['Content']['id'], 'views' => $views);
        $this->Content->save($userResult);
        $linkNameArr = explode(" ", $checkPost['Content']['page_name'], 2);
        $linkName1 = $linkNameArr['0'];
        if (count($linkNameArr) > 1)
            $linkName2 = $linkNameArr['1'];
        else
            $linkName2 = "";
        $this->set('linkName1', $linkName1);
        $this->set('linkName2', $linkName2);
        $this->set('contentId', $id);
    }
    public function rest_about()
    {
        try {
            $response = $this->Content->findByPageUrl('About-Us');
            $status = true;
            $message = __('Pages fetch successfully');
            $views = $response['Content']['views'];
            $views++;
            $userResult = array('id' => $response['Content']['id'], 'views' => $views);
            $this->Content->save($userResult);
        } catch (Exception $e) {
            $message = ($e->getMessage());
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }

    public function rest_pages()
    {
        try {
            $response = $this->Content->find('all', array('conditions' => array('Content.published' => 'Published', 'Content.page_url NOT IN ' => array('Registers', 'Login', 'Packages')), 'order' => array('Content.ordering' => 'asc')));
            $status = true;
            $message = __('Pages fetch successfully');
        } catch (Exception $e) {
            $message = ($e->getMessage());
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }
}
