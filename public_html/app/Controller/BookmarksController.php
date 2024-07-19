<?php

class BookmarksController extends AppController
{
    public $helpers = array('Paginator', 'Html', 'Paginator', 'Js' => array('Jquery'));
    public $components = array('search-master.Prg', 'Paginator', 'RequestHandler');
    public $currentDateTime, $studentId;
    var $paginate = array('page' => 1, 'order' => array('Bookmark.start_time' => 'desc'));

    public function beforeFilter()
    {
        parent::beforeFilter();
        if (!$this->adminValue) {
            if($this->userValue){
				$this->studentId = $this->userValue['Student']['id'];
			}
        }
    }

    public function crm_index($type = "crm")
    {
        if ($type == "crm") {
            $this->authenticate();
        } else {
            $this->layout = 'rest';
            if ($this->authenticateRest($this->request->query)) {
                $this->studentId = $this->restStudentId($this->request->query);
            } else {
                echo __('Invalid Post');
                die();
            }
        }
        $this->set('type', $type);
        $this->set('queryArr', $this->request->query);
        $this->Prg->commonProcess();
        $this->Paginator->settings = $this->paginate;
        $this->Bookmark->virtualFields = array('bookmark_count' => "COUNT(Bookmark.id AND `bookmark` = 'Y')");
        $this->Paginator->settings['fields'] = array('bookmark_count', 'Exam.name', 'Exam.declare_result', 'Bookmark.id');
        $this->Paginator->settings['conditions'] = array('Bookmark.student_id' => $this->studentId, 'Bookmark.user_id >' => 0);
        $this->Paginator->settings['group'] = array('Exam.name', 'Exam.declare_result', 'Bookmark.id','Bookmark.start_time');
        $this->Paginator->settings['limit'] = $this->pageLimit;
        $this->Paginator->settings['maxLimit'] = $this->maxLimit;
        $Bookmark = $this->Paginator->paginate();
        $this->set('Bookmark', $Bookmark);
        if ($this->request->is('ajax')) {
            $this->render('crm_index', 'ajax'); // View, Layout
        }
    }

    public function rest_index()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->studentId = $this->restStudentId($this->request->query);
            $this->Prg->commonProcess();
            $this->Bookmark->virtualFields = array('bookmark_count' => "COUNT(Bookmark.id AND `bookmark` = 'Y')");
            $this->Paginator->settings['fields'] = array('bookmark_count', 'Exam.name', 'Exam.declare_result', 'Bookmark.id');
            $this->Paginator->settings['conditions'] = array('Bookmark.student_id' => $this->studentId, 'Bookmark.user_id >' => 0);
            $this->Paginator->settings['group'] = array('Exam.name', 'Exam.declare_result', 'Bookmark.id','Bookmark.start_time');
            $this->Paginator->settings['limit'] = 99999999999999999;
            $this->Paginator->settings['maxLimit'] = $this->maxLimit;
            $response = $this->Paginator->paginate();
            $status = true;
            $message = __('Bookmark fetch successfully');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $response = (object)array();
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }


    public function crm_bookmarkquestion($id = null, $type = "crm")
    {
        if ($type == "crm") {
            $this->authenticate();
        } else {
            $this->layout = 'rest';
            if ($this->authenticateRest($this->request->query)) {
                $this->studentId = $this->restStudentId($this->request->query);
            } else {
                echo __('Invalid Post');
                die();
            }
        }
        $this->set('type', $type);
        $this->loadModel('Result');
        $declareResult = $this->Result->find('count', array('conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Exam.declare_result' => 'Yes')));
        if ($declareResult == 0) {
            $this->Session->setFlash(__('Invalid Post'), 'flash', array('alert' => 'danger'));
            $this->redirect(array('action' => 'index'));
        }
        $this->loadModel('ExamStat');
        $this->loadModel('ExamQuestion');
        $studentCount = $this->Result->find('count', array('conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId)));
        if ($id == null || $studentCount == 0) {
            $this->redirect(array('action' => 'index'));
        }
        $examDetails = $this->Result->find('first', array('fields' => array('Exam.id', 'Exam.name', 'Exam.type', 'Exam.multi_language', 'User.name', 'Result.id', 'Result.percent', 'Result.obtained_marks', 'Result.total_marks', 'Result.total_question', 'Result.total_attempt', 'Exam.passing_percent', 'Exam.duration', 'Result.result', 'Result.start_time', 'Result.end_time', 'Exam.declare_result', 'Result.total_answered'),
            'joins' => array(array('table' => 'users', 'alias' => 'User', 'type' => 'inner',
                'conditions' => array('Result.user_id=User.id'))),
            'conditions' => array('Result.id' => $id, 'Result.student_id' => $this->studentId, 'Result.user_id >' => 0)));

        $this->set('examDetails', $examDetails);
        $this->set('id', $id);
        $this->set('studentId', $this->studentId);

        $post = $this->Result->userExamQuestion($id, $this->studentId, 'Y');

        $this->set('post', $post);
        $this->loadModel('Language');
        if ($examDetails['Exam']['multi_language'] == 1) {
            $langArr = $this->Language->find('list');
            $this->set('langArr', $langArr);
            $this->set('languageCount', count($langArr));
            $this->set('languageArr', $this->Language->find('all'));
        } else {
            $langArr = $this->Language->find('list', array('conditions' => array('Language.id' => 1)));
            $this->set('langArr', $langArr);
            $this->set('languageCount', 1);
            $this->set('languageArr', $this->Language->find('list', array('conditions' => array('Language.id' => 1))));

            $langArr1 = $this->Language->find('list');
            $this->set('fullLanguageCount', count($langArr1));
        }
    }
}
