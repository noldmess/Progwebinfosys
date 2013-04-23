<?php
require_once 'php/wikilist.php';
require_once 'php/article.php';
require_once 'php/db.php';


use Wiki\Wikilist;
use Wiki\Article;
use Wiki\DB;
$TEMPLATE=array();
	session_set_cookie_params(604800); 
	session_start();
	$wiki=Wiki\Wikilist::getInstance();
	if(isset($_GET['title']))
	$title=trim($_GET['title']);
	if(isset($_POST['id']) && !empty($_POST['id'])){
		$TEMPLATE['id'] = $_POST['id'];
	}
	if(isset($_POST['text']) && trim($_POST['title'])!=""){
		if(!isset($TEMPLATE['id'])){
			$TEMPLATE['id'] = $wiki->addNewArticle(trim($_POST['title']),trim($_POST['text']));
		}else{
			$wiki->updateArticle($TEMPLATE['id'],trim($_POST['title']),trim($_POST['text']));
		}
		$title=trim($_POST['title']);
	}elseif(isset($_POST['text'])){
		echo "Title is needed!";
	}
$wiki=Wiki\Wikilist::getInstance();

$includeNewArticle = false;
$found = true;

if(isset($_GET['action'])){
	switch ($_GET['action']){
		case "remove":
			$found = $wiki->removeArticle($title);
			$TEMPLATE['removedTitle']=$title;
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
}

$TEMPLATE['paginatornumber']=$wiki->getPaginator();
if(isset($_GET['number']))
	$TEMPLATE['paginatorstart']=$_GET['number'];
else
	$TEMPLATE['paginatorstart']=0;
$min=$TEMPLATE['paginatorstart']*10;
$max=10;
$TEMPLATE['index']=$wiki->getIndexArray($min,$max);

if($found && isset($_GET['title'])){
	$article=$wiki->getArticle($title);
	$TEMPLATE['title']=$article->getTitle();
	$TEMPLATE['text']=$article->getText();
	$TEMPLATE['id']=$article->getID();
	$TEMPLATE['parsedText'] = $article->getParsedText();
	$TEMPLATE['linklist'] = $article->getLinkList();
}
?>
<div class="row-fluid">
<?php
	include 'template/wikilist.php';
	if(isset($TEMPLATE['removedTitle']))
		include 'template/removearticle.php';
	if(isset($_POST['searchtitle']) && !empty($_POST['searchtitle']) || isset($_GET['searchtitle']) && !empty($_GET['searchtitle']) ){
		if(isset($_GET['searchpaginator']))
			$TEMPLATE['searchPaginatorStart']=$_GET['searchpaginator'];
		else
		$TEMPLATE['searchPaginatorStart']=0;
		echo $min=$TEMPLATE['searchPaginatorStart']*2;
		echo $max=2;
		$TEMPLATE['searchText']=trim($_POST['searchtitle']);
		if( isset($_GET['searchtitle']) )
			$TEMPLATE['searchText']=$_GET['searchtitle'];
		$TEMPLATE['searchList']=$wiki->searchArticleTitleLimit(trim($_POST['searchtitle']),$min,$max);
			$TEMPLATE['searchPaginatorNumber']=round($wiki->searchArticleTitleCount(trim($_POST['searchtitle']))/2, 0, PHP_ROUND_HALF_DOWN);
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