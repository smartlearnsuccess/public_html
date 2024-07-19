<?php

class ResultsController extends AppController
{
	public $helpers = array('Paginator', 'Html', 'Paginator', 'Js' => array('Jquery'));
	public $components = array('search-master.Prg', 'Paginator', 'HighCharts.HighCharts', 'RequestHandler' => array('viewClassMap' => array('pdf' => 'CakePdf.Pdf')));
	public $currentDateTime, $studentId;
	var $paginate = array('page' => 1, 'order' => array('Result.id' => 'desc'));

	public function beforeFilter()
	{
		parent::beforeFilter();
		if (!$this->adminValue) {
			if($this->userValue){
				$this->studentId = $this->userValue['Student']['id'];
			}
		}
		if($this->RequestHandler->isMobile()){
			$this->set('isMobile',true);
		}else{
			$this->set('isMobile',false);
		}
	}

	public function crm_index()
	{
		if (!$this->adminValue) {
			$this->authenticate();
			$this->studentId = $this->userValue['Student']['id'];
		}
		$this->Prg->commonProcess();
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['conditions'] = array('Result.student_id' => $this->studentId, 'Result.user_id >' => 0);
		$this->Paginator->settings['limit'] = $this->pageLimit;
		$this->Paginator->settings['maxLimit'] = $this->maxLimit;
		$this->set('Result', $this->Paginator->paginate());
		if ($this->request->is('ajax')) {
			$this->render('crm_index', 'ajax'); // View, Layout
		}
	}

	public function rest_index()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$response = array();
			$responseArr = $this->Result->find('all', array(
				'conditions' => array('Result.student_id' => $this->studentId, 'Result.user_id >' => 0),
				'order'=>array('Result.id'=>'desc')
			));
			foreach ($responseArr as $item) {
				$response[] = array(
					'result_id' => $item['Result']['id'],
					'exam_id' => $item['Result']['exam_id'],
					'exam_name' => $item['Exam']['name'],
					'result_details'=>$item['Exam']['declare_result'],
					'start_time' => CakeTime::format($this->dtmFormat, $item['Result']['start_time']),
					'result' => $item['Result']['result'],
					'score' => $item['Result']['obtained_marks'] . '/' . $item['Result']['total_marks'],
					'percentage' => $item['Result']['percent'],
					'declare_result' => $item['Exam']['declare_result'],
					'certificate' => $this->sysSetting['Configuration']['certificate'],
				);
			}
			$status = true;
			$message = __('Result fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = array();
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	public function crm_view($id = null, $type = "crm", $publicKey = null, $privateKey = null, $isExamClose = null)
	{
		if ($isExamClose == "examclose") {
			$this->layout = 'result';
		}
		$this->set('isExamClose', $isExamClose);
		if ($this->adminValue) {
			$studArr = $this->Result->findById($id);
			$this->studentId = $studArr['Result']['student_id'];
		} else {
			if ($type == "crm") {
				$this->authenticate();
			} else {
				if ($this->authenticateRest($this->request->query)) {
					$this->studentId = $this->restStudentId($this->request->query);
				} else {
					echo __('Invalid Post');
					die();
				}
			}
		}
		$this->set('type', $type);
		$declareResult = $this->Result->find('count', array('conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Exam.declare_result' => 'Yes')));
		if ($declareResult == 0) {
			$this->Session->setFlash(__('You can not see the result of this exam'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}
		$this->loadModel('ExamStat');
		$this->loadModel('ExamQuestion');
		$studentCount = $this->Result->find('count', array('conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId)));
		if ($id == null || $studentCount == 0) {
			$this->redirect(array('action' => 'index'));
		}
		$examDetails = $this->Result->find('first', array(
			'fields' => array('Exam.id', 'Exam.multi_language', 'Exam.name', 'Exam.type','Exam.math_editor_type', 'User.name', 'Result.id','Result.exam_id', 'Result.percent', 'Result.obtained_marks', 'Result.total_marks', 'Result.total_question', 'Result.total_attempt', 'Exam.passing_percent', 'Exam.duration', 'Result.result', 'Result.start_time', 'Result.end_time', 'Exam.declare_result', 'Result.total_answered','Result.test_time'),
			'joins' => array(array('table' => 'users', 'alias' => 'User', 'type' => 'inner', 'conditions' => array('Result.user_id=User.id'))),
			'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));
		$userSubject = $this->Result->userSubject($id);
		$userMarksheet = $this->Result->userMarksheet($id);
		$scoreCardArr = $this->score_card_details($id, $examDetails);
		$totalStudentCount = $scoreCardArr['student_count'];
		$correctQuestion = $scoreCardArr['correct_questions'];
		$incorrectQuestion = $scoreCardArr['incorrect_questions'];
		$rightMarks = $scoreCardArr['right_marks'];
		$negativeMarks = $scoreCardArr['negative_marks'];
		$leftQuestion = $examDetails['Result']['total_question'] - $examDetails['Result']['total_answered'];
		$leftQuestionMarks = $scoreCardArr['left_questions_marks'];
		$rank = $scoreCardArr['my_rank'];
		$topRankArr = $this->topRank($examDetails['Result']['exam_id']);
		$userSectionQuestion = $this->Result->userSectionQuestion($examDetails['Result']['id'], $examDetails['Exam']['id'], $examDetails['Exam']['type'], $this->studentId);
		if ($rightMarks == "")
			$rightMarks = 0;
		if ($negativeMarks == "")
			$negativeMarks = 0;
		if ($leftQuestionMarks == "")
			$leftQuestionMarks = 0;
		$attemptedQuestion = $examDetails['Result']['total_answered'];
		$this->loadModel('Student');
		$tudentArr=$this->Student->findById($this->studentId);
		if (strlen($tudentArr['Student']['photo']) > 0)
			$studentImage = 'student_thumb/' . $tudentArr['Student']['photo'];
		else
			$studentImage = 'User.png';
		$mainRank = $rank;
		$myRank = $this->showRank($rank);
		$percent = $examDetails['Result']['percent'];
		$percentile = round((($totalStudentCount - $rank) / $totalStudentCount) * 100, 2);

		$this->set('examDetails', $examDetails);
		$this->set('userMarksheet', $userMarksheet);
		$this->set('id', $id);
		$this->set('totalStudentCount', $totalStudentCount);
		$this->set('correctQuestion', $correctQuestion);
		$this->set('incorrectQuestion', $incorrectQuestion);
		$this->set('rightMarks', $rightMarks);
		$this->set('negativeMarks', $negativeMarks);
		$this->set('leftQuestion', $leftQuestion);
		$this->set('leftQuestionMarks', $leftQuestionMarks);
		$this->set('myRank', $myRank);
		$this->set('percentile', $percentile);
		$this->set('userSectionQuestion', $userSectionQuestion);
		$this->set('attemptedQuestion', $attemptedQuestion);
		$this->set('studentImage', $studentImage);

		$post = $this->Result->userExamQuestion($id, $this->studentId);

		$this->set('post', $post);
		$this->loadModel('Language');
		if ($examDetails['Exam']['multi_language'] == 1) {
			$langMainArr = $this->Language->find('list');
			$langArr=array();
			$i=0;
			foreach ($langMainArr as $item){
				$i++;
				$langArr[$i]=$item;
			}
			$this->set('langArr', $langArr);
			$this->set('languageCount', count($langArr));
			$this->set('languageArr', $this->Language->find('all'));
		} else {
			$langArr = $this->Language->find('list', array('conditions' => array('Language.id' => 1)));
			$this->set('langArr', $langArr);
			$this->set('languageCount', 1);
			$this->set('languageArr', $this->Language->find('list', array('conditions' => array('Language.id' => 1))));
			$langArr1 = $this->Language->find('list');
			$this->set('fullLanguageCount', count($langArr1));
		}
		foreach ($userSubject as $subjectValue) {
			$xAxisCategories[] = $subjectValue['Subject']['subject_name'];
		}
		foreach ($userMarksheet as $k => $userMarkValue) {
			if (strlen($k) != 5) {
				$chartData[] = (float)$userMarkValue['Subject']['total_marks'];
				$chartData1[] = (float)$userMarkValue['Subject']['obtained_marks'];
				$timeTaken = $this->CustomFunction->secondsToWords($userMarkValue['Subject']['time_taken'], '-');
				$chartRerData[] = array('name' => $userMarkValue['Subject']['name'], 'y' => ($userMarkValue['Subject']['time_taken'] / 60), 'mylabel' => $timeTaken);
			}
		}

		$chartName = "My Chartdl";
		$mychart = $this->HighCharts->create($chartName, 'column');
		$this->HighCharts->setChartParams(
			$chartName,
			array(
				'renderTo' => "mywrapperdl",  // div to display chart inside
				'xAxisCategories' => $xAxisCategories,
				'yAxisTitleText' => __('Score'),
				'plotOptionsColumnDataLabelsEnabled' => TRUE,
				'legendEnabled' => FALSE,
				'enableAutoStep' => FALSE,
				'creditsEnabled' => FALSE,
			)
		);
		$series = $this->HighCharts->addChartSeries();
		$series1 = $this->HighCharts->addChartSeries();
		$series->addName(__('Max Marks'))->addData($chartData);
		$series1->addName(__('Marks Scored'))->addData($chartData1);
		$mychart->addSeries($series);
		$mychart->addSeries($series1);

		$chartName = "Pie Chartqc";
		$pieChart = $this->HighCharts->create($chartName, 'pie');
		$this->HighCharts->setChartParams(
			$chartName,
			array(
				'renderTo' => "piewrapperqc",  // div to display chart inside
				'title' => __('Subject Wise Time Taken'),
				'titleAlign' => 'center',
				'creditsEnabled' => FALSE,
				'plotOptionsShowInLegend' => TRUE,
				'plotOptionsPieShowInLegend' => TRUE,
				'plotOptionsPieDataLabelsEnabled' => TRUE,
				'plotOptionsPieDataLabelsFormat' => '{point.name}:<b>{point.mylabel}</b>',
				'tooltipEnabled' => TRUE,
				'tooltipPointFormat' => '{point.mylabel}</b>'
			)
		);

		$series = $this->HighCharts->addChartSeries();
		$series->addName(__('Subject Wise Time Taken'))->addData($chartRerData);
		$pieChart->addSeries($series);

		$chartDataTotal[] = (float)$examDetails['Result']['total_marks'];
		$chartDataTotal1[] = (float)$examDetails['Result']['obtained_marks'];
		$chartName = "My Chartd2";
		$mychart = $this->HighCharts->create($chartName, 'column');
		$this->HighCharts->setChartParams(
			$chartName,
			array(
				'renderTo' => "mywrapperd2",  // div to display chart inside
				'xAxisCategories' => array(__('Student Performance')),
				'yAxisTitleText' => __('Score'),
				'plotOptionsColumnDataLabelsEnabled' => TRUE,
				'legendEnabled' => FALSE,
				'enableAutoStep' => FALSE,
				'creditsEnabled' => FALSE,
			)
		);
		$series = $this->HighCharts->addChartSeries();
		$series1 = $this->HighCharts->addChartSeries();
		$series->addName(__('Max Marks'))->addData($chartDataTotal);
		$series1->addName(__('Marks Scored'))->addData($chartDataTotal1);
		$mychart->addSeries($series);
		$mychart->addSeries($series1);

		$chartQuestionRerData = array(array(__('Correct Question'), $correctQuestion),
			array(__('Incorrect Question'), $incorrectQuestion),
			array(__('Right Marks'), (int)$rightMarks),
			array(__('Negative Marks'), (int)str_replace("-", "", $negativeMarks)));
		$chartName = "My Chartd3";
		$pieChart = $this->HighCharts->create($chartName, 'pie');
		$this->HighCharts->setChartParams(
			$chartName,
			array(
				'renderTo' => "mywrapperd3",  // div to display chart inside
				'titleAlign' => 'center',
				'creditsEnabled' => FALSE,
				'plotOptionsShowInLegend' => TRUE,
				'plotOptionsPieShowInLegend' => TRUE,
				'plotOptionsPieDataLabelsEnabled' => TRUE,
				'plotOptionsPieDataLabelsFormat' => '{point.name}:<b>{point.y}</b>',
				'tooltipEnabled' => TRUE,
				'tooltipPointFormat' => '<b>{point.y}</b>'
			)
		);

		$series = $this->HighCharts->addChartSeries();
		$series->addName(__('Subject Wise Time Taken'))->addData($chartQuestionRerData);
		$pieChart->addSeries($series);
		############## Compare Report #############
		$xAxisCategories = array();
		$compareArr = array();
		$isYou = false;
		$myRank = 0;
		$final_result_percent = NULL;
		foreach ($topRankArr as $k => $postArr) {
			$studentId = $postArr['exam_results']['student_id'];
			if ($final_result_percent == $postArr['exam_results']['percent']) {
				$myRank;
			} else {
				$myRank++;
			}
			$final_result_percent = $postArr['exam_results']['percent'];
			$resultId = $postArr['exam_results']['id'];
			$examDetails = $this->Result->find('first', array('fields' => array('Student.name', 'Student.photo', 'Result.exam_id', 'Result.percent', 'Result.obtained_marks', 'Result.total_marks', 'Result.total_question', 'Result.total_attempt', 'Result.result', 'Result.start_time', 'Result.end_time', 'Result.total_answered','Result.test_time'),
				'joins' => array(array('table' => 'students', 'alias' => 'Student', 'type' => 'inner', 'conditions' => array('Result.student_id=Student.id'))),
				'conditions' => array('Result.id' => $resultId, 'Result.student_id' => $studentId, 'Result.user_id >' => 0)));
			$correctQuestion = $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $resultId, 'answered' => 1, 'ques_status' => 'R')));
			$incorrectQuestion = $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $resultId, 'answered' => 1, 'ques_status' => 'W')));
			$leftQuestion = $examDetails['Result']['total_question'] - $examDetails['Result']['total_answered'];
			$attemptedQuestion = $examDetails['Result']['total_answered'];
			if (strlen($examDetails['Student']['photo']) > 0)
				$studentImage = 'student_thumb/' . $examDetails['Student']['photo'];
			else
				$studentImage = 'User.png';
			$rank = $this->showRank($myRank);
			$totalStudentCount = $this->Result->find('count', array('conditions' => array('Result.exam_id' => $examDetails['Result']['exam_id'], 'Result.user_id >' => 0)));
			$percentile = round((($totalStudentCount - $myRank) / $totalStudentCount) * 100, 2);
			if ($id != $resultId)
				$compareArr[] = array($examDetails, 'correct_question' => $correctQuestion, 'incorrect_question' => $incorrectQuestion, 'left_question' => $leftQuestion, 'attempted_question' => $attemptedQuestion, 'student_image' => $studentImage, 'rank' => $rank, 'percentile' => $percentile);
			$topperData[] = (float)$examDetails['Result']['percent'];
			if ($id == $resultId) {
				$isYou = true;
				$xAxisCategories[] = array(__d('default', "You %s", $myRank));
			} else
				$xAxisCategories[] = array(__d('default', "Topper %s", $myRank));
			if ($k == 9)
				break;
		}
		if ($isYou == false) {
			$xAxisCategories[] = array(__d('default', "You %s", $mainRank));
			$topperData[] = (float)$percent;
		}
		$this->set('compareArr', $compareArr);
		$this->set('compareCount', count($compareArr) - 1);
		$chartName = "My Chartd5";
		$mychart = $this->HighCharts->create($chartName, 'column');
		$this->HighCharts->setChartParams(
			$chartName,
			array(
				'renderTo' => "mywrapperd5",  // div to display chart inside
				'xAxisCategories' => $xAxisCategories,
				'yAxisTitleText' => __('Percentage(%) in Exam'),
				'plotOptionsColumnDataLabelsEnabled' => TRUE,
				'legendEnabled' => FALSE,
				'enableAutoStep' => FALSE,
				'creditsEnabled' => FALSE,
				'yAxisMax' => 100,
			)
		);
		$series = $this->HighCharts->addChartSeries();
		$series->addName(__('Percentage(%)'))->addData($topperData);
		$mychart->addSeries($series);
		############## End Report     #############
	}

	public function crm_certificate($id = null)
	{
		$this->layout = 'pdf';
		if (isset($this->request->query['public_key']) && isset($this->request->query['private_key'])) {
			if (!$this->authenticateRest($this->request->query)) {
				echo('Invalid Token');
				die();
			} else {
				$this->studentId = $this->restStudentId($this->request->query);
				if (isset($this->request->query['id'])) {
					$id = $this->request->query['id'];
					$this->set('userValue', $this->userValue);
				} else {
					echo __('Invalid Post');
					die();
				}
			}
		} else {
			$this->authenticate();
		}
		$result = $this->Result->find('count', array('conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId)));
		if ($result == 0 || !$this->siteCertificate) {
			$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}
		$post = $this->Result->find('first', array('fields' => array('Exam.name', 'Result.total_marks', 'Result.obtained_marks', 'Result.percent', 'Result.start_time'),
			'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId)));
		$this->pdfConfig = array('filename' => 'Certificate-' . rand() . '.pdf');
		$this->set('post', $post);
	}

	public function crm_bookmark($examResultId,$studentId=null)
	{
		$this->autoRender = false;
		$this->request->onlyAllow('ajax');
		$this->loadModel('ExamStat');
		$quesNo = $_REQUEST['id'];
		if($studentId==null){
			$studentId=$this->studentId;
		}
		$examStatArr = $this->ExamStat->find('first', array('fields' => array('ExamStat.id', 'ExamStat.bookmark'), 'conditions' => array('ExamStat.exam_result_id' => $examResultId, 'ques_no' => $quesNo, 'student_id' => $studentId)));
		if ($examStatArr) {
			$examStatId = $examStatArr['ExamStat']['id'];
			$boomark = $examStatArr['ExamStat']['bookmark'];
			if ($boomark == 'Y')
				$boomarkSave = NULL;
			else
				$boomarkSave = 'Y';
			$this->ExamStat->save(array('id' => $examStatId, 'bookmark' => $boomarkSave));
			print$boomarkSave;
		}
	}

	public function crm_printresult($id = null)
	{
		try {
			$this->layout = 'pdf';
			if (isset($this->request->query['public_key']) && isset($this->request->query['private_key'])) {
				if (!$this->authenticateRest($this->request->query)) {
					echo('Invalid Token');
					die();
				} else {
					$this->studentId = $this->restStudentId($this->request->query);
					if (isset($this->request->query['id'])) {
						$id = $this->request->query['id'];
						$this->set('userValue', $this->userValue);
					} else {
						echo __('Invalid Post');
						die();
					}
				}
				$this->crm_view($id, 'rest', $this->request->query['public_key'], $this->request->query['private_key']);
			} elseif ($this->adminValue) {
				$this->crm_view($id);
			} else {
				$this->authenticate();
				$this->crm_view($id);
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function rest_scorecard()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$id = $this->request->query['id'];
			if($id) {
				$examDetails = $this->Result->find('first', array(
					'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));
				$scoreCardArr = $this->score_card_details($id, $examDetails);
				$scorecard = array(
					'exam_name' => $examDetails['Exam']['name'],
					'student_count' => $scoreCardArr['student_count'],
					'test_marks' => $examDetails['Result']['total_marks'],
					'total_questions' => $examDetails['Result']['total_question'],
					'test_time' => $this->CustomFunction->secondsToWords($examDetails['Result']['total_test_time'] * 60),
					'my_marks' => $examDetails['Result']['obtained_marks'],
					'percentage'=>$examDetails['Result']['percent'],
					'my_percentile' => $scoreCardArr['percentile'],
					'total_question_attempts' => $examDetails['Result']['total_answered'],
					'time_taken' => $this->CustomFunction->secondsToWords($examDetails['Result']['test_time']),
					'correct_questions' => $scoreCardArr['correct_questions'],
					'incorrect_questions' => $scoreCardArr['incorrect_questions'],
					'right_marks' => $scoreCardArr['right_marks'],
					'negative_marks' => $scoreCardArr['negative_marks'],
					'left_questions' => $scoreCardArr['left_questions'],
					'left_questions_marks' => $scoreCardArr['left_questions_marks'],
					'my_rank' => $this->showRank($scoreCardArr['my_rank']),
					'my_result' => $examDetails['Result']['result'],
				);
				$status = true;
				$message = __('Result fetch successfully');
			}else{
				$status = false;
				$message = ('Invalid Token');
				$scorecard = array();
			}
		} else {
			$status = false;
			$message = ('Invalid Token');
			$scorecard = array();
		}
		$this->set(compact('status', 'message', 'scorecard'));
		$this->set('_serialize', array('status', 'message', 'scorecard'));
	}

	public function rest_subject_report()
	{
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$id = $this->request->query['id'];
			$this->loadModel('ExamStat');
			$examDetails = $this->Result->find('first', array(
				'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));
			$userMarks = $this->Result->userMarksheet($id);
			$studArr = $this->Result->findById($id);
			if (!empty($studArr) && !empty($id)) {
				foreach ($userMarks as $key => $subjects_rep) {
					$name = $subjects_rep['Subject']['name'];
					$marks_scored = $subjects_rep['Subject']['marks_scored'];
					$negative_marks = $subjects_rep['Subject']['negative_marks'];
					$total_question = $subjects_rep['Subject']['total_question'];
					$correct_question = $subjects_rep['Subject']['correct_question'];
					$incorrect_question = $subjects_rep['Subject']['incorrect_question'];
					$unattempted_question = $subjects_rep['Subject']['unattempted_question'];
					$unattempted_question_marks = $subjects_rep['Subject']['unattempted_question_marks'];

					if ($subjects_rep['Subject']['name'] == 'Grand Total') {
						$subject_report['Grand_total'] = array('exam_name' => $examDetails['Exam']['name'], 'total_questions' => $total_question, 'correct_incorrect_count' => $correct_question . '/' . $incorrect_question, 'positive_negative_marks' => $marks_scored . '/' . $negative_marks, 'unattempt_question_count_marks' => $unattempted_question . '/' . $unattempted_question_marks);
					} else {
						$subject_report['Subject_stats'][] = array('subject_name' => $name, 'total_questions' => $total_question, 'correct_incorrect_count' => $correct_question . '/' . $incorrect_question, 'positive_negative_marks' => $marks_scored . '/' . $negative_marks, 'unattempt_question_count_marks' => $unattempted_question . '/' . $unattempted_question_marks);
					}
				}
			}
			$status = true;
			$message = __('Result fetch successfully');
		} else {
			$status = false;
			$message = ('Invalid Token');
			$response = array();
		}
		$this->set(compact('status', 'message', 'subject_report'));
		$this->set('_serialize', array('status', 'message', 'subject_report'));
	}

	public function crm_solution()
	{
		$this->layout = 'result';
		if (!$this->authenticateRest($this->request->query)) {
			echo __('Invalid Token');
			die;
		}
		$this->studentId = $this->restStudentId($this->request->query);
		$id = $this->request->query['id'];
		$examDetails = $this->Result->find('first', array(
			'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));
		if (!$examDetails ) {
			echo __('Invalid Post');
			die;
		}
		$post = $this->Result->userExamQuestion($id, $this->studentId);
		$this->set('post', $post);
		$this->loadModel('Language');
		if ($examDetails['Exam']['multi_language'] == 1) {
			$langMainArr = $this->Language->find('list');
			$langArr=array();
			$i=0;
			foreach ($langMainArr as $item){
				$i++;
				$langArr[$i]=$item;
			}
			$this->set('langArr', $langArr);
			$this->set('languageCount', count($langArr));
			$this->set('languageArr', $this->Language->find('all'));
		} else {
			$langArr = $this->Language->find('list', array('conditions' => array('Language.id' => 1)));
			$this->set('langArr', $langArr);
			$this->set('languageCount', 1);
			$this->set('languageArr', $this->Language->find('list', array('conditions' => array('Language.id' => 1))));
			$langArr1 = $this->Language->find('list');
			$this->set('fullLanguageCount', count($langArr1));
		}
		$userSectionQuestion = $this->Result->userSectionQuestion($examDetails['Result']['id'], $examDetails['Exam']['id'], $examDetails['Exam']['type'], $this->studentId);
		$this->set('userSectionQuestion',$userSectionQuestion);
		$this->set('studentId',$this->studentId);
		$this->set('examDetails',$examDetails);
		$this->set('id',$id);
	}

	public function crm_compare()
	{
		$this->layout = 'result';
		if (!$this->authenticateRest($this->request->query)) {
			echo __('Invalid Token');
			die;
		}
		$this->studentId = $this->restStudentId($this->request->query);
		$myStudentArr=$this->Student->findById($this->studentId);
		$id = $this->request->query['id'];
		$post = $this->Result->find('first', array(
			'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));
		if (!$post ) {
			echo __('Invalid Post');
			die;
		}
		############## Compare Report #############
		$topRankArr = $this->topRank($post['Result']['exam_id']);
		$xAxisCategories = array();
		$compareArr = array();
		$isYou = false;
		foreach ($topRankArr as $k => $postArr) {
			$studentId = $postArr['exam_results']['student_id'];
			$resultId = $postArr['exam_results']['id'];
			$examDetails = $this->Result->find('first', array('fields' => array('Student.name', 'Student.photo', 'Result.exam_id', 'Result.percent', 'Result.obtained_marks', 'Result.total_marks', 'Result.total_question', 'Result.total_attempt', 'Result.result', 'Result.start_time', 'Result.end_time', 'Result.total_answered','Result.test_time'),
				'joins' => array(array('table' => 'students', 'alias' => 'Student', 'type' => 'inner', 'conditions' => array('Result.student_id=Student.id'))),
				'conditions' => array('Result.id' => $resultId, 'Result.student_id' => $studentId, 'Result.user_id >' => 0)));
			$attemptedQuestion = $examDetails['Result']['total_answered'];
			$scoreCardArr = $this->score_card_details($id, $examDetails);
			$totalStudentCount = $scoreCardArr['student_count'];
			$correctQuestion = $scoreCardArr['correct_questions'];
			$incorrectQuestion = $scoreCardArr['incorrect_questions'];
			$leftQuestion = $scoreCardArr['left_questions'];
			$rank = $scoreCardArr['my_rank'];
			if (strlen($examDetails['Student']['photo']) > 0)
				$studentImage = 'student_thumb/' . $examDetails['Student']['photo'];
			else
				$studentImage = 'User.png';
			$myRank = $this->showRank($rank);
			$percentile = round((($totalStudentCount - $rank) / $totalStudentCount) * 100, 2);
			if ($id != $resultId)
				$compareArr[] = array($examDetails, 'correct_question' => $correctQuestion, 'incorrect_question' => $incorrectQuestion, 'left_question' => $leftQuestion, 'attempted_question' => $attemptedQuestion, 'student_image' => $studentImage, 'rank' => $myRank, 'percentile' => $percentile);
			$topperData[] = (float)$examDetails['Result']['percent'];
			if ($k == 9)
				break;
		}
		if (strlen($myStudentArr['Student']['photo']) > 0)
			$studentImage = 'student_thumb/' . $myStudentArr['Student']['photo'];
		else
			$studentImage = 'User.png';
		$this->set('compareArr', $compareArr);
		$this->set('compareCount', count($compareArr) - 1);
		$this->set('studentImage',$studentImage);
		$this->set('examName',$post['Exam']['name']);
		$this->set('examDetails',$post);
		$examDetails = $this->Result->find('first', array('fields' => array('Student.name', 'Student.photo', 'Result.exam_id', 'Result.percent', 'Result.obtained_marks', 'Result.total_marks', 'Result.total_question', 'Result.total_attempt', 'Result.result', 'Result.start_time', 'Result.end_time', 'Result.total_answered','Result.test_time'),
			'joins' => array(array('table' => 'students', 'alias' => 'Student', 'type' => 'inner', 'conditions' => array('Result.student_id=Student.id'))),
			'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));
		$scoreCardArr = $this->score_card_details($id, $examDetails);
		$this->set('attemptedQuestion',$examDetails['Result']['total_answered']);
		$this->set('totalStudentCount',$scoreCardArr['student_count']);
		$this->set('correctQuestion',$scoreCardArr['correct_questions']);
		$this->set('incorrectQuestion',$scoreCardArr['incorrect_questions']);
		$this->set('rightMarks',$scoreCardArr['right_marks']);
		$this->set('negativeMarks',$scoreCardArr['negative_marks']);
		$this->set('leftQuestion',$scoreCardArr['left_questions']);
		$this->set('leftQuestionMarks',$scoreCardArr['left_questions_marks']);
		$this->set('myRank',$this->showRank($scoreCardArr['my_rank']));
		$this->set('percentile',$scoreCardArr['percentile']);
		############## End Report     #############
	}

	public function crm_view_report($id = null)
	{
		$this->authenticate();
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('You can not see the result of this exam'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('controller' => 'Exams', 'action' => 'close'));
		}
		$Exam = ClassRegistry::init('Exam');
		$examArr = $Exam->find('first', array('fields' => array('Exam.id', 'Exam.finish_result'), 'conditions' => array('id' => $id, 'Exam.finish_result' => '1'), 'recursive' => -1));
		if (!$examArr) {
			$this->Session->setFlash(__('You can not see the result of this exam'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('controller' => 'Exams', 'action' => 'close'));
		}
		$resultArr = $this->Result->find('first', array(
			'conditions' => array(
				'Result.exam_id' => $examArr['Exam']['id'],
				'Result.student_id' => $this->studentId,
				'Result.end_time IS NOT NULL'),
			'fields' => array('Result.id'),
			'order' => array('Result.id' => 'desc'),
			'recursive' => -1
		));
		if (!$resultArr) {
			$this->Session->setFlash(__('You can not see the result of this exam'), 'flash', array('alert' => 'danger'));
			$this->redirect(array('controller' => 'Exams', 'action' => 'close'));
		}
		$this->redirect(array('controller' => 'Results', 'action' => 'view', $resultArr['Result']['id'], "crm", "null", "null", "examclose"));
	}

	public function rest_view_report()
	{
		$status = false;
		$message = ('Invalid Token');
		$response = '';
		if ($this->authenticateRest($this->request->query)) {
			$this->studentId = $this->restStudentId($this->request->query);
			$id = $this->request->query['id'];
			$Exam = ClassRegistry::init('Exam');
			$examArr = $Exam->find('first', array('fields' => array('Exam.id', 'Exam.finish_result'), 'conditions' => array('id' => $id, 'Exam.finish_result' => '1'), 'recursive' => -1));
			if ($examArr) {
				$resultArr = $this->Result->find('first', array(
					'conditions' => array(
						'Result.exam_id' => $examArr['Exam']['id'],
						'Result.student_id' => $this->studentId,
						'Result.end_time IS NOT NULL'),
					'fields' => array('Result.id'),
					'order' => array('Result.id' => 'desc'),
					'recursive' => -1
				));
				if ($resultArr) {
					$status = true;
					$message = __('Result fetch successfully');
					$response = $resultArr['Result']['id'];

				}else{
					$message=__('You can not see the result of this exam');
				}
			}else{
				$message=__('You can not see the result of this exam');
			}
		}
		$this->set(compact('status', 'message', 'response'));
		$this->set('_serialize', array('status', 'message', 'response'));
	}

	private function showRank($rank)
	{
		if ($rank == 1)
			$rank = "1<sup>" . __('st') . "</sup>";
		elseif ($rank == 2)
			$rank = "2<sup>" . __('nd') . "</sup>";
		elseif ($rank == 3)
			$rank = "3<sup>" . __('rd') . "</sup>";
		else
			$rank = "$rank<sup>" . __('th') . "</sup>";
		return $rank;
	}

	private function score_card_details($id, $examDetails)
	{
		$this->loadModel('ExamStat');
		$totalStudentCount = $this->Result->find('count', array('conditions' => array('Result.exam_id' => $examDetails['Result']['exam_id'], 'Result.user_id >' => 0)));
		$correctQuestion = $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $id, 'answered' => 1, 'ques_status' => 'R')));
		$incorrectQuestion = $this->ExamStat->find('count', array('conditions' => array('ExamStat.exam_result_id' => $id, 'answered' => 1, 'ques_status' => 'W')));
		$this->ExamStat->virtualFields = array('total_marks' => 'SUM(marks_obtained)');
		$rightMarksArr = $this->ExamStat->find('first', array('fields' => array('total_marks'), 'conditions' => array('ExamStat.exam_result_id' => $id, 'answered' => 1, 'ques_status' => 'R')));
		$negativeMarksArr = $this->ExamStat->find('first', array('fields' => array('total_marks'), 'conditions' => array('ExamStat.exam_result_id' => $id, 'answered' => 1, 'ques_status' => 'W')));
		$this->ExamStat->virtualFields = array();
		$leftQuestion = $examDetails['Result']['total_question'] - $examDetails['Result']['total_answered'];
		$this->ExamStat->virtualFields = array('left_marks' => 'SUM(ExamStat.marks)');
		$leftQuestionArr = $this->ExamStat->find('first', array(
			'fields' => array('left_marks'),
			'conditions' => array('ExamStat.exam_result_id' => $id, 'answered' => 0)));
		$rankArr = $this->Result->query('SELECT `id`,`student_id`,`percent` FROM `exam_results` WHERE `exam_id`=' . $examDetails['Result']['exam_id'] . ' ORDER BY `percent` DESC');
		$rank = 0;
		$final_result_percent = NULL;
		foreach ($rankArr as $rnk) {
			if ($final_result_percent == $examDetails['Result']['percent']) {
				break;
			}
			if ($final_result_percent != $rnk['exam_results']['percent']) {
				$rank++;
			}
			$final_result_percent = $rnk['exam_results']['percent'];
		}
		$this->ExamStat->virtualFields = array();
		$percentile = round((($totalStudentCount - $rank) / $totalStudentCount) * 100, 2);
		return array(
			'student_count' => $totalStudentCount,
			'correct_questions' => $correctQuestion,
			'incorrect_questions' => $incorrectQuestion,
			'right_marks' => $rightMarksArr['ExamStat']['total_marks'],
			'negative_marks' => $negativeMarksArr['ExamStat']['total_marks'],
			'left_questions' => $leftQuestion,
			'left_questions_marks' => $leftQuestionArr['ExamStat']['left_marks'],
			'my_rank' => $rank,
			'percentile'=>$percentile
		);
	}

	private function topRank($examId){
		return $this->Result->query('SELECT `id`,`student_id`,`percent` FROM `exam_results` WHERE `exam_id`=' . $examId . ' AND `user_id` IS NOT NULL ORDER BY `percent` DESC');
	}
}
