<?php

class Stopic extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array('search-master.Searchable');
    public $belongsTo = array('Subject', 'Topic');
    public $filterArgs = array('keyword' => array('type' => 'like', 'field' => 'Stopic.name'));
    public $validate = array(
                             'name' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Only letters and numbers allowed'),
                                             'isUnique' => array('rule' => array('isUnique', array('subject_id', 'topic_id', 'name'), false), 'message' => 'The Subject, Topic & Sub Topic combination has already been exist! try new one'))
    );
}

?>