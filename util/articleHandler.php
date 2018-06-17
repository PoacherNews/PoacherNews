<?php
session_start();
include('db.php');
include('userUtils.php');

if(!empty($_GET)) {
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
}

?>