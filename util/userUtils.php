<?php
	if(!function_exists("mysqliToArray")) {
		function mysqliToArray($a) {
		    $data = array();
		    while($row = mysqli_fetch_array($a, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
		        $data[] = $row;
		    }
		    return $data;
		}
	}

	function getUserById($uid, $db) {
		/* Returns an associated array for a user with provided matching userID.
		   Will return all columns other than the user's hashed password. */
		$sql = "SELECT UserID,FirstName,LastName,Email,Username,Usertype,ProfilePicture,Bio,TimeZone FROM User WHERE UserID = {$uid};";
		$result = mysqli_query($db, $sql);
	    if(mysqli_num_rows($result) == 0) {
	        return null;
	    }
	    return mysqli_fetch_assoc($result);
	}
	function isFavorite($uid, $aid, $db) {
		/* Returns TRUE if article of provided article ID is a favorite of user of provided user ID, otherwise returns FALSE. */
		$sql = "SELECT * FROM Favorite WHERE ArticleID = {$aid} AND UserID = {$uid};";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result) > 0;
	}
	function getFavoriteIDs($uid, $db) {
		$sql = "SELECT ArticleID FROM Favorite WHERE UserId = {$uid};";
		$result = mysqli_query($db, $sql);
	    $data = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
	        $data[] = $row['ArticleID'];
	    }
	    return $data;
	}
	function getUserFavorites($uid, $category=NULL, $limit=NULL, $db) {
		/* Returns a `limit` length array of a provided user's favorite articles.
		   If `category` is provided, will return an array of favorites from only the provided section name. */
		$favorites = getFavoriteIDs($uid, $db);
	    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0";
	    if(!is_null($category)) {
	    	$sql .= " AND Category = '{$category}'";
	    }

	    $sql .= " AND ArticleID = {$favorites[0]}";
	    foreach(array_slice($favorites, 1) as &$val) { // Gather results for all other specified editor picks
	        $sql .= " OR ArticleID = {$val}";
	    }
            if(!is_null($limit)) {
	        $sql .= " LIMIT {$limit};";
	    }
	    $result = mysqli_query($db, $sql);
	    if(!$result || mysqli_num_rows($result) == 0) {
	        return null;
	    }
	    return mysqliToArray($result);
	}

	function addToFavorites($uid, $aid, $db) {
		/* Adds a favorite record for a user of provided userID for article of provided articleID. */
		$sql = "INSERT INTO Favorite VALUES($uid, $aid);";
		if(mysqli_query($db, $sql)) {
			return TRUE; // Success
		} else {
			return mysqli_error($db);
		}
	}
	function removeFromFavorites($uid, $aid, $db) {
		$sql = "DELETE FROM Favorite WHERE UserID = {$uid} AND ArticleID = {$aid};";
		if(mysqli_query($db, $sql)) {
			return TRUE; // Success
		} else {
			return mysqli_error($db);
		}
	}
// COMMENTS
	function getCommentUserIDs($uid, $db) {
		$sql = "SELECT UserID FROM Comment WHERE UserId = {$uid};";
		$result = mysqli_query($db, $sql);
	    $data = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
	        $data[] = $row['UserID'];
	    }
	    return $data;
	}
	function getCommentArticleIDs($uid, $db) {
		$sql = "SELECT ArticleID FROM Comment WHERE UserId = {$uid};";
		$result = mysqli_query($db, $sql);
	    $data = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
	        $data[] = $row['ArticleID'];
	    }
	    return $data;
	}
	function getUserCommentArticles($uid, $limit=NULL, $db) {
		/* Returns a `limit` length array of a provided user's favorite articles. */
		$comments = getCommentArticleIDs($uid, $db);
	    $sql = "SELECT * FROM Article WHERE ArticleID = {$comments[0]} ";
	    foreach(array_slice($comments, 1) as &$val) { // Gather results for all other specified editor picks
	        $sql .= "OR ArticleID = {$val} ";
	    }
            if(!is_null($limit)) {
	        $sql .= "LIMIT {$limit};";
	    }
	    $result = mysqli_query($db, $sql);
	    if(!$result || mysqli_num_rows($result) == 0) {
	        return null;
	    }
	    return mysqliToArray($result);
	}
	function getUserComments($uid, $limit=NULL, $db) {
		/* Returns a `limit` length array of a provided user's favorite articles. */
		$comments = getCommentUserIDs($uid, $db);
	    $sql = "SELECT * FROM Comment WHERE UserID = {$uid} ";
	    foreach(array_slice($comments, 1) as &$val) { // Gather results for all other specified editor picks
	        $sql .= "OR UserID = {$val} ";
	    }
            if(!is_null($limit)) {
	        $sql .= "LIMIT {$limit};";
	    }
	    $result = mysqli_query($db, $sql);
	    if(!$result || mysqli_num_rows($result) == 0) {
	        return null;
	    }
	    return mysqliToArray($result);
	}
	function postComment($aid, $content, $replyTo=NULL, $db) {
		if(empty($_SESSION['userid'])) { // Prevent posting comments when not logged in.
			// return;	
			$_SESSION['userid'] = 13; //DEBUG
		}
		if(is_null($replyTo)) {
			$replyTo = "NULL"; // Convert to string NULL for the query.
		}
		$sql = "INSERT INTO Comment (ReplyToID, UserID, ArticleID, CommentText) VALUES ({$replyTo}, {$_SESSION['userid']}, {$aid}, '{$content}')";
		$query = $db->query($sql);
		if(!$query) { 
			return FALSE;
		}
		return TRUE;
	}

	/* TODO: Change to getRootComments and getReplies functions */
	function getArticleComments($aid, $db) {
		/* Returns an array of comments from an article with articleID specified with `aid`. */
		$sql = "SELECT * FROM Comment WHERE ArticleID = {$aid} ORDER BY CommentDate DESC";
		return mysqliToArray(mysqli_query($db, $sql));
	}
?>