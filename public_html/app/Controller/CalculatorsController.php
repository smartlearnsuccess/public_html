<?php
class CalculatorsController extends AppController
{
    public $helpers = array('Html', 'Form','Session');
    public $components = array('Session');
    public $presetVars = true;
    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    public function crm_index()
    {
		$this->authenticate();
       $this->layout=null;
    }
	public function rest_index()
	{
		$this->layout=null;
		if (!$this->authenticateRest($this->request->query)) {
			echo __('Invalid Token');
			die;
		}
	}
}
