<?php
namespace  Wiki;

use Wiki\DB;
class Article{
	private $id;
	private $title;
	private $text;
	private $parsedText;
	private $date;
	private $usermodified;
	private $usercreate;
	private $imageName;
	private $align;
	
	private $links = array();
	
 	 public function  __construct($id,$title,$text,$usercreate,$usermodified, $imagename='', $align, $parsedText='',$date=''){
		$this->id=$id;
		$this->title=$title;
		$this->text=$text;
		$this->usercreate=$usercreate;
		$this->usermodified=$usermodified;
		$this->imageName=$imagename;
		$this->align = $align;
		$this->date=$date;
		if($parsedText === '' && $text !== ''){
			$this->parsedText = $this->parse($text);
		}else{
			$this->parsedText=$parsedText;
		}
	}
	
	public function addLinkTitle($title){
		if(!in_array($title, $this->links)){
			array_push($this->links, $title);
		}
	}
	
	public function parse($text){
		$tmp=preg_replace( '/([^\-]*)\-{3}([^\-]*)\-{3}([^\-]*)/', '$1<b>$2</b>$3', $text);

		$res = preg_replace_callback('/(\w*)\[\[(.[^\]]*)\]\](\w*)/', array(&$this, 'parse_links'), $tmp);
		return $res;
	}
	
	public function parse_links($matches){
		$db=DB::getInstance();
		$art = $db->selectTitle($matches[2]);
		if(isset($art['id'])){
			$this->addLinkTitle($art['id']);
			return $matches[1].'<a href="'.str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME'])).'/wiki/'.$art['id'].'/'.urlencode($matches[2]).'/">'.$matches[2].'</a>'.$matches[3]; 
		}else{
			return $matches[1].'<a href="'.str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME'])).'/wiki/'.urlencode($matches[2]).'/">'.$matches[2].'</a>'.$matches[3]; 
		}
	}
	
	public function getLinkList(){return $this->links;}
	public function setLinkList($links){$this->links = $links;}
	
	
	public function setParsedText($text){$this->parsedText = $text;}
	public function getParsedText(){return $this->parsedText;}
	
	public  function getTitle(){return $this->title;}
	public  function getText(){return $this->text;}
	public function getID(){return $this->id;}
	
	public function setUserModified($usermodified){$this->usermodified = $usermodified;}
	public function getUserModified(){return $this->usermodified;}
	
	public function setUserCreate($usercreate){$this->usercreate = $usercreate;}
	public function getUserCreate(){return $this->usercreate;}
	
	public function setImageName($imagename){$this->imageName = $imagename;}
	public function getImageName(){return $this->imageName;}
	
	public function setAlign($align){$this->align = $align;}
	public function getAlign(){return $this->align;}
	
	public function setDate($date){$this->date = $date;}
	public function getDate(){return $this->date;}
	
}