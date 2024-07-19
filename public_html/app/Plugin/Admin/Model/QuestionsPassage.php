<?php

class QuestionsPassage extends AppModel
{
    public $validationDomain = 'validation';
    public $belongsTo = array('Language');
}

?>