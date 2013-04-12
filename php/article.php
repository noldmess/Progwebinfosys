<?php
namespace  Wiki;
class Article{
	private $title;
	private $text;
	
 	 public function  __construct($title,$text){
		$this->title=$title;
		$this->text=$text;
	}
	
	public function __toString(){
		$text='<div class="span9"><div class="hero-unit">';
		$text.="<h1>".$this->title."</h1>";
		$text.="<p>".$this->paes($this->text)."</p>";
		$text.="<a class='btn btn-danger' href='/wiki/$this->title/remove'><i class='icon-remove'></i>remove</a> <a class='btn btn-primary' href='/wiki/$this->title/change'><i class='icon-pencil'></i>change</a></div></div>";
		
		return $text;
	}
	
	
	public function paes($text){
			 $tmp=preg_replace( '/(\w*)\-\-\-(\w*)\-\-\-(\w*)/', '$1<h2>$2</h2>$3', $text);
			 return preg_replace( '/(\w*)\[\[(\w*)\]\](w*)/', '$1<a href="/wiki/$2/">$2</a>$3', $tmp);
	}
	
	
	public  function getTitle(){return $this->title;}
	public  function getText(){return $this->text;}
	
}