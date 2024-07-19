<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');
App::uses('CakeTime', 'Utility');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class FunctionHelper extends Helper
{
	var $helpers = array('Html', 'Form');

	public function bcmod($x, $y)
	{
		$mod = $x % $y;
		return (int) $mod;
	}

	public function secondsToWords($seconds, $msg = "Unlimited")
	{
		$ret = "";
		if ($seconds > 0) {
			/*** get the hours ***/
			$hours = intval(intval($seconds) / 3600);
			if ($hours > 0) {
				$ret .= $hours . ' ' . __('Hours') . ' ';
			}
			/*** get the minutes ***/
			$minutes = $this->bcmod((intval($seconds) / 60), 60);
			if ($minutes > 0) {
				$ret .= $minutes . ' ' . __('Mins') . ' ';
			}
			$tarMinutes = $this->bcmod((intval($seconds)), 60);
			if (strlen($ret) == 0 || $tarMinutes > 0) {
				if ($tarMinutes > 0)
					$ret .= $tarMinutes . ' ' . __('Sec');
				else
					$ret .= $seconds . ' ' . __('Sec');
			}
		} else {
			$ret = $msg;
		}
		return $ret;
	}

	public function showGroupName($gropArr, $string = " | ")
	{
		$groupNameArr = array();
		foreach ($gropArr as $groupName) {
			$groupNameArr[] = $groupName['group_name'];
		}
		unset($groupName);
		$showGroup = implode($string, $groupNameArr);
		unset($groupNameArr);
		return h($showGroup);
	}

	public function showPackageName($groupArr, $string = " | ")
	{
		$groupNameArr = array();
		foreach ($groupArr as $groupName) {
			$groupNameArr[] = $groupName['name'];
		}
		unset($groupName);
		$showGroup = implode($string, $groupNameArr);
		unset($groupNameArr);
		return h($showGroup);
	}

	public function cleanContent($content)
	{
		return str_replace("<script", "", $content);
	}

	public function showExamType($post)
	{
		if ($post['type'] == "Exam") {
			$showExam = "<strong>" . $post['total_marks'] . "</strong> " . __('Marks');
		} else {
			$showExam = "<strong>" . $post['total_question'] . "</strong> " . __('Questions');
		}
		return $showExam;
	}

	public function showExamList($showType, $exam, $examResultArr, $currentDateTime, $currency, $dateFormat, $frontExamPaid, $examExpiry, $isMobile = null)
	{
		$examList = "";
		$attempt = "";
		$serialNo = 0;
		if ($showType != "expired") {
			$expiredHeading = "<th>" . __('Attempts') . "<br>" . __('Remaining') . "</th>";
		} else {
			$expiredHeading = null;
		}
		foreach ($exam as $post) {
			$dateValue = null;
			$serialNo++;
			$attempt = "";
			$id = $post['Exam']['id'];
			if (isset($examResultArr[$id])) {
				$isExamOpen = true;
				$examResultId = $examResultArr[$id];
			} else {
				$isExamOpen = false;
				$examResultId = 0;
			}
			$viewUrl = $this->Html->url(array('controller' => 'Exams', 'action' => "view/$id"));
			if ($post['Exam']['attempt_count'] == 0) {
				$attemptRemaining = __('Unlimited');
				if ($isExamOpen) {
					$isAttempt = true;
					$attemptText = __('Resume now');
					$btnColor = "#FF5722;";
				} else {
					$isAttempt = true;
					$attemptText = __('Attempt now');
					$btnColor = "#795548;";
				}

			} else {
				if ($post['Exam']['attempt_order'] == 0) {
					$post['Exam']['attempt_order'] = 1;
				}
				$attemptRemaining = ($post['Exam']['attempt_order'] * $post['Exam']['attempt_count'] - $post['Exam']['attempt']);
				if ($isExamOpen) {
					$isAttempt = true;
					$attemptText = __('Resume Now');
					$btnColor = "#FF5722;";
				} else {
					if ($attemptRemaining > 0) {
						$isAttempt = true;
						$attemptText = __('Attempt Now');
						$btnColor = "#795548;";
					} else {
						$isAttempt = false;
						$attemptText = __('View Report');
					}
				}

			}
			$startDate = CakeTime::format($dateFormat, $post['Exam']['start_date']);
			$endDate = CakeTime::format($dateFormat, $post['Exam']['end_date']);
			if ($post['Exam']['fexpiry_date'] == null) {
				$expiredDate = __("Unlimited");
			} else {
				$expiredDate = CakeTime::format(str_replace(array("h", "i", "s", "A", ":"), "", trim($dateFormat)), $post['Exam']['fexpiry_date']);
			}
			if ($showType == 'today') {
				if ($currentDateTime >= $post['Exam']['start_date']) {
					if ($currentDateTime < $post['Exam']['end_date']) {
						if ($isAttempt == true) {
							if ($isMobile) {
								$attempt = '<br>' . $this->Html->link('<span class="fa fa-sign-in"></span>&nbsp;' . $attemptText, array('controller' => 'Exams', 'action' => 'guidelines', $id), array('data-toggle' => 'tooltip', 'title' => $attemptText, 'escape' => false, 'class' => 'btn btn-success btn-block', 'style' => 'margin-top: 5px;background-color:' . $btnColor));
							} else {
								/* $attempt = '<br>' . $this->Form->button('<span class="fa fa-sign-in"></span>&nbsp;' . $attemptText, array('onclick' => "javascript:showpop_up('" . $this->Html->url(array('controller' => 'Exams', 'action' => 'guidelines', $id)) . "')", 'data-toggle' => 'tooltip', 'title' => $attemptText, 'escape' => false, 'class' => 'btn btn-success btn-block', 'style' => 'margin-top: 5px;background-color:' . $btnColor)); */
								$attempt = '<br>' . $this->Form->button('<span class="fa fa-sign-in"></span>&nbsp;' . $attemptText, array('onclick' => "javascript:showpop_up('" . $this->Html->url(array('controller' => 'Exams', 'action' => 'instruction', $id)) . "')", 'data-toggle' => 'tooltip', 'title' => $attemptText, 'escape' => false, 'class' => 'btn btn-success btn-block', 'style' => 'margin-top: 5px;background-color:' . $btnColor));
							}
						}
					}
					if ($post['Exam']['attempt'] > 0 && !$isExamOpen) {
						if ($isMobile) {
							$attempt .= '<br>' . $this->Html->link('<span class="fa fa-sign-in"></span>&nbsp;' . __('View Report'), array('controller' => 'Results', 'action' => 'view_report', $id), array('data-toggle' => 'tooltip', 'title' => $attemptText, 'escape' => false, 'class' => 'btn btn-success btn-block', 'style' => 'margin-top: 5px;background-color:#673AB7'));
						} else {
							$attempt .= '<br>' . $this->Form->button('<span class="fa fa-sign-in"></span>&nbsp;' . __('View Report'), array('onclick' => "javascript:showpop_up('" . $this->Html->url(array('controller' => 'Results', 'action' => 'view_report', $id)) . "')", 'data-toggle' => 'tooltip', 'title' => $attemptText, 'escape' => false, 'class' => 'btn btn-success btn-block', 'style' => 'margin-top: 5px;background-color:#673AB7'));
						}
					}
				}
			}
			if ($showType != "expired") {
				$attemptRemaining = "<span class='exam_value'>" . $attemptRemaining;
			} else {
				$attemptRemaining = null;
			}
			$examList .= '<div class="panel panel-default">
					<div class="panel-body">';
			$examList .= "
						<div class='row'>
							<div class='col-sm-9'>
								<div class='col-sm-12 exam_name pad'>" . __('Exam') . " : <span class='exam_value'>" . h($post['Exam']['name']) . "</span></div>
                        		<div class='col-sm-2 exam_type pad'>" . __('Type') . "&nbsp;:&nbsp;<span class='exam_value'>" . ($post['Exam']['type']) . "</span></div>
                        		<div class='col-sm-3 pad'>" . __('Start') . "&nbsp;:&nbsp;<span class='exam_value'>" . preg_replace("/ /", "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", CakeTime::format($dateFormat, $startDate), 1) . "</span></div>
                        		<div class='col-sm-4 pad'>" . __('Duration') . "&nbsp;:&nbsp;<span class='exam_value'>" . $post['Exam']['duration'] . "</span></div>";
			if ($showType == "expired") {
				$examList .= "<div class='col-sm-3 pad'>" . __('Expired Date') . "&nbsp;:&nbsp;<span class='exam_value'>" . $expiredDate . "</span></div>";
			} else {
				$examList .= "<div class='col-sm-3 pad'>" . __('Attempts Remaining') . "&nbsp;:&nbsp;<span class='exam_value'>" . $attemptRemaining . "</span></div>";
			}
			$examList .= "
                        	</div>
                        	<div class='col-sm-3 pad'>" . $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;' . __('View Details'), 'javascript:void(0);', array('onclick' => "show_modal('$viewUrl/$showType');", 'escape' => false, 'class' => 'btn btn-info btn-block')) . ' ' .
				$attempt . "
                   			</div>
                        </div>";
			$examList .= '</div></div>';
		}
		unset($post);
		unset($id);
		return $examList;
	}

	public function userMenu($id)
	{
		$Content = ClassRegistry::init('Content');
		$contentArr = $Content->find('all', array('fileds' => array('link_name,is_url,url,page_url'), 'conditions' => array('parent_id' => $id, 'published' => 'Published'), 'order' => 'ordering asc'));
		return $contentArr;
	}

	public function queStatus($id)
	{
		$ExamQuestion = ClassRegistry::init('ExamQuestion');
		$contentArr = $ExamQuestion->find('count', array('conditions' => array('question_id' => $id)));
		if ($contentArr > 0)
			return true;
		else
			return false;
	}

	public function showSubjectWiseQuestionCount($id)
	{
		$Question = ClassRegistry::init('Question');
		$questionCount = $Question->find('count', array('conditions' => array('Question.subject_id' => $id)));
		if ($questionCount > 0) {

			return $questionCount;
		} else {
			return 0;
		}
	}
	public function makeStar($rating)
	{
		$star = '';
		foreach (range(1, 5) as $i) {
			if ($rating > 0) {
				if ($rating > 0.9) {
					$star .= '<i class="fas fa-star star-color">&nbsp;</i>';
				} else {
					$star .= '<i class="fas fa-star-half-alt  star-color">&nbsp;</i>';
				}
				$rating--;
			} else {
				$star .= '<i class="far fa-star star-color">&nbsp;</i>';
			}
		}
		return $star;
	}
}
