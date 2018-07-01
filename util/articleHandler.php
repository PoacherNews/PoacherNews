<?php
session_start();
include('db.php');
include('userUtils.php');

// $_SERVER['REQUEST_METHOD'] = 'POST';
// $_POST['aid'] = 3;
// $_SESSION['uid'] = 13;
// $_POST['content'] = "manual test";

if($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
	if($_GET['request'] == "favorite") {
		if(empty($_GET['aid'])) { // Article ID not provided
			exit;
		}
		if(empty($_SESSION['userid'])) { // User is trying to favorite an article while not logged in
			exit;
		}
		if(isFavorite($_SESSION['userid'], $_GET['aid'], $db)) { // User is trying to favorite an article they already favorited
			exit;
		}
		
		if(addToFavorites($_SESSION['userid'], $_GET['aid'], $db)) {
			print "Success";
		} else {
			print "Error";
		}
		exit;
	} else if($_GET['request'] == "unfavorite") {
		if(empty($_GET['aid'])) { // Article ID not provided
			exit;
		}
		if(empty($_SESSION['userid'])) { // User is trying to unfavorite an article while not logged in
			exit;
		}
		if(!isFavorite($_SESSION['userid'], $_GET['aid'], $db)) { // User is trying to unfavorite an article they haven't favorited
			exit;
		}

		if(removeFromFavorites($_SESSION['userid'], $_GET['aid'], $db)) {
			print "Success";
		} else {
			print "Error";
		}

		exit;
	} else {
		exit;
	}
} else if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
	if(empty($_POST['aid']) || empty($_POST['content'])) { // Exit if not all required elements are included in POST
		exit;
	}
	if(empty($_POST['replyTo'])) {
		$replyTo = NULL;
	} else { $replyTo = $_POST['replyTo']; }

	print(postComment($_POST['aid'], $_SESSION['userid'], $_POST['content'], $replyTo, $db));
}



?>