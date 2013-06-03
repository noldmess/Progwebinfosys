<?php

namespace  Wiki;
class DB{
	private $mysqli;
	private  static $instance=null;
	
	public static  function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
 	public function  __construct(){
		$this->mysqli= new \mysqli("localhost", "wikiuser", "wiki#02", "wiki");
		if ($this->mysqli->connect_error) {
			echo 'Connect Error (' . $this->mysqli->connect_errno . ') '
					. $this->mysqli->connect_error;
		}
	}
	
 	public function  __destruct(){
 		$this->mysqli->close();
	 }
	 
	 public function select($title){
	 	$list=array();
	 	if ($result = $this->mysqli->query("Select * from  article_old where title like '$title'")) {
	 		if($row = $result->fetch_object()){
        		$list['title'] = $row->title;
        		$list['text'] = $row->text;
				$list['id'] = $row->id;
				$list['parsedText'] = $row->text_parsed;
    		}
	 	}
	 	return $list;
	 }
	 
	 public function selectLinks($to){
		$list=array();
	 	if ($result = $this->mysqli->query("Select `from` FROM `links_old` where `to` like '$to'")) {
	 		while($row = $result->fetch_object()){
        		$list[] = $row->from;
    		}
	 	}
	 	return $list;
	 }
	 
	 public function selectList(){
	 	$list=array();
	 	if ($result = $this->mysqli->query("Select title from article_old")) {
	 		while ($row = $result->fetch_object()){
        		$list[] = $row->title;
        
    		}
	 	}
	 	return $list;
	 }
	 
	 public function selectListLimit($min,$max){
	 	$list=array();
	 	if ($result = $this->mysqli->query("Select title from article_old LIMIT $min,$max")) {
	 		while ($row = $result->fetch_object()){
	 			$list[] = $row->title;
	 
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function insert($title,$text,$parsedText){
	 	if ($this->mysqli->query("insert into article_old (title,text,text_parsed) values ('$title','$text','$parsedText')") === FALSE) {
	 		echo ("Error insert");
			return false;
	 	}else{
			return $this->mysqli->insert_id;
		}
	 }
	 
	 public function insertLinks($from, $links){
		 foreach($links as $link){
			if($this->mysqli->query("INSERT INTO `links_old`(`from`, `to`) VALUES ('$from','$link')") === FALSE){
				echo ("Error inserting links");
			}
		 }
	 }
	 
	 
	 public function search($title){
	 	$list=array();
	 	$result=$this->mysqli->query("Select title from article_old where title like '%$title%' ") ;
	 	if($result===FAlSE){
	 		echo ("Error insert");
	 		return false;
	 	}else{
	 	while ($row = $result->fetch_object()){
        		$list[] = $row->title;
    		}
	 	}
	 	return $list;
	 }
	 
	 public function searchLimit($title,$min,$max){
	 	$list=array();
	 	$result=$this->mysqli->query("Select title from article_old where title like '%$title%' LIMIT $min,$max ") ;
	 	if($result===FAlSE){
	 		echo ("Error insert");
	 		return false;
	 	}else{
	 		while ($row = $result->fetch_object()){
	 			$list[] = $row->title;
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function searchCount($title){
	 	$list=array();
	 	$result=$this->mysqli->query("Select count(*) as number from article_old where title like '%$title%' ") ;
	 	if($result===FAlSE){
	 		echo ("Error insert");
	 		return false;
	 	}else{
	 		while ($row = $result->fetch_object()){
	 			return  $row->number;
	 		}
	 	}
	 	return $list;
	 }
	 
 	public function remove($title){
 		if ($this->mysqli->query("DELETE FROM article_old WHERE title like '$title'") === FALSE) {
 			echo ("Error remove");
 		}
		if($this->mysqli->query("DELETE FROM links_old WHERE from like '$title'") === FALSE){
			echo ("Error removing links");	
		}
	 }
	 
	 public function update($id,$title,$text,$parsedText){
	 	if ($this->mysqli->query("UPDATE article_old SET title='$title',text='$text', text_parsed='$parsedText' WHERE id = '$id'") === FALSE) {
	 		echo ("Error updating");
	 	}
	 }
	 
	 public function updateLinks($from, $links){
		 
		$list=array();
	 	$result=$this->mysqli->query("SELECT `to` FROM `links_old` WHERE `from` LIKE '$from'") ;
	 	if($result!==FAlSE){
			while ($row = $result->fetch_object()){
        		array_push($list,$row->to);
    		}
	 	}
		$insertLinks = array();
		$deleteLinks = array();
		foreach($links as $index=>$link){
			if(!in_array($link, $list) && !in_array($link, $insertLinks)){
				array_push($insertLinks, $link);
			}
		}
		foreach($list as $link){
			if(!in_array($link,$links)){
				array_push($deleteLinks, $link);	
			}
		}
		
		$this->insertLinks($from,$insertLinks);
		$this->removeLinks($from,$deleteLinks);
	 }
	 
	 public function removeLinks($from, $links){
		 foreach($links as $link){
			if($this->mysqli->query("DELETE FROM `links_old` where `from` like '$from' AND `to` like '$link'") === FALSE){
				echo ("Error removing links");
			}
		 }
	 }
	 
	 
	 public function countList(){
	 	return sizeof($this->selectList())/30;
	 }
	 
	 
}