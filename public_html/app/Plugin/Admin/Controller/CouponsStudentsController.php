<?php

class CouponsStudentsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg', 'PhpExcel.PhpExcel');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('CouponsStudent.id' => 'desc'));

    public function index($id = null)
    {
        if (isset($this->params['named']['excel'])) {
            if ($this->params['named']['excel'] == "Yes") {
                $id = $this->params['pass'][0];
                $url_var = null;
                if (isset($this->params['named']['name']))
                    $url_var .= "/name:" . $this->params['named']['name'];
				if (isset($this->params['named']['mode']))
					$url_var .= "/mode:" . $this->params['named']['mode'];
				if (isset($this->params['named']['time_frame']))
                if (isset($this->params['named']['time_frame']))
                    $url_var .= "/time_frame:" . $this->params['named']['time_frame'];
                if (isset($this->params['named']['status']))
                    $url_var .= "/status:" . $this->params['named']['status'];
                if (isset($this->params['named']['gstart_date']))
                    $url_var .= "/gstart_date:" . $this->params['named']['gstart_date'];
                if (isset($this->params['named']['gend_date']))
                    $url_var .= "/gend_date:" . $this->params['named']['gend_date'];
                if (isset($this->params['named']['start_date']))
                    $url_var .= "/start_date:" . $this->params['named']['start_date'];
                if (isset($this->params['named']['end_date']))
                    $url_var .= "/end_date:" . $this->params['named']['end_date'];
                return $this->redirect(array('action' => "excel/$id/$url_var"));
            }
        }
		$cond = array('CouponsStudent.coupon_id IS NOT NULL','CouponsStudent.status'=>'Approved');
        if ($id != null)
            $cond = array('CouponsStudent.coupon_id' => $id);
        $this->Prg->commonProcess();
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings['limit'] = $this->pageLimit;
        $this->Paginator->settings['maxLimit'] = $this->maxLimit;
        $this->Paginator->settings['conditions'] = array($cond, $this->CouponsStudent->parseCriteria($this->Prg->parsedParams()));
        $this->set('CouponsStudent', $this->Paginator->paginate());
        if ($this->request->is('ajax')) {
            $this->set('couponId', $id);
            $this->render('index', 'ajax'); // View, Layout
        }
        $this->set('couponId', $id);
    }

    public function excel($id = null)
    {
		$cond = array('CouponsStudent.coupon_id IS NOT NULL','CouponsStudent.status'=>'Approved');
        if ($id != null)
            $cond = array('CouponsStudent.coupon_id' => $id);
        $this->Prg->commonProcess();
        $conditions = array($cond, $this->CouponsStudent->parseCriteria($this->Prg->parsedParams()));
        $post = $this->CouponsStudent->find('all', array('conditions' => $conditions));
        $data = $this->CouponsStudent->showCouponData($post, $this->dtmFormat);
        $this->PhpExcel->createWorksheet();
        $this->PhpExcel->addTableRow($data);
        $this->PhpExcel->output('UsedCoupon', $this->siteName, 'usedCoupon.xls', 'Excel2007');
    }
}
