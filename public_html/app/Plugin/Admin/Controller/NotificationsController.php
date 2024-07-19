<?php
define('FIREBASE_API_KEY', 'AIzaSyAx0hySjbhcjqUztKrPkGuqg5G5KAcJvEc');

class Push {

    // push message title
    private $title;
    private $message;
    private $image;
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct() {

    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setImage($imageUrl) {
        $this->image = $imageUrl;
    }

    public function setPayload($data) {
        $this->data = $data;
    }

    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }

    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['message'] = $this->message;
        $res['data']['image'] = $this->image;
        $res['data']['payload'] = $this->data;
        $res['data']['timestamp'] = date('d-m-Y h:i:s A');
        $res['data']['click_action'] = "MainActivity";
        return $res;
    }

}

class NotificationsController extends AdminAppController
{
    public function index()
    {
        try {
            if ($this->request->is('post')) {
                $push = new Push();

                $title = $this->request->data['Notification']['title'];
                $message = $this->request->data['Notification']['message'];
                $img = $this->siteDomain.'/img/'.$this->sysSetting['Configuration']['photo'];

                $push->setTitle($title);
                $push->setMessage($message);
                $push->setImage($img);
                $push->setIsBackground(FALSE);

                $json = '';
                $response = '';
                $json = $push->getPush();
                $response = $this->sendToTopic('global', $json);
                $this->Session->setFlash($response, 'flash', array('alert' => 'success'));
                return $this->redirect(array('action' => 'index'));
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    // sending push message to single user by firebase reg id
    private function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    private function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    private function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {

          // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}
