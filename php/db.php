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
	 
	 
	 public function insert($title,$text){
	 	if ($this->mysqli->query("insert into article (title,text) value ('$title','$text')") === FALSE) {
	 		echo ("Error");
	 	}
	 }
	 
 	public function remove($title){
 		if ($this->mysqli->query("DELETE FROM article WHERE  title like '$title'") === FALSE) {
 			echo ("Error");
 		}
	 }
	 
	 public function update($title,$text){
	 	if ($this->mysqli->query("UPDATE article SET title='$title',text='$text' WHERE  title like '$title'") === FALSE) {
	 		echo ("Error");
	 	}
	 }
	 
	 
}