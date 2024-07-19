<?php
App::uses('AppController', 'Controller');
App::uses('CheckoutsController', 'Controller');
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');
App::uses('Paypal', 'Paypal.Lib');

class CartsController extends AppController
{
    public $uses = array('Package', 'Cart');

    public function buy()
    {
        $this->autoRender = false;
        $this->request->onlyAllow('ajax');
        $id = $this->request->query('prodId');
        if (!$id) {
            return $this->redirect(array('action' => 'view'));
        }
        $post = $this->Package->findById($id);
        if (!$post)
            return $this->redirect(array('action' => 'view'));
        $this->Cart->addPackage($id);
        echo $this->Cart->getCount();
    }

    public function view()
    {
        $this->loadModel('Package');
        $carts = $this->Cart->readPackage();
        $products = array();
        $id = $this->request->query('prodId');
        if (null != $carts) {
            foreach ($carts as $productId => $count) {
                $product = $this->Package->read(null, $productId);
                $product['Package']['count'] = $count;
                $products[] = $product;
            }
        }
        $showPackageTypeArr = array();
        if ($this->showPackageType == 0) {
            $showPackageTypeArr = array('Package.package_type <>' => 'F');
        }
        $response = $this->Package->find('all', array('conditions' => array('Package.status' => 'Active', $showPackageTypeArr), 'order' => array('Package.id' => 'ASC'),'limit'=>12));
        $this->set('Packages', $response);
        $this->set('id', $id);
        $this->set(compact('products'));
        $this->set('totalAmount', $this->getTotalAmount());
    }

    public function crm_view()
    {
        $this->redirect(array('crm' => false, 'controller' => 'Carts', 'action' => 'view'));
    }

    public function addedtobag($id)
    {
        $this->loadModel('Package');
        if (!$id) {
            $this->Session->setFlash(__('Invalid post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('controller' => 'Packages', 'action' => 'index'));
        }
        $carts = $this->Cart->readPackage();
        $products = array();

        if (null != $carts) {
            foreach ($carts as $productId => $count) {
                $product = $this->Package->read(null, $productId);
                $product['Package']['count'] = $count;
                $products[] = $product;
            }
        }

        $response = $this->Package->find('all', array('conditions' => array('Package.status' => 'Active'), 'order' => array('Package.id' => 'ASC')));
        $this->set('Packages', $response);

        $this->set('id', $id);
        $this->set(compact('products'));

    }

    public function update()
    {
        if ($this->request->is('post')) {
            if (!empty($this->request->data)) {
                $cart = array();
                foreach ($this->request->data['Cart']['count'] as $index => $count) {
                    if ($count > 0) {
                        $productId = $this->request->data['Cart']['package_id'][$index];
                        $cart[$productId] = $count;
                    }
                }
                $this->Cart->savePackage($cart);
            }
        }
        $this->redirect(array('action' => 'view'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->redirect(array('action' => 'view'));
        }
        $carts = $this->Cart->readPackage();
        unset($carts[$id]);
        $this->Cart->savePackage($carts);
        if ($this->Session->check('couponArr')) {
            $this->redirect(array('controller' => 'Carts', 'action' => 'couponDelete', 'Yes'));
        } else {
            $this->redirect(array('controller' => 'Carts', 'action' => 'View'));
        }
    }

    public function viewajax()
    {
        $this->layout = null;
        $this->view();
    }


    public function coupon()
    {
        $this->authenticate();
        $this->autoRender = false;
        $msg = null;
        $couponCode = $_POST['redeem_code'];
        $totalAmount = $this->getTotalAmount();
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
                        $discountRate = ($totalAmount * $couponArr['Coupon']['amount']) / 100;
                    } else {
                        $discountRate = $couponArr['Coupon']['amount'];
                    }
                    $discountRate = number_format(floor($discountRate), 2);
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
                    $finalAmount = $totalAmount - $discountRate;
                    $sc_msg = __("Coupon '%s' applied", $couponArr['Coupon']['code']) . '&nbsp;<a href="checkout/couponDelete">Remove</a>';
                    $couponMessage = '<div class="col-sm-8">' . __('Coupon Discount') . '</div><div class="col-sm-4">-' . $this->currency . $discountRate . '</div>';
                    echo '<div class="coupon_applied">' . $sc_msg . '</div>' . "^" . $finalAmount . "^" . $couponMessage;
                }
            } else {
                $msg = "You have already used this Coupon";
            }
        } else {
            $msg = "You have already used this Coupon";
        }
        if (strlen($msg) > 0) {
            echo '<div class="error_message">' . $msg . '</div>';
        }
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
                        $discountRate = ($totalAmount * $couponArr['Coupon']['amount']) / 100;
                    } else {
                        $discountRate = $couponArr['Coupon']['amount'];
                    }
                    $discountRate = number_format(floor($discountRate), 2);
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


}
