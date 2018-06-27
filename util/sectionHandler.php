<?php
include('db.php');
include('articleUtils.php');

$defaultSectionLimit = 3;

if(!empty($_GET)) {
	$offset = isset($_GET['offset']) ? $_GET['offset'] : NULL;
	if(!empty($_GET['category'])) {
		print json_encode(getSectionArticles($_GET['category'], $defaultSectionLimit, ($offset * $defaultSectionLimit), $db));
	}
}

?>