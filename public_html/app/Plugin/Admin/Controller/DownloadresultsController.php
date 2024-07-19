<?php
App::uses('CakeTime', 'Utility');
App::uses('CakeNumber', 'Utility');
ini_set('max_execution_time', 900);
ini_set('memory_limit', '2048M');

class DownloadresultsController extends AdminAppController
{
    public $components = array('Session', 'PhpExcel.PhpExcel');

    public function index($id = null)
    {
        $this->layout = null;
        $this->autoRender = false;
        try {
            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            $post = $this->Downloadresult->find('first', array('conditions' => array('Downloadresult.exam_id' => $id)));
            if (!$post) {
                $this->Session->setFlash(__('No Result'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('controller' => 'Exams', 'action' => 'index'));
            }
            $this->Downloadresult->virtualFields = array('time_taken' => 'test_time',
                'question_correct' => "(SELECT COUNT(`ExamStat`.`id`) FROM `exam_stats` AS `ExamStat` WHERE `ExamStat`.`exam_result_id`=`Downloadresult`.`id` AND `ExamStat`.`answered`='1' AND `ExamStat`.`ques_status`='R')",
                'incorrect_correct' => "(SELECT COUNT(`ExamStat`.`id`) FROM `exam_stats` AS `ExamStat` WHERE `ExamStat`.`exam_result_id`=`Downloadresult`.`id` AND `ExamStat`.`answered`='1' AND `ExamStat`.`ques_status`='W')");
            $postAllArr = $this->Downloadresult->find('all', array('fields' => array('time_taken', 'question_correct', 'incorrect_correct', 'Downloadresult.*', 'Student.*', 'Exam.*'),
                'conditions' => array('Downloadresult.exam_id' => $id),
                'order' => array('Downloadresult.percent' => 'desc','time_taken'=>'asc')));
            $postAll = array();
            $mainPercentage = NULL;
            $mainTime = NULL;
            $rank = 0;
            foreach($postAllArr as $value){
                if($mainPercentage != $value['Downloadresult']['percent'] /*|| $mainTime != $value['Downloadresult']['time_taken']*/){
                    $rank++;
                }
                $mainPercentage = $value['Downloadresult']['percent'];
                $mainTime = $value['Downloadresult']['time_taken'];
                $value['Downloadresult']['rank'] = $rank;
                $postAll[] = $value;
            }
            $data = $this->exportData($post, $postAll);
            $this->PhpExcel->createWorksheet();
            $this->PhpExcel->addTableRow($data);
            $this->PhpExcel->output('Result', $this->siteName, str_replace(" ", "-", $post['Exam']['name']) . '-' . rand() . '.xls', 'Excel2007');
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('controller' => 'Exams', 'action' => 'index'));
        }
    }

    public function exportData($post, $postAll)
    {
        $showData = array(array(__('Name'), __('Enrollment No.'), __('Email address'), __('State'), __('Started on'), __('Completed'), __('Time taken'), __('Total Questions'), __('Questions Attempted'), __('Questions Correct'), __('Questions Incorrect'), __('Percentage'), __('Rank'), __('Grade/' . $post['Downloadresult']['total_marks'])));
        $index = 13;
        foreach ($post['ExamStat'] as $value) {
            $index++;
            $showData[0][$index] = __('Q.') . ' ' . $value['ques_no'] . ' /' . $value['marks'];
        }
        unset($value);
        $totalQuestion = count($post['ExamStat']);
        $index = 0;
        $totalMarksObtained = array();
        for ($j = 0; $j < $totalQuestion + 14; $j++) {
            $totalMarksObtained[$j] = 0;
        }
        foreach ($postAll as $rankRow=> $value) {
            $rankRow++;
            $rank = $value['Downloadresult']['rank'];
            $totalMarksObtained[13] = $totalMarksObtained[13] + $value['Downloadresult']['obtained_marks'];
            $index++;
            if ($value['Downloadresult']['end_time'] == NULL) {
                $status = __('Unfinished');
                $endTime = '-';
            } else {
                $status = __('Finished');
                $endTime = CakeTime::format('d F Y H:i A', $value['Downloadresult']['end_time']);
            }

            $timeTaken = $this->CustomFunction->secondsToWords($value['Downloadresult']['time_taken']);
            $showData[] = array($value['Student']['name'], $value['Student']['enroll'], $value['Student']['email'], $status, CakeTime::format('d F Y H:i A', $value['Downloadresult']['start_time']), $endTime,
                $timeTaken, $value['Downloadresult']['total_question'], $value['Downloadresult']['total_answered'], $value['Downloadresult']['question_correct'], $value['Downloadresult']['incorrect_correct'], $value['Downloadresult']['percent'], $rank, $value['Downloadresult']['obtained_marks']);
            foreach ($value['ExamStat'] as $k => $value1) {
                if ($value1['marks_obtained'] == NULL) {
                    $marksObtained = '-';
                } else {
                    $marksObtained = $value1['marks_obtained'];
                }
                $totalMarksObtained[$k + 14] = $totalMarksObtained[$k + 14] + $value1['marks_obtained'];
                $showData[$index][] = $marksObtained;
            }
            $totalMarksObtained[11] = $totalMarksObtained[11] + $value['Downloadresult']['percent'];
            $totalMarksObtained[12] = $totalMarksObtained[12] + $value['Downloadresult']['rank'];
            unset($value1);
        }
        foreach ($totalMarksObtained as $k => $value) {
            if ($k == 0) {
                $totalMarksObtained1[] = __('Overall Average');
            } elseif ($k >= 11) {
                if ($value == null) {
                    $totalMarksObtained1[] = '0';
                } else {
                    $totalMarksObtained1[] = ($value / $rankRow);
                }
            } else {
                $totalMarksObtained1[] = '';
            }
        }
        $showData[$rankRow + 1] = array();
        $showData[$rankRow + 2] = $totalMarksObtained1;
        unset($value);
        return $showData;
    }
}
