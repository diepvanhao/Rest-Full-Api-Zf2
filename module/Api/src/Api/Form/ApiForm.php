<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Api\Form;
use Zend\Form\Form;

class ApiForm extends Form{
    public function __construct($name = null, $options = array()) {
        parent::__construct('api');
        $this->add(array(
            'name'=>'postId',
            'type'=>'Hidden',
        ));
        $this->add(array(
            'name'=>'title',
            'type'=>'Text',
            'options'=>array(
                'label'=>'Title',
            ),
        ));
        $this->add(array(
            'name'=>'body',
            'type'=>'Text',
            'options'=>array(
                'label'=>'Artist',
            ),
        ));
        $this->add(array(
            'name'=>'submit',
            'type'=>'submit',
            'attributes'=>array(
                'value'=>'Go',
                'id'=>'submitbutton',
            ),
        ));
    }
}
