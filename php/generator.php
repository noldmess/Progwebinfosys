<?php
require_once 'db.php';
require_once 'article.php';

use Wiki\DB;
use Wiki\Article;

if(isset($_GET['generate']) && is_numeric($_GET['generate'])){
	$numberArticles =  abs($_GET['generate']);
}else{
	$numberArticles = 1000;	
}

$words = array();
$handle = fopen("words.txt", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle);
        $words = explode(" ",$buffer);
    }
    fclose($handle);
}


$db=DB::getInstance();

$articles = $db->selectList();

for($i = 0; $i<$numberArticles; $i++){
	$title = generateTitle($words);
	$text = generateText($articles, $words);
	
	
	$art = new Article("",$title,$text,1,1);
	while(!$db->insert($title, $text, $art->getParsedText(),$art->getUserModifiet(),$art->getUserModifiet(),"")){
		$title = generateTitle($words);
	}
	$art_id=$db->selectTitle($title);
	array_push($articles, $title);
	
	$links = $art->getLinkList();
	echo $art_id['id'];
	$db->insertLinks($art_id['id'], $links);
}

echo "finished inserting ".$numberArticles." new articles!";

function generateTitle($words){
	$minWords = 1;
	$maxWords = 5;
	$usedWords = rand($minWords, $maxWords);
	$result = "";
	for($i = 0; $i<$usedWords; $i++){
			$result .= getWord($words).' ';
	}

	
	return trim($result);	
}

function generateText($articles, $words){
	
	$minWords = 5;
	$maxWords = 100;
	$usedWords = rand($minWords, $maxWords);
	$result = "";
	for($i = 0; $i<$usedWords; $i++){
		$r = rand(0, 100);
		if($r <= 15){
			$result .= getLink($articles).' ';	
		}else{
			$result .= getWord($words).' ';
		}
	}
	
	return trim($result);
}

function getLink($articles){
	
	$count = count($articles);
	
	if($count > 0){
		$index = rand(0, $count-1);
		return '[['.$articles[$index].']]';
	}else{
		return '';
	}
}

function getWord($words){
	$count = count($words);
	$index = rand(0, $count-1);
	return $words[$index];	
}

?>
