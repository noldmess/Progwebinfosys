<?php
echo $_GET['searchtitle'];
require_once 'php/wikilist.php';
require_once 'php/article.php';
require_once 'php/db.php';


use Wiki\Wikilist;
use Wiki\Article;
use Wiki\DB;

$TEMPLATE=array();

/*if(!isset($_SESSION['index'])){
		$_SESSION['index']=array();
}*/
	session_set_cookie_params(604800); 
	session_start();
	$wiki=Wiki\Wikilist::getInstance();
	$title=urlencode($_GET['title']);
	if(isset($_POST['id']) && !empty($_POST['id'])){
		$TEMPLATE['id'] = $_POST['id'];
	}
	if(isset($_POST['text']) && trim($_POST['title'])!=""){
		if(!isset($TEMPLATE['id'])){
			$TEMPLATE['id'] = $wiki->addNewArticle(urlencode($_POST['title']),$_POST['text']);
		}else{
			$wiki->updateArticle($TEMPLATE['id'],urlencode($_POST['title']),$_POST['text']);
		}
		$title=urlencode($_POST['title']);
	}elseif(isset($_POST['text'])){
		echo "Title is needed!";
	}
$wiki=Wiki\Wikilist::getInstance();

$includeNewArticle = false;
$found = true;

switch ($_GET['action']){
	case "remove":
		$found = $wiki->removeArticle($title);
		$TEMPLATE['removedTitle']=urldecode($title);
		if(!$found){
			$includeNewArticle = true;
		}
		break;
	case "new";
	case "change":
		$includeNewArticle = true;
		break;
	default:
		if($wiki->issetArticle($title)){
			$includeNewArticle = false;
		}
		break;
}
$TEMPLATE['paginatornumber']=$wiki->getPaginator();
if(isset($_GET['number']))
	$TEMPLATE['paginatorstart']=$_GET['number'];
else
	$TEMPLATE['paginatorstart']=0;
$min=$TEMPLATE['paginatorstart']*30;
$max=($TEMPLATE['paginatorstart']+1)*30;
$TEMPLATE['index']=$wiki->getIndexArray($min,$max);

if(!$found){
	$_GET['title'] = "";
}else{
	$article=$wiki->getArticle($title);
	$TEMPLATE['title']=$article->getTitle();
	$TEMPLATE['text']=$article->getText();
	$TEMPLATE['id']=$article->getID();
}
/*
if($includeNewArticle){
		if(!$found){
			$_GET['title'] = "";
		}else{
			$article=$wiki->getArticle(urlencode($_GET['title']));
			$TEMPLATE['title']=$article->getTitle();
			$TEMPLATE['text']=$article->getText();
			$TEMPLATE['id']=$article->getID();
		}
	
}
elseif( !is_null($title)){
		$article=$wiki->getArticle($title);
		$TEMPLATE['title']=$article->getTitle();
		$TEMPLATE['text']=$article->getText();
		$TEMPLATE['id']=$article->getID();
}
*/
?>
<div class="row-fluid">
<?php
	include 'template/wikilist.php';
	if(isset($TEMPLATE['removedTitle']))
		include 'template/removearticle.php';
	if(isset($_POST['searchtitle'])){
		$TEMPLATE['searchTest']=$_POST['searchtitle'];
		$TEMPLATE['searchList']=$wiki->searchArtikleTitle(urlencode($_POST['searchtitle']));
		include 'template/searchResult.php';
	}else {
		if($includeNewArticle)
			include 'template/newarticle.php';
		elseif(isset($TEMPLATE['title']) && !empty($TEMPLATE['title'])){
			include 'template/article.php';
		}else{
			include 'template/empty.php';
		}
	}
?>
</div>