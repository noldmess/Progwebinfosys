<?php
namespace Game\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Game
{
	public $id;
    public $user1;
    public $email1;
    public $user2;
    public $email2;
    public $choice1;
    public $choice2;
	public $choiceArray = array('ROCK', 'PAPER', 'SCISSOR', 'SPOCK', 'LIZARD');
    public $hash;
	public $winner;
	public $winner_name;
	public $wins;
	public $result;
    protected $inputFilter;
    

    
    public function exchangeArray($data)
    {
    	$this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->user1     = (isset($data['user1'])) ? $data['user1'] : null;
        $this->email1     = (isset($data['email1'])) ? $data['email1'] : null;
        $this->choice1     = (isset($data['choice1'])) ? $data['choice1'] : null;
        
        $this->user2     = (isset($data['user2'])) ? $data['user2'] : null;
        $this->email2     = (isset($data['email2'])) ? $data['email2'] : null;
        $this->choice2     = (isset($data['choice2'])) ? $data['choice2'] : null;
		
		$this->result	= (isset($data['result'])) ? $data['result'] : null;
		
        $this->winner     = (isset($data['winner'])) ? $data['winner'] : null;
        $this->winner_name     = (isset($data['winner_name'])) ? $data['winner_name'] : null;
		$this->wins     = (isset($data['wins'])) ? $data['wins'] : null;
        					
        $this->hash     = (isset($data['hash'])) ?$data['hash']:hash('sha256',$this->user1.$this->user2.time());
        
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user1',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            
            
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'user2',
            		'required' => true,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 100,
            						),
            				),
            		),
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'email1',
            		'required' => true,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 100,
            						),
            				),
            		),
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'email2',
            		'required' => true,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 100,
            						),
            				),
            		),
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'choice1',
            		'required' => true,
            		'filters'  => array(
            				array('name' => 'Int'),
            		),
            		'validators' => array(
            				  array(
           						 'name' => 'Between',
           						 'options' => array(
               					 'min' => 1,
               					 'max' => 5,
           						 ), 
            				),
          	  ))));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
    public function getInputFilter2()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory     = new InputFactory();
    
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'id',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'Int'),
    				),
    		)));

    
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'choice2',
    				'required' => false,
    				'filters'  => array(
    						array('name' => 'Int'),
    				),
    				'validators' => array(
    						array(
    								'name' => 'Between',
    								'options' => array(
    										'min' => 1,
    										'max' => 5,
    								),
    						),
    				))));
    
    		$this->inputFilter = $inputFilter;
    	}
    
    	return $this->inputFilter;
    }
    
}