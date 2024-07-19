<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class ForgotsController extends AppController
{
    public $components = array('CustomFunction', 'RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function crm_password()
    {
        $this->layout = 'login';
        if ($this->request->is(array('post', 'put'))) {
            $email = $this->request->data['Forgot']['email'];
			if ($this->Forgot->find('count', array('conditions' => array(
					'OR'=>array(
						array('Forgot.email' => $email),
						array('Forgot.phone'=>$email),
					)))) == 0) {
                $this->Session->setFlash(__('Email id or Mobile No. is not registered in system'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('controller' => 'Forgots', 'action' => 'password'));
            } else {
				$userValue = $this->Forgot->find('first', array('conditions' => array(
					'OR'=>array(
						array('Forgot.email' => $email),
						array('Forgot.phone'=>$email),
					)
				)));
                $code = $this->CustomFunction->generateNumber(6);
				$studentName = $userValue['Forgot']['name'];
				$email = $userValue['Forgot']['email'];
				$mobileNo = $userValue['Forgot']['phone'];
				$siteName = $this->siteName;
                $this->Forgot->save(array('id' => $userValue['Forgot']['id'], 'presetcode' => $code));
                try {
                    if ($this->emailNotification) {
                        /* Send Email */
                        $this->loadModel('Emailtemplate');
                        $emailTemplateArr = $this->Emailtemplate->findByType('SFP');
                        if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
                            $rand1 = $this->CustomFunction->generate_rand(35);
                            $rand2 = rand();
                            $url = "$this->siteDomain/crm/Forgots/presetcode/$code/$rand1/$rand2";
                            $siteEmailContact = $this->siteEmailContact;
							$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
								'{#studentName#}' => $studentName,
								'{#url#}' => $url,
								'{#code#}' => $code,
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
                        $smsTemplateArr = $this->Smstemplate->findByType('SFP');
                        if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
                            $url = "$this->siteDomain";
							$message = strtr($smsTemplateArr['Smstemplate']['description'], [
								'{#studentName#}' => $studentName,
								'{#url#}' => $url,
								'{#code#}' => $code,
								'{#siteName#}' => $siteName
							]);
                            $this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
                        }
                        /* End Sms */
                    }
                    $this->Session->setFlash(__('Your password reset link is sent to your registered email id  or Code send to your Mobile No.'), 'flash', array('alert' => 'success'));
                    $this->redirect(array('controller' => 'Forgots', 'action' => 'presetcode'));
                } catch (Exception $e) {
                    $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                }

            }
        }
    }

    public function crm_presetcode($id = null)
    {
        $this->layout = 'login';
        if ($this->request->is(array('post', 'put'))) {
            $id = $this->request->data['Forgot']['verificationcode'];
        }
        if ($id) {
            $post = $this->Forgot->find('first', array('conditions' => array('Forgot.presetcode' => $id)));
            if (!$post) {
                $this->Session->setFlash(__('Incorrect code'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('controller' => 'Forgots', 'action' => 'presetcode'));
            } else {
                $this->Session->write('Parc', $id);
                $this->redirect(array('controller' => 'Forgots', 'action' => 'reset'));
            }
        }
    }

    public function crm_reset()
    {
        $this->layout = 'login';
        try {
            if ($this->Session->read('Parc')) {
                if ($this->request->is(array('post', 'put'))) {
                    $userValue = $this->Forgot->find('first', array('conditions' => array('Forgot.presetcode' => $this->Session->read('Parc'))));
                    $passwordValue = $this->request->data['Forgot']['password'];
                    $passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
                    $password = $passwordHasher->hash($passwordValue);
                    $this->Forgot->save(array('id' => $userValue['Forgot']['id'], 'password' => $password, 'presetcode' => NULL));
                    $this->Session->delete('Parc');
                    $this->Session->setFlash(__('Password Changed Successfully'), 'flash', array('alert' => 'success'));
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
            } else {
                $this->redirect(array('controller' => 'Forgots', 'action' => 'presetcode'));
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            $this->redirect(array('controller' => 'Forgots', 'action' => 'reset'));
        }
    }

    public function rest_password()
    {
        $message = __('Invalid Post');
        $status = false;
        if ($this->request->is('post')) {
            if (isset($this->request->data['email'])) {
                $email = $this->request->data['email'];
                if ($this->Forgot->find('count', array('conditions' => array(
                	'or'=>array(
						array('Forgot.email' => $email),
						array('Forgot.phone'=>$email),
					)))) == 0) {
                    $message = __('Email id or Mobile No. is not registered in system');
                } else {
					$userValue = $this->Forgot->find('first', array('conditions' => array(
						'OR'=>array(
							array('Forgot.email' => $email),
							array('Forgot.phone'=>$email),
						)
					)));
					$code = $this->CustomFunction->generateNumber(6);
                    $mobileNo = $userValue['Forgot']['phone'];
					$email = $userValue['Forgot']['email'];
                    $this->Forgot->save(array('id' => $userValue['Forgot']['id'], 'presetcode' => $code));
                    try {
                        if ($this->emailNotification) {
                            /* Send Email */
                            $this->loadModel('Emailtemplate');
                            $emailTemplateArr = $this->Emailtemplate->findByType('SFP');
                            if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
                                $rand1 = $this->CustomFunction->generate_rand(35);
                                $rand2 = rand();
                                $studentName = $userValue['Forgot']['name'];
                                $url = "$this->siteDomain/crm/Forgots/presetcode/$code/$rand1/$rand2";
                                $siteName = $this->siteName;
                                $siteEmailContact = $this->siteEmailContact;
								$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#url#}' => $url,
									'{#code#}' => $code,
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
                            $smsTemplateArr = $this->Smstemplate->findByType('SFP');
                            if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
                                $url = "$this->siteDomain";
								$message = strtr($smsTemplateArr['Smstemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#url#}' => $url,
									'{#code#}' => $code,
									'{#siteName#}' => $siteName
								]);
								$this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
                            }
                            /* End Sms */
                        }
                        $message = __('Your password reset link is sent to your registered email id  or Code send to your Mobile No.');
                        $status = true;
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                    }
                }
            } else {
                $message = __('Email or Mobile No. can not not empty.');
            }
        } else {
            $message = __('GET request not allowed!');
        }
        $this->set(compact('status', 'message'));
        $this->set('_serialize', array('status', 'message'));
    }

    public function rest_reset_password()
    {
        $message = __('Invalid Post');
        $status = false;
        if ($this->request->is('post')) {
            if (isset($this->request->data['verification_code'])) {
                $id = $this->request->data['verification_code'];
                $post = $this->Forgot->find('first', array('conditions' => array('Forgot.presetcode' => $id)));
                if ($post) {
                    $passwordValue = $this->request->data['password'];
                    $passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
                    $password = $passwordHasher->hash($passwordValue);
                    $this->Forgot->save(array('id' => $post['Forgot']['id'], 'password' => $password, 'presetcode' => NULL));
                    $message = __('Password Changed Successfully');
                    $status = true;
                } else {
                    $message = __('Incorrect verification code');
                }
            } else {
                $message = __('Verification code can not not empty.');
            }
        } else {
            $message = __('GET request not allowed!');
        }
        $this->set(compact('status', 'message'));
        $this->set('_serialize', array('status', 'message'));
    }
}
