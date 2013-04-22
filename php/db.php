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
	 	if ($result = $this->mysqli->query("Select * from  article where title like '$title'")) {
	 		if($row = $result->fetch_object()){
        		$list['title'] = $row->title;
        		$list['text'] = $row->text;
				$list['id'] = $row->id;
				$list['parsedText'] = $row->text_parsed;
    		}
	 	}
	 	return $list;
	 }
	 
	 public function selectLinks($title){
		$list=array();
	 	if ($result = $this->mysqli->query("Select from FROM links where to like '$title'")) {
	 		while($row = $result->fetch_object()){
        		$list[] = $row->from;
    		}
	 	}
	 	return $list;
	 }
	 
	 public function selectList(){
	 	$list=array();
	 	if ($result = $this->mysqli->query("Select title from article")) {
	 		while ($row = $result->fetch_object()){
        		$list[] = $row->title;
        
    		}
	 	}
	 	return $list;
	 }
	 
	 public function selectListLimit($min,$max){
	 	$list=array();
	 	if ($result = $this->mysqli->query("Select title from article LIMIT $min,$max")) {
	 		while ($row = $result->fetch_object()){
	 			$list[] = $row->title;
	 
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function insert($title,$text,$parsedText){
	 	if ($this->mysqli->query("insert into article (title,text,text_parsed) value ('$title','$text','$parsedText')") === FALSE) {
	 		echo ("Error insert");
			return false;
	 	}else{
			return $this->mysqli->insert_id;
		}
	 }
	 
	 public function insertLinks($from, $links){
		 foreach($links as $link){
			if($this->mysqli->query("insert into links (from,to) value ('$from','$link')") === FALSE){
				echo ("Error inserting links");
			}
		 }
	 }
	 
	 
	 public function search($title){
	 	$list=array();
	 	$result=$this->mysqli->query("Select title from article where title like '%$title%' ") ;
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
	 
 	public function remove($title){
 		if ($this->mysqli->query("DELETE FROM article WHERE title like '$title'") === FALSE) {
 			echo ("Error remove");
 		}
		if($this->mysqli->query("DELETE FROM links WHERE from like '$title'") === FALSE){
			echo ("Error removing links");	
		}
	 }
	 
	 public function update($id,$title,$text,$parsedText){
	 	if ($this->mysqli->query("UPDATE article SET title='$title',text='$text', text_parsed='$parsedText' WHERE id = '$id'") === FALSE) {
	 		echo ("Error updating");
	 	}
	 }
	 
	 public function updateLinks($from, $links){
		 
		$list=array();
	 	$result=$this->mysqli->query("Select to from links where from like '$from' ") ;
	 	if($result!==FAlSE){
			while ($row = $result->fetch_object()){
        		array_push($list,$row->to);
    		}
	 	}
		$insertLinks = array();
		$deleteLinks = array();
		foreach($links as $index=>$link){
			if(!in_array($link, $list)){
				array_push($insertLinks, $link);
			}
		}
		foreach($list as $link){
			if(!in_array($link,$links)){
				array_push($deleteLinks, $link);	
			}
		}
		
		$this->insertLinks($from, $insertLinks);
		$this->removeLinks($from,$deleteLinks);
	 }
	 
	 public function removeLinks($from, $links){
		 foreach($links as $link){
			if($this->mysqli->query("DELETE FROM links where from like '$from' AND to like '$link'") === FALSE){
				echo ("Error removing links");
			}
		 }
	 }
	 
	 
	 public function countList(){
	 	return sizeof($this->selectList())/30;
	 }
	 
	 
}