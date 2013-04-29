<?php
include 'template/top.php';

if(isset($_GET['page'])){
	switch ($_GET['page']){
		case 'wiki':
			include 'include/wiki.php';
			break;
		case 'info':
			include 'template/info.php';
			break;
		case 'login':
			include 'template/login.php';
			break;
		default:
			include 'template/index.php';
			break;
	}
}else{
	include 'template/index.php';
}
include 'template/bottom.php';