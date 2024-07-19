<?php

class PackagesController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Package.id' => 'desc'));

    public function index()
    {
        try {
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $cond = array();
            $this->Paginator->settings['conditions'] = array($this->Package->parseCriteria($this->Prg->parsedParams()), $cond);
            $this->set('Package', $this->Paginator->paginate());
            if ($this->request->is('ajax')) {
                $this->render('index', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function add()
    {
        $this->loadModel('Exam');
        $this->set('exam', $this->Package->Exam->find('list', array('order' => array('Exam.name' => 'asc'))));
        if ($this->request->is('post')) {
            try {
                $this->Package->create();
                if ($this->Package->save($this->request->data)) {
                    $this->Session->setFlash(__('Package Added Successfully'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'add'));
                }

            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
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
        foreach ($ids as $k => $id) {
            $k++;
            $post[$k] = $this->Package->findById($id);
        }
        $this->set('Package', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('exam', $this->Package->Exam->find('list', array('order' => array('Exam.name' => 'asc'))));
        if ($this->request->is(array('post', 'put'))) {
            $this->Package->id = $id;
            try {
                if ($this->Package->saveAll($this->request->data)) {
                    $this->Session->setFlash(__('Package has been updated'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', True);
        } else {
            $this->layout = 'tinymce';
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function view($id = null)
    {
        try {
            $this->layout = null;
            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            $post = $this->Package->findById($id);
            if (!$post) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            if (strlen($post['Package']['photo']) > 0)
                $viewImg = 'package_thumb/' . $post['Package']['photo'];
            else {
                $viewImg = 'nia.png';
            }
            $this->set('post', $post);
            $this->set('viewImg', $viewImg);
            $this->set('id', $id);
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function deleteall()
    {
        if ($this->request->is('post')) {
            try {
                $this->Package->begin('Package');
                foreach ($this->data['Package']['id'] as $key => $value) {
                    $this->Package->delete($value);
                }
                $this->Package->commit();
                $this->Session->setFlash(__('Package has been deleted'), 'flash', array('alert' => 'success'));
            } catch (Exception $e) {
                $this->Package->rollback('Package');
                $this->Session->setFlash(__('Purchased Package can not delete!'), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
        }
        $this->redirect(array('action' => 'index'));
    }
}
