<?php

class CouponsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Coupon.id' => 'desc'));

    public function index()
    {
        try {
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $cond = array();
            $this->Paginator->settings['conditions'] = array($this->Coupon->parseCriteria($this->Prg->parsedParams()), $cond);
            $this->set('Coupon', $this->Paginator->paginate());
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
                if (!$this->request->data['Coupon']['code']) {
                    $this->request->data['Coupon']['code'] = $this->CustomFunction->generate_rand(10);
                }
                $this->Coupon->create();
                if ($this->Coupon->save($this->request->data)) {
                    $this->Session->setFlash(__('Coupon Added Successfully'), 'flash', array('alert' => 'success'));
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
        foreach ($ids as $id) {
            $post[] = $this->Coupon->findById($id);
        }
        $this->set('Coupon', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->Coupon->id = $id;
            try {
                if ($this->Coupon->saveAll($this->request->data)) {
                    $this->Session->setFlash(__('Coupon has been updated'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', True);
        } else {
            $this->layout = 'ajax';
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }


    public function deleteall()
    {
        if ($this->request->is('post')) {
            try {
                $this->Coupon->begin('Coupon');
                foreach ($this->data['Coupon']['id'] as $key => $value) {
                    $this->Coupon->delete($value);
                }
                $this->Coupon->commit();
                $this->Session->setFlash(__('Coupon has been deleted'), 'flash', array('alert' => 'success'));
            } catch (Exception $e) {
                $this->Coupon->rollback('Coupon');
                $this->Session->setFlash(__('Used Coupon can not delete!'), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
        }
        $this->redirect(array('action' => 'index'));
    }
}
