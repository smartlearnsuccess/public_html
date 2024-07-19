<?php
App::uses('HttpSocket', 'Network/Http');

class Checkout extends AppModel
{
    public $useTable = false;

    public function cartProduct($carts)
    {
        $products = array();
        $record_arr = array();
        if (null != $carts) {
            $Package = ClassRegistry::init('Package');
            foreach ($carts as $productId => $count) {
                $product = $Package->findById($productId);
                $record_arr[] = array('name' => $product['Package']['name'], 'tax' => 0.00, 'shipping' => 0.00, 'description' => '',
                    'quantity' => $count, 'amount' => $product['Package']['amount'], 'subtotal' => $count * $product['Package']['amount']);
            }
        }
        return $record_arr;
    }

    function packageExamOrder($paymentArr)
    {
        $ExamOrder = ClassRegistry::init('ExamOrder');
        $packageDetail = null;
        foreach ($paymentArr['Package'] as $post) {
            $expiryDays = $post['expiry_days'];
            if ($expiryDays == 0) {
                $expiryDays = __('Unlimited');
            }
            if (is_array($post['Exam'])) {
                $examOrder = array();
                foreach ($post['Exam'] as $item) {
					if($post['package_type']=="P") {
						$this->setStudentGroup($item['id'], $paymentArr['Payment']['student_id']);
					}
                    $examOrder[] = array(
                        'ExamOrder' => array(
                            'student_id' => $paymentArr['Payment']['student_id'],
                            'exam_id' => $item['id'],
                            'payment_id' => $paymentArr['Payment']['id'],
                            'date' => $post['PackagesPayment']['date'],
                            'expiry_date' => $post['PackagesPayment']['expiry_date']
                        ),
                    );
                }
            }
            $packageDetail .= "<strong>" . __('Package') . " :  " . $post['name'] . "</strong><br>
                    " . __('Cost') . " :  " . $post['PackagesPayment']['amount'] . "<br>
                    " . __('Package Purchase Date') . " :  " . $post['PackagesPayment']['date'] . "<br>
                    " . __('Package Valid Upto') . " :  " . $expiryDays . " " . __('Days') . "<br><br><br>";
            if ($examOrder) {
                $ExamOrder->create();
                $ExamOrder->saveAll($examOrder);
            }
        }
        return $packageDetail;
    }

    private function setStudentGroup($examId, $studentId)
    {
        $ExamGroup = ClassRegistry::init('ExamGroup');
        $StudentGroup = ClassRegistry::init('StudentGroup');
        $examGroupArr = $ExamGroup->findAllByExamId($examId);
        $studentSaveArr = array();
        if ($examGroupArr) {
            foreach ($examGroupArr as $item) {
                $groupId = $item['ExamGroup']['group_id'];
                $studentGroupArr = $StudentGroup->findByStudentIdAndGroupId($studentId, $groupId);
                if (!$studentGroupArr) {
                    $studentSaveArr[] = array(
                        'StudentGroup' => array(
                            'student_id' => $studentId,
                            'group_id' => $groupId
                        ),
                    );
                }
            }
            if ($studentSaveArr) {
                $StudentGroup->create();
                $StudentGroup->saveAll($studentSaveArr);
            }
        }
    }

     /**
     * Function to encrypt
     * @param $plainText string
     * @param $key string
     * @return string
     */
    public function encrypt($plainText, $key){
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
   }

    /**
     * Function to decrypt
     * @param $encryptedText string
     * @param $key
     * @return string
     */
    public function decrypt($encryptedText, $key){
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    //*********** Padding Function *********************

    function pkcs5_pad($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

    //********** Hexadecimal to Binary function for php 4.0 version ********

    function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }

    public function ccAvenuePaymentStatus($studentId, $accessCode, $workingKey)
    {
        $Payment = ClassRegistry::init('Payment');
        $paymentArr = $Payment->find('first', array(
                'conditions' => array(
                    'student_id' => $studentId,
                    'status' => 'Pending',
                    'type' => 'CAE',
                    'transaction_id IS NULL'),
                'recursive' => 2)
        );
        if ($paymentArr) {
            $this->HttpSocket = new HttpSocket();
            $token = $paymentArr['Payment']['token'];
            $enc_request = $this->encrypt("|" . $token . "|", $workingKey);
            $url = "https://api.ccavenue.com/apis/servlet/DoWebTrans";
            $query = array("enc_request" => $enc_request, "access_code" => $accessCode, "command" => "orderStatusTracker", "request_type" => "STRING", "response_type" => "JSON");
            $response = $this->HttpSocket->post($url, $query);
            if ($response && $response['body']) {
                $responseArr = explode("&", $response['body']);
                if ($responseArr[0] == "status=0") {
                    $encResponse = str_replace("enc_response=", "", $responseArr[1]);
                    $encodeString = $this->decrypt($encResponse, $workingKey);
                    $encodeStringArr = explode("}}", $encodeString);
                    $jsonValue = $encodeStringArr[0] . '}}';
                    $decryptValues = json_decode($jsonValue, TRUE);
                    $transactionId = $decryptValues['Order_Status_Result']['reference_no'];
                    $order_status = $decryptValues['Order_Status_Result']['order_status'];
                    if ($order_status == "Successful") {
                        $this->packageExamOrder($paymentArr);
                        $Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Approved'));
						$this->loadModel('PackagesPayment');
						$this->PackagesPayment->updateAll(array('status'=>"'Approved'"),array('payment_id'=>$paymentArr['Payment']['id']));
                    } else {
                        $Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Cancelled'));
                    }
                }
            }
        }
    }

    public function payumoneyPaymentStatus($studentId, $merchantKey, $authorization, $url)
    {
        $Payment = ClassRegistry::init('Payment');
        $paymentArr = $Payment->find('first', array(
                'conditions' => array(
                    'student_id' => $studentId,
                    'status' => 'Pending',
                    'type' => 'PME',
                    'transaction_id IS NULL'),
                'recursive' => 2)
        );
        if ($paymentArr) {
            $this->HttpSocket = new HttpSocket();
            $token = $paymentArr['Payment']['token'];
            $url = $url .= '?merchantTransactionIds=' . $token . '&merchantKey=' . $merchantKey;
            $response = $this->HttpSocket->post($url, null, array('header' => array('Content-Type' => 'application/json', 'Authorization' => $authorization)));
            if ($response && $response['body']) {
                $responseArr = json_decode($response['body'], TRUE);
                if ($responseArr['status'] == "0") {
                    $transactionId = $responseArr['result'][0]['merchantTransactionId'];
                    $order_status = $responseArr['result'][0]['status'];
                    if ($order_status == "Money with Payumoney") {
                        $this->packageExamOrder($paymentArr);
                        $Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Approved'));
						$this->loadModel('PackagesPayment');
						$this->PackagesPayment->updateAll(array('status'=>"'Approved'"),array('payment_id'=>$paymentArr['Payment']['id']));
                    } else {
                        $Payment->save(array('id' => $paymentArr['Payment']['id'], 'transaction_id' => $transactionId, 'status' => 'Cancelled'));
                    }
                } else {
                    $Payment->save(array('id' => $paymentArr['Payment']['id'], 'status' => 'Cancelled'));
                }
            }
        }
    }
}
