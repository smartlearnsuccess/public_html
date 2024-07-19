<?php

class PackagesController extends AppController
{
    public $helpers = array('Paginator', 'Js' => array('Jquery'));
    public $components = array('Paginator', 'RequestHandler');
    var $paginate = array('page' => 1, 'order' => array('Package.id' => 'desc'));

    public function index()
    {
        $showPackageTypeArr = array();
        if ($this->showPackageType == 0) {
            $showPackageTypeArr = array('Package.package_type <>' => 'F');
        }
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings['conditions'] = array('Package.status' => 'Active', $showPackageTypeArr);
        $this->Paginator->settings['limit'] = $this->pageLimit;
        $this->Paginator->settings['maxLimit'] = $this->maxLimit;
        $this->set('Package', $this->Paginator->paginate());
        if ($this->request->is('ajax')) {
            $this->render('index', 'ajax'); // View, Layout
        }

    }

    public function crm_index()
    {
        return $this->redirect(array('crm' => false, 'controller' => 'Packages', 'action' => 'index'));
    }

    public function view($id)
    {
        $this->layout = null;
        $this->set('post', $this->Package->findByIdAndStatus($id, 'Active'));
    }

    public function rest_index()
    {
        $showPackageTypeArr = array();
        if ($this->showPackageType == 0) {
            $showPackageTypeArr = array('Package.package_type <>' => 'F');
        }
        try {
            $response = $this->Package->find('all', array('conditions' => array('Package.status' => 'Active', $showPackageTypeArr), 'order' => array('Package.id' => 'desc')));
            $status = true;
            $message = __('Package fetch successfully');
            $currency = $this->currencyArr['Currency']['short'];
        } catch (Exception $e) {
            $message = ($e->getMessage());
        }
        $this->set(compact('status', 'message', 'currency', 'response'));
        $this->set('_serialize', array('status', 'message', 'currency', 'response'));
    }

    public function singleproduct($id)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $showPackageTypeArr = array();
        if ($this->showPackageType == 0) {
            $showPackageTypeArr = array('Package.package_type <>' => 'F');
        }
        $post = $this->Package->find('first', array('conditions' => array('Package.id' => $id, 'Package.status' => 'Active', $showPackageTypeArr)));
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('post', $post);
        $response = $this->Package->find('all', array('conditions' => array('Package.status' => 'Active', $showPackageTypeArr), 'order' => array('Package.id' => 'ASC'),'limit'=>12));
        $this->set('Packages', $response);
    }
    public function startnow($id)
    {
        $this->autoRender=false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $post = $this->Package->find('first', array('conditions' => array('Package.id' => $id, 'Package.status' => 'Active', 'Package.package_type'=>'F')));
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        if (!$this->Session->check('Student')) {
            $this->Session->write('FreeCheckoutPackage', '1');
        }
        $this->redirect(array('crm'=>true,'controller'=>'Exams','action' => 'free'));

    }

}
