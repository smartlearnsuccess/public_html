<?php

class TopicsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Subject.subject_name' => 'asc','Topic.name' => 'asc'));

    public function index()
    {
        try {
            $this->Prg->commonProcess();
            $this->Paginator->settings = $this->paginate;
            $this->Paginator->settings['joins'] = array(array('table' => 'subject_groups', 'type' => 'INNER', 'alias' => 'SubjectGroup', 'conditions' => array('Subject.id=SubjectGroup.subject_id')));
            $this->Paginator->settings['fields'] = array('Subject.id', 'Subject.subject_name', 'Topic.id', 'Topic.name');
            $this->Paginator->settings['group'] = array('Subject.id', 'Subject.subject_name', 'Topic.id', 'Topic.name');
            $cond = array('SubjectGroup.group_id' => $this->userGroupWiseId);
            $this->Paginator->settings['conditions'] = array($this->Topic->parseCriteria($this->Prg->parsedParams()),$cond);
            $this->Paginator->settings['limit'] = $this->pageLimit;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $this->set('Topic', $this->Paginator->paginate());
            if ($this->request->is('ajax')) {
                $this->render('index', 'ajax'); // View, Layout
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function add()
    {
        $subject = $this->CustomFunction->getSubjectList($this->userGroupWiseId);
        $this->set('subject', $subject);
        if ($this->request->is('post')) {
            $this->Topic->create();
            try {
                if ($this->Topic->save($this->request->data)) {
                    $this->Session->setFlash(__('Topic Added Successfully'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'add'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function edit($id = null)
    {
        $this->loadModel('Subject');
        $subject = $this->CustomFunction->getSubjectList($this->userGroupWiseId);
        $this->set('subject', $subject);

        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $ids = explode(",", $id);
        $post = array();
        foreach ($ids as $id) {
            $post[] = $this->Topic->findById($id);
        }
        $this->set('Topic', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            try {
                if ($this->Topic->saveAll($this->request->data)) {
                    $this->Session->setFlash(__('Topic has been saved'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->set('isError', true);
        } else {
            $this->layout = null;
            $this->set('isError', false);
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function deleteall()
    {
        try {
            if ($this->request->is('post')) {
                foreach ($this->data['Topic']['id'] as $key => $value) {
                    $this->Topic->delete($value);
                }
                $this->Session->setFlash(__('Topic has been deleted'), 'flash', array('alert' => 'success'));
            }
            $this->redirect(array('action' => 'index'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Delete sub topic first'), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }
}
