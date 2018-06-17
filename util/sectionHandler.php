<?php
include('db.php');
include('articleUtils.php');

if(!empty($_GET)) {
	if(!empty($_GET['category'])) {
		print json_encode(getSectionArticles($_GET['category'], 3, NULL, $db));
	}
}

?>