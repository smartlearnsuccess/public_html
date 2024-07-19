<?php

class Coupon extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array('search-master.Searchable');
    public $filterArgs = array(
        'keyword' => array(
            'type' => 'like', 'field' => array(
                'Coupon.name',
                'Coupon.description',
                'Coupon.amount',
                'Coupon.min_amount',
                'Coupon.code',
                'Coupon.coupon_no',
                'Coupon.per_customer',
                'Coupon.status',
            )
        )
    );
    public $validate = array(
        'name' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Invalid Name')),
        'description' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => true, 'message' => 'Invalid Description')),
        'amount' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'message' => 'Amount only decimal number')),
        'discount_type' => array('inList' => array('rule' => array('inList', array('Amount', 'Percent')), 'required' => true, 'allowEmpty' => true, 'message' => 'Invalid discount type')),
        'min_amount' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'message' => 'Coupon Minimum Order only decimal number')),
        'code' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => true, 'message' => 'Invalid Coupon Code'),
            'isUnique' => array('rule' => 'isUnique', 'message' => 'Coupon Code already exist')),
        'coupon_no' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'allowEmpty' => true, 'message' => 'Invalid Uses per Coupon')),
        'per_customer' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'allowEmpty' => true, 'message' => 'Invalid Uses per Customer')),
        'start_date' => array('date' => array('rule' => 'date', 'required' => true, 'allowEmpty' => false, 'message' => 'Start Date Only Date format')),
        'end_date' => array('date' => array('rule' => 'date', 'required' => true, 'allowEmpty' => false, 'message' => 'End Date only Date format'))
    );

    public function beforeValidate($options = array())
    {
        if (!empty($this->data['Coupon']['start_date'])) {
            $this->data['Coupon']['start_date'] = $this->dateFormatBeforeSave($this->data['Coupon']['start_date']);
        }
        if (!empty($this->data['Coupon']['end_date'])) {
            $this->data['Coupon']['end_date'] = $this->dateFormatBeforeSave($this->data['Coupon']['end_date']);
        }
        return true;
    }
}

?>