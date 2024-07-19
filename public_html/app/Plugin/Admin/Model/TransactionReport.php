<?php

class TransactionReport extends AppModel
{
    public $useTable = "payments";
    public $belongsTo = array('Student');
    public $hasAndBelongsToMany = array('Package');
    public $actsAs = array('search-master.Searchable');
    public $filterArgs = array(
        'status' => array('type' => 'query', 'method' => 'orStatusConditions'),
        'payment_mode' => array('type' => 'query', 'method' => 'orPaymentModeConditions'),
        'keyword' => array('type' => 'like', 'field' => array('Student.name', 'Student.email', 'Student.phone')),
        'token' => array('field' => 'TransactionReport.token'),
        'transaction_id' => array('field' => 'TransactionReport.transaction_id'),
        'cart_amount' => array('field' => 'TransactionReport.cart_amount'),
        'transaction_amount' => array('field' => 'TransactionReport.amount'),
        'start_date' => array('type' => 'expression', 'method' => 'UsedDate', 'field' => 'TransactionReport.date BETWEEN ? AND ?'),
        'end_date' => array('type' => 'query', 'method' => 'CreationDateRangeCondition'),
    );

    public function orStatusConditions($data = array())
    {
        $filter = $data['status'];
        $cond = array('TransactionReport.status' => $filter);
        return $cond;
    }

    public function orPaymentModeConditions($data = array())
    {
        $filter = $data['payment_mode'];
        $cond = array('TransactionReport.type' => $filter);
        return $cond;
    }

    public function UsedDate($data = array())
    {
        return array(CakeTime::format('Y-m-d', $data['start_date']) . ' 00:00:00', CakeTime::format('Y-m-d', $data['end_date']) . ' 23:59:59');
    }


    function showReportData($postArr, $dtmFormat)
    {
        $showData = array(array(
            __('Sr. No.'),
            __('Transaction Status'),
            __('Payment Mode'),
            __('User Name'),
            __('User Email'),
            __('User Phone No.'),
            __('Transaction ID'),
            __('Packages Purchased'),
            __('Payment Gateway Transaction ID'),
            __('Cart Amount'),
            __('Transaction Amount'),
            __('Transaction Date and Time')
        ));
        $totalCartAmount = 0;
        $totalTransactionAmount = 0;
        foreach ($postArr as $k => $post) {
            $serialNo = $k + 1;
            if ($post['TransactionReport']['status'] == "Approved") {
                $transactionStatus = __("Success");
            } elseif ($post['TransactionReport']['status'] == "Pending") {
                $transactionStatus = __("Pending");
            } else {
                $transactionStatus = __("Failed");
            }
            $cartAmount = $post['TransactionReport']['cart_amount'];
            $transactionAmount = $post['TransactionReport']['amount'];
            $totalCartAmount = $totalCartAmount + $cartAmount;
            $totalTransactionAmount = $totalTransactionAmount + $transactionAmount;
            $showData[] = array(
                $serialNo,
                $transactionStatus,
                h(strtoupper($post['TransactionReport']['name'])),
                h($post['Student']['name']),
                h($post['Student']['email']),
                h($post['Student']['phone']),
                $post['TransactionReport']['token'],
                $this->showPackageName($post['Package']),
                $post['TransactionReport']['transaction_id'],
                $cartAmount,
                $transactionAmount,
                CakeTime::format($dtmFormat, $post['TransactionReport']['date'])
            );
        }
        $showData[] = array(null, null, null, null, null, null, null, null, __('Total'), $totalCartAmount, $totalTransactionAmount, null);
        return $showData;
    }

    public function showPackageName($groupArr, $string = " | ")
    {
        $groupNameArr = array();
        foreach ($groupArr as $groupName) {
            $groupNameArr[] = $groupName['name'];
        }
        unset($groupName);
        $showGroup = implode($string, $groupNameArr);
        unset($groupNameArr);
        return h($showGroup);
    }
}

?>