<?php
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');
App::uses('Paypal', 'Paypal.Lib');
App::uses('HttpSocket', 'Network/Http');

class DashboardsController extends AppController
{
    public $components = array('HighCharts.HighCharts', 'RequestHandler');
    public $currentDateTime, $studentId;

    public function beforeFilter()
    {
        parent::beforeFilter();
        if($this->userValue){
			$this->studentId = $this->userValue['Student']['id'];
		}
        $this->limit = 5;
		if($this->RequestHandler->isMobile()){
			$this->set('isMobile',true);
		}else{
			$this->set('isMobile',false);
		}
        /* PAYPAL Settings*/
        $this->PPL = false;
        $this->CAE = false;
        $this->PME = false;
        $this->loadModel('PaymentSetting');
        $paySetting = $this->PaymentSetting->findByType('PPL');
        if ($paySetting['PaymentSetting']['published'] == "Yes" && strlen($paySetting['PaymentSetting']['username']) > 0 && strlen($paySetting['PaymentSetting']['password']) > 0 && strlen($paySetting['PaymentSetting']['signature']) > 0) {
            if ($paySetting['PaymentSetting']['sandbox_mode'] == 1)
                $sandboxMode = true;
            else
                $sandboxMode = false;
            $this->Paypal = new Paypal(array(
                'sandboxMode' => $sandboxMode,
                'nvpUsername' => $paySetting['PaymentSetting']['username'],
                'nvpPassword' => $paySetting['PaymentSetting']['password'],
                'nvpSignature' => $paySetting['PaymentSetting']['signature']
            ));
            $this->paymentNamePPL = $paySetting['PaymentSetting']['name'];
            $this->PPL = true;
        }
        /* PAYPAL Settings*/
        /* CCAVENUE Settings*/
        $paySetting = $this->PaymentSetting->findByType('CAE');
        if ($paySetting['PaymentSetting']['published'] == "Yes" && strlen($paySetting['PaymentSetting']['username']) > 0 && strlen($paySetting['PaymentSetting']['password']) > 0 && strlen($paySetting['PaymentSetting']['signature']) > 0) {
            if ($paySetting['PaymentSetting']['sandbox_mode'] == 1) {
                $this->gatewayUrl = 'https://test.ccavenue.com';
            } else {
                $this->gatewayUrl = $paySetting['PaymentSetting']['gateway_url'];
            }
            $this->merchantIdAvenue = $paySetting['PaymentSetting']['username'];
            $this->accessCode = $paySetting['PaymentSetting']['password'];
            $this->workingKey = $paySetting['PaymentSetting']['signature'];
            $this->paymentNameCAE = $paySetting['PaymentSetting']['name'];
            $this->CAE = true;
        }
        /* CCAVENUE Settings*/
        /* PAYUMONEY Settings*/
        $paySetting = $this->PaymentSetting->findByType('PME');
        if ($paySetting['PaymentSetting']['published'] == "Yes" && strlen($paySetting['PaymentSetting']['username']) > 0 && strlen($paySetting['PaymentSetting']['password']) > 0 && strlen($paySetting['PaymentSetting']['signature']) > 0) {
            if ($paySetting['PaymentSetting']['sandbox_mode'] == 1) {
                $this->payumoneyUrl = "https://test.payumoney.com/payment/payment/chkMerchantTxnStatus";
            } else {
                $this->payumoneyUrl = "https://www.payumoney.com/payment/payment/chkMerchantTxnStatus";
            }
            $this->merchantId = $paySetting['PaymentSetting']['username'];
            $this->merchantKey = $paySetting['PaymentSetting']['password'];
            $this->merchantSalt = $paySetting['PaymentSetting']['signature'];
            $this->serviceProvider = $paySetting['PaymentSetting']['gateway_url'];
            $this->paymentNamePME = $paySetting['PaymentSetting']['name'];
            $this->payumoneyAuthorization = $paySetting['PaymentSetting']['authorization'];
            $this->PME = true;
        }
        /* PAYUMONEY Settings*/
    }

    public function crm_index($type = "crm", $publicKey = "0", $privateKey = "0")
    {
        $this->authenticate();
        /* Paypal Pending Payment*/
        if ($this->PPL == true) {
            $this->loadModel('Payment');
            $this->loadModel('Checkout');
            $paymentArr = $this->Payment->find('first', array(
                    'conditions' => array(
                        'student_id' => $this->studentId,
                        'status' => 'Pending',
                        'type' => 'PPL',
                        'transaction_id IS NOT NULL'),
                    'recursive' => 2)
            );
            if ($paymentArr) {
                $responseArr = $this->Paypal->getPaymentDetails($paymentArr['Payment']['transaction_id']);
                if ($responseArr == "Completed") {
                    $recordArr = array('id' => $paymentArr['Payment']['id'], 'correlation_id' => $paymentArr['Payment']['correlation_id'], 'timestamp' => $paymentArr['Payment']['timestamp'], 'status' => 'Approved');
                    $packageDetail = $this->Checkout->packageExamOrder($paymentArr);
                    $this->Payment->save($recordArr);
					$this->loadModel('PackagesPayment');
					$this->PackagesPayment->updateAll(array('status'=>"'Approved'"),array('payment_id'=>$paymentArr['Payment']['id']));
                    $transactionId = $paymentArr['Payment']['transaction_id'];
                    $totalAmount = $paymentArr['Payment']['amount'];
					$couponAmount = $paymentArr['Payment']['coupon_amount'];
					$netAmount=$totalAmount-$couponAmount;
                    try {
                        $siteName = $this->siteName;
                        $siteEmailContact = $this->siteEmailContact;
                        $studentName = $this->userValue['Student']['name'];
                        $email = $this->userValue['Student']['email'];
                        $mobileNo = $this->userValue['Student']['phone'];
                        $currency = '<img src="' . Router::url('/', true) . 'img/' . $this->currencyImage . '"> ';
                        /* Send Email */
                        if ($this->emailNotification) {
                            $this->loadModel('Emailtemplate');
                            $emailTemplateArr = $this->Emailtemplate->findByType('PPD');
                            if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
								$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#currency#}' => $currency ,
									'{#totalAmount#}' => $totalAmount,
									'{#couponDiscount#}' => $couponAmount,
									'{#netAmount#}' => $netAmount,
									'{#transactionId#}' => $transactionId,
									'{#packageDetail#}' => $packageDetail,
									'{#siteName#}' => $siteName,
									'{#siteEmailContact#}' => $siteEmailContact
								]);
                                $Email = new CakeEmail();
                                $Email->transport($this->emailSettype);
                                if ($this->emailSettype == "Smtp")
                                    $Email->config(array('host' => $this->emailHost, 'port' => $this->emailPort, 'username' => $this->emailUsername, 'password' => $this->emailPassword));
                                $Email->from(array($this->siteEmail => $this->siteName));
                                $Email->to($email);
                                $Email->template('default');
                                $Email->emailFormat('html');
                                $Email->subject($emailTemplateArr['Emailtemplate']['name']);
                                $Email->send($message);
                            }
                            /* End Email */
                        }
                        if ($this->smsNotification) {
                            /* Send Sms */
                            $this->loadModel('Smstemplate');
                            $smsTemplateArr = $this->Smstemplate->findByType('PPD');
                            if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
                                $packageDetail = strip_tags($packageDetail);
								$message = strtr($smsTemplateArr['Smstemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#currency#}' => $currency ,
									'{#totalAmount#}' => $totalAmount,
									'{#couponDiscount#}' => $couponAmount,
									'{#netAmount#}' => $netAmount,
									'{#transactionId#}' => $transactionId,
									'{#packageDetail#}' => $packageDetail,
									'{#siteName#}' => $siteName
								]);
                                $this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
                            }
                            /* End Sms */
                        }
                    } catch (Exception $e) {
                        $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                    }
                } else {
                    $this->Session->setFlash(__('Previous payment pending'), 'flash', array('alert' => 'danger'));
                }
            }
        }
        /* Paypal Pending Payment*/
        /* CCAVENUE Pending Payment*/
        if ($this->CAE == true) {
            $this->loadModel('Checkout');
            $this->Checkout->ccAvenuePaymentStatus($this->studentId, $this->accessCode, $this->workingKey);
        }
        /* CCAVENUE Pending Payment*/
        /* PAYUMONEY Pending Payment*/
        if ($this->PME == true && strlen($this->payumoneyAuthorization) > 0) {
            $this->loadModel('Checkout');
            $this->Checkout->payumoneyPaymentStatus($this->studentId, $this->merchantKey, $this->payumoneyAuthorization, $this->payumoneyUrl);
        }
        /* PAYUMONEY Pending Payment*/
        $this->loadModel('Exam');
        /* Start Remaining Exam Checking*/
        $this->loadModel('ExamResult');
        $testId = null;
        $remExamName = null;
        $remExam = $this->ExamResult->find('first', array('joins' => array(array('table' => 'exams', 'type' => 'INNER', 'alias' => 'Exam', 'conditions' => array('ExamResult.exam_id=Exam.id'))),
            'fields' => array('Exam.id', 'Exam.name'),
            'conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
        if ($remExam) {
            $testId = $remExam['Exam']['id'];
            $remExamName = $remExam['Exam']['name'];
        }
        $this->set('testId', $testId);
        $this->set('remExamName', $remExamName);
        /* End Remaining Exam Cheking*/

        $totalExamGiven = $this->Dashboard->find('count', array('conditions' => array('Dashboard.student_id' => $this->studentId)));
        $failedExam = $this->Dashboard->find('count', array('conditions' => array('Dashboard.student_id' => $this->studentId, 'Dashboard.result' => 'Fail')));
        $userTotalAbsent = $this->Dashboard->userTotalAbsent($this->studentId);
        if ($userTotalAbsent < 0)
            $userTotalAbsent = 0;
        $bestScoreArr = $this->Dashboard->userBestExam($this->studentId);
        $bestScore = "";
        $bestScoreDate = "";
        if (isset($bestScoreArr['Exam']['name'])) {
            $bestScore = $bestScoreArr['Exam']['name'];
            $bestScoreDate = CakeTime::format($this->sysDay . $this->dateSep . $this->sysMonth . $this->dateSep . $this->sysYear . $this->dateGap . $this->sysHour . $this->timeSep . $this->sysMin . $this->dateGap . $this->sysMer, $bestScoreArr['ExamResult']['start_time']);
        }
        $this->set('limit', $this->limit);
        $this->set('totalExamGiven', $totalExamGiven);
        $this->set('failedExam', $failedExam);
        $this->set('userTotalAbsent', $userTotalAbsent);
        $this->set('bestScore', $bestScore);
        $this->set('bestScoreDate', $bestScoreDate);
        $performanceChartData = array();
        $currentMonth = CakeTime::format('m', time());
        for ($i = 1; $i <= 12; $i++) {
            if ($i > $currentMonth)
                break;
            $examData = $this->Dashboard->performanceCount($this->studentId, $i);
            $performanceChartData[] = (float)$examData;
        }
        $tooltipFormatFunction = "function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+ this.y +'%';}";
        $chartName = "My Chartdl";
        $mychart = $this->HighCharts->create($chartName, 'spline');
        $this->HighCharts->setChartParams(
            $chartName,
            array(
                'renderTo' => "mywrapperdl",  // div to display chart inside
                'titleAlign' => 'center',
                'creditsEnabled' => FALSE,
                'xAxisLabelsEnabled' => TRUE,
                'xAxisCategories' => array(__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun'), __('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec')),
                'yAxisTitleText' => __('Percentage'),
                'tooltipEnabled' => TRUE,
                'tooltipFormatter' => $tooltipFormatFunction,
                'enableAutoStep' => FALSE,
                'plotOptionsShowInLegend' => TRUE,
                'yAxisMax' => 100,
            )
        );
        $series = $this->HighCharts->addChartSeries();
        $series->addName(__('Month'))->addData($performanceChartData);
        $mychart->addSeries($series);

        $this->loadModel('ExamResult');
        $examResultArr = $this->ExamResult->find('all', array(
            'fields' => array('Exam.name', 'ExamResult.percent'),
            'joins' => array(array('table' => 'exams', 'alias' => 'Exam', 'type' => 'INNER', 'conditions' => array('ExamResult.exam_id=Exam.id'))),
            'conditions' => array('ExamResult.student_id' => $this->studentId),
            'order' => array('ExamResult.id' => 'desc'),
            'limit' => 10));

        $this->ExamResult->virtualFields = array('total_percent' => 'SUM(ExamResult.percent)');
        $totalPercentArr = $this->ExamResult->find('first', array('fields' => array('total_percent'), 'conditions' => array('ExamResult.student_id' => $this->studentId)));
        $this->ExamResult->virtualFields = array();
        $totalExamAttempt = $this->ExamResult->find('count', array('conditions' => array('ExamResult.student_id' => $this->studentId)));
        $totalPercent = $totalPercentArr['ExamResult']['total_percent'];
        if ($totalExamAttempt > 0)
            $averagePercent = round($totalPercent / $totalExamAttempt, 2);
        else
            $averagePercent = 0;
        $performanceChartData = array();
        $xAxisCategories = array();
        foreach ($examResultArr as $post) {
            $xAxisCategories[] = array($post['Exam']['name']);
            $performanceChartData[] = array((float)$post['ExamResult']['percent']);
        }
        $tooltipFormatFunction = "function() { return ''+ this.x +': '+ this.y +'%';}";
        $chartName = "My Chartd2";
        $mychart = $this->HighCharts->create($chartName, 'column');
        $this->HighCharts->setChartParams(
            $chartName,
            array(
                'renderTo' => "mywrapperd2",  // div to display chart inside
                'titleAlign' => 'center',
                'creditsEnabled' => FALSE,
                'xAxisLabelsEnabled' => TRUE,
                'xAxisCategories' => $xAxisCategories,
                'yAxisTitleText' => __('Percentage'),
                'tooltipEnabled' => TRUE,
                'tooltipFormatter' => $tooltipFormatFunction,
                'enableAutoStep' => FALSE,
                'plotOptionsShowInLegend' => TRUE,
                'yAxisMax' => 100,
            )
        );
        $series = $this->HighCharts->addChartSeries();
        $series->addName(__('Exams'))->addData($performanceChartData);
        $mychart->addSeries($series);

        $rank = 0;
        $rankPostArr = $this->ExamResult->query("SELECT `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo`, ROUND(SUM(`ExamResult`.`percent`)/COUNT(`ExamResult`.`exam_id`),2) as `points`,COUNT(`ExamResult`.`exam_id`) as `exam_given` FROM `exam_results` AS `ExamResult` INNER JOIN `students` AS `Student` ON (`ExamResult`.`student_id`=`Student`.`id`) WHERE `ExamResult`.`finalized_time` IS NOT NULL GROUP BY `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo` ORDER BY `points` DESC");
        $rank = 0;
        $final_result_percent = 0;
        foreach ($rankPostArr as $item) {
            if ($final_result_percent == $item[0]['points']) {
                $rank;
            } else {
                $rank++;
            }
            if($item['ExamResult']['student_id']==$this->studentId){
                break;
            }
        }
        $this->set('averagePercent', $averagePercent);
        $this->set('rank', $rank);
    }

    public function rest_index()
    {
        //$this->autoRender = false;
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);

            $this->loadModel('Exam');
            /* Start Remaining Exam Cheking*/
            $this->loadModel('ExamResult');
            $totalExamGiven = $this->Dashboard->find('count', array('conditions' => array('Dashboard.student_id' => $this->studentId)));
            $failedExam = $this->Dashboard->find('count', array('conditions' => array('Dashboard.student_id' => $this->studentId, 'Dashboard.result' => 'Fail')));
            $userTotalAbsent = $this->Dashboard->userTotalAbsent($this->studentId);
            if ($userTotalAbsent < 0)
                $userTotalAbsent = 0;
            $bestScoreArr = $this->Dashboard->userBestExam($this->studentId);
            $bestScore = "";
            $bestScoreDate = "";
            if (isset($bestScoreArr['Exam']['name'])) {
                $bestScore = $bestScoreArr['Exam']['name'];
                $bestScoreDate = CakeTime::format($this->sysDay . $this->dateSep . $this->sysMonth . $this->dateSep . $this->sysYear . $this->dateGap . $this->sysHour . $this->timeSep . $this->sysMin . $this->dateGap . $this->sysMer, $bestScoreArr['ExamResult']['start_time']);
            }

            $this->loadModel('ExamResult');
            $examResultArr = $this->ExamResult->find('all', array('fields' => array('Exam.name', 'ExamResult.percent'),
                'joins' => array(array('table' => 'exams', 'alias' => 'Exam', 'type' => 'INNER', 'conditions' => array('ExamResult.exam_id=Exam.id'))),
                'conditions' => array('ExamResult.student_id' => $this->studentId),
                'order' => array('ExamResult.id' => 'desc'),
                'limit' => 10));
            $this->ExamResult->virtualFields = array('total_percent' => 'SUM(ExamResult.percent)');
            $totalPercentArr = $this->ExamResult->find('first', array('fields' => array('total_percent'), 'conditions' => array('ExamResult.student_id' => $this->studentId)));
            $this->ExamResult->virtualFields = array();
            $totalExamAttempt = $this->ExamResult->find('count', array('conditions' => array('ExamResult.student_id' => $this->studentId)));
            $totalPercent = $totalPercentArr['ExamResult']['total_percent'];
            if ($totalExamAttempt > 0)
                $averagePercent = round($totalPercent / $totalExamAttempt, 2);
            else
                $averagePercent = 0;


            $rank = 0;
            $rankPostArr = $this->ExamResult->query("SELECT `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo`, ROUND(SUM(`ExamResult`.`percent`)/COUNT(`ExamResult`.`exam_id`),2) as `points`,COUNT(`ExamResult`.`exam_id`) as `exam_given` FROM `exam_results` AS `ExamResult` INNER JOIN `students` AS `Student` ON (`ExamResult`.`student_id`=`Student`.`id`) WHERE `ExamResult`.`finalized_time` IS NOT NULL GROUP BY `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo` ORDER BY `points` DESC");
            $rank = 0;
            $final_result_percent = 0;
            foreach ($rankPostArr as $item) {
                if ($final_result_percent == $item[0]['points']) {
                    $rank;
                } else {
                    $rank++;
                }
                if($item['ExamResult']['student_id']==$this->studentId){
                    break;
                }
            }

            $averagePercentnew = CakeNumber::toPercentage($averagePercent);
            $dashboard_data['my_exam_status'] = array('total_exam_given' => $totalExamGiven, 'absent_exams' => $userTotalAbsent, 'best_score_in' => $bestScore, 'on' => $bestScoreDate, 'failed_exam_count' => $failedExam, 'average_percentage' => $averagePercentnew, 'rank' => $rank);

            $response = $this->Exam->getPurchasedExam("today", $this->studentId, $this->currentDateTime, $this->limit);
            foreach ($response as $key => $value) {
                $exam_id = $value['Exam']['id'];
                $exam_name = $value['Exam']['name'];
                $start_date = $value['Exam']['start_date'];
                $end_date = $value['Exam']['end_date'];
                $expiry_date = $value['Exam']['fexpiry_date'];
                $attempts = $value['Exam']['attempt'];

                $exam[] = array('exam_id' => $exam_id, 'exam_name' => $exam_name, 'start_date' => $start_date, 'start_date' => $start_date, 'expiry_date' => $expiry_date, 'attempts' => $attempts);
            }

            $status = true;
            $message = 'Data reterive successfully';
            $dashboard_data['todays_exams'] = $exam;


        } else {
            $status = false;
            $message = ('Invalid Token');
            $response = (object)array();
        }
        $this->set(compact('status', 'message', 'dashboard_data'));
        $this->set('_serialize', array('status', 'message', 'dashboard_data'));
    }

    public function rest_exam()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);
            $this->loadModel('Exam');
            $response = $this->Exam->getPurchasedExam("today", $this->studentId, $this->currentDateTime, $this->limit);
            $status = true;
            $message = __('Exam fetch successfully');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $response = (object)array();
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }

    public function rest_balance()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);
            $balance = $this->CustomFunction->WalletBalance($this->studentId);
            $status = true;
            $message = __('Balance fetch successfully');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $balance = null;
        }
        $this->set(compact('status', 'message', 'balance'));
        $this->set('_serialize', array('status', 'message', 'balance'));
    }

    public function rest_mail()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);
            $email = $this->Mail->find('count', array('conditions' => array('email' => $this->userValue['Student']['email'], 'status <>' => 'Trash', 'type' => 'Unread', 'mail_type' => 'To')));
            $status = true;
            $message = __('Email fetch successfully');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $email = null;
        }
        $this->set(compact('status', 'message', 'email'));
        $this->set('_serialize', array('status', 'message', 'email'));
    }

    public function rest_remainingExam()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);
            /* Start Remaining Exam Cheking*/
            $this->loadModel('ExamResult');
            $testId = null;
            $remExamName = null;
            $remExam = $this->ExamResult->find('first', array('joins' => array(array('table' => 'exams', 'type' => 'INNER', 'alias' => 'Exam', 'conditions' => array('ExamResult.exam_id=Exam.id'))),
                'fields' => array('Exam.id', 'Exam.name'),
                'conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
            if ($remExam) {
                $testId = $remExam['Exam']['id'];
                $remExamName = $remExam['Exam']['name'];
                $status = true;
                $message = __('Click here to complete %s', $remExamName);
            } else {
                $status = false;
                $message = __('No remaining exam.');
            }
            /* End Remaining Exam Cheking*/
        } else {
            $status = false;
            $message = ('Invalid Token');
            $testId = null;
            $remExamName = null;
        }
        $this->set(compact('status', 'message', 'testId', 'remExamName'));
        $this->set('_serialize', array('status', 'message', 'testId', 'remExamName'));
    }
}
