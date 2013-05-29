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

class GameController extends AbstractActionController
{
	protected $gameTable;
	
	public function getGameTable()
	{
		if (!$this->gameTable) {
			$sm = $this->getServiceLocator();
			$this->gameTable = $sm->get('Game\Model\GameTable');
		}
		return $this->gameTable;
	}
	
    public function revengeAction(){
  

		$form = new GameForm();
		//$manager = new SessionManager();
		//Container::setDefaultManager($manager);
		$session = new Container('base');
		
		/*
		if($session->offsetExists('email')&& $session->offsetExists('user') && $session->offsetExists('email2')&& $session->offsetExists('user2')){
			$form->get('user1')->setValue($session->offsetGet('user'));
			$form->get('email1')->setValue($session->offsetGet('email'));
			$form->get('user2')->setValue($session->offsetGet('user2'));
			$form->get('email2')->setValue($session->offsetGet('email2'));
		}
		*/
		
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
				$this->sendMail($game->email2, $subject, $msg, $link);
				$this->getGameTable()->saveGame($game);
				return $this->redirect()->toRoute('game', array('action' => 'invite','hash'=>$game->hash));
			}
		}
		return new ViewModel(array('form'=>$form));
  
    }


    public function indexAction()
    {
 		return new ViewModel();
    }
	
	public function highscoreAction(){
		
		$limit = 20;
    	
 		return new ViewModel(array('highscore'=>$this->getGameTable()->getHighscore($limit), 'limit' => $limit));
    }

    public function allAction()
    {
    	return new ViewModel(array('games'=>$this->getGameTable()->fetchAll()));
    }

	public function newAction(){
		$form = new GameForm();

    	//$manager = new SessionManager();
    	//Container::setDefaultManager($manager);
    	$session = new Container('base');
		/*
    	if($session->offsetExists('email')&& $session->offsetExists('user')){
    		$form->get('user1')->setValue($session->offsetGet('user'));
    		$form->get('email1')->setValue($session->offsetGet('email'));
    	}
		*/
		$form->get('user1')->setValue($session->user);
    	$form->get('email1')->setValue($session->email);
		
    	$form->get('submit')->setValue('New Game');
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$game= new Game();
    		$form->setInputFilter($game->getInputFilter());
    		$form->setData($request->getPost());
    		 
    		if ($form->isValid()) {
    			$game->exchangeArray($form->getData());
    			$this->getGameTable()->saveGame($game);
    			
    			$session->email =  $game->email1;
    			$session->user = $game->user1;
				
				$msg = 'Your friend, '.$game->user1.', wants to challenge you. To accept the challenge follow the link: ';
				$link= $this->getBaseUrl().$this->url()->fromRoute('game',array('action' => 'fight','hash'=>$game->hash));
				$subject = 'Challenge accepted?'; 
				$this->sendMail($game->email2, $subject, $msg, $link);
				return $this->redirect()->toRoute('game', array('action' => 'invite', 'hash'=>$game->hash));
    		}
    	}
		
    	return new ViewModel(array('form'=>$form));
	}
	
	public function inviteAction(){
		$hash =  $this->params()->fromRoute('hash', 0);
		if($hash==!0){
    		$gametmp=$this->getGameTable()->getGameHash($hash);
		}
		return new ViewModel(array('game' => $gametmp));
	}

    public function fightAction()
    {	
    	
    	$hash =  $this->params()->fromRoute('hash', 0);
    	if($hash==!0){
    		$gametmp=$this->getGameTable()->getGameHash($hash);
    		if($gametmp->choice2==!0){
    			return $this->redirect()->toRoute('game',array("action"=>'result','hash'=>$gametmp->hash));
    		}
			
			$session = new Container('base');
			
			$session->email = $gametmp->email2;
			$session->user = $gametmp->user2;
			$session->email2 = $gametmp->email1;
			$session->user2 = $gametmp->user1;
			
    		$form = new GameForm();
    		$request = $this->getRequest();
    		if ($request->isPost()) {
    			$game= new Game();
    			$form->setInputFilter($game->getInputFilter2());
    			$form->setData($request->getPost());
    			if ($form->isValid() ){
					
    				$game->exchangeArray($form->getData());
    				$game->user1=$gametmp->user1;
    				$game->email1=$gametmp->email1;
    				$game->choice1=$gametmp->choice1;
    				$game->user2=$gametmp->user2;
    				$game->email2=$gametmp->email2;
    				$game->hash=$gametmp->hash;
					
					$var1 = ($game->choice1 - 1 < 0) ? 4 : $game->choice1 - 1;
					$var2 = ($game->choice2 - 1 < 0) ? 4 : $game->choice2 - 1;
					if($game->choice1 === $game->choice2){
						$game->winner = 0;	
					}elseif($var2 === ($var1 + 2) % 5 || $var2 === ($var1 + 4) % 5){
						$game->winner = 1;
						$game->winner_name = $game->user1;
					}else{
						$game->winner = 2;
						$game->winner_name = $game->user2;
					}
					
					
    				$this->getGameTable()->saveGame($game);
					$msg = 'Your oppononent, '.$game->user2.', has chosen his weapon. To see the result click';
					$link= $this->getBaseUrl().$this->url()->fromRoute('game',array('action' => 'result','hash'=>$game->hash));
					$subject = 'See the result';
					$this->sendMail($game->email1, $subject, $msg, $link);
    				return $this->redirect()->toRoute('game',array("action"=>'result','hash'=>$gametmp->hash));
    			}
    		}else{
    			$form->bind($gametmp);
    		}

    		return new ViewModel(array('hallo'=>$this->getGameTable()->getGameHash($hash),'form'=>$form));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    	
    }

    public function resultAction()
    {
    	$hash =  $this->params()->fromRoute('hash', 0);
    	if($hash==!0){
    		$game = $this->getGameTable()->getGameHash($hash);
			if((int)$game->winner === 0){
				$game->result = "The game ended in a draw.";	
			}elseif((int)$game->winner === 1){
				$game->result = $game->user1." has won the game.";
			}elseif((int)$game->winner === 2){
				$game->result = $game->user2." has won the game.";	 
			}else{
				$game->result = "Opponent has not chosen his weapon yet!";
			}
    		return new ViewModel(array('game'=>$game));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    
    }
	
	public function sendMail($email, $subject, $msg, $link){
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
				->setBody($msg.' '.$link);
		$transport->send($message);
	}
	
	public function getBaseUrl(){
		
		$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $server = $_SERVER['HTTP_HOST'];
		return $protocol.'://'.$server;
		
	}
}
