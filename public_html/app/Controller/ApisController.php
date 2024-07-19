<?php
App::uses('CakeEmail', 'Network/Email');

class ApisController extends AppController
{
    public $components = array('CustomFunction', 'RequestHandler');

    public function index()
    {


    }

    public function rest_home()
    {
        $this->loadModel('Slide');
        $this->loadModel('Testimonial');
        $this->loadModel('Content');
        $response = $this->Slide->find('all', array('conditions' => array('status' => 'Active'), 'order' => array('ordering' => 'ASC')));
        $Testimonials = $this->Testimonial->find('all', array('conditions' => array('Testimonial.status' => 'Active')));
        $slides=array();
        foreach ($response as $value) {
			$slides_photo = $this->siteDomain . '/img/slides_thumb/' . $value['Slide']['photo'];
			$slides[] = array('slides_photo' => $slides_photo);
		}

        $Tml_sn = "";
        foreach ($Testimonials as $Tml) {
            $Tml_sn++;
            $name = $Tml['Testimonial']['name'];
            $Rating = $Tml['Testimonial']['rating'];
            $Comments = $Tml['Testimonial']['description'];
            if (empty($Tml['Testimonial']['photo'])) {
                $Image = $this->siteDomain . '/img/student_thumb/user.png';

            } else {
                $Image = $this->siteDomain . '/img/testimonial_thumb/' . $Tml['Testimonial']['photo'];
            }

            $Testimonial[] = array('name' => $name, 'image' => $Image, 'rating' => $Rating, 'comments' => $Comments);
            unset($Image);
        }
        $home['home_data'] = $slides;
        $home['testimonials'] = $Testimonial;
        $status = true;
        $message = __('Slide data fetch successfully.');
        $this->set(compact('status', 'message', 'home'));
        $this->set('_serialize', array('status', 'message', 'home'));

    }

    public function rest_studentGroups()
    {
        if ($this->authenticateRest($this->request->query)) {
            $this->loadModel('StudentGroup');
            $this->studentId = $this->restStudentId($this->request->query);
            $studentid = $this->studentId;
            $groupSelect = $this->StudentGroup->find('all', array('fields' => array('Groups.group_name'),
                'joins' => array(array('table' => 'groups', 'type' => 'Inner', 'alias' => 'Groups',
                    'conditions' => array('StudentGroup.group_id=Groups.id', "student_id=$studentid")))));
            foreach ($groupSelect as $groupValue) {
                $gname = $groupValue['Groups']['group_name'];
                $usergroupSelect[] = array('group_name' => $gname);
            }
            unset($groupValue);
            $response = $usergroupSelect;
            $status = true;
            $message = __('Group fetch successfully');
        } else {
            $status = false;
            $message = ('Invalid Token');
            $response = (object)array();
        }
        $this->set(compact('status', 'message', 'response'));
        $this->set('_serialize', array('status', 'message', 'response'));
    }

}
