<?php

class PaymentSettingsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');

    public function index()
    {
        try {
            $post = $this->PaymentSetting->find('all');
            if ($this->request->is('post')) {
                $post = $this->PaymentSetting->findByType($this->request->data['PaymentSetting']['type']);
                $this->PaymentSetting->id = $post['PaymentSetting']['id'];
                try {
                    if ($this->PaymentSetting->save($this->request->data)) {
                        $this->Session->setFlash(__('Payment Setting has been saved'), 'flash', array('alert' => 'success'));
                        return $this->redirect(array('action' => 'index'));
                    }
                } catch (Exception $e) {
                    $this->Session->setFlash(__('Setting Problem'), 'flash', array('alert' => 'danger'));
                    return $this->redirect(array('action' => 'index'));
                }
            }
            if (!$this->request->data) {
                $this->set('post', $post);
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }
}