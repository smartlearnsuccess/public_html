<?php
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class ExamsController extends AppController
{
	public $helpers = array('Html');
	public $components = array('CustomFunction', 'RequestHandler');

	public function beforeFilter()
	{
		parent::beforeFilter();
		if ($this->userValue) {
			$this->studentId = $this->userValue['Student']['id'];
		}
		$this->limit = 50;
		if ($this->RequestHandler->isMobile()) {
			$this->set('isMobile', true);
		} else {
			$this->set('isMobile', false);
		}
	}

	public function crm_index($offset = 0)
	{
		$this->authenticate();
		$limit = $this->limit;
		$offset = $offset + 0;
		if (!$this->Session->check('paidExamCount')) {
			$paidExamCount = $this->Exam->paidExamCount("today", $this->studentId, $this->currentDateTime);
			$this->Session->write('paidExamCount', $paidExamCount);
		} else {
			$paidExamCount = $this->Session->read('paidExamCount');
		}
		$mainExamArr = $this->Exam->getPurchasedExam("today", $this->studentId, $this->currentDateTime, $limit, $offset);
		$mainExam = array();
		$packageName = null;
		$serialNo = -1;
		foreach ($mainExamArr as $item) {
			if ($item['Package']['name'] != $packageName) {
				$serialNo++;
				if (strlen($item['Package']['photo']) > 0) {
					$photo = "package_thumb/" . $item['Package']['photo'];
				} else {
					$photo = "nia.png";
				}
				$mainExam[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
				$mainExam[$serialNo]['exam_detail'][] = $item;
			} else {
				$mainExam[$serialNo]['exam_detail'][] = $item;
			}
			$packageName = $item['Package']['name'];
		}
		$this->set('mainExam', $mainExam);
		$this->set('paidExamCount', $paidExamCount);
		$this->set('limit', $limit);
		$ExamResult = ClassRegistry::init('ExamResult');
		$examResultArr = $ExamResult->find('list', array('fields' => array('exam_id', 'id'), 'conditions' => array('student_id' => $this->studentId, 'end_time IS NULL')));
		$this->set('examResultArr', $examResultArr);
	}

	public function crm_upcoming($offset = 0)
	{
		$this->authenticate();
		$limit = $this->limit;
		$offset = $offset + 0;
		if (!$this->Session->check('paidExamCount')) {
			$paidExamCount = $this->Exam->paidExamCount("upcoming", $this->studentId, $this->currentDateTime);
			$this->Session->write('upcomingExamCount', $paidExamCount);
		} else {
			$paidExamCount = $this->Session->read('upcomingExamCount');
		}
		$mainExamArr = $this->Exam->getPurchasedExam("upcoming", $this->studentId, $this->currentDateTime, $limit, $offset);
		$mainExam = array();
		$packageName = null;
		$serialNo = -1;
		foreach ($mainExamArr as $item) {
			if ($item['Package']['name'] != $packageName) {
				$serialNo++;
				if (strlen($item['Package']['photo']) > 0) {
					$photo = "package_thumb/" . $item['Package']['photo'];
				} else {
					$photo = "nia.png";
				}
				$mainExam[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
				$mainExam[$serialNo]['exam_detail'][] = $item;
			} else {
				$mainExam[$serialNo]['exam_detail'][] = $item;
			}
			$packageName = $item['Package']['name'];
		}
		$this->set('mainExam', $mainExam);
		$this->set('paidExamCount', $paidExamCount);
		$this->set('limit', $limit);
		$ExamResult = ClassRegistry::init('ExamResult');
		$examResultArr = $ExamResult->find('list', array('fields' => array('exam_id', 'id'), 'conditions' => array('student_id' => $this->studentId, 'end_time IS NULL')));
		$this->set('examResultArr', $examResultArr);
	}

	public function crm_expired($offset = 0)
	{
		$this->authenticate();
		$limit = $this->limit;
		$offset = $offset + 0;
		if (!$this->Session->check('paidExamCount')) {
			$paidExamCount = $this->Exam->paidExamCount("expired", $this->studentId, $this->currentDateTime);
			$this->Session->write('expiredExamCount', $paidExamCount);
		} else {
			$paidExamCount = $this->Session->read('expiredExamCount');
		}
		$mainExamArr = $this->Exam->getPurchasedExam("expired", $this->studentId, $this->currentDateTime, $limit, $offset);
		$mainExam = array();
		$packageName = null;
		$serialNo = -1;
		foreach ($mainExamArr as $item) {
			if ($item['Package']['name'] != $packageName) {
				$serialNo++;
				if (strlen($item['Package']['photo']) > 0) {
					$photo = "package_thumb/" . $item['Package']['photo'];
				} else {
					$photo = "nia.png";
				}
				$mainExam[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
				$mainExam[$serialNo]['exam_detail'][] = $item;
			} else {
				$mainExam[$serialNo]['exam_detail'][] = $item;
			}
			$packageName = $item['Package']['name'];
		}
		$this->set('mainExam', $mainExam);
		$this->set('paidExamCount', $paidExamCount);
		$this->set('limit', $limit);
		$ExamResult = ClassRegistry::init('ExamResult');
		$examResultArr = $ExamResult->find('list', array('fields' => array('exam_id', 'id'), 'conditions' => array('student_id' => $this->studentId, 'end_time IS NULL')));
		$this->set('examResultArr', $examResultArr);
	}

	public function crm_free($offset = 0)
	{
		$this->authenticate();
		$limit = $this->limit;
		$offset = $offset + 0;
		if (!$this->Session->check('freeExamCount')) {
			$freeExamCount = $this->Exam->freeExamCount($this->studentId, $this->currentDateTime);
			$this->Session->write('freeExamCount', $freeExamCount);
		} else {
			$freeExamCount = $this->Session->read('freeExamCount');
		}
		$freeExam = $this->Exam->insertFreeExam($this->studentId, $this->currentDateTime, $limit, $offset);
		$this->loadModel('Package');
		$this->loadModel('Checkout');
		$this->loadModel('Payment');
		$this->loadModel('PackagesPayment');
		$count = 1;
		$amount = 0;
		$packagesPayment = array();
		foreach ($freeExam as $item) {
			$packagesPayment = array();
			$productId = $item['Package']['id'];
			$packagesPaymentArr = $this->PackagesPayment->find(
				'first',
				array(
					'conditions' => array(
						'PackagesPayment.package_id' => $productId,
						'PackagesPayment.student_id' => $this->studentId,
						'PackagesPayment.status' => 'Approved'
					)
				)
			);
			if (count($packagesPaymentArr) == 0) {
				$product = $this->Package->findById($productId);
				$expiryDays = $product['Package']['expiry_days'];
				if ($expiryDays == 0) {
					$expiryDate = null;
				} else {
					$expiryDate = date('Y-m-d', strtotime($this->currentDate . "+$expiryDays days"));
				}
				$amount = $amount + $product['Package']['amount'];
				$packagesPayment = array(
					array(
						'package_id' => $product['Package']['id'],
						'student_id' => $this->studentId,
						'status' => 'Approved',
						'price' => $product['Package']['amount'],
						'quantity' => $count,
						'amount' => $count * $product['Package']['amount'],
						'date' => $this->currentDate,
						'expiry_date' => $expiryDate
					)
				);
				$token = time() . rand();
				$transactionId = time() . rand();
				$amount = $amount;
				$description = __('Free Purchase Package From Administrator');
				$name = __('Free');
				$type = 'FRE';
				if ($packagesPayment) {
					$paymentArr = array(
						'Payment' => array(
							'student_id' => $this->studentId,
							'token' => $token,
							'transaction_id' => $transactionId,
							'amount' => $amount,
							'status' => 'Approved',
							'date' => $this->currentDateTime,
							'remarks' => $description,
							'name' => $name,
							'type' => $type,
							'payments_from' => 'web'
						),
						'Package' => $packagesPayment
					);
					$this->Payment->create();
					$this->Payment->saveAll($paymentArr);
					$paymentArrDetail = $this->Payment->find(
						'first',
						array(
							'conditions' => array(
								'id' => $this->Payment->id
							),
							'recursive' => 2
						)
					);
					$this->Checkout->packageExamOrder($paymentArrDetail);
				}
			}
		}
		$mainExamArr = $this->Exam->getFreeExam($this->studentId, $this->currentDateTime, $limit, $offset);
		$mainExam = array();
		$packageName = null;
		$serialNo = -1;
		foreach ($mainExamArr as $item) {
			if ($item['Package']['name'] != $packageName) {
				$serialNo++;
				if (strlen($item['Package']['photo']) > 0) {
					$photo = "package_thumb/" . $item['Package']['photo'];
				} else {
					$photo = "nia.png";
				}
				$mainExam[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
				$mainExam[$serialNo]['exam_detail'][] = $item;
			} else {
				$mainExam[$serialNo]['exam_detail'][] = $item;
			}
			$packageName = $item['Package']['name'];
		}
		$this->set('mainExam', $mainExam);
		$this->set('freeExamCount', $freeExamCount);
		$this->set('limit', $limit);
		$ExamResult = ClassRegistry::init('ExamResult');
		$examResultArr = $ExamResult->find('list', array('fields' => array('exam_id', 'id'), 'conditions' => array('student_id' => $this->studentId, 'end_time IS NULL')));
		$this->set('examResultArr', $examResultArr);
	}

	public function crm_view($id, $showType)
	{
		$this->authenticate();
		$this->layout = null;
		$this->loadModel('ExamQuestion');
		$this->loadModel('ExamGroup');
		if (!$id) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}
		$checkPost = $this->Exam->checkPost($id, $this->studentId);
		if ($checkPost == 0) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
			$this->redirect(array('action' => 'error'));
		}
		$post = $this->Exam->findByIdAndStatus($id, 'Active');
		if (!$post) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
			$this->redirect(array('action' => 'error'));
		}
		$examCount = $this->Exam->find(
			'count',
			array(
				'joins' => array(array('table' => 'exam_maxquestions', 'type' => 'INNER', 'alias' => 'ExamMaxquestion', 'conditions' => array('Exam.id=ExamMaxquestion.exam_id'))),
				'conditions' => array('Exam.id' => $id)
			)
		);
		if ($post['Exam']['type'] == "Exam") {
			$subjectDetailArr = $this->Exam->getSectionSubject($id);
			foreach ($subjectDetailArr as $value) {
				$subjectId = $value['Subject']['id'];
				$subjectName = $value['Subject']['subject_name'];
				if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
					$duration = $value['ExamsSubject']['duration'];
				} else {
					$duration = $value['Subject']['section_time'];
				}
				$totalQuestionArr = $this->Exam->subjectWiseQuestion($id, $subjectId, 'Exam');
				$subjectDetail[$subjectName] = $totalQuestionArr;
				$subjectDetail[$subjectName]['duration'] = $duration;
			}
			$totalMarks = $this->Exam->totalMarks($id);
		} else {
			$subjectDetailArr = $this->Exam->getSectionPrepSubject($id);
			foreach ($subjectDetailArr as $value) {
				$subjectId = $value['Subject']['id'];
				$subjectName = $value['Subject']['subject_name'];
				if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
					$duration = $value['ExamsSubject']['duration'];
				} else {
					$duration = $value['Subject']['section_time'];
				}
				$totalQuestionArr = $this->Exam->subjectWiseQuestion($id, $subjectId);
				$subjectDetail[$subjectName] = $totalQuestionArr;
				$subjectDetail[$subjectName]['duration'] = $duration;
			}
			$totalMarks = 0;
		}
		$this->set('post', $post);
		$this->set('subjectDetail', $subjectDetail);
		$this->set('totalMarks', $totalMarks);
		$this->set('examCount', $examCount);
		$this->set('showType', $showType);
		$this->set('id', $id);
	}

	public function crm_guidelines($id = null)
	{
		$this->layout = 'exam';
		if (!$id) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}
		$checkPost = $this->Exam->checkPost($id, $this->studentId);

		if ($checkPost == 0) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'error'));
		}
		$this->loadModel('ExamResult');
		$remExam = $this->ExamResult->find('first', array('joins' => array(array('table' => 'exams', 'type' => 'INNER', 'alias' => 'Exam', 'conditions' => array('ExamResult.exam_id=Exam.id'))), 'fields' => array('Exam.id', 'Exam.name'), 'conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
		if ($remExam) {
			$testId = $remExam['Exam']['id'];
			$remExamName = $remExam['Exam']['name'];
			$this->Session->setFlash(__('Your previous exam is pending.'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('controller' => 'Exams', 'action' => 'start', $testId));
		}
		$post = $this->Exam->findByIdAndStatus($id, 'Active');

		if (!$post) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'error'));
		}
		$this->set('post', $post);
	}

	public function crm_instruction($id)
	{
		$this->authenticate();
		$this->layout = 'exam';
		$this->loadModel('ExamQuestion');
		if (!$id) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}
		$checkPost = $this->Exam->checkPost($id, $this->studentId);
		if ($checkPost == 0) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'error'));
		}
		$this->loadModel('Exam');
		$post = $this->Exam->findByIdAndStatus($id, 'Active');
		if (!$this->checkPaidStatus($id, $this->studentId)) {
			$this->Session->setFlash(__('You have attempted maximum exam.'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('post', $post);
		$this->loadModel('Language');
		$langMainArr = $this->Language->find('list');
		$langArr = array();
		$i = 0;
		foreach ($langMainArr as $item) {
			$i++;
			$langArr[$i] = $item;
		}
		$this->set('language', $langArr);
	}

	public function crm_error()
	{
		$this->authenticate();
		$this->layout = null;
	}

	public function crm_start($id = null, $quesNo = null, $currQuesNo = 1)
	{
		$this->authenticate();
		$this->layout = 'examstart';
		if ($id == null)
			$id = 0;
		if (isset($this->request->data['Exam']['lang'])) {
			$lang = $this->request->data['Exam']['lang'];
		} else {
			$lang = 1;
		}
		$this->loadModel('ExamResult');
		$this->loadModel('ExamOrder');
		$post = $this->Exam->findById($id);

		$currentExamResult = $this->ExamResult->find('count', array('conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
		if ($currentExamResult == 0) {
			$checkPost = $this->Exam->checkPost($id, $this->studentId);
			if ($checkPost == 0) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			if (!$this->checkPaidStatus($id, $this->studentId)) {
				$this->Session->setFlash(__('You have attempted maximum exam.'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Exam->userExamInsert($id, $post['Exam']['ques_random'], $post['Exam']['type'], $post['Exam']['option_shuffle'], $this->studentId, $this->currentDateTime);
		}
		if ($currentExamResult == 1) {
			$checkPost = $this->Exam->checkPostActive($id, $this->studentId);
			if ($checkPost == 0) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
		}
		$examWise = $this->ExamResult->find('first', array('conditions' => array('student_id' => $this->studentId, 'exam_id' => $id, 'end_time' => null)));
		if ($quesNo == null) {
			if ($currentExamResult == 1) {
				$this->loadModel('ExamStat');
				$examStat = $this->ExamStat->find('first', array('fields' => array('ques_no'), 'conditions' => array('exam_result_id' => $examWise['ExamResult']['id'], 'attempt_time' => NULL)));
				if ($examStat && $examStat['ExamStat']['ques_no'] != 1)
					$quesNo = $examStat['ExamStat']['ques_no'] - 1;
				else
					$quesNo = 1;
			} else
				$quesNo = 1;
		}
		if ($currentExamResult == 1) {
			$checkPost = $this->Exam->checkPostActive($id, $this->studentId);
			if ($checkPost == 0) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
				$this->redirect(array('action' => 'error'));
			}
			$examWiseId = $examWise['ExamResult']['exam_id'];
			if ($post['Exam']['status'] != "Active") {
				$this->redirect(array('action' => 'finish', $examWiseId));
			}
		}
		if ($post['Exam']['exam_mode'] == "T") {
			############ Start Section Wise Time ##############
			$this->loadModel('ExamQuestion');
			$userExamQuestion = $this->Exam->userExamQuestion($id, $this->studentId);
			$examResult = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
			$userSectionQuestion = $this->Exam->userSectionQuestion($id, $post['Exam']['type'], $this->studentId);

			$userSectionSubject = $this->Exam->userSectionSubject($id, $post['Exam']['type'], $this->studentId);
			$examDuration = $this->Exam->getSectionTime($id, $userExamQuestion[0]['Question']['subject_id'], $userExamQuestion[0]['ExamStat']['exam_result_id']);
			$this->set('examDuration', $examDuration);
			$this->set('userSectionSubject', $userSectionSubject);
			$this->set('startQuestionNo', $userExamQuestion[0]['ExamStat']['ques_no']);
			$this->loadModel('ExamStat');
			$totalQuestion = $this->ExamStat->find('count', array('conditions' => array('exam_result_id' => $userExamQuestion[0]['ExamStat']['exam_result_id'], 'is_section <>' => '0')));
			$this->set('remainingSection', count($this->ExamStat->find('all', array('fields' => array('tsubject_id'), 'conditions' => array('exam_result_id' => $userExamQuestion[0]['ExamStat']['exam_result_id'], 'is_section' => '0'), 'group' => array('tsubject_id')))));
			$totalSectionQuestion = $this->ExamStat->find('count', array('conditions' => array('exam_result_id' => $userExamQuestion[0]['ExamStat']['exam_result_id'], 'is_section' => '1')));
			$this->set('totalSectionQuestion', $totalSectionQuestion);
			############ End Section Wise Time ##############
		} else {
			$this->loadModel('ExamQuestion');
			$userExamQuestion = $this->Exam->userDefaultExamQuestion($id, $this->studentId);
			$examResult = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
			$userSectionQuestion = $this->Exam->userDefaultSectionQuestion($id, $post['Exam']['type'], $this->studentId);
			if ($post['Exam']['type'] == "Exam") {
				$totalQuestion = $this->ExamQuestion->find('count', array('conditions' => array('exam_id' => $id)));
			} else {
				$totalQuestion = $this->Exam->totalPrepQuestions($id, $this->studentId);
			}
			$totalSectionQuestion = $totalQuestion;
			$examDuration = $examWise['ExamResult']['total_test_time'];
			$this->set('startQuestionNo', 1);
			$this->set('examDuration', $examDuration);
			$this->set('totalQuestion', $totalQuestion);
			$this->set('totalSectionQuestion', $totalSectionQuestion);
		}
		if ($currentExamResult == 1) {
			$examWiseId = $examWise['ExamResult']['exam_id'];
			//			if ($examWise['ExamResult']['pause_time'] != null) {
//				$elaspsedTime = CakeTime::fromString($examWise['ExamResult']['pause_time']) - CakeTime::fromString($examWise['ExamResult']['start_time']);
//				$pauseTime = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString($this->currentDateTime) - $elaspsedTime);
//				$this->ExamResult->save(array('ExamResult' => array('id' => $examResult['ExamResult']['id'], 'start_time' => $pauseTime, 'pause_time' => null)));
//			}
			$examWise = $this->ExamResult->find('first', array('conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
			if ($examWise['ExamResult']['start_time'] != null && $examWise['ExamResult']['pause_time'] == null) {
				$endTime = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString($examWise['ExamResult']['start_time']) + ($examDuration * 60));
				if ($this->currentDateTime >= $endTime && $post['Exam']['duration'] > 0)
					$this->redirect(array('action' => 'finish', $examWiseId));
				if ($examWiseId != $id)
					$this->redirect(array('action' => 'start', $examWiseId, $quesNo));
			}
		}
		$this->loadModel('ExamMaxquestion');
		$this->set('maxQuestionCount', $this->ExamMaxquestion->find('count', array('conditions' => array('exam_id' => $id))));

		$this->set('userExamQuestionArr', $userExamQuestion);
		$this->set('userSectionQuestion', $userSectionQuestion);
		$this->set('post', $post);
		$this->set('examResult', $examResult);
		$this->set('siteTimezone', $this->siteTimezone);
		$this->set('examId', $id);
		$this->set('examResultId', $userExamQuestion[0]['ExamStat']['exam_result_id']);
		$this->set('totalQuestion', $totalQuestion);
		$this->set('lang', $lang);
		$this->loadModel('Language');
		if ($post['Exam']['multi_language'] == 1) {
			$langMainArr = $this->Language->find('list');
			$langArr = array();
			$i = 0;
			foreach ($langMainArr as $item) {
				$i++;
				$langArr[$i] = $item;
			}
			$this->set('langArr', $langArr);
			$this->set('languageCount', count($langArr));
			$this->set('languageArr', $this->Language->find('all'));
		} else {
			$langArr = $this->Language->find('list', array('conditions' => array('Language.id' => 1)));
			$this->set('langArr', $langArr);
			$this->set('languageCount', 1);
			$this->set('languageArr', $this->Language->find('all', array('conditions' => array('Language.id' => 1))));

			$langArr1 = $this->Language->find('list');
			$this->set('fullLanguageCount', count($langArr1));
		}
	}

	public function crm_attemptTime($id, $quesNo, $currQuesNo)
	{
		$this->authenticate();
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$this->Exam->userQuestionUpdate($id, $currQuesNo, $this->studentId, $this->currentDateTime);
		$this->Exam->userQuestionRead($id, $quesNo, $this->studentId, $this->currentDateTime);
	}

	public function crm_save($id, $quesNo)
	{
		$this->authenticate();
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$_REQUEST['data']['Exam']['review'] = 0;
		$dataArr = $_REQUEST['data'];
		if ($this->Exam->userSaveAnswer($id, $quesNo, $this->studentId, $this->currentDateTime, $dataArr)) {
			return true;
		} else {
			$this->Session->setFlash(__('You have attempted maximum number of questions in this subject'), 'flash', array('alert' => 'danger'));
			return false;
		}
	}

	public function crm_resetAnswer($id, $quesNo)
	{
		$this->authenticate();
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$this->Exam->userResetAnswer($id, $quesNo, $this->studentId);
	}

	public function crm_reviewAnswer($id, $quesNo)
	{
		$this->authenticate();
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$this->Exam->userReviewAnswer($id, $quesNo, $this->studentId, 1);
	}

	public function crm_startTime($id)
	{
		$this->authenticate();
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$this->loadModel('ExamResult');
		$examResultArr = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
		$examResultId = $examResultArr['ExamResult']['id'];
		if ($examResultArr['ExamResult']['pause_time'] != null) {
			$elapsedTime = CakeTime::fromString($examResultArr['ExamResult']['pause_time']) - CakeTime::fromString($examResultArr['ExamResult']['start_time']);
			$pauseTime = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString($this->currentDateTime) - $elapsedTime);
			$this->ExamResult->save(array('ExamResult' => array('id' => $examResultArr['ExamResult']['id'], 'start_time' => $pauseTime, 'pause_time' => null)));
			$examResultArr = $this->ExamResult->findById($examResultArr['ExamResult']['id']);
		}
		if ($examResultArr['ExamResult']['start_time'] == null) {
			$startTime = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString($this->currentDateTime) + 0);
		} else {
			$startTime = $examResultArr['ExamResult']['start_time'];
		}
		$post = $this->Exam->findById($id);
		if ($post['Exam']['exam_mode'] == "T") {
			############ Start Section Wise Time ##############
			$userExamQuestion = $this->Exam->userExamQuestion($id, $this->studentId);
			$examDuration = $this->Exam->getSectionTime($id, $userExamQuestion[0]['Question']['subject_id'], $userExamQuestion[0]['ExamStat']['exam_result_id']);
			$sectionRemainingTime = $this->Exam->getSectionRemainingTime($userExamQuestion[0]['ExamStat']['exam_result_id']);
		} else {
			$examDuration = $examResultArr['ExamResult']['total_test_time'];
			$sectionRemainingTime = 0;
		}
		$this->ExamResult->save(array('id' => $examResultId, 'start_time' => $startTime));
		if ($examResultArr['ExamResult']['attempt_time'] == null) {
			$this->ExamResult->save(array('id' => $examResultId, 'attempt_time' => $startTime));
		} else {
			$this->loadModel('ExamStat');
			$examStatArr = $this->ExamStat->findByExamResultIdAndIsSection($examResultId, '1');
			$quesNo = $examStatArr['ExamStat']['ques_no'];
			$this->Exam->userQuestionRead($id, $quesNo, $this->studentId, $startTime);
			$this->Exam->userQuestionUpdate($id, $quesNo, $this->studentId, $startTime);
		}
		$endTime = CakeTime::format('M d, Y H:i:s', CakeTime::fromString($startTime) + ($examDuration * 60));
		$overallTime = CakeTime::format('M d, Y H:i:s', CakeTime::fromString($startTime) + ($examDuration * 60) + ($sectionRemainingTime * 60));
		echo json_encode(array('startTime' => $startTime, 'endTime' => $endTime, 'overallTime' => $overallTime));
	}

	public function crm_submit($examId = null, $examResultId = null)
	{
		$this->layout = null;
		$this->loadModel('ExamStat');
		$this->set('examId', $examId);
		$this->set('post', $this->Exam->findById($examId));
		$this->set('answered', $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $examResultId, 'answered' => 1, 'review' => 0))));
		$this->set('notAnswered', $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $examResultId, 'opened' => 1, 'answered' => 0, 'review' => 0))));
		$this->set('notansmarked', $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $examResultId, 'answered' => 0, 'review' => 1))));
		$this->set('ansmarked', $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $examResultId, 'answered' => 1, 'review' => 1))));
		$this->set('notAttempted', $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $examResultId, 'opened IS NULL'))));
	}

	public function crm_finish($id = null, $warn = null, $origQuesNo = null, $forceSubmit = null)
	{
		$this->authenticate();
		$this->autoRender = false;
		if ($id == null) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
			$this->redirect(array('action' => 'error'));
		}
		$this->loadModel('ExamResult');
		$currentExamResult = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
		if ($currentExamResult) {
			$this->loadModel('Exam');
			$examArr = $this->Exam->findById($id);

			if ($forceSubmit != "Y" && $examArr['Exam']['exam_mode'] == "T") {
				if ($this->Exam->updateExamSection($currentExamResult['ExamResult']['id'])) {
					$this->Exam->userQuestionUpdate($id, $origQuesNo, $this->studentId, $this->currentDateTime);
					$this->redirect(array('action' => 'start', $id));
				}
				$statPos = 2;
			} else {
				$statPos = 1;
			}
			$finalTime = strtotime($this->currentDateTime) - strtotime($currentExamResult['ExamResult']['start_time']);
			if ($examArr['Exam']['duration'] != 0 && $finalTime > $examArr['Exam']['duration'] * 60) {
				$this->loadModel('ExamStat');
				$timeSubjectTimeArr = $this->ExamStat->findByExamResultIdAndIsSection($currentExamResult['ExamResult']['id'], $statPos);
				$this->ExamStat->virtualFields = array('total_time_taken' => 'SUM(time_taken)');
				$examStatArr = $this->ExamStat->find('first', array('fields' => array('total_time_taken'), 'conditions' => array('exam_result_id' => $currentExamResult['ExamResult']['id'])));
				$this->ExamStat->virtualFields = array();
				$elapsedTime = $examStatArr['ExamStat']['total_time_taken'] - 1;
				//$mainCurrentDateTime = CakeTime::format('Y-m-d H:i:s', strtotime($currentExamResult['ExamResult']['start_time']) + ($examArr['Exam']['duration'] * 60));
				$mainCurrentDateTime = CakeTime::format('Y-m-d H:i:s', strtotime($currentExamResult['ExamResult']['start_time']) + ($timeSubjectTimeArr['ExamStat']['tsubject_time'] * 60));
				$questionCurrentDateTime = CakeTime::format('Y-m-d H:i:s', strtotime($currentExamResult['ExamResult']['start_time']) + (($examArr['Exam']['duration'] * 60) - $elapsedTime));
			} else {
				$mainCurrentDateTime = $this->currentDateTime;
				$questionCurrentDateTime = $this->currentDateTime;
			}
			$this->Exam->userQuestionUpdate($id, $origQuesNo, $this->studentId, $questionCurrentDateTime);
			$this->Exam->userExamFinish($id, $this->studentId, $mainCurrentDateTime);
			if ($warn == null || $warn == 'null') {
				if ($this->examFeedback) {
					if ($examArr['Exam']['finish_result'])
						$this->resultEmailSms($currentExamResult, $examArr);
					$this->redirect(array('controller' => 'Exams', 'action' => 'feedbacks', $currentExamResult['ExamResult']['id']));
					exit(0);
				} else {
					if ($examArr['Exam']['finish_result']) {
						$this->resultEmailSms($currentExamResult, $examArr);
						$this->redirect(array('controller' => 'Results', 'action' => 'view', $currentExamResult['ExamResult']['id'], "crm", 'null', 'null', "examclose"));
					} else {
						$this->redirect(array('controller' => 'Exams', 'action' => 'close'));
					}
				}
			} else {
				$this->redirect(array('controller' => 'Ajaxcontents', 'action' => 'examclose', $currentExamResult['ExamResult']['id']));
				exit(0);
			}
		} else {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
			$this->redirect(array('action' => 'error'));
		}
	}

	public function crm_feedbacks($id)
	{
		$this->layout = 'exam';
		if (!$id) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
			$this->redirect(array('action' => 'error'));
		}
		$this->loadModel('ExamResult');
		$examArr = $this->ExamResult->findByIdAndStudentId($id, $this->studentId);
		if (!$examArr) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'error'));
			$this->redirect(array('action' => 'error'));
		}
		$this->set('id', $id);
		$this->set('isClose', 'No');
		$this->set('examPost', $this->Exam->find('first', array('fields' => array('Exam.finish_result'), 'conditions' => array('id' => $examArr['ExamResult']['exam_id']), 'recursive' => -1)));
		if ($this->request->is('post')) {
			try {
				$this->loadModel('ExamFeedback');
				$this->ExamFeedback->create();
				$this->request->data['Exam']['exam_result_id'] = $id;
				$recordArr['ExamFeedback'] = $this->request->data['Exam'];
				$this->ExamFeedback->save($recordArr);
				$this->Session->setFlash(__('Feedback has submitted successfully!'), 'flash', array('alert' => 'success'));
				$this->set('isClose', 'Yes');
			} catch (Exception $e) {
				$this->Session->setFlash(__('Feedback already submitted.'), 'flash', array('alert' => 'danger'));
				$this->set('isClose', 'Yes');
			}
		}
	}

	public function crm_close()
	{
		$this->layout = 'exam';
	}

	public function crm_pause($id = null)
	{
		$this->authenticate();
		$this->autoRender = false;
		if ($id == null) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'close'));
		}
		$this->loadModel('ExamResult');
		$currentExamResult = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'start_time IS NOT NULL', 'end_time' => null)));
		if ($currentExamResult) {
			$arrayName['ExamResult'] = array('id' => $currentExamResult['ExamResult']['id'], 'pause_time' => $this->currentDateTime);
			if ($this->ExamResult->save($arrayName)) {
				$this->Session->setFlash(__('Your exam has been paused'), 'flash', array('alert' => 'success'));
				$this->redirect(array('controller' => 'Exams', 'action' => 'close'));
			}
		}
	}

	public function rest_index()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$responseArr = $this->Exam->getPurchasedExam("today", $this->studentId, $this->currentDateTime);
			$packageName = null;
			$serialNo = -1;
			foreach ($responseArr as $item) {
				if ($item['Exam']['attempt_count'] == 0) {
					$attemptRemaining = __('Unlimited');
				} else {
					if ($item['Exam']['attempt_order'] == 0) {
						$item['Exam']['attempt_order'] = 1;
					}
					$item['Exam']['attempt_count'] = $item['Exam']['attempt_order'] * $item['Exam']['attempt_count'];
					$attemptRemaining = ($item['Exam']['attempt_count'] - $item['Exam']['attempt']);
				}
				$item['Exam']['attempt_remaining'] = $attemptRemaining;
				if ($item['Package']['name'] != $packageName) {
					$serialNo++;
					if (strlen($item['Package']['photo']) > 0) {
						$photo = $this->siteDomain . "/img/package_thumb/" . $item['Package']['photo'];
					} else {
						$photo = $this->siteDomain . "/img/nia.png";
					}
					$response[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
					$response[$serialNo]['exam_detail'][] = $item;
				} else {
					$response[$serialNo]['exam_detail'][] = $item;
				}
				$packageName = $item['Package']['name'];
			}
			$status = true;
			$message = __('Exam fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = (object) array();
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	public function rest_upcoming()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$responseArr = $this->Exam->getPurchasedExam("upcoming", $this->studentId, $this->currentDateTime);
			$packageName = null;
			$serialNo = -1;
			foreach ($responseArr as $item) {
				if ($item['Exam']['attempt_count'] == 0) {
					$attemptRemaining = __('Unlimited');
				} else {
					if ($item['Exam']['attempt_order'] == 0) {
						$item['Exam']['attempt_order'] = 1;
					}
					$item['Exam']['attempt_count'] = $item['Exam']['attempt_order'] * $item['Exam']['attempt_count'];
					$attemptRemaining = ($item['Exam']['attempt_count'] - $item['Exam']['attempt']);
				}
				$item['Exam']['attempt_remaining'] = $attemptRemaining;
				if ($item['Package']['name'] != $packageName) {
					$serialNo++;
					if (strlen($item['Package']['photo']) > 0) {
						$photo = $this->siteDomain . "/img/package_thumb/" . $item['Package']['photo'];
					} else {
						$photo = $this->siteDomain . "/img/nia.png";
					}
					$response[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
					$response[$serialNo]['exam_detail'][] = $item;
				} else {
					$response[$serialNo]['exam_detail'][] = $item;
				}
				$packageName = $item['Package']['name'];
			}
			$status = true;
			$message = __('Exam fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = (object) array();
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	public function rest_expired()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$responseArr = $this->Exam->getPurchasedExam("expired", $this->studentId, $this->currentDateTime);
			$packageName = null;
			$serialNo = -1;
			foreach ($responseArr as $item) {
				if ($item['Exam']['attempt_count'] == 0) {
					$attemptRemaining = __('Unlimited');
				} else {
					if ($item['Exam']['attempt_order'] == 0) {
						$item['Exam']['attempt_order'] = 1;
					}
					$item['Exam']['attempt_count'] = $item['Exam']['attempt_order'] * $item['Exam']['attempt_count'];
					$attemptRemaining = ($item['Exam']['attempt_count'] - $item['Exam']['attempt']);
				}
				$item['Exam']['attempt_remaining'] = $attemptRemaining;
				if ($item['Package']['name'] != $packageName) {
					$serialNo++;
					if (strlen($item['Package']['photo']) > 0) {
						$photo = $this->siteDomain . "/img/package_thumb/" . $item['Package']['photo'];
					} else {
						$photo = $this->siteDomain . "/img/nia.png";
					}
					$response[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
					$response[$serialNo]['exam_detail'][] = $item;
				} else {
					$response[$serialNo]['exam_detail'][] = $item;
				}
				$packageName = $item['Package']['name'];
			}
			$status = true;
			$message = __('Exam fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = (object) array();
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	public function rest_free()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$freeExam = $this->Exam->insertFreeExam($this->studentId, $this->currentDateTime);
			$this->loadModel('Package');
			$this->loadModel('Checkout');
			$this->loadModel('Payment');
			$this->loadModel('PackagesPayment');
			$count = 1;
			$amount = 0;
			$packagesPayment = array();
			foreach ($freeExam as $item) {
				$productId = $item['Package']['id'];
				$packagesPaymentArr = $this->PackagesPayment->find(
					'first',
					array(
						'conditions' => array(
							'PackagesPayment.package_id' => $productId,
							'PackagesPayment.student_id' => $this->studentId,
							'PackagesPayment.status' => 'Approved'
						)
					)
				);
				if (count($packagesPaymentArr) == 0) {
					$product = $this->Package->findById($productId);
					$expiryDays = $product['Package']['expiry_days'];
					if ($expiryDays == 0) {
						$expiryDate = null;
					} else {
						$expiryDate = date('Y-m-d', strtotime($this->currentDate . "+$expiryDays days"));
					}
					$amount = $amount + $product['Package']['amount'];
					$packagesPayment = array(
						array(
							'package_id' => $product['Package']['id'],
							'student_id' => $this->studentId,
							'status' => 'Approved',
							'price' => $product['Package']['amount'],
							'quantity' => $count,
							'amount' => $count * $product['Package']['amount'],
							'date' => $this->currentDate,
							'expiry_date' => $expiryDate
						)
					);
					$token = time() . rand();
					$transactionId = time() . rand();
					$amount = $amount;
					$description = __('Free Purchase Package From Administrator');
					$name = __('Free');
					$type = 'FRE';
					if ($packagesPayment) {
						$paymentArr = array(
							'Payment' => array(
								'student_id' => $this->studentId,
								'token' => $token,
								'transaction_id' => $transactionId,
								'amount' => $amount,
								'status' => 'Approved',
								'date' => $this->currentDateTime,
								'remarks' => $description,
								'name' => $name,
								'type' => $type,
								'payments_from' => 'mobile'
							),
							'Package' => $packagesPayment
						);
						$this->Payment->create();
						$this->Payment->saveAll($paymentArr);
						$paymentArrDetail = $this->Payment->find(
							'first',
							array(
								'conditions' => array(
									'id' => $this->Payment->id
								),
								'recursive' => 2
							)
						);
						$this->Checkout->packageExamOrder($paymentArrDetail);
					}
				}
			}
			$responseArr = $this->Exam->getFreeExam($this->studentId, $this->currentDateTime);
			$packageName = null;
			$serialNo = -1;
			foreach ($responseArr as $item) {
				if ($item['Exam']['attempt_count'] == 0) {
					$attemptRemaining = __('Unlimited');
				} else {
					if ($item['Exam']['attempt_order'] == 0) {
						$item['Exam']['attempt_order'] = 1;
					}
					$item['Exam']['attempt_count'] = $item['Exam']['attempt_order'] * $item['Exam']['attempt_count'];
					$attemptRemaining = ($item['Exam']['attempt_count'] - $item['Exam']['attempt']);
				}
				$item['Exam']['attempt_remaining'] = $attemptRemaining;
				if ($item['Package']['name'] != $packageName) {
					$serialNo++;
					if (strlen($item['Package']['photo']) > 0) {
						$photo = $this->siteDomain . "/img/package_thumb/" . $item['Package']['photo'];
					} else {
						$photo = $this->siteDomain . "/img/nia.png";
					}
					$response[$serialNo] = array('name' => $item['Package']['name'], 'photo' => $photo);
					$response[$serialNo]['exam_detail'][] = $item;
				} else {
					$response[$serialNo]['exam_detail'][] = $item;
				}
				$packageName = $item['Package']['name'];
			}
			$status = true;
			$message = __('Exam fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = (object) array();
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	public function rest_view()
	{
		$status = false;
		$message = __('Invalid Post');
		$response = (object) array();
		$subjectDetail = array();
		$totalMarks = null;
		$examCount = null;
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$this->loadModel('ExamQuestion');
			$this->loadModel('ExamGroup');
			if (isset($this->request->query['id'])) {
				$id = $this->request->query['id'];
			} else {
				$id = 0;
			}
			$checkPost = $this->Exam->checkPost($id, $this->studentId);
			$post = $this->Exam->findByIdAndStatus($id, 'Active');
			if ($checkPost && $post) {
				$post['Exam']['duration'] = $this->CustomFunction->secondsToWords($post['Exam']['duration'] * 60);
				$examCount = $this->Exam->find(
					'count',
					array(
						'joins' => array(array('table' => 'exam_maxquestions', 'type' => 'INNER', 'alias' => 'ExamMaxquestion', 'conditions' => array('Exam.id=ExamMaxquestion.exam_id'))),
						'conditions' => array('Exam.id' => $id)
					)
				);
				if ($post['Exam']['type'] == "Exam") {
					$subjectDetailArr = $this->Exam->getSectionSubject($id);
					foreach ($subjectDetailArr as $value) {
						$subjectId = $value['Subject']['id'];
						$subjectName = $value['Subject']['subject_name'];
						if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
							$duration = $value['ExamsSubject']['duration'];
						} else {
							$duration = $value['Subject']['section_time'];
						}
						$totalQuestionArr = $this->Exam->subjectWiseQuestion($id, $subjectId, 'Exam');
						$subjectDetail[$subjectName] = $totalQuestionArr;
						$subjectDetail[$subjectName]['duration'] = $duration;
					}
					$totalMarks = $this->Exam->totalMarks($id);
				} else {
					$subjectDetailArr = $this->Exam->getSectionPrepSubject($id);
					foreach ($subjectDetailArr as $value) {
						$subjectId = $value['Subject']['id'];
						$subjectName = $value['Subject']['subject_name'];
						if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
							$duration = $value['ExamsSubject']['duration'];
						} else {
							$duration = $value['Subject']['section_time'];
						}
						$totalQuestionArr = $this->Exam->subjectWiseQuestion($id, $subjectId);
						$subjectDetail[$subjectName] = $totalQuestionArr;
						$subjectDetail[$subjectName]['duration'] = $duration;
					}
					$totalMarks = 0;
				}
				$status = true;
				$message = __('Exam fetch successfully.');
				$this->set('response', $post);
			} else {
				$message = __('Invalid Post');
				$subjectDetail = (object) array();
			}
		} else {
			$message = ('Invalid Token');
			$subjectDetail = (object) array();
		}
		$this->set(compact('status', 'message', 'subjectDetail', 'totalMarks', 'examCount', 'id'));
		$this->set('_serialize', array('status', 'message', 'response', 'subjectDetail', 'totalMarks', 'examCount', 'id'));
	}

	public function rest_instruction()
	{
		$status = false;
		$message = ('Invalid Post');
		$response = (object) array();
		$isPaid = true;
		$languageArr = (object) array();
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$this->loadModel('ExamQuestion');
			if (isset($this->request->query['id'])) {
				$id = $this->request->query['id'];
			} else {
				$id = 0;
			}
			$checkPost = $this->Exam->checkPost($id, $this->studentId);
			if ($id && $checkPost) {
				$this->loadModel('Exam');
				$isPaid = $this->checkPaidStatus($id, $this->studentId);
				if ($isPaid == true) {
					$response = $this->Exam->findByIdAndStatus($id, 'Active');
					if ($response['Exam']['multi_language'] == 1) {
						$this->loadModel('Language');
						$languageArr = $this->Language->find('list');
					}
					$status = true;
					$message = __('Exam fetch successfully.');
				} else {
					$message = __('You have attempted maximum exam.');
				}
			} else {
				$message = __('Invalid Post');
			}
		} else {
			$message = ('Invalid Token');
		}
		$this->set(compact('status', 'message', 'response', 'languageArr'));
		$this->set('_serialize', array('status', 'message', 'response', 'languageArr'));
	}

	public function rest_start()
	{
		$status = false;
		$message = __('Invalid Post');
		$userExamQuestion = array();
		$userSectionQuestion = array();
		$currSubjectName = null;
		$post = (object) array();
		$examResult = (object) array();
		$examId = null;
		$totalQuestion = null;
		$examResultId = null;
		$siteDomain = null;
		$studentDetail = (object) array();
		$studentPhoto = null;
		$examStatus = true;
		$examExpire = false;
		$languageArr = (object) array();
		$subjectArr = array();
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			if (isset($this->request->query['id'])) {
				$id = $this->request->query['id'];
			} else {
				$id = 0;
			}
			$checkPost = $this->Exam->checkPost($id, $this->studentId);
			if ($checkPost == 0) {
				$message = __('Invalid Post');
			} else {
				$this->loadModel('ExamResult');
				$this->loadModel('ExamOrder');
				$post = $this->Exam->findById($id);
				$examId = $post['Exam']['id'];
				$currentExamResult = $this->ExamResult->find('count', array('conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
				if ($currentExamResult == 0) {
					if (!$this->checkPaidStatus($id, $this->studentId)) {
						$message = __('You have attempted maximum exam.');
						$examStatus = false;
					}
					if ($examStatus == true) {
						$this->Exam->userExamInsert($id, $post['Exam']['ques_random'], $post['Exam']['type'], $post['Exam']['option_shuffle'], $this->studentId, $this->currentDateTime, "A");
					}
				}
				if ($examStatus == true) {
					if ($post['Exam']['pause_exam'] == "0") {
						$examWise = $this->ExamResult->find('first', array('conditions' => array('student_id' => $this->studentId, 'end_time' => null)));
						if ($currentExamResult == 1) {
							$endTime = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString($examWise['ExamResult']['start_time']) + ($post['Exam']['duration'] * 60));
							if ($this->currentDateTime >= $endTime && $post['Exam']['duration'] > 0) {
								$message = __('Exam expire');
								$examExpire = true;
								$post = (object) array();
							}
						}
					}
					if ($examExpire == false) {
						$this->loadModel('ExamQuestion');
						$userExamQuestion = $this->Exam->userExamQuestion($id, $this->studentId);
						foreach ($userExamQuestion as $k => $item) {
							if ($item['Question']) {
								foreach ($item['Question'] as $k1 => $item1) {
									if ($k1 == "Passage") {
										if (empty($item1)) {
											$userExamQuestion[$k]['Question']['Passage'] = (object) array();
										}
									}
								}
							}
						}
						$examResult = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
						$userSectionQuestion = $this->Exam->userSectionQuestion($id, $post['Exam']['type'], $this->studentId);
						if ($post['Exam']['type'] == "Exam") {
							$totalQuestion = $this->ExamQuestion->find('count', array('conditions' => array('exam_id' => $id)));
						} else {
							$totalQuestion = $this->Exam->totalPrepQuestions($id, $this->studentId);
						}
						$currSubjectName = $this->Exam->userSubject($id, 1, $this->studentId);
						$examResultId = $userExamQuestion[0]['ExamStat']['exam_result_id'];
						$this->loadModel('Student');
						$studentDetail = $this->Student->findById($this->studentId);
						if ($studentDetail['Student']['photo'] != null) {
							$studentPhoto = $this->siteDomain . '/img' . '/student_thumb/' . $studentDetail['Student']['photo'];
						} else {
							$studentPhoto = null;
						}
						if ($post['Exam']['multi_language'] == 1) {
							$this->loadModel('Language');
							$languageArr = $this->Language->find('list');
						} else {
							$this->loadModel('Language');
							$languageArr = $this->Language->find('list', array('conditions' => array('id' => 1)));
						}
						if ($post['Exam']['exam_mode'] == "T") {
							$this->loadModel('ExamsSubject');
							$this->loadModel('Subject');
							$subjectAllArr = $this->Subject->find(
								'all',
								array(
									'joins' => array(array('table' => 'exams_subjects', 'alias' => 'ExamsSubject', 'type' => 'LEFT', 'conditions' => array('Subject.id=ExamsSubject.subject_id'))),
									'fields' => array('Subject.id', 'Subject.subject_name', 'Subject.section_time', 'ExamsSubject.duration'),
									'conditions' => array('ExamsSubject.exam_id' => $post['Exam']['id']),
									'order' => array('Subject.ordering' => 'asc')
								)
							);
							if (!$subjectAllArr) {
								foreach ($userSectionQuestion as $k => $item) {
									$subjectTempAllArr[] = $k;
								}
								unset($k, $item);
								$subjectAllArr = $this->Subject->find('all', array('conditions' => array('Subject.subject_name' => $subjectTempAllArr), 'order' => array('Subject.ordering' => 'asc')));
							}
							foreach ($subjectAllArr as $item) {
								if (isset($item['ExamsSubject']['duration']) && $item['ExamsSubject']['duration']) {
									$duration = $item['ExamsSubject']['duration'];
								} else {
									$duration = $item['Subject']['section_time'];
								}
								$subjectArr[] = array('subject_name' => $item['Subject']['subject_name'], 'duration' => $duration);
							}
						}
					}
					if ($examStatus == true && $examExpire == false) {
						$status = true;
						$message = "Exam fetch successfully.";
					}
				}
			}
		} else {
			$message = ('Invalid Token');
		}

		if (!empty($userExamQuestion)) {

			foreach ($userExamQuestion as $key => $value) {
				// userExamQuestion ExamStat
				// if ($value['ExamStat']['attempt_time']=='') {
				//     $userExamQuestion[$key]['ExamStat']['attempt_time']='null';
				// }
				if ($value['ExamStat']['option_selected'] == '') {
					$userExamQuestion[$key]['ExamStat']['option_selected'] = 'null';
				}
				if ($value['ExamStat']['answer'] == '') {
					$userExamQuestion[$key]['ExamStat']['answer'] = 'null';
				}
				if ($value['ExamStat']['true_false'] == '') {
					$userExamQuestion[$key]['ExamStat']['true_false'] = 'null';
				}
				// if ($value['ExamStat']['fill_blank']=='') {
				//     $userExamQuestion[$key]['ExamStat']['fill_blank']='null';
				// }
				if ($value['ExamStat']['marks_obtained'] == '') {
					$userExamQuestion[$key]['ExamStat']['marks_obtained'] = 'null';
				}
				// if ($value['ExamStat']['ques_status']=='') {
				//     $userExamQuestion[$key]['ExamStat']['ques_status']='null';
				// }
				if ($value['ExamStat']['user_id'] == '') {
					$userExamQuestion[$key]['ExamStat']['user_id'] = 'null';
				}
				// if ($value['ExamStat']['checking_time']=='') {
				//     $userExamQuestion[$key]['ExamStat']['checking_time']='null';
				// }
				if ($value['ExamStat']['time_taken'] == '') {
					$userExamQuestion[$key]['ExamStat']['time_taken'] = 'null';
				}
				if ($value['ExamStat']['bookmark'] == '') {
					$userExamQuestion[$key]['ExamStat']['bookmark'] = 'null';
				}

				// userExamQuestion Question
				// if ($value['Question']['passage_id']=='') {
				//     $userExamQuestion[$key]['Question']['passage_id']='null';
				// }

				if ($value['Question']['true_false'] == '') {
					$userExamQuestion[$key]['Question']['true_false'] = 'null';
				}
				if ($value['Question']['fill_blank'] == '') {
					$userExamQuestion[$key]['Question']['fill_blank'] = 'null';
				}

				// userExamQuestion Exam
				if ($value['Exam']['amount'] == '') {
					$userExamQuestion[$key]['Exam']['amount'] = 'null';
				}

				if ($value['Exam']['finalized_time'] == '') {
					$userExamQuestion[$key]['Exam']['finalized_time'] = 'null';
				}
			}
		}

		//examResult
		if (!empty($examResult)) {
			foreach ($examResult as $value) {
				if ($value['end_time'] == '') {
					$examResult['ExamResult']['end_time'] = 'null';
				}
				if ($value['pause_time'] == '') {
					$examResult['ExamResult']['pause_time'] = 'null';
				}
				if ($value['obtained_marks'] == '') {
					$examResult['ExamResult']['obtained_marks'] = 'null';
				}
				if ($value['result'] == '') {
					$examResult['ExamResult']['result'] = 'null';
				}
				if ($value['percent'] == '') {
					$examResult['ExamResult']['percent'] = 'null';
				}
				if ($value['finalized_time'] == '') {
					$examResult['ExamResult']['finalized_time'] = 'null';
				}
				if ($value['user_id'] == '') {
					$examResult['ExamResult']['user_id'] = 'null';
				}
				if ($value['total_answered'] == '') {
					$examResult['ExamResult']['total_answered'] = 'null';
				}


			}
		}


		$this->set(compact('status', 'message', 'examExpire', 'subjectArr', 'userExamQuestion', 'userSectionQuestion', 'currSubjectName', 'post', 'examResult', 'examId', 'totalQuestion', 'examResultId', 'siteDomain', 'studentDetail', 'studentPhoto', 'languageArr'));
		$this->set('_serialize', array('status', 'message', 'examExpire', 'subjectArr', 'userExamQuestion', 'userSectionQuestion', 'currSubjectName', 'post', 'examResult', 'examId', 'totalQuestion', 'examResultId', 'siteDomain', 'studentDetail', 'studentPhoto', 'languageArr'));
	}

	public function rest_saveAll()
	{
		$message = __('Invalid Post');
		$status = false;
		$feedback = false;
		$result = false;
		$examResultId = null;
		if ($this->request->is('post')) {
			if (isset($this->request->data['responses'])) {
				$dataArr = $this->restPostKey($this->request->data);
				if ($this->authenticateRest($dataArr)) {
					$this->studentId = $this->restStudentId($dataArr);
					$this->loadModel('ExamResult');
					$id = $this->request->data['exam_id'];
					$examResultRecord = $this->ExamResult->find('first', array('fields' => array('id'), 'conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
					if ($examResultRecord) {
						$responseArr = $this->request->data['responses'];
						$examResultId = $examResultRecord['ExamResult']['id'];
						foreach ($responseArr as $item) {
							$valueArr = array();
							if ($item['question_type'] == "M") {
								$valueArr['Exam']['option_selected'] = $item['response'];
							} elseif ($item['question_type'] == "T") {
								$valueArr['Exam']['true_false'] = $item['response'];
							} elseif ($item['question_type'] == "F") {
								$valueArr['Exam']['fill_blank'] = $item['response'];
							} else {
								$valueArr['Exam']['answer'] = $item['response'];
							}
							$valueArr['Exam']['review'] = $item['review'];
							$valueArr['Exam']['attempt_time'] = $item['attempt_time'];
							$valueArr['Exam']['time_taken'] = $item['time_taken'];
							$quesNo = $item['question_no'];
							$this->Exam->userSaveAnswer($id, $quesNo, $this->studentId, $this->currentDateTime, $valueArr);
						}
						$requestArr = $this->rest_finish($id, $this->studentId);
						$status = true;
						$feedback = $requestArr['feedback'];
						$result = $requestArr['result'];
						$message = $requestArr['message'];
					} else {
						$message = __('No exam opened');
					}
				} else {
					$message = ('Invalid Token');
				}
			}
		} else {
			$message = __('GET request not allowed!');
		}
		$this->set(compact('status', 'message', 'feedback', 'result', 'examResultId'));
		$this->set('_serialize', array('status', 'message', 'feedback', 'result', 'examResultId'));
	}

	public function rest_expiredFinish()
	{
		$message = __('Invalid Post');
		$status = false;
		$feedback = false;
		$result = false;
		$examResultId = null;
		if ($this->request->is('post')) {
			if (isset($this->request->data['exam_id'])) {
				$dataArr = $this->restPostKey($this->request->data);
				if ($this->authenticateRest($dataArr)) {
					$this->studentId = $this->restStudentId($dataArr);
					$id = $this->request->data['exam_id'];
					$requestArr = $this->rest_finish($id, $this->studentId);
					$examResultId = $requestArr['examResultId'];
					$status = true;
					$feedback = $requestArr['feedback'];
					$result = $requestArr['result'];
					$message = $requestArr['message'];
				} else {
					$message = ('Invalid Token');
				}
			}
		} else {
			$message = __('GET request not allowed!');
		}
		$this->set(compact('status', 'message', 'feedback', 'result', 'examResultId'));
		$this->set('_serialize', array('status', 'message', 'feedback', 'result', 'examResultId'));
	}

	public function rest_showFeedback()
	{
		if ($this->authenticateRest($this->request->query)) {
			$response = $this->feedbackArr;
			$status = true;
			$message = __('Feedback fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = (object) array();
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	public function rest_feedbacks()
	{
		$message = __('Invalid Post');
		$status = false;
		$examResultId = null;
		if ($this->request->is('post')) {
			if (isset($this->request->data['responses'])) {
				$dataArr = $this->restPostKey($this->request->data);
				if ($this->authenticateRest($dataArr)) {
					$this->studentId = $this->restStudentId($dataArr);
					if (isset($this->request->data['exam_result_id'])) {
						$id = $this->request->data['exam_result_id'];
					} else {
						$id = 0;
					}
					$this->loadModel('ExamResult');
					$examArr = $this->ExamResult->findByIdAndStudentId($id, $this->studentId);
					if ($id && $examArr) {
						try {
							$this->loadModel('ExamFeedback');
							$this->ExamFeedback->create();
							$recordArr['ExamFeedback'] = $this->request->data['responses'];
							$recordArr['ExamFeedback']['exam_result_id'] = $id;
							$this->ExamFeedback->save($recordArr);
							$message = __('Feedback has submitted successfully!');
							$status = true;
							$examResultId = $id;
						} catch (Exception $e) {
							$message = __('Feedback already submitted.');
						}
					} else {
						$message = __('Invalid Post');
					}
				} else {
					$message = ('Invalid Token');
				}
			}
		} else {
			$message = __('GET request not allowed!');
		}
		$this->set(compact('status', 'message', 'examResultId'));
		$this->set('_serialize', array('status', 'message', 'examResultId'));
	}

	private function resultEmailSms($currentExamResult, $examArr)
	{
		try {
			if ($this->emailNotification || $this->smsNotification) {
				$valueArr = $this->ExamResult->findById($currentExamResult['ExamResult']['id']);
				$siteName = $this->siteName;
				$siteEmailContact = $this->siteEmailContact;
				$url = $this->siteDomain;
				$email = $this->userValue['Student']['email'];
				$studentName = $this->userValue['Student']['name'];
				$mobileNo = $this->userValue['Student']['phone'];
				$examName = $examArr['Exam']['name'];
				$result = $valueArr['ExamResult']['result'];
				$obtainedMarks = $valueArr['ExamResult']['obtained_marks'];
				$questionAttempt = $valueArr['ExamResult']['total_answered'];
				$timeTaken = $this->CustomFunction->secondsToWords(CakeTime::fromString($valueArr['ExamResult']['end_time']) - CakeTime::fromString($valueArr['ExamResult']['start_time']));
				$percent = $valueArr['ExamResult']['percent'];
				if ($this->emailNotification == 1) {
					/* Send Email */
					$this->loadModel('Emailtemplate');
					$emailTemplateArr = $this->Emailtemplate->findByType('ERT');
					if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
						$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
							'{#studentName#}' => $studentName,
							'{#examName#}' => $examName,
							'{#result#}' => $result,
							'{#obtainedMarks#}' => $obtainedMarks,
							'{#questionAttempt#}' => $questionAttempt,
							'{#timeTaken#}' => $timeTaken,
							'{#percent#}' => $percent,
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
				if ($this->smsNotification) {
					/* Send Sms */
					$this->loadModel('Smstemplate');
					$smsTemplateArr = $this->Smstemplate->findByType('ERT');
					if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
						$url = $this->siteDomain;
						$message = strtr($smsTemplateArr['Smstemplate']['description'], [
							'{#studentName#}' => $studentName,
							'{#examName#}' => $examName,
							'{#result#}' => $result,
							'{#obtainedMarks#}' => $obtainedMarks,
							'{#questionAttempt#}' => $questionAttempt,
							'{#timeTaken#}' => $timeTaken,
							'{#percent#}' => $percent,
							'{#siteName#}' => $siteName
						]);
						$this->CustomFunction->sendSms($mobileNo, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
					}
					/* End Sms */
				}
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
		}
	}

	private function rest_finish($id, $studentId)
	{
		$feedback = false;
		$result = false;
		$message = null;
		$this->studentId = $studentId;
		$this->loadModel('ExamResult');
		$currentExamResult = $this->ExamResult->find('first', array('conditions' => array('exam_id' => $id, 'student_id' => $this->studentId, 'end_time' => null)));
		if ($currentExamResult) {
			$this->Exam->userExamFinish($id, $this->studentId, $this->currentDateTime);
			$this->loadModel('Exam');
			$examArr = $this->Exam->findById($id);
			if ($this->examFeedback) {
				if ($examArr['Exam']['finish_result']) {
					$this->resultEmailSms($currentExamResult, $examArr);
					$result = true;
					$message = __('Please complete feedback.');
				}
				$feedback = true;
			} else {
				if ($examArr['Exam']['finish_result']) {
					$this->resultEmailSms($currentExamResult, $examArr);
					$feedback = false;
					$result = true;
					$message = __('You can find your result here');
				} else {
					$feedback = false;
					$result = false;
					$message = __('Thanks for given the exam.');
				}
			}
		}
		return array('feedback' => $feedback, 'result' => $result, 'message' => $message, 'examResultId' => $currentExamResult['ExamResult']['id']);
	}

	private function checkPaidStatus($examId, $studentId)
	{
		$this->loadModel('Exam');
		$this->loadModel('ExamResult');
		$this->loadModel('ExamOrder');
		$post = $this->Exam->findByIdAndStatus($examId, 'Active');
		$attemptCount = $post['Exam']['attempt_count'];
		$totalExam = $this->ExamResult->find('count', array('conditions' => array('exam_id' => $examId, 'student_id' => $studentId)));
		$countExamOrder = $this->ExamOrder->find('count', array('conditions' => array('exam_id' => $examId, 'student_id' => $studentId)));
		if ($countExamOrder == null || $countExamOrder == 0) {
			$countExamOrder = 1;
		}
		$isPaid = false;

		if ($countExamOrder > 0 && $attemptCount == 0) {
			$isPaid = true;
		} else {
			if ($countExamOrder * $attemptCount > $totalExam) {
				$isPaid = true;
			}
		}
		//$examOrder = $this->ExamOrder->find('first', array('conditions' => array('exam_id' => $examId, 'student_id' => $studentId), 'order' => array('id' => 'desc')));
		//if ($examOrder) {
		//    if ($examOrder['ExamOrder']['expiry_date'] != null && $this->currentDate > $examOrder['ExamOrder']['expiry_date']) {
		//        $isPaid = false;
		//    }
		//} else {
		//    $isPaid = false;
		//}
		return $isPaid;
	}
}