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
	 
	 public function select($id){
	 	$list=array();
	 	if ($result = $this->mysqli->query("select art.title as title,art.text_parsed as text_parsed,art.text as text,art.id as id,art.usermodifi as usermodifi,art.modifi_timestam as modifi_timestam,art.usercreate as usercreate,autc.name as autcname,autm.name as autmname from article as art inner join author as autc on (art.usercreate=autc.id) inner join author as autm on (art.usermodifi=autm.id) where art.id = $id")) {
	 		if($row = $result->fetch_object()){
        		$list['title'] = $row->title;
        	 	$list['text'] = $row->text;
        	 	$list['text_parsed'] = $row->text_parsed;
				$list['id'] = $row->id;
				$list['usercreate'] = $row->usercreate;
				$list['usermodifi'] = $row->usermodifi;
				$list['modifi_timestam'] = $row->modifi_timestam;
				$list['image']= $row->image;
			 	$list['usercreate'] = $row->autcname;
				$list['usermodifi'] = $row->autmname;
    		}
	 	}
	 	return $list;
	 }
	 
	 
	 public function selectTitle($title){
	 	$list=array();
	 	if ($result = $this->mysqli->query("Select * from  article where title like '$title'")) {
	 		if($row = $result->fetch_object()){
	 			$list['title'] = $row->title;
	 			$list['text'] = $row->text;
	 			$list['text_parsed'] = $row->text_parsed;
	 			$list['id'] = $row->id;
	 			$list['usercreate'] = $row->usercreate;
	 			$list['usermodifi'] = $row->usermodifi;
	 			$list['modifi_timestam'] = $row->modifi_timestam;
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function selectLinks($to){
		$list=array();
	 	if ($result = $this->mysqli->query("Select article.title AS fromtitle,article.id as id  FROM `links` inner join article on(links.from=article.id) where `to` like '$to'")) {
	 		while($row = $result->fetch_object()){
        	 $list[] = array('id'=>$row->id,'title'=>$row->fromtitle);        	 		
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
	 	if ($result = $this->mysqli->query("Select id,title from article LIMIT $min,$max")) {
	 		while ($row = $result->fetch_object()){
	 			$list[] = array("id"=>$row->id,"title"=>$row->title);
	 
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function insert($title,$text,$parsedText,$usercreate,$usermodifi,$image){
	 	if ($this->mysqli->query("insert into article (title,text,text_parsed,usercreate,usermodifi,image) values ('$title','$text','$parsedText','$usercreate','$usermodifi','$image')") === FALSE) {
	 		echo ("ERROR INSERT");
	 		
			return false;
	 	}else{
			return $this->mysqli->insert_id;
		}
	 }
	 
	 public function insertLinks($from, $links){
		 foreach($links as $link){
		 	$article=$this->selectTitle($link);
		 	$to=$article['id'];
		 	echo $from."-".$to."<br>";
			if($this->mysqli->query("INSERT INTO `links`(`from`, `to`) VALUES ('$from','$to')") === FALSE){
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
	 
	 public function searchLimit($title,$min,$max){
	 	$list=array();
	 	$result=$this->mysqli->query("Select title,id from article where title like '%$title%' LIMIT $min,$max ") ;
	 	if($result===FAlSE){
	 		echo ("Error insert");
	 		return false;
	 	}else{
	 		while ($row = $result->fetch_object()){
	 			$list[] = array("id"=>$row->id,"title"=>$row->title);
	 		}
	 	}
	 	return $list;
	 }
	 
	 

	 public function getUser($name,$pass){
	 	$list=array();
	 	$result=$this->mysqli->query("Select * from author where name like '$name' and password like'$pass' ") ;
	 	if($result===FAlSE){
	 		echo ("Error insert");
	 		return false;
	 	}else{
	 		while ($row = $result->fetch_object()){
	 			$list= array("id"=>$row->id,'name'=>$row->name,"pass"=>$row->password);
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function insertUser($name,$pass){
	 	$list=array();
	 	$result=$this->mysqli->query("INSERT INTO author (`name`, `password`) VALUES ('$name','$pass') ") ;
	 	if($result===FAlSE){
	 		echo ("Error insert");
	 		return false;
	 	}
	 }
	 
	 public function searchCount($title){
	 	$list=array();
	 	$result=$this->mysqli->query("Select count(*) as number from article where title like '%$title%' ") ;
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
	 
 	public function remove($id){
 		if ($this->mysqli->query("DELETE FROM article WHERE id=$id") === FALSE) {
 			echo ("Error remove");
 		}
		if($this->mysqli->query("DELETE FROM links WHERE `from` like '$id'") === FALSE){
			echo $this->mysqli->error;	
		}
	 }
	 
	 public function update($id,$title,$text,$parsedText,$usermodifi,$image){
	 	if ($this->mysqli->query("UPDATE article SET title='$title',text='$text',text_parsed='$parsedText',usermodifi= '$usermodifi', image = '$image',modifi_timestam = UTC_TIMESTAMP( ) WHERE id = '$id'") === FALSE) {
	 		echo ("Error updating");
	 	}
	 }
	 
	 public function updateLinks($from, $links){
		 
		$list=array();
	 	$result=$this->mysqli->query("SELECT `to` FROM `links` WHERE `from` LIKE '$from'") ;
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
			if($this->mysqli->query("DELETE FROM `links` where `from` like '$from' AND `to` like '$link'") === FALSE){
				echo ("Error removing links");
			}
		 }
	 }
	 
	 
	 public function countList(){
	 	return sizeof($this->selectList())/30;
	 }
	 
	 
	 
	 
}