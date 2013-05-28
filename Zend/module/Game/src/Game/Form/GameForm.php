<?php
namespace Game\Form;

use Zend\Form\Form;
use Zend\Mail;

class GameForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('Game');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'user1',
            'type' => 'Text',
            'options' => array(
                'label' => 'Your Name',
            ),
        ));
        $this->add(array(
        		'name' => 'email1',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'Your Email',
        		),
        ));
        $this->add(array(
        		'name' => 'user2',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'Your Opponents Name',
        		),
        ));
        $this->add(array(
        		'name' => 'email2',
        		'type' => 'Text',
        		'options' => array(
        				'label' => 'Your Opponents Email',
        		),
        ));
        $msg1 = $this->createElement('textarea', 'msg1');
        $msg1->setLabel('Send a message to your opponent');
        $msg1->setAttrib('rows', 4);
        $msg1->setAttrib('cols', 50);
        
        $this->addElement($msg1);
        
        $msg2 = $this->createElement('textarea', 'msg2');
        $msg2->setLabel('Send a message to your challenger');
        $msg2->setAttrib('rows', 4);
        $msg2->setAttrib('cols', 50);
        
        $this->addElement($msg2);
        
        /*
        $this->add(array(
        		'name' => 'msg1',
        		'type' => 'Textarea',
        		'options' => array(
        				'label' => 'Send a message to your opponent!',
        		),
        ));
        $this->add(array(
        		'name' => 'msg2',
        		'type' => 'Textarea',
        		'options' => array(
        				'label' => 'Send a message to your challenger!',
        		),
        ));
        */
        
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
                'value' => 'Fight',
                'id' => 'submitbutton',
            ),
        ));
    }
}
