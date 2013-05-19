<?php
namespace Game\Form;

use Zend\Form\Form;

class GameForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('Game');
        $this->setAttribute('method', 'post');
        $this->setAttribute('onsubmit' => 'onsubmittest()');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'user1',
            'type' => 'Text',
            'options' => array(
                'label' => 'User',
            ),
        ));
        $this->add(array(
        		'name' => 'email1',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'Email',
        		),
        ));
        $this->add(array(
        		'name' => 'user2',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'User',
        		),
        ));
        $this->add(array(
        		'name' => 'email2',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'Email',
        		),
        ));
        $this->add(array(
            'name' => 'choice1',
            'type' => 'Text',
            'options' => array(
                'label' => 'choice',
            ),
        ));
        $this->add(array(
        		'name' => 'choice2',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'choice',
        		),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
