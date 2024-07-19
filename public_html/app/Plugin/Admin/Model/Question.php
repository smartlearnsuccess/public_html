<?php

class Question extends AppModel
{
	public $validationDomain = 'validation';
	public $actsAs = array('search-master.Searchable');
	public $hasMany = array('QuestionsLang');
	public $filterArgs = array('keyword' => array('type' => 'like', 'field' => array(
		'Question.question', 'Question.option1', 'Question.option2', 'Question.option3', 'Question.option4', 'Question.option5', 'Question.option6')),
		'subject_ids' => array('field' => 'Question.subject_id'),
		'topic_ids' => array('field' => 'Question.topic_id'),
		'stopic_ids' => array('field' => 'Question.stopic_id'),
		'qtype_ids' => array('field' => 'Question.qtype_id'),
		'diff_ids' => array('field' => 'Question.diff_id'));
	public $validate = array('subject_id' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Please select')),
		'diff_id' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Please select')),
		'marks' => array('Numeric' => array('rule' => 'Numeric', 'required' => true, 'allowEmpty' => false, 'message' => 'Only numbers')),
		'negative_marks' => array('Numeric' => array('rule' => 'Numeric', 'required' => true, 'allowEmpty' => false, 'message' => 'Only numbers')),


	);

	public function UserWiseGroup($userGroupWiseId)
	{
		$this->bindModel(array('hasAndBelongsToMany' => array('Group' => array('className' => 'Group',
			'joinTable' => 'question_groups',
			'foreignKey' => 'question_id',
			'associationForeignKey' => 'group_id',
			'conditions' => array('QuestionGroup.group_id' => $userGroupWiseId)
		))));
	}

}

?>
