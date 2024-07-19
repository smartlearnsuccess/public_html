<?php

class SchedulesController extends AppController
{
    public $helpers = array('Paginator', 'Js' => array('Jquery'));
    public $components = array('Paginator', 'RequestHandler');
    var $paginate = array('page' => 1, 'order' => array('Package.id' => 'desc'));


    public function crm_index()
    {
        return $this->redirect(array('crm' => false, 'controller' => 'Schedules', 'action' => 'index'));
    }

    public function index()
    {
        $this->loadModel('Package');
        $this->loadModel('Exam');
        $this->loadModel('ExamsPackage');

        $this->set('Exams', $this->Exam->find('all', array('conditions' => array('Exam.status' => 'Active'),'order'=>array('Exam.start_date'=>'asc'))));


        $this->set('Package_id', $this->Package->find('list', array('fields' => array('id', 'name'), 'conditions' => array('Package.status' => 'Active'))));
        $Packages = $this->Package->find('first', array('conditions' => array('Package.status' => 'Active')));
        $Examscount = $this->ExamsPackage->find('count', array('conditions' => array('ExamsPackage.package_id' => $Packages['Package']['id'])));
        $this->set('Packages', $Packages);
        $this->set('Examscount', $Examscount);

    }

    public function exam_by_package_id($id = null)
    {
        $this->loadModel('ExamsPackage');
        $this->loadModel('Exam');
        $this->loadModel('Package');
        if ($_POST['package_id'] != '') {
            $id = $_POST['package_id'];
        }
        $this->set('Package_id', $this->Package->find('list', array('fields' => array('id', 'name'), 'conditions' => array('Package.status' => 'Active'))));

        $ExamsPackages = $this->ExamsPackage->find('all', array('conditions' => array('ExamsPackage.package_id' => $id)));
        foreach ($ExamsPackages as $key => $ExamsPackage) {
            $exam_id[] = $ExamsPackage['ExamsPackage']['exam_id'];
        }
        $Exams = $this->Exam->find('all', array('conditions' => array('Exam.status' => 'Active', 'Exam.id' => $exam_id),'order'=>array('Exam.start_date'=>'asc')));
        $this->set('Exams', $Exams);

    }


    public function get_package_by_id($id = null)
    {
        $this->autoRender = false;
        $this->loadModel('Package');
        $this->loadModel('ExamsPackage');
        $Packages = $this->Package->find('all', array('conditions' => array('Package.id' => $_POST['package_id'])));
        $Examscount = $this->ExamsPackage->find('count', array('conditions' => array('ExamsPackage.package_id' => $_POST['package_id'])));
        $Packagedetails = '<p><b>Package :</b> <span id="pac_name">' . $Packages[0]['Package']['name'] . '</span></p>
        <p><b>Cost :</b> <span id="pac_amount">' . $Packages[0]['Package']['amount'] . '</span></p>
        <p><b>Total Tests :</b> <span id="pac_tests">' . $Examscount . '</span></p>';
        echo $Packages[0]['Package']['name'] . ',|' . $Packages[0]['Package']['amount'] . ',|' . $Examscount . ',|' . $Packages[0]['Package']['description'];

    }

    public function view($id)
    {
        $this->layout = null;
        $this->set('post', $this->Package->findByIdAndStatus($id, 'Active'));
    }


}