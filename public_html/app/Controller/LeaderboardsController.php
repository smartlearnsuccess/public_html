<?php

class LeaderboardsController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function crm_index()
    {
        $this->authenticate();
        //////////////////// CUSTOM QUERY START ///////////////////////
        $scoreboard = $this->Leaderboard->query("SELECT `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo`, ROUND(SUM(`ExamResult`.`percent`)/COUNT(`ExamResult`.`exam_id`),2) as `points`,COUNT(`ExamResult`.`exam_id`) as `exam_given` FROM `exam_results` AS `ExamResult` INNER JOIN `students` AS `Student` ON (`ExamResult`.`student_id`=`Student`.`id`) WHERE `ExamResult`.`finalized_time` IS NOT NULL GROUP BY `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo` ORDER BY `points` DESC LIMIT 25");
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
        $this->set('leaderboard', $leaderboard);


    }

    public function rest_index()
    {
        $scoreboard = $this->Leaderboard->query("SELECT `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo`, ROUND(SUM(`ExamResult`.`percent`)/COUNT(`ExamResult`.`exam_id`),2) as `points`,COUNT(`ExamResult`.`exam_id`) as `exam_given` FROM `exam_results` AS `ExamResult` INNER JOIN `students` AS `Student` ON (`ExamResult`.`student_id`=`Student`.`id`) WHERE `ExamResult`.`finalized_time` IS NOT NULL GROUP BY `ExamResult`.`student_id`, `Student`.`name`, `Student`.`photo` ORDER BY `points` DESC LIMIT 25");
        $rank = 0;
        $final_result_percent = 0;
		$leaderboard=array();
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
                $student_photo = $this->siteDomain . '/img/User.png';
            } else {
                $student_photo = $this->siteDomain . '/img/student_thumb/' . $value['Student']['photo'];
            }
            $leaderboard[] = array('student_id' => $student_id, 'student_name' => $student_name, 'student_photo' => $student_photo, 'result_percent' => $result_percent, 'exam_given' => $value[0]['exam_given'], 'rank' => $rank);
            unset($student_photo);
        }
        $status = true;
        $message = __('Leader Board data fetch successfully.');
        $this->set(compact('status', 'message', 'leaderboard'));
        $this->set('_serialize', array('status', 'message', 'leaderboard'));
        unset($leaderboard);

    }

}
