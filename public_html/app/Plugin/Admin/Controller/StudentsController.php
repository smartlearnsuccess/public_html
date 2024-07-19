<?php
App::uses('CakeTime', 'Utility');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
App::uses('CakeEmail', 'Network/Email');

class StudentsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg', 'CustomFunction');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Student.id' => 'desc'));
    var $paginate1 = array('page' => 1, 'order' => array('Wallet.id' => 'desc'));

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->adminId = $this->adminValue['User']['id'];
    }

    public function index()
    {

        try {
            $this->Student->UserWiseGroup($this->userGroupWiseId);
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['joins'] = array(array('table' => 'student_groups', 'type' => 'INNER', 'alias' => 'StudentGroup', 'conditions' => array('Student.id=StudentGroup.student_id')));
            $this->Paginator->settings['fields'] = array('Student.id', 'Student.email', 'Student.name', 'Student.phone', 'Student.reg_status', 'Student.created', 'Student.status', 'Student.reg_code');
            $this->Paginator->settings['group'] = array('Student.id', 'Student.email', 'Student.name', 'Student.phone', 'Student.reg_status', 'Student.created', 'Student.status', 'Student.reg_code');
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $cond = array('StudentGroup.group_id' => $this->userGroupWiseId);
            $this->Paginator->settings['conditions'] = array($this->Student->parseCriteria($this->Prg->parsedParams()), $cond);
            $studentArr = $this->Paginator->paginate();
            $this->set('Student', $studentArr);
            $this->set('frontExamPaid', $this->frontExamPaid);
            if ($this->request->is('ajax')) {
                $this->render('index', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function add()
    {
        $this->loadModel('Group');
        $this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
        if ($this->request->is('post')) {
            try {
                $password = $this->request->data['Student']['password'];
                $this->request->data['Student']['reg_status'] = "Done";
                $this->request->data['Student']['renewal_date'] = $this->currentDate;
                if (is_array($this->request->data['StudentGroup']['group_name'])) {
                    $this->Student->create();
                    $this->Student->unbindValidation('remove', array('amount', 'action', 'remarks'), true);
                    if ($this->Student->save($this->request->data)) {
                        $this->loadModel('StudentGroup');
                        $studentId = $this->Student->id;
                        foreach ($this->request->data['StudentGroup']['group_name'] as $groupId) {
                            $studentGroup[] = array('student_id' => $studentId, 'group_id' => $groupId);
                        }
                        $this->StudentGroup->create();
                        $this->StudentGroup->saveAll($studentGroup);
                        $email = $this->request->data['Student']['email'];
                        $studentName = $this->request->data['Student']['name'];
                        $mobileNo = $this->request->data['Student']['phone'];
                        $siteName = $this->siteName;
                        $siteEmailContact = $this->siteEmailContact;
                        $url = $this->siteDomain;
                        if ($email) {
                            if ($this->emailNotification) {
                                /* Send Email */
                                $this->loadModel('Emailtemplate');
								$emailTemplateArr = $this->Emailtemplate->findByType('SLC');
                                if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
									$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
										'{#studentName#}' => $studentName,
										'{#email#}' => $email,
										'{#password#}' => $password,
										'{#url#}' => $url,
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
                                    /* End Email */
                                }
                            }
                        }
                        if ($this->smsNotification) {
                            /* Send Sms */
                            $this->loadModel('Smstemplate');
                            $smsTemplateArr = $this->Smstemplate->findByType('SLC');
                            if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
                                $url = $this->siteDomain;
								$message = strtr($smsTemplateArr['Smstemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#email#}' => $email,
									'{#password#}' => $password,
									'{#url#}' => $url,
									'{#siteName#}' => $siteName
								]);
                                $this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
                            }
                            /* End Sms */
                        }
                        $this->Session->setFlash(__('Student added Successfully'), 'flash', array('alert' => 'success'));
                        return $this->redirect(array('action' => 'add'));
                    }
                } else {
                    $this->Session->setFlash(__('Please Select atleast one group'), 'flash', array('alert' => 'danger'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            }
        }
    }

    public function edit($id = null)
    {
        $this->Student->UserWiseGroup($this->userGroupWiseId);
        $this->loadModel('Group');
        $this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $ids = explode(",", $id);
        $post = array();
        foreach ($ids as $id) {
            $this->Student->UserWiseGroup($this->userGroupWiseId);
            $post[] = $this->Student->studentRecord($id, $this->userGroupWiseId);
        }
        $this->set('Student', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $isSave = true;
            try {
                foreach ($this->request->data as $k => $value) {
                    if (!is_array($value['StudentGroup']['group_name'])) {
                        $this->Session->setFlash(__('Please Select any group'), 'flash', array('alert' => 'danger'));
                        $isSave = false;
                        break;
                    }
                    if ($value['Student']['status'] == "Active") {
                        $this->request->data[$k]['Student']['reg_status'] = "Done";
                        $this->request->data[$k]['Student']['reg_code'] = "";
                    }
                }
                if ($isSave == true) {
                    $this->Student->unbindValidation('remove', array('amount', 'action', 'remarks', 'password', 'photo'), true);
                    if ($this->Student->saveAll($this->request->data)) {

                        $this->loadModel('StudentGroup');
                        foreach ($this->request->data as $k => $value) {
                            $studentId = $value['Student']['id'];
                            $this->StudentGroup->deleteAll(array('StudentGroup.student_id' => $studentId, 'StudentGroup.group_id' => $this->userGroupWiseId));
                            foreach ($value['StudentGroup']['group_name'] as $groupId) {
                                $studentGroup[] = array('student_id' => $studentId, 'group_id' => $groupId);
                            }
                        }
                        $this->StudentGroup->create();
                        $this->StudentGroup->saveAll($studentGroup);
                        $this->Session->setFlash(__('Student has been updated'), 'flash', array('alert' => 'success'));
                        return $this->redirect(array('action' => 'index'));
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = 'ajax';
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function deleteall()
    {
        try {
            if ($this->request->is('post')) {
                $this->loadModel('StudentGroup');
                foreach ($this->data['Student']['id'] as $key => $value) {
                    $this->StudentGroup->deleteAll(array('StudentGroup.student_id' => $value, 'StudentGroup.group_id' => $this->userGroupWiseId));
                }
                $prefix = $this->Student->tablePrefix;
                $this->Student->query("DELETE `Student` FROM `" . $prefix . "students` AS `Student` LEFT JOIN `" . $prefix . "student_groups` AS `StudentGroup` ON `Student`.`id` = `StudentGroup`.`student_id` WHERE `StudentGroup`.`id` IS NULL");
                $this->Session->setFlash(__('Student has been deleted'), 'flash', array('alert' => 'success'));
            }
            $this->redirect(array('action' => 'index'));
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function view($id = null)
    {
        try {
            $this->loadModel('Payment');
            $this->layout = null;
            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            $this->Student->UserWiseGroup($this->userGroupWiseId);
            $post = $this->Student->studentRecord($id, $this->userGroupWiseId);
            if (!$post) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            if (strlen($post['Student']['photo']) > 0)
                $std_img = 'student_thumb/' . $post['Student']['photo'];
            else
                $std_img = 'User.png';
            $this->set('post', $post);
            $this->set('std_img', $std_img);
            $this->set('id', $id);

            $postArr = $this->Payment->find('all', array('conditions' => array('Payment.student_id' => $id, 'Payment.status' => 'Approved'),
                'order' => array('Payment.id' => 'desc'),
                'recursive' => 2));
            $this->set('postArr', $postArr);
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function changepass($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $post = $this->Student->studentRecord($id, $this->userGroupWiseId);
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
            try {
                $this->Student->id = $id;
                $this->Student->unbindValidation('keep', array('password'), true);
                if ($this->Student->save($this->request->data)) {
                    $this->Session->setFlash(__('Password Changed Successfully'), 'flash', array('alert' => 'success'));
                    $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = null;
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function changephoto($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $post = $this->Student->studentRecord($id, $this->userGroupWiseId);
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
            try {
                $this->Student->id = $id;
                $this->Student->unbindValidation('keep', array('photo'), true);
                if ($this->Student->save($this->request->data)) {
                    $this->Session->setFlash(__('Photo Changed Successfully'), 'flash', array('alert' => 'success'));
                    $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = null;
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }


    public function salesPackage($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $post = $this->Student->studentRecord($id, $this->userGroupWiseId);
        if (!$post) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $this->loadModel('Package');
        $this->set('package', $this->Package->find('list', array(
                'conditions' => array('Package.status' => 'Active', 'Package.package_type <>' => 'F'),
                'order' => array('Package.name' => 'asc')
            )
        )
        );
        if ($this->request->is(array('post', 'put'))) {
            try {
                $this->loadModel('Payment');
                $this->loadModel('Checkout');
                $carts = $this->request->data['ExamsPackage']['package_id'];
                if (null != $carts) {
                    $count = 1;
                    $this->loadModel('Package');
                    $amount = 0;
                    foreach ($carts as $productId) {
                        $product = $this->Package->findById($productId);
                        $expiryDays = $product['Package']['expiry_days'];
                        if ($expiryDays == 0) {
                            $expiryDate = null;
                        } else {
                            $expiryDate = date('Y-m-d', strtotime($this->currentDate . "+$expiryDays days"));
                        }
                        $amount = $amount + $product['Package']['amount'];
                        $packagesPayment[] = array(
                            'package_id' => $product['Package']['id'],
							'student_id' => $id,
							'status' => 'Approved',
                            'price' => $product['Package']['amount'],
                            'quantity' => $count,
                            'amount' => $count * $product['Package']['amount'],
                            'date' => $this->currentDate,
                            'expiry_date' => $expiryDate
                        );
                    }
                    $token = time() . rand();
                    $transactionId = time() . rand();
                    $amount = $amount;
                    $description = __('Purchase Package From Administrator');
                    $name = __('Administrator');
                    $type = 'ADM';
                    $paymentArr = array(
                        'Payment' => array(
                            'student_id' => $id, 'token' => $token, 'transaction_id' => $transactionId, 'amount' => $amount, 'status' => 'Approved', 'date' => $this->currentDateTime, 'remarks' => $description, 'name' => $name, 'type' => $type),
                        'Package' => $packagesPayment
                    );
                    $this->Payment->create();
                    $this->Payment->saveAll($paymentArr);
                    $paymentArrDetail = $this->Payment->find('first', array(
                            'conditions' => array(
                                'id' => $this->Payment->id),
                            'recursive' => 2)
                    );
                    $this->Checkout->packageExamOrder($paymentArrDetail);
                    $this->Session->setFlash(__('Package Sales Successfully'), 'flash', array('alert' => 'success'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Please select at least one package'), 'flash', array('alert' => 'danger'));
                }

            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = 'ajax';
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }
}
