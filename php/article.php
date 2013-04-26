<?php
namespace  Wiki;

class Article{
	private $id;
	private $title;
	private $text;
	private $parsedText;
	private $date;
	private $usermodifiet;
	private $usercreate;
	private $imageName;
	private $namemodifiet;
	private $namecreate;
	
	private $links = array();
	
 	 public function  __construct($id,$title,$text,$usercreate,$usermodifiet,$imagename='',$parsedText='',$date='',$namemodifiet='',$namecreate=''){
		$this->id=$id;
		$this->title=$title;
		$this->text=$text;
		$this->usercreate=$usercreate;
		$this->usermodifiet=$usermodifiet;
		$this->imageName=$imagename;
		$this->date=$date;
		$this->namecreate=$namecreate;
		$this->namemodifiet=$namemodifiet;
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
		
		preg_match_all('/(\w*)\[\[(.[^\]]*)\]\](\w*)/', $tmp, $m, PREG_SET_ORDER);
		foreach($m as $val){
			$this->addLinkTitle($val[2]);
		}
		$res = preg_replace_callback('/(\w*)\[\[(.[^\]]*)\]\](\w*)/', 
			function($matches){
				return $matches[1].'<a href="/wiki/'.urlencode($matches[2]).'/">'.$matches[2].'</a>'.$matches[3]; 
			}, $tmp);
		return $res;
	}
	
	public function getLinkList(){return $this->links;}
	public function setLinkList($links){$this->links = $links;}
	
	
	public function setParsedText($text){$this->parsedText = $text;}
	public function getParsedText(){return $this->parsedText;}
	
	public  function getTitle(){return $this->title;}
	public  function getText(){return $this->text;}
	public function getID(){return $this->id;}
	
	public function setUserModifiet($usermodifiet){$this->usermodifiet = $usermodifiet;}
	public function getUserModifiet(){return $this->usermodifiet;}
	
	public function setUserCreate($usercreate){$this->usercreate = $usercreate;}
	public function getUserCreate(){return $this->usercreate;}
	
	public function setImageName($imagename){$this->imageName = $imagename;}
	public function getImageName(){return $this->imageName;}
	
	public function setNameCreate($imagename){$this->imageName = $imagename;}
	public function getNameCreate(){return $this->imageName;}
	
	public function setNameModifiet($imagename){$this->imageName = $imagename;}
	public function getNameModifiet(){return $this->imageName;}
	
	public function setData($date){$this->date = $date;}
	public function getData(){return $this->date;}
	
}