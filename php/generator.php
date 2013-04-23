<?php
require_once 'php/db.php';

use Wiki\DB;
use Wiki\Article;

if(isset($_GET['generate']) && is_numeric($_GET['generate'])){
	$numberArticles =  abs($_GET['generate']);
}else{
	$numberArticles = 1000;	
}

$db=DB::getInstance();

$articles = $db->selectList();

echo $articles[0];
shuffle($articles);
echo $articles[0];

for($i = 0; $i<$numberArticles; $i++){
	$title = generateTitle($articles);
	$text = generateText($articles);
	
	
	$art = new Article("",$title,$text);
	
	/*	
	while(!$db->insert($title, $text, $art->getParsedText())){
		$title = generateTitle($articles);
	}
	*/
	
	array_push($articles, $title);
}

function generateTitle($articles){
	$maxWords = 5;
	$result = "";
	for($i = 0; $i<$maxWords; $i++){
		
	}
	
	return $result;	
}

function generateText($articles){
	
	$maxWords = 100;
	$result = "";
	for($i = 0; $i<$maxWords; $i++){
		
	}
	
	//getLink($articles);
	
	return $result;	
}

function getLink($articles){
	shuffle($articles);
	return '[['.$articles[0].']]';
}

function getWord(){

}

?>