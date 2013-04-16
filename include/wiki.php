<?php

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
	if(isset($_POST['id'])){
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
		$title=null;
		break;
	case "new";
	case "change":
		$title=null;
		$includeNewArticle = true;
		break;
	default:
		if($wiki->issetArticle($title)){
			$includeNewArticle = true;
			$title=null;
		}
		break;
}

$TEMPLATE['index']=$wiki->getIndexArray();
$TEMPLATE['new']=false;
if($includeNewArticle){
		if(!$found){
			$_GET['title'] = "";
		}else{
			$article=$wiki->getArticle(urlencode($_GET['title']));
			$TEMPLATE['text']=$article->getText();
			$TEMPLATE['id']=$article->getID();
		}
		
	$TEMPLATE['new']=true;
}
elseif( !is_null($title)){
		$wiki=Wiki\Wikilist::getInstance();
		$article=$wiki->getArticle($title);
		echo $article->getTitle();
		$TEMPLATE['title']=$article->getTitle();
		$TEMPLATE['text']=$article->getText();
		$TEMPLATE['id']=$article->getID();
}
?>
<div class="row-fluid">
<?php 
	include 'template/wikilist.php';
	if(isset($TEMPLATE['removedTitle']))
		include 'template/removearticle.php';
	if($TEMPLATE['new'])
		include 'template/newarticle.php';
	else
		include 'template/article.php';
?>
</div>