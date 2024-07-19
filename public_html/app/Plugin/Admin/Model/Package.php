<?php

class Package extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array(
        'search-master.Searchable',
        'Upload.Upload' => array(
            'photo' => array(
                'pathMethod' => 'flat',
                'thumbnailSizes' => array('' => '200h',),
                'thumbnailMethod' => 'php',
                'thumbnailPrefixStyle' => false,
                'deleteOnUpdate' => true,
                'thumbnailType' => true,
                'deleteOriginal' => true,
            )
        ));
    public $filterArgs = array(
        'keyword' => array(
            'type' => 'like', 'field' => array(
                'Package.name',
                'Package.amount',
                'Package.expiry_days',
                'Package.description',
            )
        )
    );
    public $validate = array(
        'name' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => false, 'message' => 'Only Alphabets')),
        'description' => array('alphaNumeric' => array('rule' => 'alphaNumericCustom', 'required' => true, 'allowEmpty' => true, 'message' => 'Please select')),
        'amount' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'message' => 'Only numbers allowed')),
        'show_amount' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'message' => 'Invalid Discounted Amount')),
        'expiry_days' => array('numeric' => array('rule' => 'numeric', 'required' => true, 'message' => 'Only numbers allowed')),
        'photo' => array('isValidExtension' => array('rule' => array('isValidExtension', array('jpg', 'jpeg', 'png'), false), 'allowEmpty' => true, 'message' => 'File does not have a valid extension'),
            'isValidMimeType' => array('rule' => array('isValidMimeType', array('image/jpeg', 'image/png', 'image/bmp', 'image/gif'), false), 'allowEmpty' => true, 'message' => 'You must supply a JPG, GIF  or PNG File.')),
    );
    
    public function afterValidate($options = array())
    {
        if ($this->data['Package']['package_type'] == "F") {
            $this->data['Package']['amount'] = NULL;
            $this->data['Package']['show_amount'] = NULL;
        }
        return true;
    }
    public $hasAndBelongsToMany = array('Exam');
}

?>