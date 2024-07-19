<?php

class QuestionsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Tinymce', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Question.id' => 'desc'), 'group' => array('Question.id'));

    public function index()
    {
        try {
            $subjectId = null;
            $topicId = null;
            $this->loadModel('Subject');
            $this->loadModel('Topic');
            $this->loadModel('Stopic');
            $this->loadModel('Qtype');
            $this->loadModel('Diff');
            $this->Question->UserWiseGroup($this->userGroupWiseId);
            $this->set('subjectArr', $this->Subject->find('list', array('fields' => array('Subject.id', 'Subject.subject_name'))));
            $this->set('topicArr', $this->Topic->find('list'));
            $this->set('stopicArr', $this->Stopic->find('list'));
            $this->set('subjectId', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
            $this->set('qtypeId', $this->Qtype->find('list', array('fields' => array('id', 'question_type'))));
            $this->set('diffId', $this->Diff->find('list', array('fields' => array('id', 'diff_level'))));
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['joins'] = array(array('table' => 'question_groups', 'type' => 'INNER', 'alias' => 'QuestionGroup', 'conditions' => array('Question.id=QuestionGroup.question_id')));
            $this->Paginator->settings['fields'] = array('Question.id', 'Question.question', 'Question.subject_id', 'Question.topic_id', 'Question.stopic_id');
            $this->Paginator->settings['group'] = array('Question.id', 'Question.question', 'Question.subject_id', 'Question.topic_id', 'Question.stopic_id');
            $cond = array('QuestionGroup.group_id' => $this->userGroupWiseId);
            $this->Paginator->settings['conditions'] = array($this->Question->parseCriteria($this->Prg->parsedParams()), $cond);
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $this->set('Question', $this->Paginator->paginate());
            if ($this->request->data) {
                if (isset($this->request->data['Question']['subject_ids'])) {
                    $subjectId = $this->request->data['Question']['subject_ids'];
                }
                if (isset($this->request->data['Question']['topic_ids'])) {
                    $topicId = $this->request->data['Question']['topic_ids'];
                }
            }
            $this->set('topic', $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('name' => 'asc'))));
            $this->set('stopic', $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $topicId), 'order' => array('name' => 'asc'))));
            if ($this->request->is('ajax')) {
                $this->render('index', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }


    }

    public function add($id = null, $passageId = null)
    {
        $subjectId = null;
        $topicId = null;
        $this->loadModel('Topic');
        $this->loadModel('Qtype');
        $this->loadModel('Subject');
        $this->loadModel('Diff');
        $this->loadModel('Group');
        $this->set('qtype_id', $this->Qtype->find('list', array('fields' => array('id', 'question_type'))));
        $this->set('subject_id', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
        $this->set('diff_id', $this->Diff->find('list', array('fields' => array('id', 'diff_level'))));
        $this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
        if ($id == null)
            $selSubject = null;
        else
            $selSubject = $id;
        $this->set('selSubject', $selSubject);
        if ($passageId == null)
            $selPassage = null;
        else
            $selPassage = $passageId;
        $this->set('selPassage', $selPassage);
        if ($this->request->is('post')) {
            $this->Question->create();
            try {
                if (is_array($this->request->data['Question']['answer']))
                    $this->request->data['Question']['answer'] = implode(",", $this->request->data['Question']['answer']);
                if ($this->request->data['Question']['qtype_id'] == 1 && $this->request->data['Question']['answer'] == null) {
                    $this->Session->setFlash(__('Please Choose the Correct answer'), 'flash', array('alert' => 'danger'));
                } elseif ($this->request->data['Question']['qtype_id'] == 2 && !isset($this->request->data['Question']['true_false'])) {
                    $this->Session->setFlash(__('Please select true or false'), 'flash', array('alert' => 'danger'));
                } elseif ($this->request->data['Question']['qtype_id'] == 3 && $this->request->data['Question']['fill_blank'] == null) {
                    $this->Session->setFlash(__('Please fill blank space'), 'flash', array('alert' => 'danger'));
                } elseif (!is_array($this->request->data['QuestionGroup']['group_name'])) {
                    $this->Session->setFlash(__('Please Select any group'), 'flash', array('alert' => 'danger'));
                } else {
                    if ($this->Question->save($this->request->data)) {
                        $this->loadModel('QuestionGroup');
                        $questionId = $this->Question->id;
                        foreach ($this->request->data['QuestionGroup']['group_name'] as $groupId) {
                            $QuestionGroup[] = array('question_id' => $questionId, 'group_id' => $groupId);
                        }
                        $this->QuestionGroup->create();
                        $this->QuestionGroup->saveAll($QuestionGroup);
                        $this->Session->setFlash(__('Question added to bank successfully'), 'flash', array('alert' => 'success'));
                        return $this->redirect(array('action' => 'add'));
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $subjectId = $this->request->data['Question']['subject_id'];
            $topicId = $this->request->data['Question']['topic_id'];
        }
        $this->set('topic', $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('Topic.name' => 'asc'))));
        $this->loadModel('Stopic');
        $stopic = $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $topicId), 'order' => array('Stopic.name' => 'asc')));
        $this->set('stopic', $stopic);
    }

    public function edit($id = null)
    {
        $this->Question->UserWiseGroup($this->userGroupWiseId);
        $this->loadModel('Qtype');
        $this->loadModel('Subject');
        $this->loadModel('Diff');
        $this->loadModel('Group');
        $this->set('qtype_id', $this->Qtype->find('list', array('fields' => array('id', 'question_type'))));
        $this->set('subject_id', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
        $this->set('diff_id', $this->Diff->find('list', array('fields' => array('id', 'diff_level'))));
        $this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $post = array();
        $post = $this->Question->findById($id);
        $subjectId = $post['Question']['subject_id'];
        $topicId = $post['Question']['topic_id'];
        $this->loadModel('Topic');
        $this->set('topic', $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('Topic.name' => 'asc'))));
        $this->loadModel('Stopic');
        $stopic = $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $topicId), 'order' => array('Stopic.name' => 'asc')));
        $this->set('stopic', $stopic);
        $this->set('post', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $isSave = true;
            try {
                if (is_array($this->request->data['Question']['answer']))
                    $this->request->data['Question']['answer'] = implode(",", $this->request->data['Question']['answer']);
                if ($this->request->data['Question']['qtype_id'] == 1 && $this->request->data['Question']['answer'] == null) {
                    $this->Session->setFlash(__('Please Choose the Correct answer'), 'flash', array('alert' => 'danger'));
                } elseif ($this->request->data['Question']['qtype_id'] == 2 && !isset($this->request->data['Question']['true_false'])) {
                    $this->Session->setFlash(__('Please select true or false'), 'flash', array('alert' => 'danger'));
                } elseif ($this->request->data['Question']['qtype_id'] == 3 && $this->request->data['Question']['fill_blank'] == null) {
                    $this->Session->setFlash(__('Please fill blank space'), 'flash', array('alert' => 'danger'));
                } elseif (!is_array($this->request->data['QuestionGroup']['group_name'])) {
                    $this->Session->setFlash(__('Please Select any group'), 'flash', array('alert' => 'danger'));
                } else {
                    if ($this->request->data['Question']['qtype_id'] == 1) {
                        $this->request->data['Question']['true_false'] = null;
                        $this->request->data['Question']['fill_blank'] = null;
                    } elseif ($this->request->data['Question']['qtype_id'] == 2) {
                        $this->request->data['Question']['answer'] = null;
                        $this->request->data['Question']['fill_blank'] = null;
                        $this->request->data['Question']['option1'] = null;
                        $this->request->data['Question']['option2'] = null;
                        $this->request->data['Question']['option3'] = null;
                        $this->request->data['Question']['option4'] = null;
                        $this->request->data['Question']['option5'] = null;
                        $this->request->data['Question']['option6'] = null;
                    } elseif ($this->request->data['Question']['qtype_id'] == 3) {
                        $this->request->data['Question']['answer'] = null;
                        $this->request->data['Question']['true_false'] = null;
                    } else {
                        $this->request->data['Question']['answer'] = null;
                        $this->request->data['Question']['true_false'] = null;
                        $this->request->data['Question']['fill_blank'] = null;
                    }
                    if ($this->Question->save($this->request->data)) {
                        $this->loadModel('QuestionGroup');
                        $questionId = $this->request->data['Question']['id'];
                        $this->QuestionGroup->deleteAll(array('QuestionGroup.question_id' => $questionId, "QuestionGroup.group_id" => $this->userGroupWiseId));
                        foreach ($this->request->data['QuestionGroup']['group_name'] as $groupId) {
                            $QuestionGroup[] = array('question_id' => $questionId, 'group_id' => $groupId);
                        }
                        $this->QuestionGroup->create();
                        $this->QuestionGroup->saveAll($QuestionGroup);
                        $this->Session->setFlash(__('Question has been updated'), 'flash', array('alert' => 'success'));
                        $redirectArr['action'] = 'index';
                        foreach ($this->params['named'] as $k => $value):
                            $redirectArr[$k] = $value;
                        endforeach;
                        unset($k, $value);
                        return $this->redirect($redirectArr);
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = 'tinymce';
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function deleteall()
    {
        if ($this->request->is('post')) {
            try {
                $this->loadModel('QuestionGroup');
                $this->QuestionGroup->begin('QuestionGroup');
                foreach ($this->data['Question']['id'] as $key => $value) {
                    $this->QuestionGroup->deleteAll(array('QuestionGroup.question_id' => $value, 'QuestionGroup.group_id'=>$this->userGroupWiseId));
                }
                $this->Question->query("DELETE `Question` FROM `questions` AS `Question` LEFT JOIN `question_groups` AS `QuestionGroup` ON `Question`.`id` = `QuestionGroup`.`question_id` WHERE `QuestionGroup`.`id` IS NULL");
                $this->QuestionGroup->commit();
                $this->Session->setFlash(__('Question has been deleted'), 'flash', array('alert' => 'success'));
            } catch (Exception $e) {
                $this->QuestionGroup->rollback('QuestionGroup');
                $this->Session->setFlash(__('Delete exam first!'), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
        }
        $this->redirect(array('action' => 'index'));
    }

    public function viewquestion($id = null)
    {
        try {
            $this->layout = null;
            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            $this->set('id', $id);
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function view($id = null)
    {
        try {
            $this->layout = 'script';
            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
            $post = $this->Question->findById($id);
            $this->set('post', $post);
            $this->loadModel('Qtype');
            $this->loadModel('Subject');
            $this->loadModel('Topic');
            $this->loadModel('Stopic');
            $this->loadModel('Diff');
            $this->set('questionTypeArr',$this->Qtype->findById($post['Question']['qtype_id']));
            $this->set('subjectArr',$this->Subject->findById($post['Question']['subject_id']));
            $this->set('topicArr',$this->Topic->findById($post['Question']['topic_id']));
            $this->set('stopicArr',$this->Stopic->findById($post['Question']['stopic_id']));
            $this->set('diffArr',$this->Diff->findById($post['Question']['diff_id']));
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function topic($name = null)
    {
        $this->layout = null;
        $this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        $this->loadModel('Topic');
        $topic = $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $id), 'order' => array('Topic.name' => 'asc')));
        $this->set(compact('topic'));
        if ($name == null) {
            $this->set('name', __('Please Select'));
        } else {
            $this->set('name', $name);
        }
    }

    public function stopic($name = null)
    {
        $this->layout = null;
        $this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        $this->loadModel('Stopic');
        $stopic = $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $id), 'order' => array('Stopic.name' => 'asc')));
        $this->set(compact('stopic'));
        if ($name == null) {
            $this->set('name', __('Please Select'));
        } else {
            $this->set('name', $name);
        }
    }
}
