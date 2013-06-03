<?php
include 'template/top.php';

switch ($_GET['page']){
	case 'wiki':
		include 'include/wiki.php';
		break;
	case 'info':
		include 'template/info.php';
		break;
	default:
		include 'template/index.php';
		break;
}
include 'template/bottom.php';