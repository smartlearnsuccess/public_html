<?php

class StopicsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('Session', 'Paginator', 'search-master.Prg');
    public $presetVars = true;
    var $paginate = array('page' => 1, 'order' => array('Stopic.name' => 'asc'));

    public function index()
    {
		try {
			$this->Prg->commonProcess();
			$this->Paginator->settings = $this->paginate;
			$this->Paginator->settings['joins'] = array(array('table' => 'subject_groups', 'type' => 'INNER', 'alias' => 'SubjectGroup', 'conditions' => array('Subject.id=SubjectGroup.subject_id')));
			$this->Paginator->settings['fields'] = array('Stopic.id','Stopic.name', 'Subject.subject_name', 'Topic.name');
			$this->Paginator->settings['group'] = array('Stopic.id','Stopic.name', 'Subject.subject_name', 'Topic.name');
			$cond = array('SubjectGroup.group_id' => $this->userGroupWiseId);
			$this->Paginator->settings['conditions'] = array($this->Stopic->parseCriteria($this->Prg->parsedParams()),$cond);
			$this->Paginator->settings['limit'] = $this->pageLimit;
			$this->Paginator->settings['maxLimit'] = $this->maxLimit;
			$this->set('Stopic', $this->Paginator->paginate());
			if ($this->request->is('ajax')) {
				$this->render('index', 'ajax'); // View, Layout
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
		}
    }

    public function add()
    {
        $this->loadModel('Subject');
        $subject = $this->CustomFunction->getSubjectList($this->userGroupWiseId);
        $this->set('subject', $subject);
        $subjectId = null;
        if ($this->request->is('post')) {
            $this->Stopic->create();
            try {
                if ($this->Stopic->save($this->request->data)) {
                    $this->Session->setFlash(__('Sub Topic Added Successfully'), 'flash', array('alert' => 'success'));
                    return $this->redirect(array('action' => 'add'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'index'));
            }
            $subjectId = $this->request->data['Stopic']['subject_id'];
        }
        $this->loadModel('Topic');
        $topic = $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('name' => 'asc')));
        $this->set('topic', $topic);

    }

    public function edit($id = null)
    {
        $this->loadModel('Subject');
        $subject = $this->Subject->find('list', array('fields' => array('id', 'subject_name'), 'order' => array('subject_name' => 'asc')));
        $this->set('subject', $subject);
        $this->loadModel('Topic');
        $topic = $this->Topic->find('list', array('order' => array('name' => 'asc')));
        $this->set('topic', $topic);

        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $ids = explode(",", $id);
        $post = array();
        foreach ($ids as $k => $id) {
            $k++;
            $post[$k] = $this->Stopic->findById($id);
            $topic = $this->Topic->find('list', array('order' => array('name' => 'asc'),
                'conditions' => array('Topic.subject_id' => $post[$k]['Stopic']['subject_id'])));
            $this->set("topic$k", $topic);
        }
        $this->set('Stopic', $post);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            try {
                if ($this->Stopic->saveAll($this->request->data)) {
                    $this->Session->setFlash(__('Sub Topic has been saved'), 'flash', array('alert' => 'success'));
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
                foreach ($this->data['Stopic']['id'] as $key => $value) {
                    $this->Stopic->delete($value);
                }
                $this->Session->setFlash(__('Sub Topic has been deleted'), 'flash', array('alert' => 'success'));
            }
            $this->redirect(array('action' => 'index'));
        } catch (Exception $e) {
            $this->Session->setFlash(__('Delete exam first'), 'flash', array('alert' => 'danger'));
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
}
