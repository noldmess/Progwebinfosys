<?php
namespace  Wiki;


use Wiki\DB;
use Wiki\Article;
use Wiki\Image;

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
	
	public  function  addNewArticle($title,$text,$userid,$image,$align){

		$db=DB::getInstance();
		
		$art = new Article("",$title,$text,$userid,$userid,$image,$align);
		
		$id = $db->insert($title, $text, $art->getParsedText(),$userid,$userid,$image, $align);

		if(!$id){
			return "";	
		}
		
		$links = $art->getLinkList();
		$db->insertLinks($id, $links);
		
		if(isset($_FILES['image'])){
			$image = new Image();
			$image->startUpload($_FILES['image'], $id);
		}
		
		return $id;
	}
	
	
	public  function updateArticle($id,$title,$text,$usermodifi,$image, $align){
		$db=DB::getInstance();
		
		$art = new Article($id,$title,$text, $usermodifi, $usermodifi, $image, $align, "", "");
		
		$db->update($id,$title,$text,$art->getParsedText(),$usermodifi,$image, $align);
		
		$db->updateLinks($id, $art->getLinkList());
		
		if(isset($_FILES['image'])){
			$image = new Image();
			$image->startUpload($_FILES['image'], $id);
		}
	}
	
	public  function removeArticle($id){
		$db=DB::getInstance();
		$db->remove($id);
		
		$image = new Image();
		$image->deleteImage($id);
	}
	
	public  function getArticle($id){
		$db=DB::getInstance();
		$array=$db->select($id);
		$art = new Article($array['id'],$array['title'],$array['text'],$array['user_create'] ,$array['user_mod'],$array['image'], $array['align'], $array['text_parsed'],$array['timestamp_mod']);
		$links = $db->selectLinks($array['id']);
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
