<?php

class AddquestionsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Addquestion.id' => 'desc'));

    public function index($id = null)
    {
        try {
            $subjectId = null;
            $topicId = null;
            $this->loadModel('Topic');
            $this->loadModel('Stopic');
            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('controller' => 'Exams', 'action' => 'index'));
            }
            $this->loadModel('Exam');
            $post = $this->Exam->findById($id);
            if (!$post) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            }
            $this->loadModel('ExamGroup');
            $examGroupArr = $this->ExamGroup->find('list', array(
                'fields' => array('ExamGroup.group_id', 'ExamGroup.group_id'),
                'conditions' => array('ExamGroup.exam_id' => $id)));

            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['joins'] = array(array('table' => 'question_groups', 'type' => 'INNER', 'alias' => 'QuestionGroup', 'conditions' => array('Addquestion.id=QuestionGroup.question_id')));
            $this->Paginator->settings['fields'] = array('Addquestion.id', 'Addquestion.question', 'Addquestion.subject_id', 'Addquestion.topic_id', 'Addquestion.stopic_id', 'Addquestion.qtype_id', 'Addquestion.diff_id', 'Addquestion.marks');
            $this->Paginator->settings['group'] = array('Addquestion.id', 'Addquestion.question', 'Addquestion.subject_id', 'Addquestion.topic_id', 'Addquestion.stopic_id', 'Addquestion.qtype_id', 'Addquestion.diff_id', 'Addquestion.marks');
            $cond = array('QuestionGroup.group_id' => $examGroupArr);
            $this->Paginator->settings['conditions'] = array($this->Addquestion->parseCriteria($this->Prg->parsedParams()), $cond);
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $this->set('Addquestion', $this->Paginator->paginate());
            $this->loadModel('ExamQuestion');
            $this->loadModel('Subject');
            $this->loadModel('Qtype');
            $this->loadModel('Diff');
            $ExamQuestion = $this->ExamQuestion->findAllByExamId($id, array(), array('ExamQuestion.question_id' => 'desc'));

            $this->set('subjectId', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
            $this->set('subjectArr', $this->Subject->find('list', array('fields' => array('Subject.id', 'Subject.subject_name'))));
            $this->set('topicArr', $this->Topic->find('list'));
            $this->set('stopicArr', $this->Stopic->find('list'));
            $this->set('qtypeId', $this->Qtype->find('list', array('fields' => array('id', 'question_type'))));
            $this->set('diffId', $this->Diff->find('list', array('fields' => array('id', 'diff_level'))));

            $this->set('ExamQuestion', $ExamQuestion);
            $this->set('exam_id', $id);
            if (isset($this->request->data['Addquestion']['subject_id'])) {
                $subjectId = $this->request->data['Addquestion']['subject_id'];
                $topicId = $this->request->data['Addquestion']['topic_ids'];
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

    public function viewQuestion($id)
    {
        try {
            $subjectId = null;
            $topicId = null;
            $this->loadModel('Topic');
            $this->loadModel('Stopic');

            if (!$id) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('controller' => 'Exams', 'action' => 'index'));
            }
            $this->loadModel('Exam');
            $post = $this->Exam->findById($id);
            if (!$post) {
                $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            }

            $this->Prg->commonProcess();
            $postArr=$this->Addquestion->find('all', array(
                'joins' => array(array('table' => 'exam_questions', 'type' => 'INNER', 'alias' => 'ExamQuestion', 'conditions' => array('Addquestion.id=ExamQuestion.question_id'))),
                'fields'=>array('Addquestion.id', 'Addquestion.question', 'Addquestion.subject_id', 'Addquestion.topic_id', 'Addquestion.stopic_id', 'Addquestion.qtype_id', 'Addquestion.diff_id', 'Addquestion.marks'),
                'conditions' => array('ExamQuestion.exam_id' => $id,
                    $this->Addquestion->parseCriteria($this->Prg->parsedParams()))
            ));


            $this->set('Addquestion', $postArr);
            $this->loadModel('Subject');
            $this->loadModel('Qtype');
            $this->loadModel('Diff');
            $this->set('subjectId', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
            $this->set('subjectArr', $this->Subject->find('list', array('fields' => array('Subject.id', 'Subject.subject_name'))));
            $this->set('topicArr', $this->Topic->find('list'));
            $this->set('stopicArr', $this->Stopic->find('list'));
            $this->set('qtypeId', $this->Qtype->find('list', array('fields' => array('id', 'question_type'))));
            $this->set('diffId', $this->Diff->find('list', array('fields' => array('id', 'diff_level'))));

            $this->set('exam_id', $id);
            if (isset($this->request->data['Addquestion']['subject_id'])) {
                $subjectId = $this->request->data['Addquestion']['subject_id'];
                $topicId = $this->request->data['Addquestion']['topic_ids'];
            }
            $this->set('topic', $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('name' => 'asc'))));
            $this->set('stopic', $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $topicId), 'order' => array('name' => 'asc'))));
            if ($this->request->is('ajax')) {
                $this->render('view_question', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function adddelete()
    {
        try {
            if ($this->request->is('post')) {
                $exam_id = $this->request->data['Addquestion']['exam_id'];
                if ($this->data['action'] == "delete") {
                    if ($this->request->is('post')) {
                        $this->loadModel('ExamQuestion');
                        foreach ($this->data['Addquestion']['id'] as $key => $value) {
                            if ($value > 0) {
                                $this->ExamQuestion->create();
                                $this->request->data['ExamQuestion']['exam_id'] = $exam_id;
                                $this->request->data['ExamQuestion']['question_id'] = $value;
                                $eq = $this->ExamQuestion->findByQuestionIdAndExamId($value, $exam_id);
                                if ($eq) {
                                    $eq_id = $eq['ExamQuestion']['id'];
                                    $this->ExamQuestion->delete($eq_id);
                                }
                                $this->ExamQuestion->save($this->request->data['ExamQuestion']);
                            }
                        }
                        $this->Session->setFlash(__('Your Question has been added for exam'), 'flash', array('alert' => 'success'));

                    }
                }
                if ($this->data['action'] == "add") {
                    if ($this->request->is('post')) {
                        $this->loadModel('ExamQuestion');
                        foreach ($this->data['Addquestion']['id'] as $key => $value) {
                            if ($value > 0) {
                                $eq = $this->ExamQuestion->findByQuestionIdAndExamId($value, $exam_id);
                                if ($eq) {
                                    $eq_id = $eq['ExamQuestion']['id'];
                                    $this->ExamQuestion->delete($eq_id);
                                }
                            }
                        }
                        $this->Session->setFlash(__('Your Question has been deleted for exam'), 'flash', array('alert' => 'danger'));
                    }
                }
            }
            $url_var = $exam_id;
            if (isset($this->request->data['Addquestion']['limit']))
                $url_var .= "/limit:" . $this->request->data['Addquestion']['limit'];
            if (isset($this->request->data['Addquestion']['page']))
                $url_var .= "/page:" . $this->request->data['Addquestion']['page'];
            if (isset($this->request->data['Addquestion']['keyword']))
                $url_var .= "/keyword:" . $this->request->data['Addquestion']['keyword'];
            if (isset($this->request->data['Addquestion']['subject_id']))
                $url_var .= "/subject_id:" . $this->request->data['Addquestion']['subject_id'];
            if (isset($this->request->data['Addquestion']['topic_ids']))
                $url_var .= "/topic_ids:" . $this->request->data['Addquestion']['topic_ids'];
            if (isset($this->request->data['Addquestion']['stopic_ids']))
                $url_var .= "/stopic_ids:" . $this->request->data['Addquestion']['stopic_ids'];
            if (isset($this->request->data['Addquestion']['qtype_id']))
                $url_var .= "/qtype_id:" . $this->request->data['Addquestion']['qtype_id'];
            if (isset($this->request->data['Addquestion']['diff_id']))
                $url_var .= "/diff_id:" . $this->request->data['Addquestion']['diff_id'];
            return $this->redirect(array('action' => "index/$url_var"));
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function adddeleteajax()
    {
        $this->autoRender = false;
        $this->request->onlyAllow('ajax');
        try {
            if ($this->request->is('post')) {
                $exam_id = $this->request->data['Addquestion']['exam_id'];
                if ($this->data['action'] == "add") {
                    if ($this->request->is('post')) {
                        $questionId = null;
                        $this->loadModel('ExamQuestion');
                        foreach ($this->data['Addquestion']['id'] as $key => $value) {
                            if ($value > 0) {
                                $this->ExamQuestion->create();
                                $this->request->data['ExamQuestion']['exam_id'] = $exam_id;
                                $this->request->data['ExamQuestion']['question_id'] = $value;
                                $eq = $this->ExamQuestion->findByQuestionIdAndExamId($value, $exam_id);
                                if ($eq) {
                                    $eq_id = $eq['ExamQuestion']['id'];
                                    $this->ExamQuestion->delete($eq_id);
                                }
                                $questionId = $value;
                                $this->ExamQuestion->save($this->request->data['ExamQuestion']);
                            }
                        }
                        echo '<button onclick="changeStatus(\'delete\',\'' . $questionId . '\');" type="button" class="btn btn-block btn-danger"><span class="fa fa-trash"></span>&nbsp;Delete To Exam</button>';
                    }
                }
                if ($this->data['action'] == "delete") {
                    if ($this->request->is('post')) {
                        $questionId = null;
                        $this->loadModel('ExamQuestion');
                        foreach ($this->data['Addquestion']['id'] as $key => $value) {
                            if ($value > 0) {
                                $questionId = $value;
                                $eq = $this->ExamQuestion->findByQuestionIdAndExamId($value, $exam_id);
                                if ($eq) {
                                    $eq_id = $eq['ExamQuestion']['id'];
                                    $this->ExamQuestion->delete($eq_id);
                                }
                            }
                        }
                        echo '<button onclick="changeStatus(\'add\',\'' . $questionId . '\');" type="button" class="btn btn-block btn-success"><span class="fa fa-plus-circle"></span>&nbsp;Add To Exam</button>';
                    }
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
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
