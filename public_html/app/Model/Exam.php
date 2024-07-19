<?php

class Exam extends AppModel
{
	public $validationDomain = 'validation';

public function getPurchasedExam($type, $studentId, $currentDateTime, $limit = NULL, $offset = 0)
	{
		$StudentGroup = ClassRegistry::init('StudentGroup');
		$StudentGroupArr = $StudentGroup->findAllBystudentId($studentId);
		$mainStudentGroup = array();
		foreach ($StudentGroupArr as $studentGroupIdArr) {
			$mainStudentGroup[] = $studentGroupIdArr['StudentGroup']['group_id'];
		}
		if ($limit == NULL) {
			$limit = 500000000;
		}
		$start_date = "";
		$end_date = "";
		$examLimit = "";
		$expiredDate = array();
		$packageType = array();
		$virtualFieldExpiryDate = array('fexpiry_date' => 'Exam.id');
		$expiryDateField = "Exam.fexpiry_date";
		if ($type == "today") {
			$packageType = array('Package.package_type <>' => 'F');
			$start_date = array('Exam.start_date <=' => $currentDateTime);
			$end_date = array('Exam.end_date >' => $currentDateTime);
			$expiredDate = array(
				'OR' => array(
					'PackagesPayment.expiry_date >=' => substr($currentDateTime, 0, 10),
					'PackagesPayment.expiry_date IS NULL'
				)
			);
		}
		if ($type == "upcoming") {
			$start_date = array('Exam.start_date >' => $currentDateTime);
			$expiredDate = array(
				'OR' => array(
					'PackagesPayment.expiry_date >=' => substr($currentDateTime, 0, 10),
					'PackagesPayment.expiry_date IS NULL'
				)
			);
		}
		if ($type == "expired") {
			$expiredDate = array(
				'OR' => array(
					'Exam.end_date <' => $currentDateTime,
					'PackagesPayment.expiry_date <' => substr($currentDateTime, 0, 10),
					//'PackagesPayment.expiry_date IS NULL'
				)
			);
			$virtualFieldExpiryDate = array('fexpiry_date' => 'PackagesPayment.expiry_date');
			$expiryDateField = "Exam.fexpiry_date";
		}
		if ($limit > 0)
			$examLimit = $limit;
		$this->virtualFields = array();
		$this->virtualFields = array_merge(
			array(
				'attempt' => 'SELECT COUNT(ExamResult.id) FROM `exam_results` AS `ExamResult` WHERE `ExamResult`.`exam_id`=`Exam.id` AND `ExamResult`.`student_id`=' . $studentId,
				'attempt_order' => 'SELECT COUNT(ExamOrder.id) FROM `exam_orders` AS `ExamOrder` WHERE `ExamOrder`.`exam_id`=`Exam.id` AND `ExamOrder`.`student_id`=' . $studentId
			)
			,
			$virtualFieldExpiryDate
		);

		$examListArr = $this->find(
			'all',
			array(
				'fields' => array('Exam.attempt', 'Exam.attempt_order', $expiryDateField, 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Exam.duration', 'Exam.exam_mode', 'Package.name', 'Package.photo'),
				'joins' => array(
					array('table' => 'exam_preps', 'alias' => 'ExamPrep', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamPrep.exam_id')),
					array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'LEFT', 'conditions' => array('PackagesPayment.student_id' => $studentId)),
					array('table' => 'packages', 'alias' => 'Package', 'type' => 'INNER', 'conditions' => array('Package.id=PackagesPayment.package_id')),
					array('table' => 'exams_packages', 'alias' => 'ExamsPackage', 'type' => 'INNER', 'conditions' => array('ExamsPackage.exam_id=Exam.id', 'ExamsPackage.package_id=Package.id')),
				),
				'conditions' => array($start_date, $end_date, $expiredDate, 'Exam.status' => 'Active', 'Exam.user_id' => 0, 'PackagesPayment.student_id' => $studentId, 'PackagesPayment.status' => 'Approved', $packageType),
				'order' => array('Package.name' => 'asc', 'Exam.start_date' => 'asc'),
				'group' => array('Exam.start_date', 'Exam.attempt', 'Exam.attempt_order', $expiryDateField, 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Exam.duration', 'Exam.exam_mode', 'Package.name', 'Package.photo'),
				'limit' => $limit,
				'offset' => $offset
			)
		);
		$this->virtualFields = array();
		$examList = array();
		foreach ($examListArr as $item) {
			if ($item['Exam']['attempt_order'] == 0) {
				$item['Exam']['attempt_order'] = 1;
			}
			$item['Exam']['duration'] = $this->secondsToWords($item['Exam']['duration'] * 60);
			$examList[] = $item;
		}
		return $examList;
	}

	public function paidExamCount($type, $studentId, $currentDateTime)
	{
		$StudentGroup = ClassRegistry::init('StudentGroup');
		$StudentGroupArr = $StudentGroup->findAllBystudentId($studentId);
		$mainStudentGroup = array();
		foreach ($StudentGroupArr as $studentGroupIdArr) {
			$mainStudentGroup[] = $studentGroupIdArr['StudentGroup']['group_id'];
		}
		$start_date = "";
		$end_date = "";
		$examLimit = "";
		$expiredDate = array();
		if ($type == "today") {
			$start_date = array('Exam.start_date <=' => $currentDateTime);
			$end_date = array('Exam.end_date >' => $currentDateTime);
			$expiredDate = array(
				'OR' => array(
					'PackagesPayment.expiry_date >=' => substr($currentDateTime, 0, 10),
					'PackagesPayment.expiry_date IS NULL'
				)
			);
		}
		if ($type == "upcoming") {
			$start_date = array('Exam.start_date >' => $currentDateTime);
			$expiredDate = array(
				'OR' => array(
					'PackagesPayment.expiry_date >=' => substr($currentDateTime, 0, 10),
					'PackagesPayment.expiry_date IS NULL'
				)
			);
		}
		if ($type == "expired") {
			$expiredDate = array(
				'OR' => array(
					'Exam.end_date <' => $currentDateTime,
					'PackagesPayment.expiry_date <' => substr($currentDateTime, 0, 10),
					//'PackagesPayment.expiry_date IS NULL'
				)
			);
		}
		$this->virtualFields = array();
		$this->virtualFields = array(
			'attempt' => 'SELECT COUNT(ExamResult.id) FROM `exam_results` AS `ExamResult` WHERE `ExamResult`.`exam_id`=`Exam.id` AND `ExamResult`.`student_id`=' . $studentId,
			'attempt_order' => 'SELECT COUNT(ExamOrder.id) FROM `exam_orders` AS `ExamOrder` WHERE `ExamOrder`.`exam_id`=`Exam.id` AND `ExamOrder`.`student_id`=' . $studentId,
		);

		$examList = $this->find(
			'count',
			array(
				'fields' => array('Exam.attempt', 'Exam.attempt_order', 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Package.name'),
				'joins' => array(
					array('table' => 'exam_preps', 'alias' => 'ExamPrep', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamPrep.exam_id')),
					array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'LEFT', 'conditions' => array('PackagesPayment.student_id' => $studentId)),
					array('table' => 'packages', 'alias' => 'Package', 'type' => 'INNER', 'conditions' => array('Package.id=PackagesPayment.package_id')),
					array('table' => 'exams_packages', 'alias' => 'ExamsPackage', 'type' => 'INNER', 'conditions' => array('ExamsPackage.exam_id=Exam.id', 'ExamsPackage.package_id=Package.id')),
				),
				'conditions' => array($start_date, $end_date, $expiredDate, 'Exam.status' => 'Active', 'Exam.user_id' => 0, 'PackagesPayment.student_id' => $studentId, 'PackagesPayment.status' => 'Approved', 'Package.package_type <>' => 'F'),
				'group' => array('Exam.attempt', 'Exam.attempt_order', 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Package.name')
			)
		);
		$this->virtualFields = array();
		return $examList;
	}

	public function getFreeExam($studentId, $currentDateTime, $limit = NULL, $offset = 0)
	{
		$StudentGroup = ClassRegistry::init('StudentGroup');
		$StudentGroupArr = $StudentGroup->findAllBystudentId($studentId);
		$mainStudentGroup = array();
		foreach ($StudentGroupArr as $studentGroupIdArr) {
			$mainStudentGroup[] = $studentGroupIdArr['StudentGroup']['group_id'];
		}
		if ($limit == NULL) {
			$limit = 500000000;
		}
		$virtualFieldExpiryDate = array('fexpiry_date' => 'Exam.id');
		$expiryDateField = "Exam.fexpiry_date";
		$start_date = array('Exam.start_date <=' => $currentDateTime);
		$end_date = array('Exam.end_date >' => $currentDateTime);
		$expiredDate = array(
			'OR' => array(
				'PackagesPayment.expiry_date >=' => substr($currentDateTime, 0, 10),
				'PackagesPayment.expiry_date IS NULL'
			)
		);
		$this->virtualFields = array();
		$this->virtualFields = array_merge(
			array(
				'attempt' => 'SELECT COUNT(ExamResult.id) FROM `exam_results` AS `ExamResult` WHERE `ExamResult`.`exam_id`=`Exam.id` AND `ExamResult`.`student_id`=' . $studentId,
				'attempt_order' => 'SELECT COUNT(ExamOrder.id) FROM `exam_orders` AS `ExamOrder` WHERE `ExamOrder`.`exam_id`=`Exam.id` AND `ExamOrder`.`student_id`=' . $studentId
			),
			$virtualFieldExpiryDate
		);
		$examListArr = $this->find(
			'all',
			array(
				'fields' => array('Exam.attempt', 'Exam.attempt_order', $expiryDateField, 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Exam.duration', 'Exam.exam_mode', 'Package.name', 'Package.photo'),
				'joins' => array(
					array('table' => 'exam_preps', 'alias' => 'ExamPrep', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamPrep.exam_id')),
					array('table' => 'exam_groups', 'alias' => 'ExamGroup', 'type' => 'INNER', 'conditions' => array('Exam.id=ExamGroup.exam_id')),
					array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'LEFT', 'conditions' => array('PackagesPayment.student_id' => $studentId)),
					array('table' => 'packages', 'alias' => 'Package', 'type' => 'INNER', 'conditions' => array('Package.id=PackagesPayment.package_id')),
					array('table' => 'exams_packages', 'alias' => 'ExamsPackage', 'type' => 'INNER', 'conditions' => array('ExamsPackage.exam_id=Exam.id', 'ExamsPackage.package_id=Package.id')),
				),
				'conditions' => array($start_date, $end_date, $expiredDate, 'Exam.status' => 'Active', 'Exam.user_id' => 0, 'PackagesPayment.student_id' => $studentId, 'PackagesPayment.status' => 'Approved', 'Package.status' => 'Active', 'Package.package_type' => 'F', 'ExamGroup.group_id' => $mainStudentGroup),
				'order' => array('Package.name' => 'asc', 'Exam.start_date' => 'asc'),
				'group' => array('Exam.start_date', 'Exam.attempt', 'Exam.attempt_order', $expiryDateField, 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Exam.duration', 'Exam.exam_mode', 'Package.name', 'Package.photo'),
				'limit' => $limit,
				'offset' => $offset
			)
		);
		$this->virtualFields = array();
		$examList = array();
		foreach ($examListArr as $item) {
			if ($item['Exam']['attempt_order'] == 0) {
				$item['Exam']['attempt_order'] = 1;
			}
			$item['Exam']['duration'] = $this->secondsToWords($item['Exam']['duration'] * 60);
			$examList[] = $item;
		}
		return $examList;
	}

	public function freeExamCount($studentId, $currentDateTime)
	{
		$StudentGroup = ClassRegistry::init('StudentGroup');
		$StudentGroupArr = $StudentGroup->findAllBystudentId($studentId);
		$mainStudentGroup = array();
		foreach ($StudentGroupArr as $studentGroupIdArr) {
			$mainStudentGroup[] = $studentGroupIdArr['StudentGroup']['group_id'];
		}
		$start_date = array('Exam.start_date <=' => $currentDateTime);
		$end_date = array('Exam.end_date >' => $currentDateTime);
		$expiredDate = array(
			'OR' => array(
				'PackagesPayment.expiry_date >=' => substr($currentDateTime, 0, 10),
				'PackagesPayment.expiry_date IS NULL'
			)
		);
		$this->virtualFields = array();
		$this->virtualFields = array(
			'attempt' => 'SELECT COUNT(ExamResult.id) FROM `exam_results` AS `ExamResult` WHERE `ExamResult`.`exam_id`=`Exam.id` AND `ExamResult`.`student_id`=' . $studentId,
			'attempt_order' => 'SELECT COUNT(ExamOrder.id) FROM `exam_orders` AS `ExamOrder` WHERE `ExamOrder`.`exam_id`=`Exam.id` AND `ExamOrder`.`student_id`=' . $studentId,
		);
		$examList = $this->find(
			'count',
			array(
				'fields' => array('Exam.attempt', 'Exam.attempt_order', 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Package.name'),
				'joins' => array(
					array('table' => 'exam_preps', 'alias' => 'ExamPrep', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamPrep.exam_id')),
					array('table' => 'exam_groups', 'alias' => 'ExamGroup', 'type' => 'INNER', 'conditions' => array('Exam.id=ExamGroup.exam_id')),
					array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'LEFT', 'conditions' => array('PackagesPayment.student_id' => $studentId)),
					array('table' => 'packages', 'alias' => 'Package', 'type' => 'INNER', 'conditions' => array('Package.id=PackagesPayment.package_id')),
					array('table' => 'exams_packages', 'alias' => 'ExamsPackage', 'type' => 'INNER', 'conditions' => array('ExamsPackage.exam_id=Exam.id', 'ExamsPackage.package_id=Package.id')),
				),
				'conditions' => array($start_date, $end_date, $expiredDate, 'Exam.status' => 'Active', 'Exam.user_id' => 0, 'PackagesPayment.student_id' => $studentId, 'PackagesPayment.status' => 'Approved', 'Package.status' => 'Active', 'Package.package_type' => 'F', 'ExamGroup.group_id' => $mainStudentGroup),
				'group' => array('Exam.attempt', 'Exam.attempt_order', 'Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Package.name')
			)
		);
		$this->virtualFields = array();
		return $examList;
	}

	public function insertFreeExam($studentId, $currentDateTime, $limit = NULL, $offset = 0)
	{
		$StudentGroup = ClassRegistry::init('StudentGroup');
		$StudentGroupArr = $StudentGroup->findAllBystudentId($studentId);
		$mainStudentGroup = array();
		foreach ($StudentGroupArr as $studentGroupIdArr) {
			$mainStudentGroup[] = $studentGroupIdArr['StudentGroup']['group_id'];
		}
		if ($limit == NULL) {
			$limit = 500000000;
		}
		$start_date = array('Exam.start_date <=' => $currentDateTime);
		$end_date = array('Exam.end_date >' => $currentDateTime);

		if ($limit > 0)
			$examLimit = $limit;

		$examList = $this->find(
			'all',
			array(
				'fields' => array('Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Package.name'),
				'joins' => array(
					array('table' => 'exam_preps', 'alias' => 'ExamPrep', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamPrep.exam_id')),
					array('table' => 'exam_groups', 'alias' => 'ExamGroup', 'type' => 'Inner', 'conditions' => array('Exam.id=ExamGroup.exam_id')),
					array('table' => 'exams_packages', 'alias' => 'ExamsPackage', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamsPackage.exam_id', 'ExamGroup.exam_id=ExamsPackage.exam_id')),
					array('table' => 'packages', 'alias' => 'Package', 'type' => 'LEFT', 'conditions' => array('Package.id=ExamsPackage.package_id')),
				),
				'conditions' => array($start_date, $end_date, 'Exam.status' => 'Active', 'Exam.user_id' => 0, 'Package.status' => 'Active', 'Package.package_type' => 'F', 'ExamGroup.group_id' => $mainStudentGroup),
				'order' => array('Package.name' => 'asc', 'Exam.name' => 'asc'),
				'group' => array('Package.id', 'Exam.id', 'Exam.type', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.paid_exam', 'Exam.amount', 'Exam.attempt_count', 'Exam.expiry', 'Package.name'),
				'limit' => $limit,
				'offset' => $offset
			)
		);
		$this->virtualFields = array();
		return $examList;
	}

	public function getSubject($id)
	{
		$ExamQuestion = ClassRegistry::init('ExamQuestion');
		$subjectDetail = $ExamQuestion->find('all', array(
			'fields' => array('Subject.id', 'Subject.subject_name', 'ExamMaxquestion.max_question'),
			'joins' => array(array('table' => 'questions', 'type' => 'Inner', 'alias' => 'Question',
				'conditions' => array('Question.id=ExamQuestion.question_id')),
				array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject',
					'conditions' => array('Subject.id=Question.subject_id')),
				array('table' => 'exam_maxquestions', 'type' => 'left', 'alias' => 'ExamMaxquestion',
					'conditions' => array('ExamQuestion.exam_id=ExamMaxquestion.exam_id', 'Subject.id=ExamMaxquestion.subject_id')),
			),
			'conditions' => array('ExamQuestion.exam_id' => $id),
			'group' => array('Subject.id', 'Subject.subject_name', 'ExamMaxquestion.max_question'),
			'order' => 'Subject.subject_name asc'));
		return $subjectDetail;
	}

	public function getPrepSubject($id)
	{
		$ExamPrep = ClassRegistry::init('ExamPrep');
		$subjectDetail = $ExamPrep->find('all', array(
			'fields' => array('Subject.id', 'Subject.subject_name', 'ExamPrep.subject_id', 'ExamPrep.ques_no', 'ExamPrep.type', 'ExamPrep.level', 'ExamMaxquestion.max_question'),
			'joins' => array(array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject',
				'conditions' => array('Subject.id=ExamPrep.subject_id')),
				array('table' => 'exam_maxquestions', 'type' => 'Left', 'alias' => 'ExamMaxquestion', 'conditions' => array('ExamPrep.exam_id=ExamMaxquestion.exam_id', 'ExamPrep.subject_id=ExamMaxquestion.subject_id')),),
			'conditions' => array('ExamPrep.exam_id' => $id),
			'order' => 'Subject.subject_name asc'));
		return $subjectDetail;
	}

	public function totalPrepQuestions($id, $studentId = null)
	{
		if ($studentId == null) {
			$ExamPrep = ClassRegistry::init('ExamPrep');
			$ExamPrep->virtualFields = array('total_question' => 'SUM(ExamPrep.ques_no)');
			$totalQuestionArr = $ExamPrep->find('first', array(
				'fields' => array('total_question'),
				'conditions' => array('ExamPrep.exam_id' => $id)));
			$totalQuestion = $totalQuestionArr['ExamPrep']['total_question'];
			return $totalQuestion;
		} else {
			$ExamResult = ClassRegistry::init('ExamResult');
			$ExamResultArr = $ExamResult->findByExamIdAndStudentIdAndEndTime($id, $studentId, NULL);
			$ExamPrep = ClassRegistry::init('ExamStat');
			$totalQuestion = $ExamPrep->find('count', array('conditions' => array('ExamStat.exam_id' => $id, 'ExamStat.exam_result_id' => $ExamResultArr['ExamResult']['id'], 'student_id' => $studentId)));
			return $totalQuestion;
		}
	}

	public function checkPost($id, $studentId)
	{
		$checkPost = $this->find('count', array(
			'joins' => array(
				array('table' => 'exam_groups', 'alias' => 'ExamGroup', 'type' => 'Inner', 'conditions' => array('Exam.id=ExamGroup.exam_id')),
				array('table' => 'student_groups', 'alias' => 'StudentGroup', 'type' => 'Inner', 'conditions' => array('StudentGroup.group_id=ExamGroup.group_id')),
				array('table' => 'payments', 'alias' => 'Payment', 'type' => 'INNER', 'conditions' => array('StudentGroup.student_id=Payment.student_id')),
				array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'INNER', 'conditions' => array('Payment.id=PackagesPayment.payment_id')),
				array('table' => 'exams_packages', 'alias' => 'ExamsPackage', 'type' => 'INNER', 'conditions' => array('Exam.id=ExamsPackage.exam_id', 'PackagesPayment.package_id=ExamsPackage.package_id')),
			),
			'conditions' => array('Exam.id' => $id, 'Payment.student_id' => $studentId, 'Exam.status' => 'Active', 'Exam.user_id' => 0)));
		return $checkPost;
	}

	public function checkPostActive($id, $studentId)
	{
		$checkPost = $this->find('count', array(
			'conditions' => array('Exam.id' => $id)));
		return $checkPost;
	}

	public function totalMarks($id)
	{
		$limit = 0;
		$ExamQuestion = ClassRegistry::init('ExamQuestion');
		$ExamMaxquestion = ClassRegistry::init('ExamMaxquestion');
		$examMaxQuestionArr = $ExamMaxquestion->find('all', array('fields' => array('ExamMaxquestion.subject_id', 'ExamMaxquestion.max_question'), 'conditions' => array('ExamMaxquestion.exam_id' => $id)));
		$totalMarks = 0;
		if ($examMaxQuestionArr) {
			foreach ($examMaxQuestionArr as $value) {
				$quesNo = $value['ExamMaxquestion']['max_question'];
				$subjectId = $value['ExamMaxquestion']['subject_id'];
				if ($quesNo == 0)
					$limit = " ";
				else
					$limit = ' LIMIT ' . $quesNo;
				$ExamQuestion->virtualFields = array('total_marks' => 'SELECT SUM(`marks`) FROM (SELECT `Question`.`marks` FROM `exam_questions` AS `ExamQuestion` Inner JOIN `questions` AS `Question` ON (`ExamQuestion`.`question_id`=`Question`.`id`) WHERE `ExamQuestion`.`exam_id`=' . $id . ' AND `Question`.`subject_id`=' . $subjectId . $limit . ') subquery');
				$totalMarksArr = $ExamQuestion->find('first', array('fields' => array('total_marks')));
				$totalMarks = $totalMarks + $totalMarksArr['ExamQuestion']['total_marks'];
			}
		} else {
			$ExamQuestion->virtualFields = array('total_marks' => 'SUM(Question.marks)');
			$totalMarksArr = $ExamQuestion->find('first', array(
				'fields' => array('total_marks'),
				'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner',
					'conditions' => array('ExamQuestion.question_id=Question.id'))),
				'conditions' => array("ExamQuestion.exam_id=$id")));
			$totalMarks = $totalMarksArr['ExamQuestion']['total_marks'];
		}
		return $totalMarks;
	}

	public function totalQuestion($id)
	{
		$ExamQuestion = ClassRegistry::init('ExamQuestion');
		$ExamMaxquestion = ClassRegistry::init('ExamMaxquestion');
		$examMaxQuestionArr = $ExamMaxquestion->find('all', array('fields' => array('ExamMaxquestion.subject_id', 'ExamMaxquestion.max_question'), 'conditions' => array('ExamMaxquestion.exam_id' => $id)));
		$totalQuestion = 0;
		if ($examMaxQuestionArr) {
			foreach ($examMaxQuestionArr as $value) {
				$quesNo = $value['ExamMaxquestion']['max_question'];
				$subjectId = $value['ExamMaxquestion']['subject_id'];
				if ($quesNo == 0)
					$totalQuestionCount = $ExamQuestion->find('count', array('joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Left', 'conditions' => array('Question.id=ExamQuestion.question_id'))), 'conditions' => array('ExamQuestion.exam_id' => $id, 'Question.subject_id' => $subjectId)));
				else
					$totalQuestionCount = $quesNo;
				$totalQuestion = $totalQuestion + $totalQuestionCount;
			}
		} else {
			$totalQuestion = $ExamQuestion->find('count', array('joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Left', 'conditions' => array('Question.id=ExamQuestion.question_id'))), 'conditions' => array('ExamQuestion.exam_id' => $id)));
		}
		return $totalQuestion;
	}

	public function totalPrepAttemptQuestion($id)
	{
		$ExamMaxquestion = ClassRegistry::init('ExamMaxquestion');
		$ExamPrep = ClassRegistry::init('ExamPrep');
		$examMaxQuestionArr = $ExamMaxquestion->find('all', array('fields' => array('ExamMaxquestion.subject_id', 'ExamMaxquestion.max_question'), 'conditions' => array('ExamMaxquestion.exam_id' => $id)));
		$totalQuestion = 0;
		if ($examMaxQuestionArr) {
			foreach ($examMaxQuestionArr as $value) {
				$quesNo = $value['ExamMaxquestion']['max_question'];
				$subjectId = $value['ExamMaxquestion']['subject_id'];
				if ($quesNo == 0) {
					$totalQuestionCountArr = $ExamPrep->find('first', array('conditions' => array('ExamPrep.exam_id' => $id, 'ExamPrep.subject_id' => $subjectId)));
					$totalQuestionCount = $totalQuestionCountArr['ExamPrep']['ques_no'];
				} else
					$totalQuestionCount = $quesNo;
				$totalQuestion = $totalQuestion + $totalQuestionCount;
			}
		} else {
			$totalQuestion = 0;
		}
		return $totalQuestion;
	}

	public function subjectWiseQuestion($examId, $subjectId, $type = 'Prep')
	{
		if ($type == "Exam") {
			$ExamQuestion = ClassRegistry::init('ExamQuestion');
			$ExamMaxquestion = ClassRegistry::init('ExamMaxquestion');
			$totalQuestion = $ExamQuestion->find('count', array('joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamQuestion.question_id=Question.id'))),
				'conditions' => array('ExamQuestion.exam_Id' => $examId, 'Question.subject_id' => $subjectId)));
			$examMaxQuestionArr = $ExamMaxquestion->find('first', array('fields' => array('ExamMaxquestion.subject_id', 'ExamMaxquestion.max_question'), 'conditions' => array('ExamMaxquestion.exam_id' => $examId, 'ExamMaxquestion.subject_id' => $subjectId)));
			if ($examMaxQuestionArr && $examMaxQuestionArr['ExamMaxquestion']['max_question'] != 0)
				$totalAttemptQuestion = $examMaxQuestionArr['ExamMaxquestion']['max_question'];
			else
				$totalAttemptQuestion = $totalQuestion;
		} else {
			$ExamPrep = ClassRegistry::init('ExamPrep');
			$ExamMaxquestion = ClassRegistry::init('ExamMaxquestion');
			$ExamPrepArr = $ExamPrep->find('first', array('conditions' => array('ExamPrep.exam_id' => $examId, 'ExamPrep.subject_id' => $subjectId)));
			$totalQuestion = $ExamPrepArr['ExamPrep']['ques_no'];
			$examMaxQuestionArr = $ExamMaxquestion->find('first', array('fields' => array('ExamMaxquestion.subject_id', 'ExamMaxquestion.max_question'), 'conditions' => array('ExamMaxquestion.exam_id' => $examId, 'ExamMaxquestion.subject_id' => $subjectId)));
			if ($examMaxQuestionArr && $examMaxQuestionArr['ExamMaxquestion']['max_question'] != 0)
				$totalAttemptQuestion = $examMaxQuestionArr['ExamMaxquestion']['max_question'];
			else
				$totalAttemptQuestion = $totalQuestion;
		}
		$questionArr = array('total_question' => $totalQuestion, 'total_attempt_question' => $totalAttemptQuestion);
		return $questionArr;
	}

	public function userQuestion($id, $ques_random, $type)
	{
		$totalMarks = 0;
		if ($type == "Exam") {
			$ExamQuestion = ClassRegistry::init('ExamQuestion');
			$ExamQuestion->virtualFields = array();
			if ($ques_random == 1) {
				$userQuestion1 = $ExamQuestion->find('all', array('fields' => array('exam_id', 'question_id', 'Question.lock_question', 'Question.lock_answer', 'Question.marks', 'Question.answer', 'Question.true_false', 'Question.fill_blank', 'Question.subject_id', 'Qtype.type'),
					'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamQuestion.question_id=Question.id')),
						array('table' => 'qtypes', 'alias' => 'Qtype', 'type' => 'Inner', 'conditions' => array('Qtype.id=Question.qtype_id')),
						array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner', 'conditions' => array('Question.subject_id=Subject.id'))),
					'conditions' => array('exam_id' => $id, 'Question.lock_question IS NOT NULL'),
					'order' => array('Subject.ordering' => 'asc', 'Question.passage_id' => 'asc', 'rand()')));

				$userQuestion2 = $ExamQuestion->find('all', array('fields' => array('exam_id', 'question_id', 'Question.lock_question', 'Question.lock_answer', 'Question.marks', 'Question.answer', 'Question.true_false', 'Question.fill_blank', 'Question.subject_id', 'Qtype.type'),
					'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamQuestion.question_id=Question.id')),
						array('table' => 'qtypes', 'alias' => 'Qtype', 'type' => 'Inner', 'conditions' => array('Qtype.id=Question.qtype_id')),
						array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner', 'conditions' => array('Question.subject_id=Subject.id'))),
					'conditions' => array('exam_id' => $id, 'Question.lock_question IS NULL'),
					'order' => array('Subject.ordering' => 'asc', 'Question.passage_id' => 'asc', 'rand()')));

				$userTempQuestion = [];
				foreach ($userQuestion2 as $k) {

					$userTempQuestion[] = $k;
				}

				foreach ($userQuestion1 as $v) {

					$userTempQuestion[] = $v;
				}
				$tempSubjectQuestion = [];
				foreach ($userTempQuestion as $item) {
					$tempSubjectQuestion[$item['Question']['subject_id']][] = $item;
				}
				$userQuestion = [];
				foreach ($tempSubjectQuestion as $item) {
					$userQuestion = array_merge($userQuestion, $item);
				}

			} else {
				$userQuestion = $ExamQuestion->find('all', array('fields' => array('exam_id', 'question_id', 'Question.lock_question','Question.lock_answer', 'Question.marks', 'Question.answer', 'Question.true_false', 'Question.fill_blank', 'Question.subject_id', 'Qtype.type'),
					'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamQuestion.question_id=Question.id')),
						array('table' => 'qtypes', 'alias' => 'Qtype', 'type' => 'Inner', 'conditions' => array('Qtype.id=Question.qtype_id')),
						array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner', 'conditions' => array('Question.subject_id=Subject.id'))),
					'conditions' => array('exam_id' => $id),
					'order' => array('Subject.ordering' => 'asc', 'Question.passage_id' => 'asc')));
			}
		} else {
			$Question = ClassRegistry::init('Question');
			$ExamPrepArr = $this->getPrepSubject($id);
			if ($ExamPrepArr) {
				foreach ($ExamPrepArr as $value) {
					$type=array();
					$level=array();
					if($value['ExamPrep']['type']!="null"){
						$type = array("qtype_id IN(".$value['ExamPrep']['type'].")");
					}
					if($value['ExamPrep']['level']!="null"){
						$level = array("diff_id IN(".$value['ExamPrep']['level'].")");
					}
					$userQuestionArr[] = $Question->find('all', array('fields' => array('id', 'Question.lock_question','Question.lock_answer', 'Question.marks', 'Question.answer', 'Question.true_false', 'Question.fill_blank', 'Question.subject_id', 'Qtype.type'),
						'joins' => array(array('table' => 'qtypes', 'alias' => 'Qtype', 'type' => 'Inner', 'conditions' => array('Qtype.id=Question.qtype_id'))),
						'conditions' => array('subject_id' => $value['ExamPrep']['subject_id'], $type, $level),
						'order' => array('Question.passage_id' => 'asc', 'rand()'),
						'limit' => $value['ExamPrep']['ques_no']));
				}
			}
			unset($value);
			$totalMarks = 0;
			$userQuestion = array();
			foreach ($userQuestionArr as $value) {
				foreach ($value as $value1) {
					$totalMarks = $totalMarks + $value1['Question']['marks'];
					$userQuestion[] = array('Question' => array('lock_answer'=>$value1['Question']['lock_answer'], 'marks' => $value1['Question']['marks'], 'question_id' => $value1['Question']['id'], 'answer' => $value1['Question']['answer'], 'true_false' => $value1['Question']['true_false'],
						'fill_blank' => $value1['Question']['fill_blank'],
						'subject_id' => $value1['Question']['subject_id']),
						'Qtype' => array('type' => $value1['Qtype']['type']));
				}
			}
		}
		return array($userQuestion, $totalMarks);
	}

	 function shuffleAssoc($isShuffle)
    {
        $array = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6');
        if ($isShuffle == 1) {
            $keys = array_keys($array);
            shuffle($keys);
            foreach ($keys as $key) {
                $new[$key] = $array[$key];
            }
            $array = $new;
        }
        return $array;
    }

    public function getOptionsStat($isShuffle)
    {
        $option = $this->shuffleAssoc($isShuffle);
        return implode(",", $option);
    }

	public function userExamInsert($id, $ques_random, $type, $optionShuffle, $studentId, $currentDateTime, $examType = "W")
	{
		$userQuestionArr = $this->userQuestion($id, $ques_random, $type);
		$userQuestion = $userQuestionArr[0];
		if ($type == "Exam") {
			$ExamQuestion = ClassRegistry::init('ExamQuestion');
			$totalQuestion = $ExamQuestion->find('count', array('conditions' => array("exam_id=$id")));
			$totalAttemptQuestion = $this->totalQuestion($id);
			$totalMarks = $this->totalMarks($id);
			$examArr = $this->findById($id);
			if ($examArr['Exam']['exam_mode'] == "T") {
				$subjectDetailArr = $this->getSectionSubject($id);
				$totalTestTime = 0;
				foreach ($subjectDetailArr as $value) {
					if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
						$duration = $value['ExamsSubject']['duration'];
					} else {
						$duration = $value['Subject']['section_time'];
					}
					$totalTestTime = $totalTestTime + $duration;
				}
			} else {
				$totalTestTime = $examArr['Exam']['duration'];
			}
		} else {
			$totalQuestion = $this->totalPrepQuestions($id);
			$totalAttemptQuestion = $this->totalPrepAttemptQuestion($id);
			$totalMarks = $userQuestionArr[1];
			$examArr = $this->findById($id);
			if ($examArr['Exam']['exam_mode'] == "T") {
				$subjectDetailArr = $this->getSectionPrepSubject($id);
				$totalTestTime = 0;
				foreach ($subjectDetailArr as $value) {
					if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
						$duration = $value['ExamsSubject']['duration'];
					} else {
						$duration = $value['Subject']['section_time'];
					}
					$totalTestTime = $totalTestTime + $duration;
				}
			} else {
				$totalTestTime = $examArr['Exam']['duration'];
			}
		}
		$ExamResult = ClassRegistry::init('ExamResult');
		$ExamStat = ClassRegistry::init('ExamStat');
		if ($examType == "A") {
			$ExamResultArr = array("ExamResult" => array("exam_id" => $id, "student_id" => $studentId, "total_question" => $totalQuestion, 'total_attempt' => $totalAttemptQuestion, "total_marks" => $totalMarks, "total_test_time" => $totalTestTime, "start_time" => $currentDateTime));
		} else {
			$ExamResultArr = array("ExamResult" => array("exam_id" => $id, "student_id" => $studentId, "total_question" => $totalQuestion, 'total_attempt' => $totalAttemptQuestion, "total_marks" => $totalMarks, 'total_test_time' => $totalTestTime));
		}
		$ExamResult->create();
		if ($ExamResult->save($ExamResultArr)) {
			$lastId = $ExamResult->getInsertID();
			if ($type == "Exam") {
				foreach ($userQuestion as $ques_no => $examQuestionArr) {
					$ques_no++;
					if ($ques_no == 1) {
						$mainSubjectId = $examQuestionArr['Question']['subject_id'];
						$examStatStartTime = $currentDateTime;
						$examStatOpened = "1";
					} else {
						$examStatStartTime = null;
						if ($examType == "A") {
							$examStatOpened = "0";
						} else {
							$examStatOpened = null;
						}
					}
					if ($examType=="A" || $mainSubjectId == $examQuestionArr['Question']['subject_id']) {
						$isSection = '1';
					} else {
						$isSection = '0';
					}
					if ($examQuestionArr['Qtype']['type'] == "M")
						$correct_answer = $examQuestionArr['Question']['answer'];
					elseif ($examQuestionArr['Qtype']['type'] == "T")
						$correct_answer = $examQuestionArr['Question']['true_false'];
					elseif ($examQuestionArr['Qtype']['type'] == "F")
						$correct_answer = $examQuestionArr['Question']['fill_blank'];
					else
						$correct_answer = null;
					if ($examArr['Exam']['exam_mode'] == "T") {
						$tSubjectTime = $this->getSubjectQuestionTime($examQuestionArr['ExamQuestion']['exam_id'], $examQuestionArr['Question']['subject_id']);
					} else {
						$tSubjectTime = $totalTestTime;
					}
					$options = $this->getOptionsStat($optionShuffle);
					$recordArr[] = array('ExamStat' => array("exam_result_id" => $lastId, "exam_id" => $examQuestionArr['ExamQuestion']['exam_id'], "student_id" => $studentId, "ques_no" => $ques_no,
						"question_id" => $examQuestionArr['ExamQuestion']['question_id'], 'marks' => $examQuestionArr['Question']['marks'], "correct_answer" => $correct_answer, 'options' => $options,
						'tsubject_id' => $examQuestionArr['Question']['subject_id'], 'is_section' => $isSection, 'tsubject_time' => $tSubjectTime, 'attempt_time' => $examStatStartTime, 'opened' => $examStatOpened));
				}
				$ExamStat->create();
				$ExamStat->saveAll($recordArr);
			} else {
				foreach ($userQuestion as $ques_no => $examQuestionArr) {
					$ques_no++;
					if ($ques_no == 1) {
						$mainSubjectId = $examQuestionArr['Question']['subject_id'];
						$examStatStartTime = $currentDateTime;
						$examStatOpened = "1";
					} else {
						$examStatStartTime = null;
						if ($examType == "A") {
							$examStatOpened = "0";
						} else {
							$examStatOpened = null;
						}
					}
					if ($examType=="A" || $mainSubjectId == $examQuestionArr['Question']['subject_id']) {
						$isSection = 1;
					} else {
						$isSection = '0';
					}
					if ($examQuestionArr['Qtype']['type'] == "M")
						$correct_answer = $examQuestionArr['Question']['answer'];
					elseif ($examQuestionArr['Qtype']['type'] == "T")
						$correct_answer = $examQuestionArr['Question']['true_false'];
					elseif ($examQuestionArr['Qtype']['type'] == "F")
						$correct_answer = $examQuestionArr['Question']['fill_blank'];
					else
						$correct_answer = null;
					if ($examArr['Exam']['exam_mode'] == "T") {
						$tSubjectTime = $this->getSubjectQuestionTime($id, $examQuestionArr['Question']['subject_id']);
					} else {
						$tSubjectTime = $totalTestTime;
					}
					$options = $this->getOptionsStat($optionShuffle);
					$recordArr[] = array('ExamStat' => array("exam_result_id" => $lastId, "exam_id" => $id, "student_id" => $studentId, "ques_no" => $ques_no,
						"question_id" => $examQuestionArr['Question']['question_id'], 'marks' => $examQuestionArr['Question']['marks'], "correct_answer" => $correct_answer, 'options' => $options,
						'tsubject_id' => $examQuestionArr['Question']['subject_id'], 'is_section' => $isSection, 'tsubject_time' => $tSubjectTime, 'attempt_time' => $examStatStartTime, 'opened' => $examStatOpened));
				}
				$ExamStat->create();
				$ExamStat->saveAll($recordArr);
			}
		}
	}

	public function userExamQuestion($exam_id, $studentId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$Question = ClassRegistry::init('Question');
		$Question->bindModel(array('hasMany' => array('QuestionsLang' => array('order' => 'QuestionsLang.language_id asc'))));
		$Question->bindModel(array('belongsTo' => array('Subject', 'Qtype', 'Passage')));
		$ExamStat->bindModel(array('belongsTo' => array('Question',
			'Exam' => array(
				'fields' => array(
					'Exam.id', 'Exam.name', 'Exam.finalized_time', 'Exam.amount', 'Exam.instant_result')
			)
		)));
		$userExamQuestion = $ExamStat->find('all', array('recursive' => 2,
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'closed' => 0, 'is_section' => '1'),
			'order' => array('ExamStat.ques_no asc')));
		return $userExamQuestion;
	}

	public function userExamQuestionSingle($exam_id, $studentId, $quesNo)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$Question = ClassRegistry::init('Question');
		$Question->bindModel(array('hasMany' => array('QuestionsLang' => array('order' => 'QuestionsLang.language_id asc'))));
		$Question->bindModel(array('belongsTo' => array('Subject', 'Qtype', 'Passage')));
		$ExamStat->bindModel(array('belongsTo' => array('Question', 'Exam')));
		$userExamQuestion = $ExamStat->find('first', array('recursive' => 2,
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'ExamStat.ques_no' => $quesNo, 'closed' => 0),
			'order' => array('ExamStat.ques_no asc')));
		return $userExamQuestion;
	}

	public function userSectionQuestion($exam_id, $type, $studentId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$subjectName = $ExamStat->find('all', array('fields' => array('DISTINCT(Subject.subject_name)', 'Subject.id','Subject.ordering'),
			'joins' => array(array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner', 'conditions' => array('ExamStat.tsubject_id=Subject.id'))),
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'closed' => 0, 'is_section' => '1'),
			'order' => array('Subject.ordering asc')));
		foreach ($subjectName as $value) {
			$userSectionQuestion[$value['Subject']['subject_name']] = $ExamStat->find('all', array('fields' => array('ExamStat.ques_no', 'ExamStat.opened', 'ExamStat.answered', 'ExamStat.review'),
				'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamStat.question_id=Question.id'))),
				'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'Question.subject_id' => $value['Subject']['id'], 'closed' => 0, 'is_section' => '1'),
				'order' => array('ExamStat.ques_no asc')));
		}
		return $userSectionQuestion;
	}

	public function userSubject($exam_id, $quesNo, $studentId)
	{
		if ($quesNo == 0)
			$quesNo = 1;
		$ExamStat = ClassRegistry::init('ExamStat');
		$subjectName = $ExamStat->find('first', array('fields' => array('Subject.subject_name'),
			'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner',
				'conditions' => array('ExamStat.question_id=Question.id')),
				array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner',
					'conditions' => array('Question.subject_id=Subject.id'))),
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'ques_no' => $quesNo, 'closed' => 0)
		));
		return $subjectName['Subject']['subject_name'];
	}

	public function userQuestionRead($exam_id, $quesNo, $studentId, $currentDateTime)
	{
		if ($quesNo == 0)
			$quesNo = 1;
		$usrQues = array('opened' => 1, 'attempt_time' => "'$currentDateTime'");
		$ExamStat = ClassRegistry::init('ExamStat');
		$ExamStat->updateAll($usrQues, array('exam_id' => $exam_id, 'student_id' => $studentId, 'ques_no' => $quesNo, 'closed' => 0));
	}

	public function userQuestionUpdate($exam_id, $quesNo, $studentId, $currentDateTime)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		if ($quesNo == 0)
			$quesNo = 1;
		$currQuesArr = $ExamStat->find('first', array('fields' => array('id', 'time_taken', 'attempt_time', 'exam_result_id', 'tsubject_id', 'tsubject_time'), 'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'ques_no' => $quesNo, 'closed' => 0, 'opened' => 1)));
		if ($currQuesArr) {
			$timeTaken = $currQuesArr['ExamStat']['time_taken'] + (strtotime($currentDateTime) - strtotime($currQuesArr['ExamStat']['attempt_time']));
			$subjectMaxTime = ($currQuesArr['ExamStat']['tsubject_time'] * 60);
			$examArr = $this->findById($exam_id);
			if ($examArr['Exam']['exam_mode'] == "T") {
				$ExamStat->virtualFields = array('total_subject_time' => 'SUM(time_taken)');
				$subjectTimeArr = $ExamStat->find('first', array('fields' => array('total_subject_time'), 'conditions' => array('exam_result_id' => $currQuesArr['ExamStat']['exam_result_id'], 'tsubject_id' => $currQuesArr['ExamStat']['tsubject_id'], 'id <>' => $currQuesArr['ExamStat']['id'])));
				if (strlen($subjectTimeArr['ExamStat']['total_subject_time']) > 0) {
					$totalSubjectTime = $subjectTimeArr['ExamStat']['total_subject_time'];
				} else {
					$totalSubjectTime = 0;
				}
				$subjectLapsedTime = $totalSubjectTime + $timeTaken;
				$ExamStat->virtualFields = array();
				if ($subjectLapsedTime > $subjectMaxTime) {
					$timeTaken = $subjectMaxTime - $totalSubjectTime;
				}
			} else {
				$ExamStat->virtualFields = array('total_subject_time' => 'SUM(time_taken)');
				$subjectTimeArr = $ExamStat->find('first', array('fields' => array('total_subject_time'), 'conditions' => array('exam_result_id' => $currQuesArr['ExamStat']['exam_result_id'], 'id <>' => $currQuesArr['ExamStat']['id'])));
				if (strlen($subjectTimeArr['ExamStat']['total_subject_time']) > 0) {
					$totalSubjectTime = $subjectTimeArr['ExamStat']['total_subject_time'];
				} else {
					$totalSubjectTime = 0;
				}
				$subjectLapsedTime = $totalSubjectTime + $timeTaken;
				$ExamStat->virtualFields = array();
				if ($subjectLapsedTime > $subjectMaxTime) {
					$timeTaken = $subjectMaxTime - $totalSubjectTime;
				}
			}
			$ExamStat->save(array('id' => $currQuesArr['ExamStat']['id'], 'time_taken' => $timeTaken));
		}
	}

	public function userSaveAnswer($exam_id, $quesNo, $studentId, $currentDateTime, $valueArr)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$subjectArr = $ExamStat->find('first', array('joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'LEFT', 'conditions' => array('Question.id=ExamStat.question_id')),
			array('table' => 'exams', 'alias' => 'Exam', 'type' => 'LEFT', 'conditions' => array('Exam.id=ExamStat.exam_id'))),
			'conditions' => array('ExamStat.ques_no' => $quesNo, 'ExamStat.exam_id' => $exam_id),
			'fields' => array('Question.subject_id')));
		$subjectId = $subjectArr['Question']['subject_id'];
		$ExamMaxquestion = ClassRegistry::init('ExamMaxquestion');
		$maxQuestionArr = $ExamMaxquestion->find('first', array('conditions' => array('ExamMaxquestion.exam_id' => $exam_id, 'ExamMaxquestion.subject_id' => $subjectId)));
		if ($maxQuestionArr)
			$maxQuestion = $maxQuestionArr['ExamMaxquestion']['max_question'];
		else
			$maxQuestion = 0;
		$maxAnswer = $ExamStat->find('count', array('joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'LEFT', 'conditions' => array('Question.id=ExamStat.question_id'))),
			'conditions' => array('ExamStat.exam_id' => $exam_id, 'ExamStat.student_id' => $studentId, 'ExamStat.closed' => 0, 'ExamStat.answered' => 1, 'Question.subject_id' => $subjectId),
		));
		if ($maxAnswer >= $maxQuestion && $maxQuestion != 0) {
			return false;
		} else {
			$userExamQuestion = $this->userExamQuestionSingle($exam_id, $studentId, $quesNo);
			$id = $userExamQuestion['ExamStat']['id'];
			$usrQues = array("ExamStat" => array('id' => $id));
			$marksObtained = 0;
			$isAnswer = false;
			$isAnswered = false;
			if (isset($valueArr['Exam']['option_selected'])) {
				if (is_array($valueArr['Exam']['option_selected'])) {
					$mainAnswerArr = explode(",", $userExamQuestion['Question']['answer']);
					$tempArr = $valueArr['Exam']['option_selected'];
					foreach ($tempArr as $tempValue) {
						if (in_array($tempValue, $mainAnswerArr))
							$isAnswer = true;
						else {
							$isAnswer = false;
							break;
						}
					}
					unset($mainAnswerArr, $tempArr, $tempValue);
					$usrQues['ExamStat']['option_selected'] = implode(",", $valueArr['Exam']['option_selected']);
				} else {
					$usrQues['ExamStat']['option_selected'] = $valueArr['Exam']['option_selected'];
					if ($usrQues['ExamStat']['option_selected'] == $userExamQuestion['Question']['answer'])
						$isAnswer = true;
				}
				if ($valueArr['Exam']['option_selected'] != NULL)
					$isAnswered = true;
			}
			if (isset($valueArr['Exam']['true_false'])) {
				$usrQues['ExamStat']['true_false'] = $valueArr['Exam']['true_false'];
				if (strtolower($usrQues['ExamStat']['true_false']) == strtolower($userExamQuestion['Question']['true_false']))
					$isAnswer = true;
				if ($valueArr['Exam']['true_false'] != NULL)
					$isAnswered = true;
			}
			if (isset($valueArr['Exam']['fill_blank'])) {
				$usrQues['ExamStat']['fill_blank'] = $valueArr['Exam']['fill_blank'];
				if(isset($valueArr['Exam']['lang'])) {
					if ($valueArr['Exam']['lang'] == 1) {
						$correctFillBlank = $userExamQuestion['Question']['fill_blank'];
					} else {
						$correctFillBlank = $userExamQuestion['Question']['QuestionsLang'][$valueArr['Exam']['lang'] - 2]['fill_blank'];
					}
				}else{
					$correctFillBlank = $userExamQuestion['Question']['fill_blank'];
				}
				if (str_replace(" ", "", strtolower($usrQues['ExamStat']['fill_blank'])) == str_replace(" ", "", strtolower($correctFillBlank)))
					$isAnswer = true;
				if ($valueArr['Exam']['fill_blank'] != NULL)
					$isAnswered = true;
			}
			if (isset($valueArr['Exam']['answer'])) {
				if ($valueArr['Exam']['answer'] != NULL)
					$isAnswered = true;
			}
			$usrQues['ExamStat']['ques_status'] = null;
			$marksObtained = null;
			if ($isAnswered == true) {
				$usrQues['ExamStat']['answered'] = '1';
				if ($isAnswer == true) {
					$marksObtained = $userExamQuestion['ExamStat']['marks'];
					$usrQues['ExamStat']['ques_status'] = 'R';
				} else {
					if ($userExamQuestion['Exam']['negative_marking'] == "Yes" && !isset($valueArr['Exam']['answer'])) {
						if ($userExamQuestion['Question']['negative_marks'] == 0)
							$marksObtained = 0;
						else
							$marksObtained = -($userExamQuestion['Question']['negative_marks']);

					}
					if (!isset($valueArr['Exam']['answer']))
						$usrQues['ExamStat']['ques_status'] = 'W';
				}
			}
			$usrQues['ExamStat']['marks_obtained'] = $marksObtained;
			if (isset($valueArr['Exam']['answer'])) {
				$usrQues['ExamStat']['answer'] = $valueArr['Exam']['answer'];
			}
			if (isset($valueArr['Exam']['attempt_time']) && $valueArr['Exam']['attempt_time']!="0") {
				$usrQues['ExamStat']['attempt_time'] = $valueArr['Exam']['attempt_time'];
			}
			if (isset($valueArr['Exam']['time_taken'])) {
				$usrQues['ExamStat']['time_taken'] = $valueArr['Exam']['time_taken'];
			}
			if ($isAnswered == true) {
				$usrQues['ExamStat']['review'] = 0;
			}
			$ExamStat->save($usrQues);
			return true;
		}
	}

	public function userResetAnswer($exam_id, $quesNo, $studentId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$currRecord = $ExamStat->find('first', array('fields' => 'id',
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'ques_no' => $quesNo, 'closed' => 0)));

		$id = $currRecord['ExamStat']['id'];
		$usrQues = array("ExamStat" => array('id' => $id, 'attempt_time' => null, 'answered' => 0, 'review' => 0, 'option_selected' => null, 'answer' => null, 'true_false' => null,
			'fill_blank' => null, 'marks_obtained' => null, 'ques_status' => null, 'review' => 0));
		$ExamStat->save($usrQues);
	}

	public function userReviewAnswer($exam_id, $quesNo, $studentId, $review)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$currRecord = $ExamStat->find('first', array('fields' => 'id',
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'ques_no' => $quesNo, 'closed' => 0)));

		$id = $currRecord['ExamStat']['id'];
		$usrQues = array("ExamStat" => array('id' => $id, 'review' => $review));
		$ExamStat->save($usrQues);
	}

	public function userExamFinish($exam_id, $studentId, $currentDateTime)
	{
		$ExamResult = ClassRegistry::init('ExamResult');
		$ExamStat = ClassRegistry::init('ExamStat');
		$Exam = ClassRegistry::init('Exam');
		$ExamResultRecord = $ExamResult->find('first', array('fields' => array('id'), 'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'end_time' => null)));
		$id = $ExamResultRecord['ExamResult']['id'];
		$total_answered = $ExamStat->find('count', array('conditions' => array('exam_result_id' => $id, 'answered' => 1)));
		$ExamStat->virtualFields = array('test_time' => 'SUM(time_taken)');
		$testTimeArr = $ExamStat->find('first', array(
				'fields' => array(
					'test_time'
				),
				'conditions' => array('exam_result_id' => $id)
			)
		);
		$ExamStat->virtualFields = array();
		$testTime = $testTimeArr['ExamStat']['test_time'];
		$userResult = array('id' => $id, 'end_time' => $currentDateTime, 'total_answered' => $total_answered, 'test_time' => $testTime);
		$ExamResult->save($userResult);
		$ExamStat->updateAll(array('ExamStat.closed' => 1),
			array('ExamStat.exam_result_id' => $id));

		$ExamRecord = $Exam->find('first', array('fields' => array('finish_result'), 'conditions' => array('id' => $exam_id)));
		$finish_result = $ExamRecord['Exam']['finish_result'];
		if ($finish_result == 1) {
			$examResultId = $id;
			$UserAdmin = ClassRegistry::init('User');
			$UserAdminRecord = $UserAdmin->find('first', array('fields' => array('id')));
			$adminId = $UserAdminRecord['User']['id'];
			$post = $ExamResult->find('first', array('joins' => array(array('table' => 'exams', 'alias' => 'Exam', 'type' => 'left',
				'conditions' => array('ExamResult.exam_id=Exam.id'))),
				'conditions' => array('ExamResult.id' => $examResultId),
				'fields' => array('ExamResult.total_marks', 'Exam.passing_percent')));
			$obtainedMarks = $this->obtainedMarks($examResultId);
			if($post['ExamResult']['total_marks']!=NULL) {
				$percent = CakeNumber::precision(($obtainedMarks * 100) / $post['ExamResult']['total_marks'], 2);
			}else{
				$percent=0;
			}
			if ($percent >= $post['Exam']['passing_percent'])
				$result = "Pass";
			else
				$result = "Fail";
			$examResultArr = array('id' => $examResultId, 'user_id' => $adminId, 'finalized_time' => $currentDateTime, 'obtained_marks' => $obtainedMarks, 'percent' => $percent, 'result' => $result);
			$ExamResult->save($examResultArr);
		}
	}

	public function obtainedMarks($id = null)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$ExamStat->virtualFields = array('total_marks' => 'SUM(marks_obtained)');
		$ExamStatArr = $ExamStat->find('first', array('fields' => array('total_marks'),
			'conditions' => array('exam_result_id' => $id)));
		$obtainedMarks = $ExamStatArr['ExamStat']['total_marks'];
		if ($obtainedMarks == null)
			$obtainedMarks = 0;
		return $obtainedMarks;
	}

	public function getSectionSubject($id)
	{
		$ExamQuestion = ClassRegistry::init('ExamQuestion');
		$subjectDetail = $ExamQuestion->find('all', array(
			'fields' => array(
				'Subject.id', 'Subject.subject_name', 'ExamMaxquestion.max_question', 'Subject.section_time', 'ExamsSubject.duration'),
			'joins' => array(
				array('table' => 'questions', 'type' => 'Inner', 'alias' => 'Question', 'conditions' => array('Question.id=ExamQuestion.question_id')),
				array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject', 'conditions' => array('Subject.id=Question.subject_id')),
				array('table' => 'exam_maxquestions', 'type' => 'left', 'alias' => 'ExamMaxquestion', 'conditions' => array('ExamQuestion.exam_id=ExamMaxquestion.exam_id', 'Subject.id=ExamMaxquestion.subject_id')),
				array('table' => 'exams_subjects', 'type' => 'left', 'alias' => 'ExamsSubject', 'conditions' => array('ExamsSubject.subject_id=Subject.id', 'ExamQuestion.exam_id=ExamsSubject.exam_id')),
			),
			'conditions' => array('ExamQuestion.exam_id' => $id),
			'group' => array('Subject.id', 'Subject.subject_name', 'ExamMaxquestion.max_question', 'Subject.section_time', 'ExamsSubject.duration'),
			'order' => 'Subject.ordering asc'));
		return $subjectDetail;
	}

	public function getSectionPrepSubject($id)
	{
		$ExamPrep = ClassRegistry::init('ExamPrep');
		$subjectDetail = $ExamPrep->find('all', array(
			'fields' => array('Subject.id', 'Subject.subject_name', 'ExamPrep.subject_id', 'ExamPrep.ques_no', 'ExamPrep.type', 'ExamPrep.level', 'ExamMaxquestion.max_question', 'Subject.section_time', 'ExamsSubject.duration'),
			'joins' => array(array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject',
				'conditions' => array('Subject.id=ExamPrep.subject_id')),
				array('table' => 'exam_maxquestions', 'type' => 'Left', 'alias' => 'ExamMaxquestion', 'conditions' => array('ExamPrep.exam_id=ExamMaxquestion.exam_id', 'ExamPrep.subject_id=ExamMaxquestion.subject_id')),
				array('table' => 'exams_subjects', 'type' => 'left', 'alias' => 'ExamsSubject', 'conditions' => array('ExamsSubject.subject_id=Subject.id', 'ExamPrep.exam_id=ExamsSubject.exam_id')),
			),
			'conditions' => array('ExamPrep.exam_id' => $id),
			'order' => 'Subject.ordering asc'));
		return $subjectDetail;
	}

	public function userSectionSubject($exam_id, $type, $studentId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$subjectName = $ExamStat->find('all', array('fields' => array('DISTINCT(Subject.subject_name)', 'Subject.id','Subject.ordering'),
			'joins' => array(array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner', 'conditions' => array('ExamStat.tsubject_id=Subject.id'))),
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'closed' => 0),
			'order' => array('Subject.ordering asc')));
		foreach ($subjectName as $value) {
			$userSectionQuestion[$value['Subject']['subject_name']] = $ExamStat->find('all', array('fields' => array('ExamStat.ques_no', 'ExamStat.opened', 'ExamStat.answered', 'ExamStat.review', 'ExamStat.is_section', 'ExamStat.tsubject_id'),
				'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamStat.question_id=Question.id'))),
				'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'Question.subject_id' => $value['Subject']['id'], 'closed' => 0),
				'order' => array('ExamStat.ques_no asc')));
		}
		return $userSectionQuestion;
	}

	public function getSectionTime($examId, $subjectId, $examResultId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');

		$post = $ExamStat->find('first', array(
				'conditions' => array(
					'ExamStat.exam_result_id' => $examResultId,
					'ExamStat.exam_id' => $examId,
					'ExamStat.tsubject_id' => $subjectId
				)
			)
		);
		$sectionDuration = $post['ExamStat']['tsubject_time'];
		return $sectionDuration;
	}

	public function updateExamSection($examResultId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$ExamResult = ClassRegistry::init('ExamResult');
		$subjectCount = $this->getRemainingSubject($examResultId);
		$ExamStat->updateAll(array('is_section' => '2'), array('ExamStat.exam_result_id' => $examResultId, 'ExamStat.is_section' => '1'));
		if ($subjectCount == 0) {
			return false;
		} else {
			$ExamResult->save(array('id' => $examResultId, 'start_time' => null));
			$subjectArr = $ExamStat->find('first', array(
					'conditions' => array('ExamStat.exam_result_id' => $examResultId, 'ExamStat.is_section' => '0'),
				)
			);
			$subjectId = $subjectArr['ExamStat']['tsubject_id'];
			$ExamStat->updateAll(array('is_section' => '1'), array('ExamStat.exam_result_id' => $examResultId, 'ExamStat.tsubject_id' => $subjectId));
			return true;
		}
	}

	public function getRemainingSubject($examResultId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$subjectCount = $ExamStat->find('count', array(
				'conditions' => array('ExamStat.exam_result_id' => $examResultId, 'ExamStat.is_section' => '0'),
				'group' => array('ExamStat.tsubject_id')
			)
		);
		return $subjectCount;
	}

	public function getSubjectQuestionTime($examId, $subjectId)
	{
		$ExamsSubject = ClassRegistry::init('ExamsSubject');
		$value = $ExamsSubject->findByExamIdAndSubjectId($examId, $subjectId);
		if (isset($value['ExamsSubject']['duration']) && $value['ExamsSubject']['duration'] != "") {
			$duration = $value['ExamsSubject']['duration'];
		} else {
			$Subject = ClassRegistry::init('Subject');
			$subjectArr = $Subject->findById($subjectId);
			$duration = $subjectArr['Subject']['section_time'];
		}
		return $duration;
	}

	public function userDefaultSectionQuestion($exam_id, $type, $studentId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$subjectName = $ExamStat->find('all', array('fields' => array('DISTINCT(Subject.subject_name)', 'Subject.id','Subject.ordering'),
			'joins' => array(array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'Inner', 'conditions' => array('ExamStat.tsubject_id=Subject.id'))),
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'closed' => 0),
			'order' => array('Subject.ordering asc')));
		foreach ($subjectName as $value) {
			$userSectionQuestion[$value['Subject']['subject_name']] = $ExamStat->find('all', array('fields' => array('ExamStat.ques_no', 'ExamStat.opened', 'ExamStat.answered', 'ExamStat.review'),
				'joins' => array(array('table' => 'questions', 'alias' => 'Question', 'type' => 'Inner', 'conditions' => array('ExamStat.question_id=Question.id'))),
				'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'Question.subject_id' => $value['Subject']['id'], 'closed' => 0),
				'order' => array('ExamStat.ques_no asc')));
		}
		return $userSectionQuestion;
	}

	public function userDefaultExamQuestion($exam_id, $studentId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$Question = ClassRegistry::init('Question');
		$Question->bindModel(array('hasMany' => array('QuestionsLang' => array('order' => 'QuestionsLang.language_id asc'))));
		$Question->bindModel(array('belongsTo' => array('Subject', 'Qtype', 'Passage')));
		$ExamStat->bindModel(array('belongsTo' => array('Question', 'Exam')));
		$userExamQuestion = $ExamStat->find('all', array('recursive' => 2,
			'conditions' => array('exam_id' => $exam_id, 'student_id' => $studentId, 'closed' => 0),
			'order' => array('ExamStat.ques_no asc')));
		return $userExamQuestion;
	}

	public function getSectionRemainingTime($examResultId)
	{
		$ExamStat = ClassRegistry::init('ExamStat');
		$ExamStat->virtualFields = array('total_subject_time' => 'SUM(DISTINCT(tsubject_time))');
		$examStatArr = $ExamStat->find('all', array('fields' => array('total_subject_time'), 'conditions' => array('exam_result_id' => $examResultId, 'is_section' => '0'),'group'=>array('tsubject_id'), 'recursive' => -1));
		$ExamStat->virtualFields = array();
		if ($examStatArr) {
			$totalTime=0;
			foreach ($examStatArr as $item) {
				$totalTime=$totalTime+$item['ExamStat']['total_subject_time'];
			}
			return $totalTime;
		} else {
			return '0.00';
		}
	}
}

?>
