<?php

class QuestionsLangsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    public $presetVars = true;

    public function index($questionId, $languageId = null)
    {
        try {
            if ($languageId == null) {
                $languageCondition = array();
            } else {
                $languageCondition = array('QuestionsLang.language_id' => $languageId);
            }
            $this->loadModel('Question');
            $post = $this->QuestionsLang->find('first', array('conditions' => array('QuestionsLang.question_id' => $questionId, $languageCondition),
                'order' => array('QuestionsLang.language_id' => 'asc')));
            $questionPost = $this->Question->find('first', array('fields' => array('Qtype.question_type', 'Question.qtype_id'),
                'joins' => array(array('table' => 'qtypes', 'alias' => 'Qtype', 'type' => 'INNER', 'conditions' => array('Question.qtype_id=Qtype.id'))),
                'conditions' => array('Question.id' => $questionId)));
            $this->set('questionPost', $questionPost);
            $this->set('questionId', $questionId);
            $this->set('language', $this->QuestionsLang->Language->find('list', array('conditions' => array('Language.id !=' => 1))));
            if ($this->request->is('post')) {
                if ($post) {
                	$this->request->data['QuestionsLang']['id'] = $post['QuestionsLang']['id'];
                    $this->QuestionsLang->save($this->request->data);
                    $this->Session->setFlash(__('Question updated successfully'), 'flash', array('alert' => 'success'));
                } else {
                    $this->QuestionsLang->create();
                    $this->QuestionsLang->save($this->request->data);
                    $this->Session->setFlash(__('Question added successfully'), 'flash', array('alert' => 'success'));
                }
            }
            $post = $this->QuestionsLang->find('first', array('conditions' => array('QuestionsLang.question_id' => $questionId, $languageCondition),
                'order' => array('QuestionsLang.language_id' => 'asc')));
            $this->set('post', $post);
            if (!$this->request->data) {
                $this->request->data = $post;
                if (!$post) {
                    $this->request->data['QuestionsLang']['language_id'] = $languageId;
                }
            }
            $this->set('languageId',$languageId);
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('controller' => 'Questions', 'action' => 'index'));
        }

    }
}
