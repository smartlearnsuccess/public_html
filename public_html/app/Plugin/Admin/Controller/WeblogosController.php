<?php

class WeblogosController extends AdminAppController
{
	public $helpers = array('Html', 'Form', 'Session');
	public $components = array('Session', 'search-master.Prg');

	public function index()
	{
		if ($this->request->is('post')) {
			try {
				$this->Weblogo->id = 1;
				if ($this->Weblogo->save($this->request->data)) {
					$this->Session->setFlash(__('Logo has been saved'), 'flash', array('alert' => 'success'));
					return $this->redirect(array('action' => 'index'));
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			}
		}
	}

	public function favicon()
	{
		if ($this->request->is('post')) {
			try {
				$this->Weblogo->id = 1;
				if ($this->Weblogo->save($this->request->data)) {
					$this->Session->setFlash(__('Favicon has been saved'), 'flash', array('alert' => 'success'));
					return $this->redirect(array('action' => 'index'));
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			}
		}
	}

	public function weblogodel()
	{
		$post = $this->Weblogo->findById(1);
		$userResult = array('Weblogo' => array('id' => 1, 'photo' => null));
		$file = APP . WEBROOT_DIR . DS . 'img' . DS . $post['Weblogo']['photo'];
		try {
			if ($post['Weblogo']['photo']) {
				if ($this->Weblogo->save($userResult,array('validate'=>false))) {
					if (file_exists($file)) {
						unlink($file);
					}
					$this->Session->setFlash(__('Logo has been deleted'), 'flash', array('alert' => 'success'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('Something wrong'), 'flash', array('alert' => 'danger'));
					return $this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__('No image found!'), 'flash', array('alert' => 'danger'));
				return $this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function favicondel()
	{
		$post = $this->Weblogo->findById(1);
		$userResult = array('Weblogo' => array('id' => 1, 'favicon' => null));
		$file = APP . WEBROOT_DIR . DS . 'img' . DS . $post['Weblogo']['favicon'];
		try {
			if ($post['Weblogo']['favicon']) {
				if ($this->Weblogo->save($userResult,array('validate'=>false))) {
					if (file_exists($file)) {
						unlink($file);
					}
					$this->Session->setFlash(__('Favicon has been deleted'), 'flash', array('alert' => 'success'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('Something wrong'), 'flash', array('alert' => 'danger'));
					return $this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__('No image found!'), 'flash', array('alert' => 'danger'));
				return $this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}
}

?>
