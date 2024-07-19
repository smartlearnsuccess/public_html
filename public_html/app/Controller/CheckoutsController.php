<?php
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');
App::uses('Paypal', 'Paypal.Lib');

class CheckoutsController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
    	parent::beforeFilter();
        $this->currentDateTime = CakeTime::format('Y-m-d H:i:s', CakeTime::convert(time(), $this->siteTimezone));
        $this->currentDate = CakeTime::format('Y-m-d', CakeTime::convert(time(), $this->siteTimezone));
        $this->currentEmailDate = CakeTime::format('d-m-Y', CakeTime::convert(time(), $this->siteTimezone));
        $this->userId = $this->userValue!=NULL ? $this->userValue['Student']['id'] : NULL;
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
        $paySetting = $this->PaymentSetting->findByType('PME');
        if ($paySetting['PaymentSetting']['published'] == "Yes" && strlen($paySetting['PaymentSetting']['username']) > 0 && strlen($paySetting['PaymentSetting']['password']) > 0 && strlen($paySetting['PaymentSetting']['signature']) > 0) {
            if ($paySetting['PaymentSetting']['sandbox_mode'] == 1) {
                $this->payumoneyUrl = "https://sandboxsecure.payu.in";
                $sandboxMode=true;
            } else {
                $this->payumoneyUrl = "https://secure.payu.in";
				$sandboxMode=false;
            }
            $this->merchantId = $paySetting['PaymentSetting']['username'];
            $this->merchantKey = $paySetting['PaymentSetting']['password'];
            $this->merchantSalt = $paySetting['PaymentSetting']['signature'];
            $this->serviceProvider = $paySetting['PaymentSetting']['gateway_url'];
            $this->paymentNamePME = $paySetting['PaymentSetting']['name'];
            $this->sanboxModePME = $sandboxMode;
            $this->PME = true;
        }
        $this->set('PPL', $this->PPL);
        $this->set('CAE', $this->CAE);
        $this->set('PME', $this->PME);
        $this->studentId = $this->userId;
    }

    public function index()
    {
        $this->authenticate();
        $this->set('UserArr', $this->userValue);
        $carts = $this->Cart->readPackage();
        $products = array();
        if (null != $carts) {
            $this->loadModel('Package');
            foreach ($carts as $productId => $count) {
                $product = $this->Package->findById($productId);
                $product['Package']['count'] = $count;
                $products[] = $product;
            }
        }
        $this->set(compact('products'));
        $this->set('totalAmount', $this->getTotalAmount());
        if (isset($_REQUEST['token'])) {
            $this->Session->setFlash(__('Payment Cancel'), 'flash', array('alert' => 'danger'));
        }
    }

    public function coupon()
    {
        $this->authenticate();
        $this->autoRender = false;
        $msg = null;
        $couponCode = $_POST['redeem_code'];
        $totalAmount = $this->getTotalAmount();
        $couponArr = $this->checkCoupon($couponCode, $totalAmount);
        if ($couponArr['Coupon']['status'] == true) {
            $sc_msg = __("Coupon '%s' applied", $couponArr['Coupon']['couponCode']) . '&nbsp;<a href="Checkouts/couponDelete">Remove</a>';
            $couponMessage = '<div class="col-sm-8">' . __('Coupon Discount') . '</div><div class="col-sm-4">-' . $this->currency . $couponArr['Coupon']['discountRate'] . '</div>';
            echo '<div class="coupon_applied">' . $sc_msg . '</div>' . "^" . $couponArr['Coupon']['finalAmount'] . "^" . $couponMessage;
        } else {
            $msg = $couponArr['Coupon']['message'];
            echo '<div class="error_message">' . $msg . '</div>';
        }
    }

    public function rest_coupon()
    {
        $message = __('Invalid Post');
        $status = false;
        $response = (object)array();
        if ($this->request->is('post')) {
            if (isset($this->request->data['public_key']) && isset($this->request->data['private_key']) && isset($this->request->data['coupon_code']) && isset($this->request->data['total_amount'])) {
                $dataArr = $this->restPostKey($this->request->data);
                if ($this->authenticateRest($dataArr)) {
                    $this->studentId = $this->restStudentId($dataArr);
                    $couponCode = $this->request->data['coupon_code'];
                    $totalAmount = $this->request->data['total_amount'];
                    $couponArr = $this->checkCoupon($couponCode, $totalAmount, 'rest');
                    if ($couponArr['Coupon']['status'] == true) {
                        $response = $couponArr;
                        $status = true;
                        $message = $couponArr['Coupon']['message'];
                    } else {
                        $message = $couponArr['Coupon']['message'];
                    }
                } else {
                    $message = ('Invalid Token');
                }
            } else {
                $message = __('Invalid Post');
            }
        } else {
            $message = __('GET request not allowed!');
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }

    private function checkCoupon($couponCode, $totalAmount, $couponMethod = 'web')
    {
        $msg = __("Invalid Coupon Code");
        $status = false;
        if (strlen($couponCode) > 0) {
            $this->loadModel('Coupon');
            $couponArr = $this->Coupon->find('first', array('conditions' => array('code' => $couponCode)));
            if ($couponArr) {
                $this->loadModel('CouponsStudent');
                $totalCoupon = $this->CouponsStudent->find('count',
                    array('conditions' => array('coupon_id' => $couponArr['Coupon']['id']))
                );
                $customerCoupon = $this->CouponsStudent->find('count',
                    array('conditions' => array('coupon_id' => $couponArr['Coupon']['id'], 'student_id' => $this->studentId))
                );
                if ($couponArr['Coupon']['coupon_no'] == null)
                    $couponArr['Coupon']['coupon_no'] = $totalCoupon + 1;
                if ($couponArr['Coupon']['per_customer'] == null)
                    $couponArr['Coupon']['per_customer'] = $customerCoupon + 1;
                if ($couponArr['Coupon']['status'] == "Suspend") {
                    $msg = __("This Coupon has been suspend");
                } elseif ($couponArr['Coupon']['coupon_no'] <= $totalCoupon) {
                    $msg = __("This Coupon has been used");
                } elseif ($couponArr['Coupon']['per_customer'] <= $customerCoupon) {
                    $msg = __("You have used this coupon");
                } elseif ($couponArr['Coupon']['start_date'] > $this->currentDate) {
                    $msg = __("Coupon Date has not been start");
                } elseif ($couponArr['Coupon']['end_date'] < $this->currentDate) {
                    $msg = __("Coupon has been expired");
                } elseif ($couponArr['Coupon']['min_amount'] > $totalAmount)
                    $msg = __("Sorry! Your order value should be atleast %s to redeem this coupon.", $this->currency . $couponArr['Coupon']['min_amount']);
                elseif ($this->Session->check('couponArr')) {
                    $msg = __("You have already used coupon for this order.");
                } else {
                    if ($couponArr['Coupon']['discount_type'] == "Percent") {
                        $discountRate = ceil(($totalAmount * $couponArr['Coupon']['amount']) / 100);
                    } else {
                        $discountRate = $couponArr['Coupon']['amount'];
                    }
                    if($discountRate > $totalAmount){
                        $discountRate = $totalAmount;
                    }
                    if ($couponMethod == "web") {
                        $this->Session->write('couponArr', array('couponTotalAmount' => $discountRate, 'couponCode' => $couponCode, 'couponId' => $couponArr['Coupon']['id']));
                        $this->CouponsStudent->create();
                        $this->CouponsStudent->save(array('CouponsStudent' => array(
                            'student_id' => $this->studentId,
                            'coupon_id' => $couponArr['Coupon']['id'],
                            'redeem_date' => $this->currentDateTime,
                            'redeem_ip' => $_SERVER['REMOTE_ADDR'],
                            'session_id' => session_id()
                        )
                        ));
                    }
                    $finalAmount = $totalAmount - $discountRate;
                    $msg = __('Coupon applied');
                    return array('Coupon' => array('status' => true, 'message' => $msg, 'couponCode' => $couponArr['Coupon']['code'], 'discountRate' => $discountRate, 'finalAmount' => $finalAmount));
                }
            }
        }
        return array('Coupon' => array('status' => $status, 'message' => $msg));
    }

    public function couponDelete($message = null)
    {
        try {
            $this->autoRender = false;
            $this->loadModel('CouponsStudent');
            $this->CouponsStudent->deleteAll(array('session_id' => session_id(), 'status' => 0));
            $this->Session->write('couponArr', null);
            if ($message == null) {
                $this->Session->setFlash(__('Coupon deleted.'), 'flash', array('alert' => 'success'));
            }
            $this->redirect(array('controller' => 'Checkouts', 'action' => 'index'));
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function rest_couponDelete()
    {
        $message = __('Invalid Post');
        $status = false;
        try {
            if (isset($this->request->data['public_key']) && isset($this->request->data['private_key'])) {
                $dataArr = $this->restPostKey($this->request->data);
                if ($this->authenticateRest($dataArr)) {
                    $this->studentId = $this->restStudentId($dataArr);
                    $status = true;
                    $message = __('Coupon deleted.');
                } else {
                    $message = ('Invalid Token');
                }
            } else {
                $message = __('Invalid Post');
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        $this->set(compact('status', 'message'));
        $this->set('_serialize', array('status', 'message'));
    }

    public function placeOrder()
    {
        $this->autoRender = false;

        if (isset($this->request->query['responses'])) {
            if ($this->authenticateRest($this->request->query)) {
                $this->loadModel('Student');
                $post = $this->Student->findByPublicKeyAndPrivateKey($this->request->query['public_key'], $this->request->query['private_key']);
                $this->Session->write('Student', $post);
                $responseArr = explode(",", $this->request->query['responses']);
                $this->loadModel('Cart');
                $this->Cart->savePackage(array());
                foreach ($responseArr as $item) {
                    if ($item) {
                        $this->Cart->addPackage($item);
                    }
                }
				$this->Session->delete('couponArr');
                if (isset($this->request->query['couponCode']) && strlen($this->request->query['couponCode']) > 0) {
                    $this->checkCoupon($this->request->query['couponCode'], $this->getTotalAmount());
                }
                $this->redirect(array('controller' => 'Checkouts', 'action' => 'paymentMethod'));
            } else {
                $message = ('Invalid Token');
            }
        } else {
            $message = __('Invalid Post');
        }
        echo $message;
    }

    public function rest_success3050($mobileStatus)
    {
        $this->layout = 'rest';
        $this->set('mobileStatus', $mobileStatus);
    }

    public function paymentMethod()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->layout = 'rest';
        }
        $this->authenticate();
        $carts = $this->Cart->readPackage();
        $products = array();
        if (null != $carts) {
            $this->loadModel('Package');
            foreach ($carts as $productId => $count) {
                $product = $this->Package->findById($productId);
                $product['Package']['count'] = $count;
                $products[] = $product;
            }
        }
        $this->set(compact('products'));
        $this->set('totalAmount', $this->getTotalAmount());
        $this->set('UserArr', $this->userValue);
        if (isset($_REQUEST['token'])) {
            $this->Session->setFlash(__('Payment Cancel'), 'flash', array('alert' => 'danger'));
        }
    }

    public function paymentGateway($type, $packageType = null)
    {
        $this->authenticate();
        $totalAmount = $this->getTotalAmount();
        if ($type == "FREE" && $totalAmount == 0) {
            $this->freeCheckout($packageType);
        } elseif ($type == "PAYPAL") {
            if ($this->PPL == false) {
                $this->Session->setFlash(__('Payment Setting not set'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('controller' => 'Checkouts', 'action' => 'index'));
            }
			$this->paypalPayment();
        } elseif ($type == "PAYUMONEY") {
            if ($this->PME == false) {
                $this->Session->setFlash(__('Payment Setting not set'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('controller' => 'Checkouts', 'action' => 'index'));
            }
            return $this->redirect(array('action' => 'payumoney'));
        } elseif ($type == "CCAVENUE") {
            if ($this->CAE == false) {
                $this->Session->setFlash(__('Payment Setting not set'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('controller' => 'Checkouts', 'action' => 'index'));
            }
            return $this->redirect(array('action' => 'ccavenue'));
        } else {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('controller' => 'Checkouts', 'action' => 'index'));
        }
    }


    private function getTotalAmount()
    {
        $totalAmount = -1;
        $carts = $this->Cart->readPackage();
        if (null != $carts) {
            $this->loadModel('Package');
            $totalAmount = 0;
            foreach ($carts as $productId => $count) {
                $product = $this->Package->findById($productId);
                $totalAmount = ($product['Package']['amount'] * $count) + $totalAmount;
            }
        }
        if ($this->Session->check('couponArr')) {
            $couponTotalAmount = $this->couponDiscountAmount();
            return $totalAmount - $couponTotalAmount;
        } else {
            return $totalAmount;
        }
    }

    private function couponDiscountAmount()
    {
        $couponTotalAmount = 0;
        if ($this->Session->check('couponArr')) {
            $couponArr = $this->Session->read('couponArr');
            $couponTotalAmount = $couponArr['couponTotalAmount'];
            return $couponTotalAmount;
        } else {
            return $couponTotalAmount;
        }
    }

    private function paymentCreate($token, $amount, $description, $name, $type, $paymentsFrom)
    {
        $this->loadModel('Payment');
        $carts = $this->Cart->readPackage();
        if (null != $carts) {
            $this->loadModel('Package');
            foreach ($carts as $productId => $count) {
                $product = $this->Package->findById($productId);
                $expiryDays = $product['Package']['expiry_days'];
                if ($expiryDays == 0) {
                    $expiryDate = null;
                } else {
                    $expiryDate = date('Y-m-d', strtotime($this->currentDate . "+$expiryDays days"));
                }
                $packagesPayment[] = array(
                	'student_id'=>$this->studentId,
                    'package_id' => $product['Package']['id'],
                    'price' => $product['Package']['amount'],
                    'quantity' => $count,
                    'amount' => $count * $product['Package']['amount'],
                    'date' => $this->currentDate,
                    'expiry_date' => $expiryDate,
					'status'=>'Pending'
                );
            }
            if ($this->Session->check('couponArr')) {
                $couponArr = $this->Session->read('couponArr');
                $couponAmount = $couponArr['couponTotalAmount'];
                $couponId = $couponArr['couponId'];
            } else {
                $couponAmount = null;
                $couponId = null;
            }
            $paymentArr = array(
                'Payment' => array(
                    'student_id' => $this->studentId, 'token' => $token, 'amount' => $amount, 'status' => 'Pending', 'date' => $this->currentDateTime, 'remarks' => $description, 'name' => $name, 'type' => $type, 'payments_from' => $paymentsFrom, 'coupon_id' => $couponId, 'coupon_amount' => $couponAmount),
                'Package' => $packagesPayment
            );
            $this->Payment->create();
            $this->Payment->saveAll($paymentArr);
        }
    }

    private function freeCheckout()
    {
        $token = time() . rand();
        $amount = $this->getTotalAmount();
        $description = __('Package Purchase');
        $this->paymentCreate($token, $amount, $description, 'Free', 'FRE', 'web');
        $transactionId = time() . rand();
        if ($this->orderComplete($token, $transactionId)) {
            $this->Session->setFlash(__('Package successfully purchased'), 'flash', array('alert' => 'success'));
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->redirect(array('rest' => true, 'controller' => 'Checkouts', 'action' => 'success3050', 'Success'));
            } else {
                $this->redirect(array('crm' => true, 'controller' => 'Payments', 'action' => 'index'));
            }
        } else {
            $this->Session->setFlash(__('Payment not done'), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'paymentMethod'));
        }
    }

    private function paypalPayment()
    {
		$carts = $this->Cart->readPackage();
        $recordArr = $this->Checkout->cartProduct($carts);
        if ($recordArr) {
            $couponDiscountAmount = $this->couponDiscountAmount();
            $recordArr[] = array('name' => __('Discount Coupon'), 'tax' => 0, 'shipping' => 0, 'description' => null, 'quantity' => 1, 'amount' => -$couponDiscountAmount, 'subtotal' => -$couponDiscountAmount);
            $description = __("Package Purchase");
            $returnUrl = $this->siteDomain . '/Checkouts/paypalPostPayment/';
            $cancelUrl = $this->siteDomain . '/crm/Payments/index/';
            $order = array(
                'description' => $description,
                'currency' => $this->currencyType,
                'return' => $returnUrl,
                'cancel' => $cancelUrl,
                'items' => $recordArr
            );
            try {
                $token = $this->Paypal->setExpressCheckout($order);
                $tokenArr = explode("&", $token);
                $tokenId = substr($tokenArr[1], 6);
                $amount = $this->getTotalAmount();
				if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
					$this->paymentCreate($tokenId, $amount, $description, $this->paymentNamePPL, 'PPL', 'app');
				}else{
					$this->paymentCreate($tokenId, $amount, $description, $this->paymentNamePPL, 'PPL', 'web');
				}
                $this->redirect($token);
            } catch (PaypalRedirectException $e) {
                $this->redirect($e->getMessage());
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            }
            $this->redirect(array('action' => 'paymentMethod'));
        } else {
            $this->Session->setFlash(__('Invalid Amount'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'paymentMethod'));
        }
    }

    public function paypalPostPayment($id = null)
    {
        if (isset($_REQUEST['token']) && isset($_REQUEST['PayerID'])) {
            $token = $_REQUEST['token'];
            try {
                $detailsArr = $this->Paypal->getExpressCheckoutDetails($token);
                if (is_array($detailsArr)) {
                    $carts = $this->Cart->readPackage();
                    $record_arr = $this->Checkout->cartProduct($carts);
                    $amount = $detailsArr['AMT'];
                    $description = $detailsArr['DESC'];
                    $payerId = $_REQUEST['PayerID'];
                    if ($detailsArr['ACK'] == "Success") {
                        $order = array(
                            'description' => $description,
                            'currency' => $this->currencyType,
                            'return' => $this->siteDomain . '/Checkouts/paypalPostPayment/',
                            'cancel' => $this->siteDomain . '/crm/Payments/index/',
                            'items' => $record_arr
                        );
                        try {
                            $paymentDetails = $this->Paypal->doExpressCheckoutPayment($order, $token, $payerId);
                            if (is_array($paymentDetails)) {
                                if ($paymentDetails['PAYMENTINFO_0_PAYMENTSTATUS'] == "Completed" && $paymentDetails['PAYMENTINFO_0_ACK'] == "Success") {
                                    $mobileStatus = "Success";
                                    $this->loadModel('Payment');
                                    $transactionId = $paymentDetails['PAYMENTINFO_0_TRANSACTIONID'];
                                    $token = $paymentDetails['TOKEN'];
                                    $correlationId = $paymentDetails['CORRELATIONID'];
                                    $timestamp = $paymentDetails['PAYMENTINFO_0_ORDERTIME'];
                                    if ($this->orderComplete($token, $transactionId, $correlationId, $timestamp)) {
                                        $this->Session->setFlash(__('Package successfully purchased'), 'flash', array('alert' => 'success'));
                                        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                                            $this->redirect(array('rest' => true, 'controller' => 'Checkouts', 'action' => 'success3050', $mobileStatus));
                                        } else {
                                            $this->redirect(array('crm' => true, 'controller' => 'Payments', 'action' => 'index'));
                                        }
                                    } else {
                                        $this->Session->setFlash(__('Payment not done'), 'flash', array('alert' => 'danger'));
                                        return $this->redirect(array('action' => 'paymentMethod'));
                                    }
                                } else {
                                    $mobileStatus = "Failed";
                                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                                        $this->redirect(array('rest' => true, 'controller' => 'Checkouts', 'action' => 'success3050', $mobileStatus));
                                    }
                                }
                                $this->Session->setFlash(__('Payment not done'), 'flash', array('alert' => 'danger'));
                                return $this->redirect(array('action' => 'paymentMethod'));
                            }
                        } catch (PaypalRedirectException $e) {
                            $this->redirect($e->getMessage());
                        } catch (Exception $e) {
                            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                        }
                    } else {
                        $this->Session->setFlash(__('Payment not done'), 'flash', array('alert' => 'danger'));
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            }
            $this->redirect(array('action' => 'paymentMethod'));
        }
    }

    public function payumoney()
    {
        $this->layout = null;
        $carts = $this->Cart->readPackage();
        $recordArr = $this->Checkout->cartProduct($carts);

        foreach ($recordArr as $Prodrecors) {
            $Prodnames[] = $Prodrecors['name'];
        }

        $name = implode(',', $Prodnames);
        $amount = $this->getTotalAmount();

        if ($recordArr) {
            $currency = $this->currencyType;
            $language = "EN";
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 25);
            $merchantId = $this->merchantId;  // Merchant id(also User_Id)
            $merchantKey = $this->merchantKey;  // Access id(also Merchant Key)
            $merchantSalt = $this->merchantSalt;  // Access id(also Merchant Salt)
            $serviceProvider = $this->serviceProvider;  // Service Provider(also payu_paisa)
            $amount = $amount;  // your script should substitute the amount here in the quotes provided here
            $surl = $this->siteDomain . '/Checkouts/payumoneypostpayment';//your redirect URL where your customer will be redirected after authorisation from PayUmoney
            $furl = $this->siteDomain . '/Checkouts/payumoneypostpayment';//your redirect URL where your customer will be redirected after authorisation from PayUmoney
            $curl = $this->siteDomain . '/Carts/View';
            $firstname = substr($this->userValue['Student']['name'],0,60);
            $email = substr($this->userValue['Student']['email'],0,50);
            $address1 = substr($this->userValue['Student']['address'],0,100);
            $phone = $this->userValue['Student']['phone'];
            $productinfo = substr(str_replace("%","",$name),0,100);
            $hashString = $merchantKey . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $firstname . '|' . $email . '|||||||||||' . $merchantSalt;
            $hash = hash('sha512', $hashString);
            $action = $this->payumoneyUrl . '/_payment';
            $this->set('action', $action);
            $this->set('hash', $hash);
            $this->set('merchantId', $merchantId);
            $this->set('merchantKey', $merchantKey);
            $this->set('merchantSalt', $merchantSalt);
            $this->set('txnid', $txnid);
            $this->set('amount', $amount);
            $this->set('productinfo', $productinfo);
            $this->set('email', $email);
            $this->set('firstname', $firstname);
            $this->set('surl', $surl);
            $this->set('furl', $furl);
            $this->set('curl', $curl);
            $this->set('address1', $address1);
            $this->set('phone', $phone);
            $this->set('serviceProvider', $serviceProvider);
            $this->set('sandboxMode', $this->sanboxModePME);
			if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
				$this->paymentCreate($txnid, $amount, $name, $this->paymentNamePME, 'PME', 'app');
			}else{
				$this->paymentCreate($txnid, $amount, $name, $this->paymentNamePME, 'PME', 'web');
			}
        }
    }


    public function payumoneypostpayment()
    {
        if (!$_POST) {
            $this->Session->setFlash(__("Security Error. Illegal access detected"), 'flash', array('alert' => 'danger'));
            $this->redirect(array('crm' => false, 'controller' => 'Checkouts', 'action' => 'index'));
        }
        $status = $_POST['status'];
        $firstname = $_POST['firstname'];
        $amount = $_POST['amount'];
		$txnid = $_POST['txnid'];
		$mihpayid = $_POST['mihpayid'];
        $posted_hash = $_POST['hash'];
        $key = $_POST['key'];
        $productinfo = $_POST['productinfo'];
        $email = $_POST['email'];
        $salt = $this->merchantSalt;
        if (isset($_POST['additionalCharges'])) {
            $additionalCharges = $_POST['additionalCharges'];
            $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        } else {
            $retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        }
        $hash = strtolower(hash("sha512", $retHashSeq));
        if ($hash != $posted_hash) {
            $this->Session->setFlash(__("Security Error. Hash not matched"), 'flash', array('alert' => 'danger'));
            $this->redirect(array('controller' => 'Checkouts', 'action' => 'index'));
        }
		$this->loadModel('Payment');
		$paymentArr = $this->Payment->find('first', array('conditions' => array('token' => $txnid)));
        if ($status === "success") {
            $mobileStatus = "Success";
            $transactionId = $mihpayid;
            $token = $txnid;
            if (!$this->orderComplete($token, $transactionId)) {
				return $this->redirect(array('crm' => true, 'controller' => 'Payments', 'action' => 'index'));
            }
        } elseif ($status === "pending") {
            $mobileStatus = "Pending";

        } elseif ($status === "failure") {
            $mobileStatus = "Failed";
            if ($paymentArr) {
                $this->Payment->save(array('id' => $paymentArr['Payment']['id'], 'status' => 'Cancelled','transaction_id'=>$mihpayid));
            }
        } else {
            $mobileStatus = "Failed";
        }
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->redirect(array('rest' => true, 'controller' => 'Checkouts', 'action' => 'success3050', $mobileStatus));
        } else {
            $this->redirect(array('crm' => true, 'controller' => 'Payments', 'action' => 'index'));
        }
    }

    public function ccavenue()
    {
        $this->layout = null;
        $this->layout = null;
        $carts = $this->Cart->readPackage();
        $recordArr = $this->Checkout->cartProduct($carts);
        foreach ($recordArr as $Prodrecors) {
            $Prodnames[] = $Prodrecors['name'];
        }
        $name = implode(',', $Prodnames);
        $amount = $this->getTotalAmount();
        $name = __('Package Purchase');
        $description = __('Package Purchase');
        if ($recordArr) {
            $currency = $this->currencyType;
            $language = "EN";
            $orderId = substr(rand() . time(), 0, 15);
            $tid = substr(rand() . time(), 0, 15);
            $merchant_id = $this->merchantIdAvenue;  // Merchant id(also User_Id)
            $access_code = $this->accessCode;  // Access id(also access_Code)
            $amount = $amount;  // your script should substitute the amount here in the quotes provided here
            $order_id = $orderId;        //your script should substitute the order description here in the quotes provided here
            $redirectUrl = $this->siteDomain . '/Checkouts/ccavenuepostpayment';//your redirect URL where your customer will be redirected after authorisation from CCAvenue
            $cancelUrl = $this->siteDomain . '/crm/Payments/index';
            $billing_name = $this->userValue['Student']['name'];
            $billing_address = $this->userValue['Student']['address'];
            $billing_city = '';
            $billing_state = '';
            $billing_zip = '';
            $billing_country = '';
            $billing_tel = $this->userValue['Student']['phone'];
            $billing_email = $this->userValue['Student']['email'];
            $delivery_name = '';
            $delivery_address = '';
            $delivery_city = '';
            $delivery_state = '';
            $delivery_zip = '';
            $delivery_country = '';
            $delivery_tel = '';
            $additionalInfo1 = '';
            $additionalInfo2 = '';
            $additionalInfo3 = '';
            $additionalInfo4 = '';
            $additionalInfo5 = '';
            $promoCode = '';
            $customerIdentifier = '';
            $workingKey = $this->workingKey;    //Put in the 32 bit alphanumeric key in the quotes provided here.
            $merchant_data = 'tid=' . $tid . '&merchant_id=' . $merchant_id . '&order_id=' . $order_id . '&amount=' . $amount . '&currency=' . $currency . '&redirect_url=' . $redirectUrl . '&cancel_url=' . $cancelUrl . '&language=' . $language . '&billing_name=' . $billing_name . '&billing_address=' . $billing_address . '&billing_city=' . $billing_city . '&billing_state=' . $billing_state . '&billing_zip=' . $billing_zip . '&billing_country=' . $billing_country . '&billing_tel=' . $billing_tel . '&billing_email=' . $billing_email . '&delivery_name=' . $delivery_name . '&delivery_address=' . $delivery_address . '&delivery_city=' . $delivery_city . '&delivery_state=' . $delivery_state . '&delivery_zip=' . $delivery_zip . '&delivery_country=' . $delivery_country . '&delivery_tel=' . $delivery_tel . '&merchant_param1=' . $additionalInfo1 . '&merchant_param2=' . $additionalInfo2 . '&merchant_param3=' . $additionalInfo3 . '&merchant_param4=' . $additionalInfo4 . '&merchant_param5=' . $additionalInfo5 . '&promo_code=' . $promoCode . '&customer_identifier=' . $customerIdentifier . '&';
            $encrypted_data = $this->Checkout->encrypt($merchant_data, $workingKey); // Method for encrypting the data.
            $this->set('encrypted_data', $encrypted_data);
            $this->set('access_code', $access_code);
            $this->set('ccavenueUrl', $this->gatewayUrl);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->paymentCreate($orderId, $amount, $description, $this->paymentNameCAE, 'CAE', 'app');
            } else {
                $this->paymentCreate($orderId, $amount, $description, $this->paymentNameCAE, 'CAE', 'web');
            }

        } else {
            $this->Session->setFlash(__('Invalid Amount'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function ccavenuepostpayment()
    {
        $workingKey = $this->workingKey;
        $encResponse = $_POST['encResp'];
        $rcvdString = $this->Checkout->decrypt($encResponse, $workingKey);
        $order_status = "";
        $token = "";
        $transactionId = "";
        $amount = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($i == 0)
                $token = $information[1];
            if ($i == 1)
                $transactionId = $information[1];
            if ($i == 3)
                $order_status = $information[1];
            if ($i == 10)
                $amount = $information[1];
        }
		$this->loadModel('Payment');
		$paymentArr = $this->Payment->find('first', array('conditions' => array('token' => $token)));
        if ($order_status === "Success") {
            $mobileStatus = "Success";
            $details = json_encode($decryptValues);
            if ($this->orderComplete($token, $transactionId)) {
                $this->Session->setFlash(__('Package Order successfully done'), 'flash', array('alert' => 'success'));
            } else {
                $this->Session->setFlash(__('Payment not done'), 'flash', array('alert' => 'danger'));
            }
        } else if ($order_status === "Aborted") {
            $mobileStatus = "Failed";
           if ($paymentArr) {
                $this->Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Cancelled'));
            }
            $this->Session->setFlash(__("Thank you for shopping with us. We will keep you posted regarding the status of your order through e-mail"), 'flash', array('alert' => 'danger'));

        } else if ($order_status === "Failure") {
            $mobileStatus = "Failed";
            if ($paymentArr) {
                $this->Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Cancelled'));
            }
            $this->Session->setFlash(__("Thank you for shopping with us. However,the transaction has been declined"), 'flash', array('alert' => 'danger'));
        } else if ($order_status === "Initiated") {
            $mobileStatus = "Failed";
            $this->loadModel('Payment');
            $paymentArr = $this->Payment->find('first', array('conditions' => array('token' => $token)));
            if ($paymentArr) {
                $this->Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Cancelled'));
            }
            $this->Session->setFlash(__("Thank you for shopping with us. However,the transaction has been declined"), 'flash', array('alert' => 'danger'));
        } else {
            $mobileStatus = "Failed";
            $this->Session->setFlash(__("Thank you for shopping with us. Please check My Payments menu for status"), 'flash', array('alert' => 'danger'));
        }
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return $this->redirect(array('rest' => true, 'controller' => 'Checkouts', 'action' => 'success3050', $mobileStatus));
        } else {
            return $this->redirect(array('crm' => true, 'controller' => 'Payments', 'action' => 'index'));
        }
    }

    private function orderComplete($token, $transactionId, $correlationId = null, $timestamp = null)
    {
        $this->loadModel('Payment');
        $this->loadModel('ExamOrder');
        $paymentArr = $this->Payment->find('first', array(
                'conditions' => array('Payment.token' => $token, 'Payment.status' => 'Pending'),
                'recursive' => 2
            )
        );
        if ($paymentArr) {
            $packageDetail = $this->Checkout->packageExamOrder($paymentArr);
            $this->Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'correlation_id' => $correlationId, 'timestamp' => $timestamp, 'status' => 'Approved'));
			$this->loadModel('PackagesPayment');
			$this->PackagesPayment->updateAll(array('status'=>"'Approved'"),array('payment_id'=>$paymentArr['Payment']['id']));
            $this->orderEmail($packageDetail, $transactionId, $paymentArr['Payment']['amount'],$paymentArr['Payment']['coupon_amount'],$paymentArr['Payment']['student_id']);
			if ($paymentArr['Payment']['coupon_id']) {
				$this->loadModel('CouponsStudent');
				$this->CouponsStudent->updateAll(array('status' => 1), array('coupon_id' => $paymentArr['Payment']['coupon_id'],'student_id'=> $paymentArr['Payment']['student_id'],'status' => 0));
			}
            return true;
        } else {
            return false;
        }
    }

    private function orderEmail($packageDetail, $transactionId, $totalAmount,$couponAmount,$studentId)
    {
        if ($packageDetail == null) {
            return false;
        }
        try {
            $siteName = $this->siteName;
            $siteEmailContact = $this->siteEmailContact;
            $this->loadModel('Student');
            $studentArr=$this->Student->findById($studentId);
            $studentName = $studentArr['Student']['name'];
            $email = $studentArr['Student']['email'];
            $mobileNo = $studentArr['Student']['phone'];
			$netAmount=$totalAmount-$couponAmount;
            $currency = '<img src="' . Router::url('/', true) . 'img/currencies/' . $this->currencyImage . '"> ';
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
                    if ($this->emailSettype == "Smtp") {
                        $Email->config($this->emailSettingsArr);
                    }
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
        return true;
    }
}
