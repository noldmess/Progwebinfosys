<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Game\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Game\Model\Game;
use Game\Model\GameTable;
use Game\Form\GameForm;

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
	
	
    public function indexAction()
    {
    	
    	$form = new GameForm();
    	//$form->bind($game);
    	$form->get('submit')->setValue('New Game');
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$game= new Game();
    		$form->setInputFilter($game->getInputFilter());
    		$form->setData($request->getPost());
    		 
    		if ($form->isValid()) {
    			$game->exchangeArray($form->getData());
    			$this->getGameTable()->saveGame($game);
    			//return $this->redirect()->toRoute('game');
    			return $this->redirect()->toRoute('game', array('action' => 'fight','hash'=>$game->hash));
    		}
    	}
    	//witerleiten zum 'kampf'
    	//return $this->redirect()->toRoute('game', array('action' => 'fight','hash'=>'sdgfsdfghdfs'));
    	return new ViewModel(array('form'=>$form));
    }

    public function allAction()
    {
    	return new ViewModel(array('games'=>$this->getGameTable()->fetchAll()));
    }

    public function fightAction()
    {	
    	
    	$hash =  $this->params()->fromRoute('hash', 0);
    	if($hash==!0){
    		$gametmp=$this->getGameTable()->getGameHash($hash);
    		if($gametmp->choice2==!0){
    			return $this->redirect()->toRoute('game',array("action"=>'result','hash'=>$gametmp->hash));
    		}
    		$form = new GameForm();
    		$request = $this->getRequest();
    		if ($request->isPost()) {
    			$game= new Game();
    			$form->setInputFilter($game->getInputFilter2());
    			$form->setData($request->getPost());
    			if ($form->isValid() ) {
    				$game->exchangeArray($form->getData());
    				$game->user1=$gametmp->user1;
    				$game->email1=$gametmp->email1;
    				$game->choice1=$gametmp->choice1;
    				$game->user2=$gametmp->user2;
    				$game->email2=$gametmp->email2;
    				$game->hash=$gametmp->hash;
    				$this->getGameTable()->saveGame($game);
    				return $this->redirect()->toRoute('game',array("action"=>'result','hash'=>$gametmp->hash));
    			}
    		}else{
    			$form->bind($gametmp);
    		}
    		//return $this->redirect()->toRoute('game');
    		return new ViewModel(array('hallo'=>$this->getGameTable()->getGameHash($hash),'form'=>$form));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    	
    }

    public function resultAction()
    {
    	$hash =  $this->params()->fromRoute('hash', 0);
    	if($hash==!0){
    		$game=$this->getGameTable()->getGameHash($hash);
    		return new ViewModel(array('game'=>$game));
    	}else{
    		return $this->redirect()->toRoute('game');
    	}
    
    }
    
    

}