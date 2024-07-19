<?php

class LanguagesController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Language.id' => 'desc'));

    public function index()
    {
        try {
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['conditions'] = $this->Language->parseCriteria($this->Prg->parsedParams());
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $this->set('Language', $this->Paginator->paginate());
            if ($this->request->is('ajax')) {
                $this->render('index', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function add()
    {
        if ($this->request->is('post')) {

            try {
                $this->Language->create();
                if ($this->Language->save($this->request->data)) {
                    $this->Session->setFlash(__('Question Language has been saved'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'add'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
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
            $post[] = $this->Language->findByid($id);
        }
        $this->set('Language', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->Language->id = $id;
            try {
                if ($this->Language->saveAll($this->request->data)) {
                    $this->Session->setFlash(__('Question Language has been saved'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'index'));
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

    public function deleteall()
    {
        try {
            if ($this->request->is('post')) {
                foreach ($this->data['Language']['id'] as $key => $value) {
                    if ($value > 0) {
                        if ($value != 1) {
                            $this->Language->delete($value);
                        }
                    }
                }
                $this->Session->setFlash(__('Question Language has been deleted'), 'flash', array('alert' => 'success'));
            }
            $this->redirect(array('action' => 'index'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Please delete related record first.'), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }
}
