<?php

class Payment extends AppModel
{
    public $hasAndBelongsToMany = array('Package');
}

?>