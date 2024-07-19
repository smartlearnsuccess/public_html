<?php

class Homesection extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array('search-master.Searchable', 'Upload.Upload' => array(
        'sections_img' => array(
            'pathMethod' => 'flat',
            'thumbnailSizes' => array(
                '' => '1350x550',
            ),
            'path' => '{ROOT}webroot{DS}img{DS}tab{DS}',
            'thumbnailPath' => '{ROOT}webroot{DS}img{DS}tab_thumb{DS}',
            'thumbnailMethod' => 'php',
            'thumbnailPrefixStyle' => false,
            'deleteOnUpdate' => true,
            'thumbnailType' => true
        ),
    )
    );
    public $validate = array('sections_img' => array('isValidExtension' => array('rule' => array('isValidExtension', array('jpg', 'jpeg', 'png'), false), 'allowEmpty' => false, 'message' => 'File does not have a valid extension'),
        'isValidMimeType' => array('rule' => array('isValidMimeType', array('image/jpeg', 'image/png', 'image/bmp', 'image/gif'), false), 'allowEmpty' => true, 'message' => 'You must supply a JPG, GIF  or PNG File.')),
    );


}

?>