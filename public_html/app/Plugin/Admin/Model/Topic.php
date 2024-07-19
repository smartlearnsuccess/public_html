<?php

class Topic extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array('search-master.Searchable');
    public $filterArgs = array('keyword' => array('type' => 'like', 'field' => 'Topic.name'));
    public $belongsTo = array('Subject');
    public $validate = array(
                             'name' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Only letters and numbers allowed'),
                                             'isUnique' => array('rule' => array('isUnique', array('subject_id', 'name'), false), 'message' => 'The Subject & Topic combination has already been exist! try new one'))
    );
}

?>