<?php
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');
ini_set('max_execution_time', 900);
ini_set('memory_limit', '2048M');

class ExamsController extends AdminAppController
{
	public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Time', 'Tinymce', 'Js' => array('Jquery'));
	public $components = array('Session', 'PhpExcel.PhpExcel', 'Paginator', 'search-master.Prg', 'HighCharts.HighCharts', 'RequestHandler' => array('viewClassMap' => array('pdf' => 'CakePdf.Pdf')));
	public $presetVars = true;
	var $paginate = array('page' => 1, 'order' => array('Exam.id' => 'desc'));

	public function index()
	{
		try {
			$this->Exam->UserWiseGroup($this->userGroupWiseId);
			$this->Prg->commonProcess();
			$this->Paginator->settings = $this->paginate;
			$this->Paginator->settings['joins'] = array(array('table' => 'exam_groups', 'type' => 'INNER', 'alias' => 'ExamGroup', 'conditions' => array('Exam.id=ExamGroup.exam_id')));
			$this->Paginator->settings['fields'] = array('Exam.id', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.type', 'Exam.status', 'Exam.exam_mode');
			$this->Paginator->settings['group'] = array('Exam.id', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.type', 'Exam.status', 'Exam.exam_mode');
			$cond = array('ExamGroup.group_id' => $this->userGroupWiseId);
			$this->Paginator->settings['conditions'] = array($this->Exam->parseCriteria($this->Prg->parsedParams()), $cond);
			$this->Paginator->settings['limit'] = $this->pageLimit;
			$this->Paginator->settings['maxLimit'] = $this->maxLimit;
			$this->set('Exam', $this->Paginator->paginate());
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
		$this->loadModel('Subject');
		$this->loadModel('Qtype');
		$this->loadModel('Diff');
		$this->loadModel('ExamPrep');
		$this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
		$this->Subject->virtualFields = array('subject' => 'CONCAT(Subject.subject_name, " (Q) ",Count(DISTINCT(Question.id)))');
		$this->loadModel('Question');
		$this->Question->virtualFields = array('question_bank' => 'Count(*)');
		$questionCountArr = $this->Question->find('list', array('fields' => array('Question.subject_id', 'Question.question_bank'), 'group' => array('Question.subject_id')));
		$subjectArr = $this->CustomFunction->getSubjectList($this->userGroupWiseId);
		foreach ($subjectArr as $k => $item) {
			if (isset($questionCountArr[$k])) {
				$subjectId[$k] = $item . " (Q) " . $questionCountArr[$k];
			}
		}
		$this->set('subjectId', $subjectId);
		$this->Qtype->virtualFields = array('quesType' => 'CONCAT(question_type,"   ")');
		$this->Diff->virtualFields = array('diffLevel' => 'CONCAT(diff_level,"   ")');
		$this->set('quesType', $this->Qtype->find('list', array('fields' => array('id', 'quesType'))));
		$this->set('diffLevel', $this->Diff->find('list', array('fields' => array('id', 'diffLevel'))));
		$this->set('package', $this->Exam->Package->find('list', array(
				'conditions' => array('Package.status' => 'Active'),
				'order' => array('Package.name' => 'asc')
			)
		)
		);
		if ($this->request->is('post')) {
			try {
				if (strtotime($this->request->data['Exam']['end_date']) < strtotime($this->request->data['Exam']['start_date'])) {
					$this->Session->setFlash(__('End Date is not less than Start date'), 'flash', array('alert' => 'danger'));
				} elseif (!is_array($this->request->data['ExamGroup']['group_name'])) {
					$this->Session->setFlash(__('Please Select any group'), 'flash', array('alert' => 'danger'));
				} elseif ($this->request->data['Exam']['type'] == "Prepration" && !isset($this->request->data['ExamPrep'])) {
					$this->Session->setFlash(__('Please Add Subject To Exam'), 'flash', array('alert' => 'danger'));
				} elseif (!is_array($this->request->data['ExamsPackage']['package_id'])) {
					$this->Session->setFlash(__('Please Select any package'), 'flash', array('alert' => 'danger'));
				} else {
					$this->Exam->create();
					if ($this->Exam->save($this->request->data)) {
						$this->loadModel('ExamGroup');
						$examId = $this->Exam->id;
						foreach ($this->request->data['ExamGroup']['group_name'] as $groupId) {
							$examGroup[] = array('exam_id' => $examId, 'group_id' => $groupId);
						}
						$this->ExamGroup->create();
						$this->ExamGroup->saveAll($examGroup);

						$examPackage = array();
						$this->loadModel('ExamsPackage');
						foreach ($this->request->data['ExamsPackage']['package_id'] as $packageId) {
							$examsPackage[] = array('exam_id' => $examId, 'package_id' => $packageId);
						}
						$this->ExamsPackage->create();
						$this->ExamsPackage->saveAll($examsPackage);

						$this->loadModel('ExamPrep');
						$this->loadModel('ExamMaxquestion');
						if (isset($this->request->data['ExamPrep']) && is_array($this->request->data['ExamPrep'])) {
							foreach ($this->request->data['ExamPrep'] as $value) {
								$examPrep[] = array('exam_id' => $examId, 'subject_id' => $value['subject_id'], 'ques_no' => $value['ques_no'], 'type' => $value['type'], 'level' => $value['level']);
							}
							$this->ExamPrep->create();
							$this->ExamPrep->saveAll($examPrep);
						}
						if ($this->request->data['Exam']['type'] == "Exam") {
							$lastId = $this->Exam->id;
							$this->Session->setFlash(__('Exam has been saved. Add questions in exam'), 'flash', array('alert' => 'success'));
							return $this->redirect(array('controller' => 'Addquestions', 'action' => 'index', $lastId));
						} else {
							$this->Session->setFlash(__('Exam has been saved'), 'flash', array('alert' => 'success'));
							return $this->redirect(array('action' => 'add'));
						}
					}
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			}
		}
		$this->set('frontExamPaid', $this->frontExamPaid);
	}

	public function edit($id = null)
	{
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->Exam->UserWiseGroup($this->userGroupWiseId);
		$ids = explode(",", $id);
		$post = array();
		foreach ($ids as $id) {
			$this->Exam->UserWiseGroup($this->userGroupWiseId);
			$post[] = $this->Exam->findById($id);
		}
		$this->set('Exam', $post);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->loadModel('Group');
		$this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
		$this->set('package', $this->Exam->Package->find('list', array(
				'conditions' => array('Package.status' => 'Active'),
				'order' => array('Package.name' => 'asc')
			)
		)
		);
		if ($this->request->is(array('post', 'put'))) {
			$isSave = true;
			try {
				foreach ($this->request->data as $k => $value) {
					if (strtotime($value['Exam']['end_date']) < strtotime($value['Exam']['start_date'])) {
						$this->Session->setFlash(__('End Date is not less than Start date'), 'flash', array('alert' => 'danger'));
						$isSave = false;
						break;
					} elseif (!is_array($value['ExamGroup']['group_name'])) {
						$this->Session->setFlash(__('Please Select any group'), 'flash', array('alert' => 'danger'));
						$isSave = false;
						break;
					} elseif (!is_array($value['ExamsPackage']['package_id'])) {
						$this->Session->setFlash(__('Please Select any package'), 'flash', array('alert' => 'danger'));
						$isSave = false;
						break;
					}
				}
				if ($isSave == true) {
					if ($this->Exam->saveAll($this->request->data)) {
						$this->loadModel('ExamGroup');
						$this->loadModel('ExamsPackage');
						foreach ($this->request->data as $k => $value) {
							$examId = $value['Exam']['id'];
							$this->ExamGroup->deleteAll(array('ExamGroup.exam_id' => $examId, "ExamGroup.group_id" => $this->userGroupWiseId));
							$this->ExamsPackage->deleteAll(array('ExamsPackage.exam_id' => $examId));
							foreach ($value['ExamGroup']['group_name'] as $groupId) {
								$examGroup[] = array('exam_id' => $examId, 'group_id' => $groupId);
							}
							foreach ($value['ExamsPackage']['package_id'] as $packageId) {
								$examsPackage[] = array('exam_id' => $examId, 'package_id' => $packageId);
							}
						}
						$this->ExamGroup->create();
						$this->ExamGroup->saveAll($examGroup);
						$this->ExamsPackage->create();
						$this->ExamsPackage->saveAll($examsPackage);
						$this->Session->setFlash(__('Exam has been updated'), 'flash', array('alert' => 'success'));
						return $this->redirect(array('action' => 'index'));
					}
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			}
			$this->set('isError', true);
		} else {
			$this->layout = 'tinymce';
			$this->set('isError', false);
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$this->set('frontExamPaid', $this->frontExamPaid);
	}

	public function deleteall()
	{
		try {
			if ($this->request->is('post')) {
				$this->loadModel('ExamGroup');
				foreach ($this->data['Exam']['id'] as $key => $value) {
					$this->ExamGroup->deleteAll(array('ExamGroup.exam_id' => $value, "ExamGroup.group_id" => $this->userGroupWiseId));
				}
				$this->ExamGroup->query("DELETE `Exam` FROM `exams` AS `Exam` LEFT JOIN `exam_groups` AS `ExamGroup` ON `Exam`.`id` = `ExamGroup`.`exam_id` WHERE `ExamGroup`.`id` IS NULL");
				$this->Session->setFlash(__('Exam has been deleted'), 'flash', array('alert' => 'success'));
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
			$this->layout = null;
			if (!$id) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Exam->bindModel(array('hasAndBelongsToMany' => array('Group' => array('className' => 'Group',
				'joinTable' => 'exam_groups',
				'foreignKey' => 'exam_id',
				'associationForeignKey' => 'group_id',
				'conditions' => array("ExamGroup.group_id" => $this->userGroupWiseId)))));
			$post = $this->Exam->findById($id);
			if (!$post) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->loadModel('ExamQuestion');
			$this->loadModel('ExamPrep');
			$this->loadModel('Diff');
			$this->loadModel('Qtype');
			$SubjectDetail = "";
			$DiffLevel = "";
			$examCount = $this->Exam->find('count', array('joins' => array(array('table' => 'exam_maxquestions', 'type' => 'INNER', 'alias' => 'ExamMaxquestion', 'conditions' => array('Exam.id=ExamMaxquestion.exam_id'))),
				'conditions' => array('Exam.id' => $id)));
			$totalMarks = $this->Exam->totalMarks($id);
			if ($post['Exam']['type'] == "Exam") {
				$SubjectDetail = array();
				$chartData = array();
				$TotalQuestion = $this->ExamQuestion->find('count', array('conditions' => array('exam_id' => $id)));
				$SubjectDetail = $this->ExamQuestion->find('all', array(
					'fields' => array('Question.subject_id', 'Subject.subject_name', 'ExamMaxquestion.max_question'),
					'joins' => array(array('table' => 'questions', 'type' => 'Inner', 'alias' => 'Question', 'conditions' => array('Question.id=ExamQuestion.question_id')),
						array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject', 'conditions' => array('Subject.id=Question.subject_id')),
						array('table' => 'exam_maxquestions', 'type' => 'Left', 'alias' => 'ExamMaxquestion', 'conditions' => array('ExamQuestion.exam_id=ExamMaxquestion.exam_id', 'Subject.id=ExamMaxquestion.subject_id'))),
					'conditions' => array('ExamQuestion.exam_id' => $id),
					'group' => array('Question.subject_id', 'Subject.subject_name', 'ExamMaxquestion.max_question')));
				$DiffLevel = $this->Diff->find('all');
				$i = 0;
				foreach ($SubjectDetail as $value) {
					$subject_id = $value['Question']['subject_id'];
					$subject_name = $value['Subject']['subject_name'];
					$QuestionDetail[$subject_name][] = $this->viewquestiontype($id, $subject_id, 'S');
					$QuestionDetail[$subject_name][] = $this->viewquestiontype($id, $subject_id, 'M');
					$QuestionDetail[$subject_name][] = $this->viewquestiontype($id, $subject_id, 'T');
					$QuestionDetail[$subject_name][] = $this->viewquestiontype($id, $subject_id, 'F');
					$DifficultyDetail[$subject_name][] = $this->viewdifftype($id, $subject_id, 'E');
					$DifficultyDetail[$subject_name][] = $this->viewdifftype($id, $subject_id, 'M');
					$DifficultyDetail[$subject_name][] = $this->viewdifftype($id, $subject_id, 'D');
					$j = 0;
					foreach ($DiffLevel as $diff) {
						$tot_ques = (float)$DifficultyDetail[$subject_name][$j];
						$chartData[] = array($diff['Diff']['diff_level'], $tot_ques);
						$j++;

					}
					$chartName = "Pie Chart$i";
					$pieChart = $this->HighCharts->create($chartName, 'pie');
					$this->HighCharts->setChartParams(
						$chartName,
						array(
							'renderTo' => "piewrapper$i",  // div to display chart inside
							'chartWidth' => 250,
							'chartHeight' => 300,
							'creditsEnabled' => FALSE,
							'title' => $subject_name,
							'titleAlign' => 'left',
							'plotOptionsPieShowInLegend' => TRUE,
							'plotOptionsPieDataLabelsEnabled' => TRUE,
							'plotOptionsPieDataLabelsFormat' => '<b>{point.y}</b>',
						)
					);
					$series = $this->HighCharts->addChartSeries();
					$series->addName(__('Difficulty Level'))->addData($chartData);
					$pieChart->addSeries($series);
					unset($chartData);
					$i++;
				}
			} else {
				$TotalQuestionArr = $this->ExamPrep->find('all', array('fields' => array('SUM(ques_no) AS total'), 'conditions' => array('exam_id' => $id)));
				$subjectPrepAll = $this->ExamPrep->find('all', array('joins' => array(array('table' => 'subjects', 'alias' => 'Subject', 'type' => 'INNER', 'conditions' => array('ExamPrep.subject_id=Subject.id')),
					array('table' => 'exam_maxquestions', 'type' => 'Left', 'alias' => 'ExamMaxquestion', 'conditions' => array('ExamPrep.exam_id=ExamMaxquestion.exam_id', 'ExamPrep.subject_id=ExamMaxquestion.subject_id'))),
					'fields' => array('Subject.subject_name', 'ExamPrep.ques_no', 'ExamPrep.type', 'ExamPrep.level', 'ExamMaxquestion.max_question'),
					'conditions' => array('ExamPrep.exam_id' => $id)));
				$SubjectDetail = array();
				$chartData = array();
				foreach ($subjectPrepAll as $value) {
					$subjectName = $value['Subject']['subject_name'];
					$totalQuestion = (int)$value['ExamPrep']['ques_no'];
					$chartData[] = array($subjectName, $totalQuestion);
					if($value['ExamPrep']['type']!="null") {
						foreach (explode(",", $value['ExamPrep']['type']) as $examType) {
							$qtypeArr = $this->Qtype->findById($examType, array('question_type'));
							$qtype[] = $qtypeArr['Qtype']['question_type'];
						}
						$questionType = implode(" | ", $qtype);
					}else{
						$questionType="";
					}
					unset($examType, $qtype);
					if($value['ExamPrep']['level']!="null") {
						foreach (explode(",", $value['ExamPrep']['level']) as $examType) {
							$qtypeArr = $this->Diff->findById($examType, array('diff_level'));
							$qtype[] = $qtypeArr['Diff']['diff_level'];
						}
						$levelType = implode(" | ", $qtype);
					}else{
						$levelType="";
					}
					unset($examType, $qtype);
					$SubjectDetail[] = array('Subject' => $subjectName, 'Type' => $questionType, 'Level' => $levelType, 'QuesNo' => $value['ExamPrep']['ques_no'], 'MaxQuestion' => $value['ExamMaxquestion']['max_question']);
					unset($questionType, $levelType);
				}
				$TotalQuestion = $TotalQuestionArr[0][0]['total'];
				$chartName = "Pie Chartsub";
				$pieChart = $this->HighCharts->create($chartName, 'pie');
				$this->HighCharts->setChartParams(
					$chartName,
					array(
						'renderTo' => "piewrappersub",  // div to display chart inside
						'title' => __('Subject Wise Question Count'),
						'titleAlign' => 'center',
						'creditsEnabled' => FALSE,
						'plotOptionsShowInLegend' => TRUE,
						'plotOptionsPieShowInLegend' => TRUE,
						'plotOptionsPieDataLabelsEnabled' => TRUE,
						'plotOptionsPieDataLabelsFormat' => '{point.name}:<b>{point.y}</b>',
					)
				);
				$series = $this->HighCharts->addChartSeries();
				$series->addName('Total Question')->addData($chartData);
				$pieChart->addSeries($series);
				unset($chartData);
			}
			$this->set('post', $post);
			$this->set('id', $id);
			$this->set('TotalQuestion', $TotalQuestion);
			$this->set('SubjectDetail', $SubjectDetail);
			if (isset($QuestionDetail))
				$this->set('QuestionDetail', $QuestionDetail);
			$this->set('DiffLevel', $DiffLevel);
			if (isset($DifficultyDetail))
				$this->set('DifficultyDetail', $DifficultyDetail);
			$this->set('examCount', $examCount);
			$this->set('totalMarks', $totalMarks);
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function activateexam($id = null, $type = "Active")
	{
		try {
			$examCount = $this->Exam->find('count', array('conditions' => array('id' => $id)));
			if ($id == null || $examCount == 0) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('controller' => 'Exams', 'action' => 'index'));
			}
			$this->Exam->unbindValidation('remove', array('name', 'amount', 'passing_percent', 'duration', 'attempt_count', 'start_date', 'end_date'), true);
			if ($type == "Inactive") {
				$this->Exam->save(array('id' => $id, 'status' => 'Inactive'));
				$this->Session->setFlash(__('Exam successfully deactivated'), 'flash', array('alert' => 'success'));
				$this->redirect(array('controller' => 'Exams', 'action' => 'index'));
			} else {
				$this->Exam->save(array('id' => $id, 'status' => 'Active', 'user_id' => 0));
				$this->Session->setFlash(__('Exam successfully activated'), 'flash', array('alert' => 'success'));
				$this->redirect(array('controller' => 'Exams', 'action' => 'aenotif', $id));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}


	public function stats($id = null)
	{
		try {
			$this->layout = null;
			if (!$id) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Exam->bindModel(array('hasAndBelongsToMany' => array('Group' => array('className' => 'Group',
				'joinTable' => 'exam_groups',
				'foreignKey' => 'exam_id',
				'associationForeignKey' => 'group_id',
				'conditions' => array("ExamGroup.group_id" => $this->userGroupWiseId)))));
			$post = $this->Exam->findByIdAndStatus($id, 'Closed');
			if (!$post) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$examStats = $this->Exam->examStats($id);
			$chartRerData = array();
			$chartRerData[] = array(__('Pass'), $examStats['StudentStat']['pass']);
			$chartRerData[] = array(__('Fail'), $examStats['StudentStat']['fail']);
			$chartRerData[] = array(__('Absent'), $examStats['StudentStat']['absent']);
			$id = $examStats['Exam']['id'];
			$chartName = "My Chartss";
			$mychart = $this->HighCharts->create($chartName, 'pie');
			$this->HighCharts->setChartParams(
				$chartName,
				array(
					'renderTo' => "mywrapperss",  // div to display chart inside
					'creditsEnabled' => FALSE,
					'chartWidth' => 300,
					'chartHeight' => 200,
					'plotOptionsPieShowInLegend' => TRUE,
					'plotOptionsPieDataLabelsEnabled' => TRUE,
					'plotOptionsPieDataLabelsFormat' => '{point.name}:<b>{point.percentage:.1f}%</b>',
				)
			);

			$series = $this->HighCharts->addChartSeries();
			$series->addName(__('Student'))->addData($chartRerData);
			$mychart->addSeries($series);

			$chartRerData = array();
			$chartRerData1 = array();
			$chartRerData = array($examStats['OverallResult']['passing']);
			$chartRerData1 = array($examStats['OverallResult']['average']);
			$id = $examStats['Exam']['id'];
			$chartName = "My Chartor";
			$mychart = $this->HighCharts->create($chartName, 'bar');
			$this->HighCharts->setChartParams(
				$chartName,
				array(
					'renderTo' => "mywrapperor",  // div to display chart inside
					'creditsEnabled' => FALSE,
					'chartWidth' => 350,
					'chartHeight' => 200,
					'legendEnabled' => TRUE,
					'plotOptionsBarDataLabelsEnabled' => TRUE,
				)
			);

			$series = $this->HighCharts->addChartSeries();
			$series1 = $this->HighCharts->addChartSeries();
			$series->addName(__('Passing %age'))->addData($chartRerData);
			$series1->addName(__('Average %age'))->addData($chartRerData1);
			$mychart->addSeries($series);
			$mychart->addSeries($series1);
			$this->set('examStats', $examStats);
			$this->set('post', $post);
			$this->set('id', $id);
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function downloadlist($id = null, $type)
	{
		try {
			$this->layout = 'pdf';
			if (!$id) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Exam->bindModel(array('hasAndBelongsToMany' => array('Group' => array('className' => 'Group',
				'joinTable' => 'exam_groups',
				'foreignKey' => 'exam_id',
				'associationForeignKey' => 'group_id',
				'conditions' => array("ExamGroup.group_id" => $this->userGroupWiseId)))));
			$post = $this->Exam->findByIdAndStatus($id, 'Closed');
			if (!$post) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->pdfConfig = array('filename' => $type . '-Student-' . rand() . '.pdf');
			$this->set('examResult', $this->Exam->examAttendance($id, $type));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function downloadabsentlist($id = null)
	{
		try {
			$this->layout = 'pdf';
			if (!$id) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Exam->bindModel(array('hasAndBelongsToMany' => array('Group' => array('className' => 'Group',
				'joinTable' => 'exam_groups',
				'foreignKey' => 'exam_id',
				'associationForeignKey' => 'group_id',
				'conditions' => array("ExamGroup.group_id" => $this->userGroupWiseId)))));
			$post = $this->Exam->findByIdAndStatus($id, 'Closed');
			if (!$post) {
				$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
			$this->pdfConfig = array('filename' => 'Absent-Student-' . rand() . '.pdf');
			$this->set('examResult', $this->Exam->examAbsent($id));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function aenotif($id = null, $offset = 0)
	{
		try {
			if ($this->emailNotification || $this->smsNotification) {
				$examCount = $this->Exam->find('count', array('conditions' => array('id' => $id, 'status' => 'Active')));
				if ($id == null || $examCount == 0) {
					$this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
					$this->redirect(array('controller' => 'Exams', 'action' => 'index'));
				}
				$this->loadModel('Emailtemplate');
				$emailTemplateArr = $this->Emailtemplate->findByType('EAN');
				$this->loadModel('Smstemplate');
				$smsTemplateArr = $this->Smstemplate->findByType('EAN');
				if (($emailTemplateArr['Emailtemplate']['status'] == "Unpublished" || !$this->emailNotification) && ($smsTemplateArr['Smstemplate']['status'] == "Unpublished" || !$this->smsNotification)) {
					$this->redirect(array('controller' => 'Exams', 'action' => 'index'));
					die;
				}
				$this->loadModel('ExamGroup');
				$examGroupArr = $this->ExamGroup->find('list', array(
					'fields' => array('ExamGroup.group_id', 'ExamGroup.group_id'),
					'conditions' => array('ExamGroup.exam_id' => $id)));
				$totArr = $this->Exam->find('count', array('fields' => array('Student.name', 'Student.email', 'Student.phone', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.type'),
					'joins' => array(
						array('table' => 'student_groups', 'alias' => 'StudentGroup', 'type' => 'Inner', 'conditions' => array('StudentGroup.group_id' => $examGroupArr)),
						array('table' => 'students', 'alias' => 'Student', 'type' => 'Inner', 'conditions' => array('Student.id=StudentGroup.student_id')),
					),
					'conditions' => array('Exam.status' => 'Active', 'Exam.id' => $id, 'Student.status' => 'Active'),
					'order' => array('StudentGroup.student_id' => 'asc'),
					'group' => array('StudentGroup.student_id')));
				$post = $this->Exam->find('all', array('fields' => array('Student.name', 'Student.email', 'Student.phone', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.type'),
					'joins' => array(
						array('table' => 'student_groups', 'alias' => 'StudentGroup', 'type' => 'Inner', 'conditions' => array('StudentGroup.group_id' => $examGroupArr)),
						array('table' => 'students', 'alias' => 'Student', 'type' => 'Inner', 'conditions' => array('Student.id=StudentGroup.student_id')),
					),
					'conditions' => array('Exam.status' => 'Active', 'Exam.id' => $id, 'Student.status' => 'Active'),
					'order' => array('Student.name' => 'asc'),
					'group' => array('Student.name', 'Student.email', 'Student.phone', 'Exam.name', 'Exam.start_date', 'Exam.end_date', 'Exam.type'),
					));
				$emailArr = array();
				$mobileNoArr = array();
				$level=0;
				$levelEnd=0;
				$maxLength = 100;
				foreach ($post as $value) {
					$level++;
					$levelEnd++;
					$emailArr[] = $value['Student']['email'];
					$mobileNoArr[] = $value['Student']['phone'];
					$startDate = CakeTime::format($this->sysDay . $this->dateSep . $this->sysMonth . $this->dateSep . $this->sysYear . $this->dateGap . $this->sysHour . $this->timeSep . $this->sysMin . $this->dateGap . $this->sysMer, $value['Exam']['start_date']);
					$endDate = CakeTime::format($this->sysDay . $this->dateSep . $this->sysMonth . $this->dateSep . $this->sysYear . $this->dateGap . $this->sysHour . $this->timeSep . $this->sysMin . $this->dateGap . $this->sysMer, $value['Exam']['end_date']);
					$examName = $value['Exam']['name'];
					$type = $value['Exam']['type'];
					$siteName = $this->siteName;
					$siteEmailContact = $this->siteEmailContact;
					$url = $this->siteDomain;
					if($level==$maxLength || $levelEnd==$totArr) {
						if ($this->emailNotification) {
							/* Send Email */
							$this->loadModel('Emailtemplate');
							$emailTemplateArr = $this->Emailtemplate->findByType('EAN');
							if ($emailTemplateArr['Emailtemplate']['status'] == "Published") {
								$message = strtr($emailTemplateArr['Emailtemplate']['description'], [
									'{#examName#}' => $examName,
									'{#type#}' => $type,
									'{#startDate#}' => $startDate,
									'{#endDate#}' => $endDate,
									'{#siteName#}' => $siteName,
									'{#siteEmailContact#}' => $siteEmailContact
								]);
								$Email = new CakeEmail();
								$Email->transport($this->emailSettype);
								if ($this->emailSettype == "Smtp") {
									$Email->config($this->emailSettingsArr);
								}
								$Email->from(array($this->siteEmail => $this->siteName));
								$Email->bcc($emailArr);
								$Email->template('default');
								$Email->emailFormat('html');
								$Email->subject($emailTemplateArr['Emailtemplate']['name']);
								$Email->send($message);
								/* End Email */
							}
						}
						if ($this->smsNotification) {
							/* Send Sms */
							if ($smsTemplateArr['Smstemplate']['status'] == "Published") {
								$message = strtr($smsTemplateArr['Smstemplate']['description'], [
									'{#examName#}' => $examName,
									'{#type#}' => $type,
									'{#startDate#}' => $startDate,
									'{#endDate#}' => $endDate,
									'{#siteName#}' => $siteName
								]);
								$this->CustomFunction->sendSms($mobileNoArr, $message, $this->smsSettingArr, $smsTemplateArr['Smstemplate']['dlt_template_value']);
							}
							/* End Sms */
						}
						$level=0;
						$emailArr = array();
						$mobileNoArr = array();
					}
				}
				$this->redirect(array('controller' => 'Exams', 'action' => 'index'));
			} else {
				$this->redirect(array('controller' => 'Exams', 'action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function maxquestion($id = null)
	{
		$this->layout = null;
		$this->loadModel('ExamQuestion');
		$post = $this->ExamQuestion->find('all', array('fields' => array('Subject.id', 'Subject.subject_name', 'ExamMaxquestion.id', 'ExamMaxquestion.max_question'),
			'joins' => array(array('table' => 'questions', 'type' => 'Inner', 'alias' => 'Question', 'conditions' => array('Question.id=ExamQuestion.question_id')),
				array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject', 'conditions' => array('Subject.id=Question.subject_id')),
				array('table' => 'exam_maxquestions', 'type' => 'Left', 'alias' => 'ExamMaxquestion', 'conditions' => array('ExamQuestion.exam_id=ExamMaxquestion.exam_id', 'Subject.id=ExamMaxquestion.subject_id'))),
			'conditions' => array('ExamQuestion.exam_id' => $id),
			'group' => array('Subject.id', 'Subject.subject_name', 'ExamMaxquestion.id', 'ExamMaxquestion.max_question')));
		$this->set('post', $post);
		$this->set('examId', $id);
		if (!$post) {
			$this->Session->setFlash(__('There are no question added!'), 'flash', array('alert' => 'danger'));
		}
		if ($this->request->is('post')) {
			try {
				$this->loadModel('ExamMaxquestion');
				$this->ExamMaxquestion->saveAll($this->request->data);
				$this->Session->setFlash(__('Maximum attempt question has been saved.'), 'flash', array('alert' => 'success'));
				$this->redirect(array('action' => 'index'));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function exportfeedback($id)
	{
		$this->autoRender = false;
		try {
			$this->loadModel('ExamFeedback');
			$post = $this->ExamFeedback->find('all', array('fields' => array('ExamFeedback.*', 'Student.name', 'Student.email', 'Exam.name'),
				'joins' => array(array('table' => 'exam_results', 'type' => 'INNER', 'alias' => 'ExamResult', 'conditions' => array('ExamFeedback.exam_result_id=ExamResult.id')),
					array('table' => 'exams', 'type' => 'INNER', 'alias' => 'Exam', 'conditions' => array('ExamResult.exam_id=Exam.id')),
					array('table' => 'students', 'type' => 'INNER', 'alias' => 'Student', 'conditions' => array('Student.id=ExamResult.student_id'))),
				'conditions' => array('ExamResult.exam_id' => $id),
				'order' => array('Student.name' => 'asc')));
			if (!$post) {
				$this->Session->setFlash(__('No feedback available'), 'flash', array('alert' => 'danger'));
				return $this->redirect(array('action' => 'index'));
			}
			$data = $this->exportData($post);
			$this->PhpExcel->createWorksheet();
			$this->PhpExcel->addTableRow($data);
			$this->PhpExcel->output('Result-Feedback', $this->siteName, str_replace(" ", "-", $post[0]['Exam']['name']) . '-feedback-' . rand() . '.xls', 'Excel2007');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function exporttolrance($id)
	{
		$this->autoRender = false;
		try {
			$this->loadModel('ExamWarn');
			$post = $this->ExamWarn->find('all', array('fields' => array('ExamWarn.*', 'Student.name', 'Student.email', 'Exam.name'),
				'joins' => array(array('table' => 'exam_results', 'type' => 'INNER', 'alias' => 'ExamResult', 'conditions' => array('ExamWarn.exam_result_id=ExamResult.id')),
					array('table' => 'exams', 'type' => 'INNER', 'alias' => 'Exam', 'conditions' => array('ExamResult.exam_id=Exam.id')),
					array('table' => 'students', 'type' => 'INNER', 'alias' => 'Student', 'conditions' => array('ExamResult.student_id=Student.id'))),
				'conditions' => array('ExamResult.exam_id' => $id),
				'order' => array('Student.name' => 'asc')));
			if (!$post) {
				$this->Session->setFlash(__('No browser tolrance available'), 'flash', array('alert' => 'danger'));
				return $this->redirect(array('action' => 'index'));
			}
			$data = $this->exportTolranceData($post);
			$this->PhpExcel->createWorksheet();
			$this->PhpExcel->addTableRow($data);
			$this->PhpExcel->output('Result-Tolrance', $this->siteName, str_replace(" ", "-", $post[0]['Exam']['name']) . '-tolrance-' . rand() . '.xls', 'Excel2007');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function sectiontime($id = null)
	{
		$this->layout = null;
		$examArr = $this->Exam->findById($id);
		if (!$examArr) {
			die();
		}
		$this->loadModel('ExamQuestion');
		if ($examArr['Exam']['type'] == "Exam") {
			$post = $this->ExamQuestion->find('all', array(
				'fields' => array('Subject.id', 'Subject.subject_name', 'Subject.section_time', 'ExamsSubject.id', 'ExamsSubject.duration'),
				'joins' => array(
					array('table' => 'questions', 'type' => 'Inner', 'alias' => 'Question', 'conditions' => array('Question.id=ExamQuestion.question_id')),
					array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject', 'conditions' => array('Subject.id=Question.subject_id')),
					array('table' => 'exams_subjects', 'type' => 'LEFT', 'alias' => 'ExamsSubject', 'conditions' => array('Subject.id=ExamsSubject.subject_id', 'ExamsSubject.exam_id=ExamQuestion.exam_id')),
				),
				'conditions' => array('ExamQuestion.exam_id' => $id),
				'group' => array('Subject.id', 'Subject.subject_name', 'Subject.section_time', 'ExamsSubject.id', 'ExamsSubject.duration'),
				'order' => array('Subject.ordering' => 'asc'),
			));
		} else {

			$this->loadModel('ExamPrep');
			$post = $this->ExamPrep->find('all', array(
				'fields' => array('Subject.id', 'Subject.subject_name', 'Subject.section_time', 'ExamsSubject.id', 'ExamsSubject.duration'),
				'joins' => array(
					array('table' => 'subjects', 'type' => 'Inner', 'alias' => 'Subject', 'conditions' => array('Subject.id=ExamPrep.subject_id')),
					array('table' => 'exams_subjects', 'type' => 'LEFT', 'alias' => 'ExamsSubject', 'conditions' => array('ExamPrep.subject_id=ExamsSubject.subject_id', 'ExamsSubject.exam_id=ExamPrep.exam_id')),
				),
				'conditions' => array('ExamPrep.exam_id' => $id),
				'order' => array('Subject.ordering' => 'asc'),
			));
		}
		if (!$post) {
			$this->Session->setFlash(__('There are no question added!'), 'flash', array('alert' => 'danger'));
		}
		$this->set('post', $post);
		$this->set('examId', $id);

		if ($this->request->is('post')) {
			try {
				$this->loadModel('ExamsSubject');
				$this->ExamsSubject->deleteAll(array('exam_id'=>$id));
				$this->ExamsSubject->saveAll($this->request->data);
				$this->Session->setFlash(__('Section wise time has been saved.'), 'flash', array('alert' => 'success'));
				$this->redirect(array('action' => 'index'));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
				$this->redirect(array('action' => 'index'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	private function viewquestiontype($id, $subject_id, $type)
	{
		try {
			$this->loadModel('Question');
			return $this->Question->find('count', array(
				'joins' => array(array('table' => 'qtypes', 'type' => 'Inner', 'alias' => 'Qtype', 'conditions' => array('Question.qtype_id=Qtype.id')),
					array('table' => 'exam_questions', 'type' => 'Inner', 'alias' => 'ExamQuestion', 'conditions' => array('Question.id=ExamQuestion.question_id'))),
				'conditions' => array('ExamQuestion.exam_id' => $id, 'Question.subject_id' => $subject_id, 'Qtype.type' => $type)));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	private function viewdifftype($id, $subject_id, $type)
	{
		try {
			$this->loadModel('Question');
			return $this->Question->find('count', array(
				'joins' => array(array('table' => 'diffs', 'type' => 'Inner', 'alias' => 'Diff', 'conditions' => array('Question.diff_id=Diff.id')),
					array('table' => 'exam_questions', 'type' => 'Inner', 'alias' => 'ExamQuestion', 'conditions' => array('Question.id=ExamQuestion.question_id'))),
				'conditions' => array('ExamQuestion.exam_id' => $id, 'Question.subject_id' => $subject_id, 'Diff.type' => $type)));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
			return $this->redirect(array('action' => 'index'));
		}
	}

	private function exportData($post)
	{
		$showData = array(array($post[0]['Exam']['name']));
		$showData[] = array(__('Name'), __('Email'), $this->feedbackArr[0], $this->feedbackArr[1], $this->feedbackArr[2], $this->feedbackArr[3], __('Date & Time'));
		foreach ($post as $value) {
			$showData[] = array($value['Student']['name'], $value['Student']['email'], $value['ExamFeedback']['comment1'], $value['ExamFeedback']['comment2'], $value['ExamFeedback']['comment3'], $value['ExamFeedback']['comments'], CakeTime::format($this->dtFormat . ' h:i:s A', $value['ExamFeedback']['created']));
		}
		return $showData;
	}

	private function exportTolranceData($post)
	{
		$showData = array(array($post[0]['Exam']['name']));
		$showData[] = array(__('Name'), __('Email'), __('Date & Time'));
		foreach ($post as $value) {
			$showData[] = array($value['Student']['name'], $value['Student']['email'], CakeTime::format($this->dtFormat . ' h:i:s A', $value['ExamWarn']['created']));
		}
		return $showData;
	}

}
