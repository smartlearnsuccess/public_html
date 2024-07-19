<?php

class Subject extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array('search-master.Searchable');
    public $filterArgs = array('keyword' => array('type' => 'like', 'field' => 'Subject.subject_name'));
    public $validate = array('subject_name' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Only Alphabets')));


    public function UserWiseGroup($userGroupWiseId)
    {
        $this->bindModel(array('hasAndBelongsToMany' => array('Group' => array('className' => 'Group',
            'joinTable' => 'subject_groups',
            'foreignKey' => 'subject_id',
            'associationForeignKey' => 'group_id',
            'conditions' => array('SubjectGroup.group_id' => $userGroupWiseId)
        ))));
    }

    public function subjectRecord($id, $userGroupWiseId)
    {
        $subjecttArr = $this->find('first', array('joins' => array(array('table' => 'subject_groups', 'type' => 'INNER', 'alias' => 'SubjectGroup', 'conditions' => array('Subject.id=SubjectGroup.subject_id'))),
            'conditions' => array('Subject.id' => $id, 'SubjectGroup.group_id' => $userGroupWiseId)
        ));
        return $subjecttArr;
    }

}

?>