<?php

class Bookmark extends AppModel
{
    public $validationDomain = 'validation';
    public $useTable = "exam_results";
    public $belongsTo = array(
        'Exam' => array('className' => 'exams',
            'order' => array('Bookmark.start_time' => 'desc')),
        'ExamStat' => array(
            'className' => 'exam_stats',
            'foreignKey' => false,
            'type' => 'INNER',
            'conditions' => array('Bookmark.id=ExamStat.exam_result_id', 'ExamStat.bookmark' => 'Y'),
        )
    );
}

?>