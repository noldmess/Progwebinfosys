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
	 	if ($result = $this->mysqli->query("select art.title as title,art.text_parsed as text_parsed,art.text as text,art.id as id,art.image as image, art.align as align, art.user_mod as user_mod,art.timestamp_mod as timestamp_mod,art.user_create as user_create,autc.name as autcname,autm.name as autmname from article as art left outer join author as autc on (art.user_create=autc.id) left outer join author as autm on (art.user_mod=autm.id) where art.id = '$id'")) {
	 		if($row = $result->fetch_object()){
        		$list['title'] = $row->title;
        	 	$list['text'] = $row->text;
        	 	$list['text_parsed'] = $row->text_parsed;
				$list['id'] = $row->id;
				$list['timestamp_mod'] = $row->timestamp_mod;
				$list['image']= $row->image;
			 	$list['user_create'] = $row->autcname;
				$list['user_mod'] = $row->autmname;
				$list['align'] = $row->align;
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
	 			$list['user_create'] = $row->user_create;
	 			$list['user_mod'] = $row->user_mod;
	 			$list['timestamp_mod'] = $row->timestamp_mod;
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function selectLinks($to){
		$list=array();
	 	if ($result = $this->mysqli->query("Select article.title AS fromtitle,article.id as id  FROM `links` inner join article on(links.from=article.id) where `to`='$to'")) {
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
	 
	 public function insert($title,$text,$parsedText,$usercreate,$usermodifi,$image,$align){
		 if($image!==false){
			 $attr = "title,text,text_parsed,user_create,user_mod,image,align";
			 $vals = "'$title','$text','$parsedText','$usercreate','$usermodifi','$image','$align'";
		 }else{
			 $attr = "title,text,text_parsed,user_create,user_mod,align";
			 $vals = "'$title','$text','$parsedText','$usercreate','$usermodifi','$align'";
		 }
	 	if ($this->mysqli->query("insert into article (".$attr.") values (".$vals.")") === FALSE) {
	 		echo ("ERROR INSERT");
			return false;
	 	}else{
			return $this->mysqli->insert_id;
		}
	 }
	 
	 public function insertLinks($from, $links){
		 foreach($links as $to){
			if($this->mysqli->query("INSERT INTO `links`(`from`, `to`) VALUES ('$from','$to')") === FALSE){
				echo ("Error inserting links");
			}
		 }
	 }
	 
	 
	 public function search($title){
	 	$list=array();
	 	$result=$this->mysqli->query("Select title from article where title like '%$title%' ") ;
	 	if($result===FALSE){
	 		echo ("Error search");
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
	 	if($result===FALSE){
	 		echo ("Error search limit");
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
	 	if($result===FALSE){
	 		return false;
	 	}else{
	 		while ($row = $result->fetch_object()){
	 			$list= array("id"=>$row->id,'name'=>$row->name,"pass"=>$row->password);
	 		}
	 	}
	 	return $list;
	 }
	 
	 public function getRandomUser(){
		$result = $this->mysqli->query("Select id from author order by rand() limit 1" );
		if($result===FALSE){
			return false;
		}else{
			if($row = $result->fetch_object()){
	 			return $row->id;
	 		}else{
				return false;
			}
		}
	 }
	 
	 public function insertUser($name,$pass){
	 	$list=array();
	 	$result=$this->mysqli->query("INSERT INTO author (`name`, `password`) VALUES ('$name','$pass') ") ;
	 	if($result===FALSE){
	 		echo ("Error insert user");
	 		return false;
	 	}else{
			return $this->mysqli->insert_id;
		}
	 }
	 
	 public function searchCount($title){
	 	$result=$this->mysqli->query("Select count(*) as number from article where title like '%$title%' ") ;
	 	if($result===FALSE){
	 		echo ("Error counting");
	 		return false;
	 	}else{
	 		if($row = $result->fetch_object()){
	 			return  $row->number;
	 		}
	 	}
	 }
	 
 	public function remove($id){
 		if ($this->mysqli->query("DELETE FROM article WHERE id=$id") === FALSE) {
 			echo ("Error remove");
 		}
		if($this->mysqli->query("DELETE FROM links WHERE `from`='$id'") === FALSE){
			echo $this->mysqli->error;	
		}
	 }
	 
	 public function update($id,$title,$text,$parsedText,$usermodifi,$image, $align){
		 if($image ===false){
			 $image = "NOIMG";
		 }
	 	if ($this->mysqli->query("UPDATE article SET title = '$title', text = '$text', text_parsed = '$parsedText', user_mod = '$usermodifi', image = '$image', align = '$align', timestamp_mod = UTC_TIMESTAMP() WHERE id = '$id'") === FALSE) {
	 		echo ("Error updating");
	 	}
	 }
	 
	 public function updateLinks($from, $links){	
		 
		$list=array();
	 	$result=$this->mysqli->query("SELECT `to` FROM `links` WHERE `from`='$from'") ;
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
		if(count($links)<1){
			return;
		}
		$in = "";
		foreach($links as $to){
			$in.=$to.',';
		}
		rtrim($in, ',');
		if($this->mysqli->query("DELETE FROM `links` where `from`='$from' AND `to`IN($in)") === FALSE){
			echo ("Error removing links");
		}
	 }
	 
	 
	 public function countList(){
	 	return sizeof($this->selectList())/30;
	 }	 
}