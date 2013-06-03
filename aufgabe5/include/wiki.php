<?php
require_once 'php/wikilist.php';
require_once 'php/article.php';
require_once 'php/db.php';
require_once 'php/login.php';
require_once 'php/image.php';

use Wiki\Wikilist;
use Wiki\Login;
use Wiki\Article;
use Wiki\DB;
$TEMPLATE=array();
if(isset($_POST['username']) && isset($_POST['password'])){
	$TEMPLATE['user_id']=Wiki\Login::checkloginFirst($_POST['username'],$_POST['password']);
	if($TEMPLATE['user_id']!== false){
		Wiki\Login::createSession($_POST['username'],$_POST['password']);
	}else{
		Wiki\Login::newUser($_POST['username'],$_POST['password']);
		Wiki\Login::createSession($_POST['username'],$_POST['password']);
		$TEMPLATE['user_id']=Wiki\Login::checkloginSession();
	}
}else{
	$TEMPLATE['user_id']=Wiki\Login::checkloginSession();
}
//echo $_POST['user_id']=$_SESSION['user'];
$timestart= microtime();
$wiki=Wiki\Wikilist::getInstance();
//look if the id is given
if(isset($_GET['title_id'])){
	 $id=trim($_GET['title_id']);
}
if(isset($_POST['id']) && !empty($_POST['id'])){
	$TEMPLATE['id'] = trim($_POST['id']);
}
if(isset($_POST['text']) && trim($_POST['title'])!=""){
	if(!isset($_FILES["image"]["name"]) || $_FILES["image"]["name"] === ''){
		$image = false;
	}else{
		$image = $_FILES["image"]["name"];
	}
	if(!isset($TEMPLATE['id'])){
		$TEMPLATE['id'] = $wiki->addNewArticle(trim($_POST['title']),trim($_POST['text']),$TEMPLATE['user_id'],$image, $_POST['align']);
	}else{
		$wiki->updateArticle($TEMPLATE['id'],trim($_POST['title']),trim($_POST['text']),$TEMPLATE['user_id'],$image, $_POST['align']);
	}
	/**
	 * TODO
	 * */
	
}elseif(isset($_POST['text'])){
	echo "Title is needed!";
}
if(isset($TEMPLATE['id'])){
	$id=$TEMPLATE['id'];
}

$wiki=Wiki\Wikilist::getInstance();

$includeNewArticle = false;
$found = true;

if(isset($_GET['action'])){
	Wiki\Login::illegaltry();
	switch ($_GET['action']){
		case "remove":
			$art=$wiki->getArticle($id);
			$found = $wiki->removeArticle($id);
			$TEMPLATE['removedTitle']=$art->getTitle();
			if(!$found){
				$includeNewArticle = true;
			}
			break;
		case "new";
		case "change":
			$includeNewArticle = true;
			break;
		default:
			if($wiki->issetArticle($id)){
				$includeNewArticle = false;
			}
			break;
	}
}

$TEMPLATE['paginatornumber']=round($wiki->getPaginator()/2, 0, PHP_ROUND_HALF_DOWN)-1;
if(isset($_GET['number']))
	$TEMPLATE['paginatorstart']=$_GET['number'];
else
	$TEMPLATE['paginatorstart']=0;
$min=$TEMPLATE['paginatorstart']*10;
$max=10;
$TEMPLATE['index']=$wiki->getIndexArray($min,$max);

if($found && (isset($_GET['title_id']) || isset($_POST['id']))){
	$article=$wiki->getArticle($id);
	$TEMPLATE['title']=$article->getTitle();
	$TEMPLATE['text']=$article->getText();
	$TEMPLATE['id']=$article->getID();
	$TEMPLATE['UserModified']=$article->getUserModified();
	$TEMPLATE['UserCreate']=$article->getUserCreate();
	$TEMPLATE['dateMod']=$article->getDate();
	$TEMPLATE['parsedText'] = $article->getParsedText();
	$TEMPLATE['linklist'] = $article->getLinkList();
	$TEMPLATE['image'] = $article->getImageName();
	$TEMPLATE['align'] = $article->getAlign();
}
?>
<div class="row-fluid">
<?php
	include 'template/wikilist.php';
	if(isset($TEMPLATE['removedTitle'])){
	
		include 'template/removearticle.php';
	}elseif(isset($_POST['searchtitle']) && !empty($_POST['searchtitle']) || isset($_GET['searchtitle']) && !empty($_GET['searchtitle']) ){
		if(isset($_GET['searchpaginator']))
			$TEMPLATE['searchPaginatorStart']=$_GET['searchpaginator'];
		else
			$TEMPLATE['searchPaginatorStart']=0;
		 $min=$TEMPLATE['searchPaginatorStart']*10;
		 $max=10;
		$TEMPLATE['searchText']=trim($_POST['searchtitle']);
		if( isset($_GET['searchtitle']) )
			$TEMPLATE['searchText']=$_GET['searchtitle'];
		$TEMPLATE['searchList']=$wiki->searchArticleTitleLimit($TEMPLATE['searchText'],$min,$max);
			$TEMPLATE['searchPaginatorNumber']=round($wiki->searchArticleTitleCount($TEMPLATE['searchText'])/10, 0, PHP_ROUND_HALF_DOWN)-1;
		include 'template/searchResult.php';
	}else {
		if($includeNewArticle){
			include 'template/newarticle.php';
		}elseif(isset($TEMPLATE['title']) && !empty($TEMPLATE['title'])){
			include 'template/article.php';
		}else{
			include 'template/empty.php';
		}
	}
	$TEMPLATE['time']=microtime()-$timestart;
?>
</div>
