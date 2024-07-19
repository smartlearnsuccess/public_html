<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('File', 'Utility');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array();

	/**
	 * Displays a view
	 *
	 * @param mixed What page to display
	 * @return void
	 * @throws NotFoundException When the view file could not be found
	 *    or MissingViewException in debug mode.
	 */
	public function index()
	{
		$this->redirect(array('controller' => ''));
	}

	public function display()
	{
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		if ($this->frontLeaderBoard == 1) {

			//////////////////// CUSTOM QUERY START ///////////////////////
			$this->loadModel('Leaderboard');
			$scoreboard = $this->Leaderboard->query("SELECT `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo`, ROUND(SUM(`ExamResult`.`percent`)/COUNT(`ExamResult`.`exam_id`),2) as `points`,COUNT(`ExamResult`.`exam_id`) as `exam_given` FROM `exam_results` AS `ExamResult` INNER JOIN `students` AS `Student` ON (`ExamResult`.`student_id`=`Student`.`id`) WHERE `ExamResult`.`finalized_time` IS NOT NULL GROUP BY `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo` ORDER BY `points` DESC LIMIT 10");
			$leaderboard = array();
			$rank = 0;
			$final_result_percent = 0;
			foreach ($scoreboard as $value) {
				if ($final_result_percent == $value[0]['points']) {
					$rank;
				} else {
					$rank++;
				}
				$result_percent = $value[0]['points'];
				$final_result_percent = $result_percent;
				$student_id = $value['ExamResult']['student_id'];
				$student_name = $value['Student']['name'];
				if ($value['Student']['photo'] == "") {
					$student_photo = 'User.png';
				} else {
					$student_photo = 'student_thumb/' . $value['Student']['photo'];
				}
				$leaderboard[] = array('student_id' => $student_id, 'student_name' => $student_name, 'student_photo' => $student_photo, 'result_percent' => $result_percent, 'exam_given' => $value[0]['exam_given'], 'rank' => $rank);
				unset($student_photo);
			}
			//////////////////// CUSTOM QUERY END ///////////////////////
			$this->set('leaderboard', $leaderboard);
		}

		$this->loadModel('Package');
		$showPackageTypeArr = array();
		if ($this->showPackageType == 0) {
			$showPackageTypeArr = array('Package.package_type <>' => 'F');
		}
		$Package_response = $this->Package->find('all', array('conditions' => array('Package.status' => 'Active', $showPackageTypeArr), 'order' => array('Package.id' => 'DESC'), 'limit' => '8'));
		$this->set('Package', $Package_response);

		$this->loadModel('Homesection');
		$Homesection_response = $this->Homesection->find('all', array('order' => array('Homesection.id' => 'ASC')));
		$this->set('Homesection', $Homesection_response);

		$this->loadModel('ExamOrder');
		$countExamOrder = $this->ExamOrder->find('count');

		$news = array();
		$slides = array();
		$this->loadModel('Advertisement');
		$this->loadModel('Testimonial');
		$this->loadModel('Student');
		$this->loadModel('Exam');
		$this->loadModel('Slide');
		$this->loadModel('News');
		$this->loadModel('Subject');
		$this->set('students', $this->Student->find('count'));
		$this->set('exams', $this->Exam->find('count'));
		$this->set('subjects', $this->Subject->find('count'));
		$this->set('package_count', $this->Package->find('count'));
		$this->set('countExamOrder', $this->News->find('count'));

		$this->set('exam_lists', $this->Exam->find('all', array('limit' => 3)));

		$this->set('package_lists', $this->Package->find('all'));

		$this->set('testimonials', $this->Testimonial->find('all', array('conditions' => array('status' => 'Active'), 'order' => array('ordering' => 'ASC'))));
		$this->set('advertisements', $this->Advertisement->findAllByStatus('Active'));
		$slides = $this->Slide->find('all', array('conditions' => array('status' => 'Active'), 'order' => array('ordering' => 'ASC')));
		$news = $this->News->find(
			'all',
			array(
				'conditions' => array('status' => 'Active'),
				'order' => 'id desc'
			)
		);
		$this->set('slides', $slides);
		$this->set('news', $news);

		$file = new File(TMP . 'visitors.txt', false);
		$visitors = $file->read(true);
		$file->close();
		$file = new File(TMP . 'visitors.txt', false);
		$visitors++;
		$file->write($visitors);
		$file->close();
		$this->set('visitors', $visitors);

		try {
			$this->render(implode('/', $path));
		} catch (MissingViewException $e) {
			if (Configure::read('debug')) {
				throw $e;
			}
			throw new NotFoundException();
		}

	}

}