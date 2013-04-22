<?php
namespace  Wiki;

class Article{
	private $id;
	private $title;
	private $text;
	private $parsedText;
	
	private $links = array();
	
 	 public function  __construct($id,$title,$text,$parsedText=''){
		$this->id=$id;
		$this->title=$title;
		$this->text=$text;
		if($parsedText === '' && $text !== ''){
			$this->parsedText = $this->parse($text);
		}else{
			$this->parsedText=$parsedText;
		}
	}
	
	public function __toString(){
		$text='<div class="span9"><div class="hero-unit">';
		$text.="<h1>".$this->title."</h1>";
		$text.="<p>".$this->parse($this->text)."</p>";
		$text.="<a class='btn btn-danger' href='/wiki/$this->title/remove'><i class='icon-remove'></i>remove</a> <a class='btn btn-primary' href='/wiki/$this->title/change'><i class='icon-pencil'></i>change</a></div></div>";
		
		return $text;
	}
	
	public function addLinkTitle($title){
		array_push($this->links, $title);	
	}
	
	public function parse($text){
		$tmp=preg_replace( '/(.*)\-\-\-(.*)\-\-\-(.*)/', '$1<h3>$2</h3>$3', $text);
		
		preg_match_all('/(\w*)\[\[(\w*)\]\](\w*)/', $tmp, $m, PREG_SET_ORDER);
		var_dump($m);
		foreach($m as $val){
			$this->addLinkTitle($val[2]);
		}
		$res = preg_replace_callback('/(\w*)\[\[(\w*)\]\](\w*)/', 
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
	
}