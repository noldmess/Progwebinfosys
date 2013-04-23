<?php
namespace  Wiki;


use Wiki\DB;
use Wiki\Article;

class Wikilist{
	private  static $instance=null;

	private function  __construct(){}
	
	public static  function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new self;	
		}
		return self::$instance;
	}

	
	public function getIndexArray($min,$max){
		$db=DB::getInstance();
		return $db->selectListLimit($min,$max);
	}
	
	public  function  addNewArticle($title,$text){

		$db=DB::getInstance();
		
		$art = new Article("",$title,$text);
		
		$id = $db->insert($title, $text, $art->getParsedText());
		
		$links = $art->getLinkList();
		
		$db->insertLinks($title, $links);
		
		if(!$id){
			return "";	
		}
		return $id;
	}
	
	
	public  function updateArticle($id,$title,$text){
		$db=DB::getInstance();
		
		$art = new Article($id,$title,$text);
		
		$db->update($id,$title,$text,$art->getParsedText());
		
		$db->updateLinks($title, $art->getLinkList());
	}
	
	public  function removeArticle($title){
		$db=DB::getInstance();
		$db->remove($title);
	}
	
	public  function getArticle($title){
		$db=DB::getInstance();
		$array=$db->select($title);
		$links = $db->selectLinks($title);
		
		$art = new Article($array['id'],$title,$array['text'],$array['parsedText']);
		
		$art->setLinkList($links);
		
		return $art;
	}
	
	public function issetArticle($title){
		$db=DB::getInstance();
		$array=$db->select($title);
		return isset($array['text']);
	}
	
	public function searchArticleTitle($search){
		$db=DB::getInstance();
		$array=$db->search($search);
		return $array;
	}
	
	
	public function searchArticleTitleLimit($search,$min,$max){
		$db=DB::getInstance();
		$array=$db->searchLimit($search,$min,$max);
		return $array;
	}
	
	public function searchArticleTitleCount($search){
		$db=DB::getInstance();
		$array=$db->searchCount($search);
		return $array;
	}
	
	public function getPaginator(){
		$db=DB::getInstance();
		return $db->countList();
	}
}
