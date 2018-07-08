<?php
session_start();
include('db.php');
include('userUtils.php');

// $_SERVER['REQUEST_METHOD'] = 'POST';
// $_POST['aid'] = 3;
// $_SESSION['uid'] = 13;
// $_POST['content'] = "manual test";

if($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
	if($_GET['request'] == "bookmark") {
		if(empty($_GET['aid'])) { // Article ID not provided
			exit;
		}
		if(empty($_SESSION['userid'])) { // User is trying to favorite an article while not logged in
			exit;
		}
		if(isBookmark($_SESSION['userid'], $_GET['aid'], $db)) { // User is trying to favorite an article they already favorited
			exit;
		}
		
		if(addBookmark($_SESSION['userid'], $_GET['aid'], $db)) {
			print "Success";
		} else {
			print "Error";
		}
		exit;
	} else if($_GET['request'] == "unbookmark") {
		if(empty($_GET['aid']) || empty($_SESSION['userid'])) { // Article ID not provided or not logged in
			exit;
		}
		if(!isBookmark($_SESSION['userid'], $_GET['aid'], $db)) { // User is trying to unfavorite an article they haven't favorited
			exit;
		}

		if(removeFromFavorites($_SESSION['userid'], $_GET['aid'], $db)) {
			print "Success";
		} else {
			print "Error";
		}
		exit;
	} else if($_GET['request'] == "rate") {
		if(empty($_GET['score']) || empty($_SESSION['userid'])) {
			exit;
		}
		if(rateArticle($_SESSION['userid'], $_GET['aid'], $_GET['score'], $db)) {
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
		http_response_code(400);
	}
	if(empty($_SESSION['loggedin']) || !isset($_SESSION['loggedin'])) {
		http_response_code(400);
	}
	$replyTo = empty($_POST['replyTo']) ? NULL : $_POST['replyTo'];

	print(postComment($_POST['aid'], $_SESSION['userid'], $_POST['content'], $replyTo, $db));
}



?>