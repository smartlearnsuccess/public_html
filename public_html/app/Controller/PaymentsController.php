<?php
App::uses('HttpSocket', 'Network/Http');

class PaymentsController extends AppController
{
    public $helpers = array('Paginator', 'Js' => array('Jquery'));
    public $components = array('Paginator', 'RequestHandler');
    var $paginate = array('page' => 1, 'order' => array('Payment.id' => 'desc'));

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
        $this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['conditions'] = array('Payment.student_id' => $this->studentId,array('OR'=>array('Payment.type <>'=>'FRE','coupon_id IS NOT NULL')));
        $this->Paginator->settings['limit'] = $this->pageLimit;
        $this->Paginator->settings['maxLimit'] = $this->maxLimit;
        $this->set('Payment', $this->Paginator->paginate());
        if ($this->request->is('ajax')) {
            $this->render('crm_index', 'ajax'); // View, Layout
        }
    }

    public function rest_index()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);
            $this->Paginator->settings = $this->paginate;
			$this->Paginator->settings['conditions'] = array('Payment.student_id' => $this->studentId,array('OR'=>array('Payment.type <>'=>'FRE','coupon_id IS NOT NULL')));
            $this->Paginator->settings['limit'] = 99999999999999999;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $this->Paginator->settings['recursive'] = 2;
            $response = $this->Paginator->paginate();
            $status = true;
            $message = __('Payment fetch successfully');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $response = (object)array();
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }

    public function crm_view($id)
    {
        $this->authenticate();
        $this->layout = null;
        $postArr = $this->Payment->find('first', array('conditions' => array('Payment.id' => $id, 'Payment.student_id' => $this->studentId),
            'recursive' => 2));
        $this->set('postArr', $postArr);
    }
}
