<?php

class QuestionsPassagesController extends AdminAppController
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
                $languageCondition = array('QuestionsPassage.language_id' => $languageId);
            }
            $post = $this->QuestionsPassage->find('first', array('conditions' => array('QuestionsPassage.question_id' => $questionId, $languageCondition),
                'order' => array('QuestionsPassage.language_id' => 'asc')));
            $this->set('questionId', $questionId);
            $this->set('language', $this->QuestionsPassage->Language->find('list'));
            if ($this->request->is('post')) {
                if ($post) {
                    $this->QuestionsPassage->save($this->request->data);
                    $this->Session->setFlash(__('Passage updated successfully'), 'flash', array('alert' => 'success'));
                } else {
                    $this->QuestionsPassage->create();
                    $this->QuestionsPassage->save($this->request->data);
                    $this->Session->setFlash(__('Passage added successfully'), 'flash', array('alert' => 'success'));
                }
            }
            $post = $this->QuestionsPassage->find('first', array('conditions' => array('QuestionsPassage.question_id' => $questionId, $languageCondition),
                'order' => array('QuestionsPassage.language_id' => 'asc')));
            $this->set('post', $post);
            if (!$this->request->data) {
                $this->request->data = $post;
                if (!$post) {
                    $this->request->data['QuestionsPassage']['language_id'] = $languageId;
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('controller' => 'Questions', 'action' => 'index'));
        }

    }
}
