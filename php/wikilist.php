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
					$text.="<li><a href='/PvW/
					wiki/$list/'>$list</a></li>";
		}
		$text.='<li><a href="/PvW/wiki/new/"><i class="icon-pencil"></i>Neuer Artikel</a></li>';
		return $text."</ul></div><!--/.well --></div><!--/span-->";
	}
	
	public function getIndexArray(){
		return $_SESSION['index'];
	}
	
	public  function  addNewArticle($title,$text){
		$_SESSION['index'][]=$title;
		$_SESSION[$title]=$text;
		$db=DB::getInstance();
		$db->insert($title,$text);
	}
	
	
	public  function  updateArticle($title,$text){
		$db=DB::getInstance();
		$db->update($title,$text);
		$_SESSION[$title]=$text;
	}
	
	public  function removeArticle($title){
		$db=DB::getInstance();
		$db->remove($title);
		$list=$_SESSION['index'];
		$key=array_search($title,$list);
		unset($list[$key]);
		$_SESSION['index']=$list;
		//var_dump($_SESSION['index']);
		unset($_SESSION[$title]);
	}
}
