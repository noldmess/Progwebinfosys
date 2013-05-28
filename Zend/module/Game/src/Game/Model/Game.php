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
    public $msg1;
    public $msg2;
	public $choiceArray = array('ROCK', 'PAPER', 'SCISSOR', 'SPOCK', 'LIZARD');
    public $hash;
	public $winner;
	public $winner_name;
	public $wins;
	public $result;
    protected $inputFilter;
    

    
    public function exchangeArray($data)
    {
    	$this->id     = (isset($data['_id'])) ? $data['_id'] : null;
        $this->user1     = (isset($data['user1'])) ? $data['user1'] : null;
        $this->email1     = (isset($data['email1'])) ? $data['email1'] : null;
        $this->choice1     = (isset($data['choice1'])) ? $data['choice1'] : null;
        
        $this->user2     = (isset($data['user2'])) ? $data['user2'] : null;
        $this->email2     = (isset($data['email2'])) ? $data['email2'] : null;
        $this->choice2     = (isset($data['choice2'])) ? $data['choice2'] : null;
		
		$this->result	= (isset($data['result'])) ? $data['result'] : null;
		
        $this->winner     = (isset($data['winner'])) ? $data['winner'] : null;
        $this->winner_mail     = (isset($data['winner_mail'])) ? $data['winner_mail'] : null;
		$this->wins     = (isset($data['wins'])) ? $data['wins'] : null;
		
	$this->msg1     = (isset($data['msg1'])) ? $data['msg1'] : null;
	$this->msg2     = (isset($data['msg2'])) ? $data['msg2'] : null;
        					
        $this->hash     = (isset($data['hash'])) ?$data['hash']:hash('sha256',$this->user1.$this->user2.time());
        
    }
	
	public function getDocument(){
		$doc = array();
		if($this->id !== null && $this->id !== 0){
			$doc['_id'] = $this->id;	
		}
		if($this->user1 !== null){
			$doc['user1'] = $this->user1;	
		}
		if($this->email1 !== null){
			$doc['email1'] = $this->email1;	
		}
		if($this->choice1 !== null){
			$doc['choice1'] = $this->choice1;	
		}
		if($this->user2 !== null){
			$doc['user2'] = $this->user2;	
		}
		if($this->email2 !== null){
			$doc['email2'] = $this->email2;	
		}
		if($this->choice2 !== null){
			$doc['choice2'] = $this->choice2;	
		}
		if($this->winner !== null){
			$doc['winner'] = $this->winner;	
		}
		if($this->winner_mail !== null){
			$doc['winner_mail'] = $this->winner_mail;	
		}
		if($this->msg1 !== null){
			$doc['msg1'] = $this->msg1;	
		}
		if($this->msg2 !== null){
			$doc['msg2'] = $this->msg2;	
		}
		
		if($this->hash !== null){
			$doc['hash'] = $this->hash;	
		}
		
		
		return $doc;			
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
                'name'     => 'msg1',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
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
    				)
    			)
    		));
    		
    		$inputFilter->add($factory->createInput(array(
                'name'     => 'msg2',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            	)));
    
    		$this->inputFilter = $inputFilter;
    	}
    
    	return $this->inputFilter;
    }
    
}
