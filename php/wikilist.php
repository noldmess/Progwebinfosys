<?php
namespace  Wiki;


use Wiki\DB;

class Wikilist{
	private  static $instance=null;

	private function  __construct(){}
	
	public static  function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new self;	
		}
		return self::$instance;
	}
	
	public   function __toString(){
		foreach ($_SESSION['index'] as $list){
					$text.="<li><a href='wiki/$list/'>$list</a></li>";
		}
		$text.='<li><a href="/wiki/new/"><i class="icon-pencil"></i>Neuer Artikel</a></li>';
		return $text."</ul></div><!--/.well --></div><!--/span-->";
	}
	
	public function getIndexArray(){
		$db=DB::getInstance();
		return $db->selectList();//$_SESSION['index'];
	}
	
	public  function  addNewArticle($title,$text){
		/*$_SESSION['index'][]=$title;
		$_SESSION[$title]=$text;*/
		$db=DB::getInstance();
		
		$id = $db->insert($title,$text);
		if(!$id){
			return "";	
		}
		return $id;
	}
	
	
	public  function updateArticle($id,$title,$text){
		$db=DB::getInstance();
		$db->update($id,$title,$text);
		//$_SESSION[$title]=$text;
	}
	
	public  function removeArticle($title){
		$db=DB::getInstance();
		$db->remove($title);
		/*$list=$_SESSION['index'];
		$key=array_search($title,$list);
		unset($list[$key]);
		$_SESSION['index']=$list;
		//var_dump($_SESSION['index']);
		unset($_SESSION[$title]);*/
	}
	
	public  function getArticle($title){
		$db=DB::getInstance();
		$array=$db->select($title);
		return new Article($array['id'],$title,$array['text']);
	}
	
	public function issetArticle($title){
		$db=DB::getInstance();
		$array=$db->select($title);
		return isset($array['text']);
	}
}
