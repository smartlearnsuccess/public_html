<?php

class Package extends AppModel
{
    public $hasAndBelongsToMany = array('Exam' => array('fields' => array('Exam.id', 'Exam.name')));
}

?>