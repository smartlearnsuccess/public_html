<?php

class HelpsController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        if($this->userValue){
			$this->studentId = $this->userValue['Student']['id'];
		}
    }

    public function crm_index()
    {
        $this->authenticate();
        $helpPost = $this->Help->find('all', array('conditions' => array('status' => 'Active'),
            'order' => 'id asc'));
        $this->set('helpPost', $helpPost);
    }

    public function rest_index()
    {
		$contact="";
    	$contact_title="";
        if ($this->authenticateRest($this->request->query)) {
			$contentArr=$this->Content->findByPageUrl('APP-Contact-Us');
			if($contentArr) {
				$contact_title = $contentArr['Content']['page_name'];
				$contact = $contentArr['Content']['main_content'];
			}
			$response = $this->Help->find('all', array('conditions' => array('status' => 'Active'),
                'order' => 'id asc'));
            $status = true;
            $message = __('Help data fetch successfully.');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $response = (object)array();
        }
        $this->set(compact('status', 'message', 'response','contact_title','contact'));
        $this->set('_serialize', array('status', 'message', 'response','contact_title','contact'));
    }
}
