<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class RegistersController extends AppController
{
	var $components = array('CustomFunction', 'RequestHandler');
	var $helpers = array('Captcha');

	public function beforeFilter()
	{
		parent::beforeFilter();
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'crm') {
			return $this->redirect(array('crm' => false, 'controller' => 'Registers', 'action' => 'index'));
		}
	}

	public function captcha()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		if (!isset($this->Captcha)) { //if Component was not loaded through $components array()
			$this->Captcha = $this->Components->load(
				'Captcha',
				array(
					'width' => 150,
					'height' => 50,
					'theme' => 'random', //possible values : default, random ; No value means 'default'
				)
			); //load it
		}
		$this->Captcha->create();
	}


	public function index($urlId = null)
	{
		$this->Captcha = $this->Components->load('Captcha', array('captchaType' => $this->captchaType, 'jquerylib' => false, 'modelName' => 'Register', 'fieldName' => 'captcha')); //load it
		if (!isset($this->Captcha)) {
			//if Component was not loaded throug $components array()
			$this->Captcha = $this->Components->load('Captcha'); //load it
		}

		$this->loadModel('Group');
		$this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'))));
		if (isset($this->request->data['Register']['captcha'])) {
			$plainPassword = $this->request->data['Register']['password'];
			if ($this->request->data['Register']['captcha'] == $this->Captcha->getVerCode()) {
				if ($this->request->is('post')) {
					try {
						if (is_array($this->request->data['StudentGroup']['group_name'])) {
							$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
							$password = $this->request->data['Register']['password'];
							$this->request->data['Register']['password'] = $passwordHasher->hash($password);
							$this->request->data['Register']['reg_code'] = $this->CustomFunction->generateNumber(6);
							$this->request->data['Register']['reg_status'] = "Live";
							$this->request->data['Register']['renewal_date'] = $this->currentDate;
							unset($this->request->data['Register']['status']);
							if ($this->Register->save($this->request->data)) {
								$this->loadModel('StudentGroup');
								$this->request->data['StudentGroup']['student_id'] = $this->Register->id;
								if (is_array($this->request->data['StudentGroup']['group_name'])) {
									foreach ($this->request->data['StudentGroup']['group_name'] as $key => $value) {
										$this->StudentGroup->create();
										$this->request->data['StudentGroup']['group_id'] = $value;
										$this->StudentGroup->save($this->request->data);
									}
								}
								$studentName = $this->request->data['Register']['name'];
								$code = $this->request->data['Register']['reg_code'];
								$email = $this->request->data['Register']['email'];
								$mobileNo = $this->request->data['Register']['phone'];
								$rand1 = $this->CustomFunction->generate_rand(35);
								$rand2 = rand();
								$url = "$this->siteDomain/crm/Emailverifications/emailcode/$code/$rand1/$rand2";
								$siteName = $this->siteName;
								$siteEmailContact = $this->siteEmailContact;
								if ($this->emailNotification) {
									/* Send Email */
									$this->loadModel('Emailtemplate');
									$emailTemplateArr = $this->Emailtemplate->findByType('SRN');
									if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
										$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
											'{#studentName#}' => $studentName,
											'{#email#}' => $email,
											'{#password#}' => $password,
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
									$smsTemplateArr = $this->Smstemplate->findByType('SRN');
									if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
										$url = "$this->siteDomain";
										$message = strtr($smsTemplateArr['Smstemplate']['description'], [
											'{#studentName#}' => $studentName,
											'{#email#}' => $email,
											'{#password#}' => $password,
											'{#url#}' => $url,
											'{#code#}' => $code,
											'{#siteName#}' => $siteName
										]);
										$this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
									}
									/* End Sms */
								}
								$this->Session->setFlash(__('A verification Code send to your Email inbox or Spam or Mobile No.'), 'flash', array('alert' => 'success'));
								return $this->redirect(array('crm' => true, 'controller' => 'Emailverifications', 'action' => 'index'));
							}
							$this->request->data['Register']['password'] = $plainPassword;
						} else {
							$this->Session->setFlash(__('Please select any group'), 'flash', array('alert' => 'danger'));
						}
					} catch (Exception $e) {
						$this->request->data['Register']['password'] = $plainPassword;
						$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
						return $this->redirect(array('crm' => true, 'controller' => 'Emailverifications', 'action' => 'index'));
					}
				}
			} else {
				$this->request->data['Register']['password'] = $plainPassword;
				$this->Session->setFlash(__('Invalid Security Code'), 'flash', array('alert' => 'danger'));
			}
		}
		$this->set('urlId', $urlId);
	}

	public function rest_save()
	{
		$message = __('Invalid Post');
		$status = false;
		if ($this->request->is('post')) {
			try {
				if (isset($this->request->data['responses']) && strlen($this->request->data['responses']['group_name']) > 0) {
					$responseArr = $this->request->data['responses'];
					$plainPassword = $responseArr['password'];
					$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
					$password = $responseArr['password'];
					$responseArr['password'] = $passwordHasher->hash($password);
					$responseArr['reg_code'] = $this->CustomFunction->generateNumber(6);
					$responseArr['reg_status'] = "Live";
					$responseArr['renewal_date'] = $this->currentDate;
					$public_key = $this->CustomFunction->generate_rand(15);
					$private_key = $passwordHasher->hash($public_key . time());
					$responseArr['public_key'] = $public_key;
					$responseArr['private_key'] = $private_key;
					unset($responseArr['status']);
					if ($this->Register->save($responseArr)) {
						$this->loadModel('StudentGroup');
						$studentGroupArr['student_id'] = $this->Register->id;
						$student_id = $this->Register->id;
						$groupArr = explode(",", $responseArr['group_name']);
						foreach ($groupArr as $key => $value) {
							$this->StudentGroup->create();
							$studentGroupArr['group_id'] = $value;
							$this->StudentGroup->save($studentGroupArr);
						}
						$studentName = $responseArr['name'];
						$code = $responseArr['reg_code'];
						$email = $responseArr['email'];
						$mobileNo = $responseArr['phone'];
						$rand1 = $this->CustomFunction->generate_rand(35);
						$rand2 = rand();
						$url = "$this->siteDomain/crm/Emailverifications/emailcode/$code/$rand1/$rand2";
						$siteName = $this->siteName;
						$siteEmailContact = $this->siteEmailContact;
						if ($this->emailNotification) {
							/* Send Email */
							$this->loadModel('Emailtemplate');
							$emailTemplateArr = $this->Emailtemplate->findByType('SRN');
							if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
								$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#email#}' => $email,
									'{#password#}' => $password,
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
							$smsTemplateArr = $this->Smstemplate->findByType('SRN');
							if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
								$url = "$this->siteDomain";
								$message = strtr($smsTemplateArr['Smstemplate']['description'], [
									'{#studentName#}' => $studentName,
									'{#email#}' => $email,
									'{#password#}' => $password,
									'{#url#}' => $url,
									'{#code#}' => $code,
									'{#siteName#}' => $siteName
								]);
								$this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
							}
							/* End Sms */
						}
						$message = __('A verification Code send to your Email inbox or Spam');
						$status = true;
					} else {
						$errors = $this->Register->validationErrors;
						if (count($errors) == 1 && isset($errors['StudentsPhoto'])) {
							$errors1 = array_shift($errors);
							$errors2 = array_shift($errors1);
							$errors3 = array_shift($errors2);
							$errors4 = array_shift($errors3);
						} else {
							$errors2 = array_shift($errors);
							$errors4 = array_shift($errors2);
						}
						$message = ($errors4);
						$status = false;
					}
				} else {
					$message = __('Please select any group');
					$status = false;
				}
			} catch (Exception $e) {
				$message = $e->getMessage();
				if ($message == "Could not send email.") {
					$status = true;
				} else {
					$status = false;
				}
			}
		} else {
			$message = __('GET request not allowed!');
			$status = false;
		}
		$this->set(compact('status', 'message', 'student_id', 'public_key', 'private_key'));
		$this->set('_serialize', array('status', 'message', 'student_id', 'public_key', 'private_key'));
	}

	public function rest_group()
	{
		$message = __('Invalid Post');
		$status = false;
		try {
			$this->loadModel('Group');
			$this->set('group', $this->Group->find('list', array('fields' => array('id', 'group_name'))));
			$message = __('Group fetch successfully');
			$status = true;
		} catch (Exception $e) {
			$message = $e->getMessage();
			$status = false;
		}
		$this->set(compact('status', 'message'));
		$this->set('_serialize', array('status', 'message', 'group'));

	}

	public function login()
	{

		if (empty($this->data['Register']['email']) == false) {
			$this->loadModel('Student');
			$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
			$password = $passwordHasher->hash($this->request->data['Register']['password']);
			$user = $this->Student->find(
				'first',
				array(
					'conditions' => array(
						'OR' => array(
							'Student.email' => $this->data['Register']['email'],
							'Student.phone' => $this->data['Register']['email']
						),
						'Student.password' => $password
					)
				)
			);
			if ($user != false) {
				if ($user['Student']['status'] == "Active") {
					$expiryDays = $user['Student']['expiry_days'];
					if ($expiryDays > 0) {
						$expiryDate = date('Y-m-d', strtotime($user['Student']['renewal_date'] . "+$expiryDays days"));
						if ($this->currentDate > $expiryDate) {
							$this->Session->setFlash(__('Your account has expired. Please contact administrator'), 'flash', array('alert' => 'danger'));
							$this->redirect(array('action' => 'index'));
							exit();
						}
					}
					$recordArr = array('Student' => array('id' => $user['Student']['id'], 'last_login' => $this->currentDateTime));
					$this->Student->save($recordArr);
					$this->Session->setFlash(__('You have been logged in successfully'), 'flash', array('alert' => 'success'));
					$this->Session->write('Student', $user);
					if ($this->Session->check('FreeCheckoutPackage')) {
						$this->redirect(array('crm' => true, 'controller' => 'Exams', 'action' => 'free'));
					} elseif ($this->cartCount > 0) {
						$this->redirect(array('crm' => false, 'controller' => 'Checkouts', 'action' => 'index'));
					} else {
						// $this->redirect(array('crm' => true, 'controller' => 'Dashboards', 'action' => 'index'));
						$this->redirect(array('crm' => true, 'controller' => 'Exams', 'action' => 'free'));
					}
					exit();
				} elseif ($user['Student']['status'] == "Pending" && $user['Student']['reg_status'] == "Live") {
					$this->Session->setFlash(__('Your email not verified! Please click on link sent to your email inbox or spam'), 'flash', array('alert' => 'danger'));
					$this->redirect(array('action' => 'index'));
					exit();
				} else {
					$status = __($user['Student']['status']);
					$this->Session->setFlash(__('You are %s Member! Please contact administrator', $status), 'flash', array('alert' => 'danger'));
					$this->redirect(array('action' => 'index'));
					exit();
				}
			} else {
				$this->Session->setFlash(__('Incorrect username/password'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
				exit();
			}
		}
	}


}