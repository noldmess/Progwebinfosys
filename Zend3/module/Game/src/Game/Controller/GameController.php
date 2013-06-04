<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Game\Controller;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Game\Model\Game;
use Game\Model\GameTable;
use Game\Form\GameForm;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\Mail;
use Zend\Session\Container;
use Zend\Json\Json;
class GameController extends AbstractActionController
{
	protected $gameTable;
	protected $db;
	
	public function getGameTable()
	{
		if (!$this->gameTable) {
			$sm = $this->getServiceLocator();
			$this->gameTable = $sm->get('Game\Model\GameTable');
		}
		return $this->gameTable;
	}
	
	public function getDb(){
		if(!$this->db){
			
			$sm = $this->getServiceLocator();
			$this->db = $sm->get('MongoGame');	
			
		}
		
		return $this->db;
	}
	
    public function revengeAction(){
  

		$form = new GameForm();
		$session = new Container('base');
		
		$form->get('user1')->setValue($session->user);
    	$form->get('email1')->setValue($session->email);
		$form->get('user2')->setValue($session->user2);
    	$form->get('email2')->setValue($session->email2);
		
		$form->get('submit')->setValue('New Game');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$game= new Game();
			$form->setInputFilter($game->getInputFilter());
			$form->setData($request->getPost());
			 
			if ($form->isValid()) {
				$game->exchangeArray($form->getData());
				
				$msg = 'Your friend, '.$game->user1.', wants to challenge you. To accept the challenge follow the link: ';
				$link= $this->getBaseUrl().$this->url()->fromRoute('game',array('action' => 'fight','hash'=>$game->hash));
				$subject = 'Challenge accepted?'; 
				$this->sendMail($game->email2, $subject, $msg, $game->msg1, $link);
				
				/*
				$this->getGameTable()->saveGame($game);
				*/
				$document = $game->getDocument();
				$this->getDb()->games->insert($document);
				
				return $this->redirect()->toRoute('game', array('action' => 'invite','hash'=>$game->hash));
			}
		}
		return new ViewModel(array('form'=>$form));
  
    }


    public function indexAction()
    {
 		return new ViewModel();
    }
	
    
    public function highscoreJSONAction()
    {
    	$limit = 20;
    	$ops = array(
			array(
				'$group' => array(
					"_id" => '$winner_mail',
					"wins" => array('$sum' => 1),
				)
			),
			array(
				'$sort' => array("wins" => -1),
			),
			array(
				'$limit' => $limit+1,
			)
		);
		
		$highscore = $this->getDb()->games->aggregate($ops);
		$length = count($highscore['result']);
		$finalHighscore = array();
		$size = 0;
		for($i = 0; $i<$length; $i++){
			$id = $highscore['result'][$i]['_id'];
			if($id !== null && $size<$limit){
				$finalHighscore[$id] = $highscore['result'][$i]['wins'];
				$size++;
			}
		}
		return $this->getResponse()->setContent(Json::encode($finalHighscore));
    }
    
	public function highscoreAction(){
		
		/*$limit = 20;
    	$ops = array(
			array(
				'$group' => array(
					"_id" => '$winner_mail',
					"wins" => array('$sum' => 1),
				)
			),
			array(
				'$sort' => array("wins" => -1),
			),
			array(
				'$limit' => $limit+1,
			)
		);
		
		$highscore = $this->getDb()->games->aggregate($ops);
		$length = count($highscore['result']);
		$finalHighscore = array();
		$size = 0;
		for($i = 0; $i<$length; $i++){
			$id = $highscore['result'][$i]['_id'];
			if($id !== null && $size<$limit){
				$finalHighscore[$id] = $highscore['result'][$i]['wins'];
				$size++;
			}
		}*/
 		return new ViewModel();
    }

    public function allAction()
    {
    	return new ViewModel(array('games'=>$this->getGameTable()->fetchAll()));
    }

    public function newJSONAction(){
    	$request = $this->getRequest();
    	$form = new GameForm();
    	if ($request->isPost()) {
    		$game= new Game();
    		$form->setInputFilter($game->getInputFilter());
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    			
    			$game->exchangeArray($form->getData());
    			
    			// $this->getGameTable()->saveGame($game);
    			$document = $game->getDocument();
    			//to do 
    			$session = new Container('base');
    			$session->email =  $game->email1;
    			$session->user = $game->user1;
    			$msg = 'Your friend, '.$game->user1.', wants to challenge you. To accept the challenge follow the link: ';
    			$link= $this->getBaseUrl().$this->url()->fromRoute('game',array('action' => 'new'))."#fight/". $game->hash;
    			$subject = 'Challenge accepted?';
    			$this->sendMail($game->email2, $subject, $msg, $game->msg1, $link);
    			$user=array("user1"=> $game->user1,"email1"=>$game->email1,"email2"=>$game->email2,"user2"=> $game->user2);
    			
    			//$user=array("user1"=>"asd","email1"=>"Aaron.Messner@student.uibk.ac.at","email2"=>"Aaron.Messner@student.uibk.ac.at","user2"=>"sdfgsd");
    			return $this->getResponse()->setContent(Json::encode(array("data"=>"sucess","user"=>$user)));
    		}
    	}
    	
    	return $this->getResponse()->setContent(Json::encode(array("data"=>"failt")));
    }
    
	public function newAction(){
		$form = new GameForm();
    	$session = new Container('base');
    	$form->setAttribute('onsubmit' , 'return NewGame.submit()');
		$form->get('user1')->setValue($session->user);
    	$form->get('email1')->setValue($session->email);
    	$form->get('submit')->setValue('New Game');
  
    	return new ViewModel(array('form'=>$form));
	}
	
	public function inviteAction(){
		$hash =  $this->params()->fromRoute('hash', 0);
		if($hash==!0){
    		//$gametmp=$this->getGameTable()->getGameHash($hash);
			$document=$this->getDb()->games->findOne(array("hash" => $hash));
			
			$game = new Game();
			
			$game->exchangeArray($document);
		}
		return new ViewModel(array('game' => $game));
	}

	
	public function fightJSONAction()
	{
		$form = new GameForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			//$gametmp=$this->getGameTable()->getGameHash($_POST['hash']);
			$document=$this->getDb()->games->findOne(array("hash" => $_POST['hash']));
				
			$gametmp = new Game();
			$gametmp->exchangeArray($document);
			$game= new Game();
			$form->setInputFilter($game->getInputFilter2());;
			$form->setData($request->getPost());
			if($gametmp->choice2==!0){
				return $this->getResponse()->setContent(Json::encode(array("data"=>"failiar")));
			}
			if ($form->isValid() ){
				$game->exchangeArray($form->getData());
				$game->id=$gametmp->id;
				$game->user1=$gametmp->user1;
				echo $game->email1=$gametmp->email1;
				$game->choice1=$gametmp->choice1;
				$game->user2=$gametmp->user2;
				$game->email2=$gametmp->email2;
				$game->msg1=$gametmp->msg1;
				$game->hash=$gametmp->hash;
					
				$var1 = ($game->choice1 - 1 < 0) ? 4 : $game->choice1 - 1;
				$var2 = ($game->choice2 - 1 < 0) ? 4 : $game->choice2 - 1;
				if($var1  === $var2 ){
					$game->winner = 0;
				}elseif($var2 === ($var1 + 2) % 5 || $var2 === ($var1 + 4) % 5){
					$game->winner = 1;
					$game->winner_mail = $game->email1;
				}else{
					$game->winner = 2;
					$game->winner_mail = $game->email2;
				}
			
				//$this->getGameTable()->saveGame($game);
				$document = $game->getDocument();
				//echo json_encode($document);
				$this->getDb()->games->save($document);
				$msg = 'Your opponent, '.$game->user2.', has chosen his weapon. To see the result click';
				$link= $this->getBaseUrl().$this->url()->fromRoute('game',array('action' => 'new'))."#fight/". $game->hash."/player/1";
				$subject = 'See the result';
				$this->sendMail($game->email1, $subject, $msg, $game->msg2, $link);
				$game=$this->result($game);
				$user=array("user1"=> $game->user1,"email1"=>$game->email1,"email2"=>$game->email2,"user2"=> $game->user2);
				return $this->getResponse()->setContent(Json::encode(array("data"=>"sucess","user"=>$user,"result"=>$game->result)));
				//return $this->getResponse()->setContent(Json::encode(array("data"=>"sucess","user"=>"sdfsdfdf","result"=>"dsfadsfds")));
				//return $this->redirect()->toRoute('game',array("action"=>'result','hash'=>$gametmp->hash));
			}
		}
	}
	
	public function getfightJSONAction()
	{
	
    		$game = $this->getGameTable()->getGameHash($_POST['hash']);
			$game=array("user1"=> $game->user1,"email1"=>$game->email1,"email2"=>$game->email2,"user2"=> $game->user2, "msg1"=>$game->msg1);
			return $this->getResponse()->setContent(Json::encode(array("data"=>"sucess","game"=>$game)));	 
    
	}
	
	 public function fightAction()
    {	
    	
    	$hash =  $this->params()->fromRoute('hash', 0);
    	if($hash==!0){
    		$gametmp = new Game();
    		//$gametmp=$this->getGameTable()->getGameHash($hash);
			$document=$this->getDb()->games->findOne(array("hash" => $hash));
			
			//
			$gametmp->exchangeArray($document);
		
			
			$session = new Container('base');
			
			$session->email = $gametmp->email2;
			$session->user = $gametmp->user2;
			$session->email2 = $gametmp->email1;
			$session->user2 = $gametmp->user1;
			
    		$form = new GameForm();
    		$form->setAttribute('onsubmit' , 'return Fight.submit()');
    		$request = $this->getRequest();
    		$form->bind($gametmp);
    		$form->add(array(
			'name' => 'hash',
      		  ));
    		$form->get('hash')->setValue($hash);
    		return new ViewModel(array('hallo'=>$gametmp,'form'=>$form));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    	
    }
    


    public function resultAction()
    {
    	
    	$hash =  $this->params()->fromRoute('hash', 0);
    	if($hash==!0){
    		//$game = $this->getGameTable()->getGameHash($hash);
			$document=$this->getDb()->games->findOne(array("hash" => $hash));
			$game = new Game();
			$game->exchangeArray($document);
			$game=$this->result($game);
		
    		return new ViewModel(array('game'=>$game));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    
    }
    

    public function resultJSONAction()
    {
    	if(isset($_POST['hash'])){
    	$hash =  $_POST['hash'];
    		//$game = $this->getGameTable()->getGameHash($hash);
    		$document=$this->getDb()->games->findOne(array("hash" => $hash));
    		$game = new Game();
    		$game->exchangeArray($document);
    		$game=$this->result($game);
    		$p=(int)$_POST['player'];
    		$game=array("hash"=> $game->hash,"user1"=> $game->user1,"email1"=>$game->email1,"email2"=>$game->email2,"user2"=> $game->user2,"result"=>$game->result,"choice1"=>$game->choiceArray[$game->choice1-1],"choice2"=>$game->choiceArray[$game->choice2-1], "msg2"=>$game->msg2);
    		return $this->getResponse()->setContent(Json::encode(array("data"=>"sucess","game"=>$game,"player"=>$p)));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    
    }
    
    public function result($game){
    	if((int)$game->winner === 0){
    		$game->result = 0;
    	}elseif((int)$game->winner === 1){
    		$game->result = 1;
    	}elseif((int)$game->winner === 2){
    		$game->result = 2;
    	}else{
    		$game->result = "Opponent has not chosen his weapon yet!";
    	}
    	return  $game;
    }
	
	public function sendMail($email, $subject, $msg, $usermessage, $link){
		$transport = new SmtpTransport();
		$options   = new SmtpOptions(array(
		'host' => 'smtp.uibk.ac.at',
		'name' => 'smtp.uibk.ac.at',
		 'port' => 587,
		 ));
		$transport->setOptions($options);
		$message = new Message();
		$message->addTo($email)
				->addFrom('Aaron.Messner@student.uibk.ac.at')
				->setSubject($subject)
				->setBody($msg.' '.$link.' Message from your opponent: '.$usermessage);
		$transport->send($message);
	}
	
	public function getBaseUrl(){
		
		$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $server = $_SERVER['HTTP_HOST'];
		return $protocol.'://'.$server;
		
	}
}
