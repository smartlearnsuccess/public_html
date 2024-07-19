<?php
ini_set('max_execution_time', 900);
ini_set('memory_limit', '1024M');

class TransactionReportsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg', 'PhpExcel.PhpExcel');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('TransactionReport.id' => 'desc'));

    public function index()
    {
        $this->TransactionReport->virtualFields = array('cart_amount' => '((TransactionReport.amount+IFNULL(TransactionReport.coupon_amount,0)))');
        $this->Prg->commonProcess();
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings['limit'] = $this->pageLimit;
        $this->Paginator->settings['maxLimit'] = $this->maxLimit;
        $this->Paginator->settings['conditions'] = array($this->TransactionReport->parseCriteria($this->Prg->parsedParams()));
        $this->Paginator->settings['fields'] = array('TransactionReport.*', 'cart_amount', 'Student.name', 'Student.email', 'Student.phone');
        $this->set('TransactionReport', $this->Paginator->paginate());
        $this->TransactionReport->virtualFields = array();
        if ($this->request->is('ajax')) {
            $this->render('index', 'ajax'); // View, Layout
        }
    }

    public function excel()
    {
        $cond = array();
        $this->TransactionReport->virtualFields = array('cart_amount' => '((TransactionReport.amount+IFNULL(TransactionReport.coupon_amount,0)))');
        $this->Prg->commonProcess();
        $conditions = array($cond, $this->TransactionReport->parseCriteria($this->Prg->parsedParams()));
        $post = $this->TransactionReport->find('all', array(
                'fields' => array('TransactionReport.*', 'cart_amount', 'Student.name', 'Student.email', 'Student.phone'),
                'conditions' => $conditions)
        );
        $data = $this->TransactionReport->showReportData($post, $this->dtmFormat);
        $this->PhpExcel->createWorksheet();
        $this->PhpExcel->addTableRow($data);
        $totalRow = count($post) + 2;
        $this->PhpExcel->formatCell('A1:L1', array(
            'font' => array(
                'bold' => true,
                'size' => 11
            )));
        $this->PhpExcel->formatCell('I' . $totalRow . ':K' . $totalRow, array(
            'font' => array(
                'bold' => true,
                'size' => 11
            )));
        $this->PhpExcel->cellAutoWidth("A", "M");
        $this->PhpExcel->output('TransactionReport', $this->siteName, 'TransactionReport.xls', 'Excel2007');
    }

    public function paymentStatus($id)
    {
        $this->autoRender = false;
        $this->loadModel('Checkout');
        $this->loadModel('Payment');
        $paymentArr = $this->Payment->find('first', array(
                'conditions' => array(
                    'id' => $id,
                    'status !=' => 'Approved'),
                'recursive' => 2)
        );
        if ($paymentArr) {
            $this->Checkout->packageExamOrder($paymentArr);
            $this->Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $paymentArr['Payment']['token'], 'status' => 'Approved'));
			$this->loadModel('PackagesPayment');
			$this->PackagesPayment->updateAll(array('status'=>"'Approved'"),array('payment_id'=>$paymentArr['Payment']['id']));
            $this->Session->setFlash(__("Transaction has been success"), 'flash', array('alert' => 'success'));
        } else {
            $this->Session->setFlash(__("Invalid Post"), 'flash', array('alert' => 'danger'));
        }
        $this->redirect(array('controller' => 'TransactionReports', 'action' => 'index'));
    }
}
