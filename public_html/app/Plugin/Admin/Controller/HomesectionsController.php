<?php

class HomesectionsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Tinymce', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Homesections.id' => 'desc'));

    public function index()
    {
        try {
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            // $cond = array();
            // $this->Paginator->settings['conditions'] = array($this->Homesections->parseCriteria($this->Prg->parsedParams()), $cond);
            $this->set('Homesections', $this->Paginator->paginate());
            if ($this->request->is('ajax')) {
                $this->render('index', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }


    public function edit($id = null)
    {

        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $ids = explode(",", $id);
        $post = array();
        foreach ($ids as $id) {
            $post[] = $this->Homesection->findById($id);
        }
        $this->set('Homesection', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            try {
                if ($this->Homesection->saveAll($this->request->data)) {
                    $this->Session->setFlash(__('Pages updated Successfully'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = 'tinymce';
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function published($id = null, $mode = null)
    {
        if (!$id || !$mode) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $post = $this->Homesection->findById($id);
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }

        $this->Homesection->id = $id;
        try {
            $published = "";
            if ($mode == "Yes") {
                $publishedValue = "Unpublished";
                $published = __('Page has been Unpublished');
            } else {
                $publishedValue = "Published";
                $published = __('Page has been Published');
            }
            $userArr = array('id' => $id, 'published' => $publishedValue);
            //$this->Homesection->unbindValidation('remove', array('link_name','parent_id','url'), true);
            if ($this->Homesection->save($userArr)) {
                $this->Session->setFlash($published, 'flash', array('alert' => 'success'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something wrong'), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function changephoto($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $post = $this->Homesection->findById($id);
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
            try {
                $this->Homesection->id = $id;
                //$this->Homesection->unbindValidation('keep', array('photo'), true);
                if ($this->Homesection->save($this->request->data)) {
                    $this->Session->setFlash(__('Photo Changed Successfully'), 'flash', array('alert' => 'success'));
                    $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = null;
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

}