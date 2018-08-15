<?php
session_start();
include('db.php');
include('userUtils.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
	// Functionality for page actions (rating, bookmarking, unbookmarking)
	if(!empty($_POST['request'])) {
		switch($_POST['request']) {
			case "bookmark":
				if(empty($_POST['aid'])) { // Article ID not provided
					exit;
				}
				if(empty($_SESSION['userid'])) { // User is trying to bookmark an article while not logged in
					exit;
				}
				if(isBookmark($_SESSION['userid'], $_POST['aid'], $db)) { // User is trying to favorite an article they already favorited
					exit;
				}
				
				if(addBookmark($_SESSION['userid'], $_POST['aid'], $db)) {
					print "Success";
				} else {
					print "Error";
				}
				break;
			case "unbookmark":
				if(empty($_POST['aid']) || empty($_SESSION['userid'])) { // Article ID not provided or not logged in
					exit;
				}
				if(!isBookmark($_SESSION['userid'], $_POST['aid'], $db)) { // User is trying to unfavorite an article they haven't favorited
					exit;
				}

				if(removeFromBookmarks($_SESSION['userid'], $_POST['aid'], $db)) {
					print "Success";
				} else {
					print "Error";
				}
				break;
			case "rate":
				if(empty($_POST['score']) || empty($_SESSION['userid'])) {
					exit;
				}
				if(rateArticle($_SESSION['userid'], $_POST['aid'], $_POST['score'], $db)) {
					print "Success";
				} else {
					print "Error";
				}
				break;
			case "editComment":
				if(empty($_POST['cid']) || empty($_POST['edit'])) {
					exit;
				}
				if(getCommentAuthorID($_POST['cid'], $db) != $_SESSION['userid']) { // Someone is trying to update a comment they didn't author
					exit;
				}
				updateComment($_POST['cid'], $_POST['edit'], $db);
				break;
			case "deleteComment":
				if(empty($_POST['cid'])) {
					exit;
				}
				if(getCommentAuthorID($_POST['cid'], $db) != $_SESSION['userid']) { // Someone is trying to delete a comment they didn't author
					exit;
				}
				deleteComment($_POST['cid'], $db);
				break;
		}
		exit;
	}

	// Comment posting functionality
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
