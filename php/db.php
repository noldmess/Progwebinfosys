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
	 		while ($row = $result->fetch_object()){
        		$list['title'] = $row->title;
        		$list['text'] = $row->text;
				$list['id'] = $row->id;
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
	 
	 public function insert($title,$text){
	 	if ($this->mysqli->query("insert into article (title,text) value ('$title','$text')") === FALSE) {
	 		echo ("Error");
			return false;
	 	}else{
			return $this->mysqli->insert_id;
		}
	 }
	 
 	public function remove($title){
 		if ($this->mysqli->query("DELETE FROM article WHERE title like '$title'") === FALSE) {
 			echo ("Error");
 		}
	 }
	 
	 public function update($id,$title,$text){
	 	if ($this->mysqli->query("UPDATE article SET title='$title',text='$text' WHERE id == '$id'") === FALSE) {
	 		echo ("Error updating");
	 	}
	 }
	 
	 
}