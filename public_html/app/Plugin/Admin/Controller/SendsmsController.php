<?php
ini_set('max_execution_time', 900);

class SendsmsController extends AdminAppController
{
	public $helpers = array('Html', 'Form', 'Session');
	public $components = array('Session');

	public function index()
	{
		$this->loadModel('Group');
		$this->loadModel('Smssetting');
		$this->loadModel('Smstemplate');
		$this->loadModel('Student');
		$this->loadModel('User');
		$this->set('smsTemplate', $this->Smstemplate->find('list', array('fields' => array('id', 'name'), 'conditions' => array('status' => 'Published', 'type' => NULL), 'order' => array('name' => 'asc'))));
		$this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array("Group.id" => $this->userGroupWiseId))));
		$smsSettingArr = $this->Smssetting->findById(1);
		$smsMessage = null;
		if ($this->request->is('post')) {
			try {
				$type = $this->request->data['Sendsms']['type'];
				$emailTemplate = $this->request->data['Sendsms']['sms_template'];
				$studentId = $this->request->data['Sendsms']['student_id'];
				$teacherId = $this->request->data['Sendsms']['teacher_id'];
				$anySms = $this->request->data['Sendsms']['any_sms'];
				$message = $this->request->data['Sendsms']['message'];
				$smsTemplateId = $this->request->data['Sendsms']['sms_template'];
				if ($type == null) {
					$this->Session->setFlash(__('Please select any type in the list'), 'flash', array('alert' => 'danger'));
				} elseif ($type == 'Any' && $anySms == null) {
					$this->Session->setFlash(__('Please type any name'), 'flash', array('alert' => 'danger'));
				} else {
					$toSmsArr = null;
					if ($type == "Student") {
						if (strlen($this->request->data['Sendsms']['start_date']) > 0 || strlen($this->request->data['Sendsms']['end_date']) >0) {
							if (strlen($this->request->data['Sendsms']['start_date']) == 0 || strlen($this->request->data['Sendsms']['end_date']) ==0) {
								$this->Session->setFlash(__('Please fill start & End Date'), 'flash', array('alert' => 'danger'));
								return $this->redirect(array('action' => 'index'));
							}
						}
						$studentCondition = array();
						if ($studentId != null) {
							$studentCondition[] = array('phone' => array_values(explode(",", $studentId)));
						}
						if (isset($this->request->data['Group']['group_name']) && is_array($this->request->data['Group']['group_name'])) {
							$studentCondition[] = array('StudentGroup.group_id' => array_values($this->request->data['Group']['group_name']));
						}
						if (strlen($this->request->data['Sendsms']['start_date']) > 0 && strlen($this->request->data['Sendsms']['end_date']) > 0) {
							$startDate = CakeTime::format('Y-m-d', $this->request->data['Sendsms']['start_date']) . ' 00:00:00';
							$endDate = CakeTime::format('Y-m-d', $this->request->data['Sendsms']['end_date']) . ' 23:59:59';
							$studentCondition[] = array(
								'Student.created >=' => $startDate,
								'Student.created <=' => $endDate);
						}
						$typeArr = $this->Student->find('all', array(
							'joins' => array(array('table' => 'student_groups', 'alias' => 'StudentGroup', 'type' => 'LEFT', 'conditions' => array('Student.id=StudentGroup.student_id'))),
							'fields' => array('DISTINCT(Student.phone)'),
							'conditions' => array('Student.status'=>'Active',$studentCondition),
						));
						foreach ($typeArr as $value) {
							$toSmsArr[] = $value['Student']['phone'];
						}
						unset($value);
					}

					if ($type == "Teacher") {
						$teacherCondition = array();
						if ($teacherId != null) {
							$teacherCondition[] = array('mobile' => array_values(explode(",", $teacherId)));
						}
						$typeArr = $this->User->find('all', array('fields' => array('User.mobile'), 'conditions' => array($teacherCondition,'User.status' => 'Active')));
						foreach ($typeArr as $value) {
							$toSmsArr[] = $value['User']['mobile'];
						}
						unset($value);
					}
					if ($type == "Any") {
						$toSmsArr = explode(",", $anySms);

					}
					if ($toSmsArr) {
						$this->loadModel('Smstemplate');
						$smsTemplateArr=$this->Smstemplate->findById($smsTemplateId);
						foreach ($toSmsArr as $toSms) {
							if ($toSms) {
								$smsMessage = $this->CustomFunction->sendSms($toSms, $message, $smsSettingArr,$smsTemplateArr['Smstemplate']['dlt_template_value']);
							}
						}
						$this->Session->setFlash($smsMessage, 'flash', array('alert' => 'success'));
						return $this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('No sms to send'), 'flash', array('alert' => 'danger'));
						return $this->redirect(array('action' => 'index'));
					}
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			}
		}
	}

	public function studentsearch()
	{
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		// get the search term from URL
		$this->loadModel('Student');
		$term = $this->request->query['q'];
		$users = $this->Student->find('all', array(
			'fields' => array('Student.id', 'Student.phone', 'Student.name'),
			'joins' => array(array('table' => 'student_groups', 'alias' => 'StudentGroup', 'conditions' => array('Student.id=StudentGroup.student_id'))),
			'conditions' => array('Student.email LIKE' => '%' . $term . '%', 'StudentGroup.group_id' => $this->userGroupWiseId),
			'group' => array('Student.id', 'Student.phone', 'Student.name'),
		));
		// Format the result for select2
		$result = array();
		foreach ($users as $key => $user) {
			$result[$key]['id'] = $user['Student']['phone'];
			$result[$key]['text'] = $user['Student']['name'];
		}
		$users = $result;
		echo json_encode($users);
	}

	public function teachersearch()
	{
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		// get the search term from URL
		$this->loadModel('User');
		$term = $this->request->query['q'];
		$users = $this->User->find('all', array('conditions' => array('User.name LIKE' => '%' . $term . '%')));
		// Format the result for select2
		$result = array();
		foreach ($users as $key => $user) {
			$result[$key]['id'] = $user['User']['mobile'];
			$result[$key]['text'] = $user['User']['name'];
		}
		$users = $result;
		echo json_encode($users);
	}

	public function sms_template($id){
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$this->loadModel('Smstemplate');
		if($id) {
			$smsTemplateArr=$this->Smstemplate->findById($id);
			if($smsTemplateArr){
				echo $smsTemplateArr['Smstemplate']['description'];
			}
		}
	}
}
