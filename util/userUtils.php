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

// GENERAL FUNCTIONS
	function getUserById($uid, $db) {
		/* Returns an associated array for a user with provided matching userID.
		   Will return all columns other than the user's hashed password. */
		$sql = "SELECT UserID,FirstName,LastName,Email,Username,Usertype,ProfilePicture,Bio,TimeZone,City,State,DateFormat FROM User WHERE UserID = {$uid};";
		$result = mysqli_query($db, $sql);
		if(!$result) { return null; }
	    if(mysqli_num_rows($result) == 0) {
	        return null;
	    }
	    return mysqli_fetch_assoc($result);
	}
	function userExists($uid, $db) {
		/* Returns True if a user with the provided user id exits, false otherwise. */
		$sql = "SELECT * FROM User WHERE UserID = {$uid}";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result) > 0;
	}
	function getHashedPassword($uid, $db) {
		/* Returns the hashed password from the user of provided user ID. */
		$sql = "SELECT Password FROM User WHERE UserID = {$uid}";
		$result = mysqli_fetch_array(mysqli_query($db, $sql));
		if($result) {
			return $result['Password'];
		}
	}
	function verifyValidPassword($password) {
		/* Will return TRUE if the provided string meets site security requirements, otherwise will return a specific error string. */
		if(!preg_match('/^.{6,}+$/', $password)) {
        	return "Password must be at least 6 characters.";
        }
        if(!preg_match('/[A-Z]/', $password)) {
        	return "Password must contain at least one uppercase letter.";
    	}
    	if(!preg_match('/[a-z]/', $password)) {
        	return "Password must contain at least one lowercase letter.";
    	}
    	if(!preg_match('/[0-9]/', $password)) {
        	return "Password must contain at least one number.";
    	}
    	return TRUE;
    }
    function getUserTimezone($uid, $db) {
    	/* Returns a user of provided userID's timezone, if one is set. */
    	$sql = "SELECT Timezone FROM User WHERE UserID = {$uid}";
    	$result = mysqli_query($db, $sql);
		if($result) {
			return mysqli_fetch_array($result)['Timezone'];
		}
		return NULL;
    }
    function getUserDateFormat($uid, $db) {
    	/* Returns the date format from a user of provided user ID, if one is set. */
    	$sql = "SELECT DateFormat FROM User WHERE UserID = {$uid}";
    	$result = mysqli_query($db, $sql);
		if($result) {
			return mysqli_fetch_array($result)['DateFormat'];
		}
		return NULL;
    }

// BOOKMARKING FUNCTIONS
	function isBookmark($uid, $aid, $db) {
		/* Returns TRUE if article of provided article ID is a bookmark of user of provided user ID, otherwise returns FALSE. */
		$sql = "SELECT * FROM Bookmark WHERE ArticleID = {$aid} AND UserID = {$uid};";
		$result = mysqli_query($db, $sql);
		if(!$result) { 
			return FALSE;
		}
		return mysqli_num_rows($result) > 0;
	}
	function getBookmarkIDs($uid, $db) {
		$sql = "SELECT ArticleID FROM Bookmark WHERE UserId = {$uid};";
		$result = mysqli_query($db, $sql);
	    $data = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
	        $data[] = $row['ArticleID'];
	    }
	    return $data;
	}
	function getUserBookmarks($uid, $category=NULL, $limit=NULL, $db) {
		/* Returns a `limit` length array of a provided user's bookmark articles.
		   If `category` is provided, will return an array of bookmarks from only the provided section name. */
		$bookmarks = getBookmarkIDs($uid, $db);
		if(empty($bookmarks) || is_null($bookmarks)) {
			return null;
		}
	    $sql = "SELECT * FROM (SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0";
	    

	    $sql .= " AND ArticleID = {$bookmarks[0]}";
	    foreach(array_slice($bookmarks, 1) as &$val) { // Gather results for all other specified editor picks
	        $sql .= " OR ArticleID = {$val}";
	    }
	    $sql .= ") AS A";
	    if(!is_null($category)) {
	    	$sql .= " WHERE Category = '{$category}'";
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
	function addBookmark($uid, $aid, $db) {
		/* Adds a bookmark record for a user of provided userID for article of provided articleID. */
		$sql = "INSERT INTO Bookmark VALUES({$uid}, {$aid});";
		if(mysqli_query($db, $sql)) {
			return TRUE; // Success
		} else {
			return mysqli_error($db);
		}
	}
	function removeFromBookmarks($uid, $aid, $db) {
		/* Removes a bookmark record for a user of provided userID for an article of provided articleID. */
		$sql = "DELETE FROM Bookmark WHERE UserID = {$uid} AND ArticleID = {$aid};";
		if(mysqli_query($db, $sql)) {
			return TRUE; // Success
		} else {
			return mysqli_error($db);
		}
	}


// RATING FUNCTIONS
	function getArticleUserRating($uid, $aid, $db) {
		/* Returns an integer representing the score the user of given user ID gave to an article of given article ID. */
		$sql = "SELECT Score FROM Rating WHERE USERID = {$uid} AND ArticleID = {$aid}";
		$query = mysqli_query($db, $sql);
		if(!$query) {
			return NULL;
		}
		$result = mysqli_fetch_array($query);
    	return $result['Score'];
	}
	function rateArticle($uid, $aid, $score, $db) {
		// Will insert a new rating score for the provided user to the provided article if none exists, or will update an existing score otherwise.
		$sql = "INSERT INTO Rating (UserID, ArticleID, Score) VALUES ({$uid}, {$aid}, {$score}) ON DUPLICATE KEY UPDATE Score={$score}";
		if(mysqli_query($db, $sql)) {
			return TRUE; // Success
		} else {
			return mysqli_error($db);
		}
	}


// COMMENTING FUNCTIONS
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
		/* Returns an array of integers, representing articleIDs of articles on which a user of provided userID has commented. */
		$sql = "SELECT ArticleID FROM Comment WHERE UserId = {$uid};";
		$result = mysqli_query($db, $sql);
	    $data = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
	        $data[] = $row['ArticleID'];
	    }
	    return $data;
	}
	function getUserCommentArticles($uid, $limit=NULL, $db) {
		/* Returns a `limit` length array of articles that a user of provided userID has commented on. */
		$comments = getCommentArticleIDs($uid, $db);
	    $sql = "SELECT * FROM Article WHERE ArticleID = {$comments[0]} ";
	    foreach(array_slice($comments, 1) as &$val) {
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
		/* Returns an array of comments from a user of provided userID. Optionally can be limited to a set size if `limit` is provided. */
	    $sql = "SELECT * FROM Comment WHERE UserID = {$uid}";
        if(!is_null($limit)) {
	        $sql .= "LIMIT {$limit}";
	    }
	    $sql .= " ORDER BY CommentDate DESC";
	    $result = mysqli_query($db, $sql);
	    if(!$result || mysqli_num_rows($result) == 0) {
	        return null;
	    }
	    return mysqliToArray($result);
	}

	function postComment($aid, $uid, $content, $replyTo=NULL, $db) {
		/* Posts a comment into the database of provided content to an article of provided articleID. */
		if(is_null($replyTo)) {
			$replyTo = "NULL"; // Convert to string NULL for the query.
		}
		$content = htmlspecialchars(mysqli_escape_string($db, $content), ENT_QUOTES);
		$sql = "INSERT INTO Comment (ReplyToID, UserID, ArticleID, CommentText) VALUES ({$replyTo}, {$uid}, {$aid}, '{$content}')";
		$query = $db->query($sql);
		if(!$query) { 
			return FALSE;
		}
		return TRUE;
	}

	function getArticleRootComments($aid, $db) {
		/* Returns an array of comments from an article with articleID specified with `aid` which do not reply to any comment. */
		$sql = "SELECT * FROM Comment WHERE ArticleID = {$aid} AND ReplyToID IS NULL ORDER BY CommentDate DESC";
		return mysqliToArray(mysqli_query($db, $sql));
	}
	function getCommentReplies($aid, $cid, $db) {
		/* Returns an array of comments that reply to a comment matching the provided commentID on an article of provided articleID. */
		$sql = "SELECT * FROM Comment WHERE ArticleID = {$aid} AND ReplyToID = {$cid}";
		return mysqliToArray(mysqli_query($db, $sql));
	}

	function getCommentAuthorID($cid, $db) {
		/* Returns the userID of a comment of provided commentID. */
		$sql = "SELECT UserID FROM Comment WHERE CommentID = {$cid}";
		$result = mysqli_fetch_array(mysqli_query($db, $sql));
		if($result) {
			return $result['UserID'];
		}
		return NULL;
	}
	function updateComment($cid, $text, $db) {
		/* Updates a comment with provided comment ID to the provided text. Will also set the edited flag for this comment. */
		$sql = "UPDATE Comment SET CommentText='{$text}', Edited=TRUE WHERE CommentID={$cid}";
		return mysqli_query($db, $sql);
	}
	function deleteComment($cid, $db) {
		/* Deletes the comment with provided comment ID from the database. */
		$sql = "UPDATE Comment SET CommentText=NULL WHERE CommentID={$cid}";
		return mysqli_query($db, $sql);
	}


// PROFILE PAGE FUNCTIONS
	function getNumUserBookmarks($uid, $db) {
		/* Returns an integer value representing the number of bookmarks for a user with provided user id. */
		$sql = "SELECT * FROM Bookmark WHERE UserID = {$uid}";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result);
	}
	function getNumUserComments($uid, $db) {
		/* Returns an integer value representing the number of comments for a user with provided user id. */
		$sql = "SELECT * FROM Comment WHERE UserID = {$uid}";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result);
	}
	function getNumUserArticlesWritten($uid, $db) {
		/* Returns an integer value representing the number of articles written for a user with provided user id. */
		$sql = "SELECT * FROM Article WHERE UserID = {$uid} AND IsDraft = 0 AND IsSubmitted = 1";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result);
	}
	function getNumUserRatings($uid, $db) {
		/* Returns an integer value representing the number of ratings for a user with provided user id. */
		$sql = "SELECT * FROM Rating WHERE UserID = {$uid}";
		$result = mysqli_query($db, $sql);
		return mysqli_num_rows($result);
	}
	function getArticlesByUserID($uid, $db) {
		/* Will return an array of all articles written by a user of provided userID. */
		$sql = "SELECT * FROM Article WHERE UserID = {$uid}";
		return mysqliToArray(mysqli_query($db, $sql));
	}


// SETTINGS PAGE FUNCTIONS
	function updateBio($uid, $bio, $db) {
		/* Updates the Bio field of a user of provided User ID. */
		$bio = htmlspecialchars(mysqli_escape_string($db, $bio), ENT_QUOTES);
		$sql = "UPDATE User SET Bio = '{$bio}' WHERE UserID = {$uid}";
		return mysqli_query($db, $sql);
	}
	function updateName($uid, $fname=NULL, $lname=NULL, $db) {
		/* Updates the first or last name, if provided, of user matching provided User ID. */
		if(!is_null($fname)) {
			$fname = htmlspecialchars(mysqli_escape_string($db, $fname), ENT_QUOTES);
			$sql = "UPDATE User SET FirstName = '{$fname}' WHERE UserID = {$uid}";
			if(!mysqli_query($db, $sql)) { return false; }
		}
		if(!is_null($lname)) {
			$lname = htmlspecialchars(mysqli_escape_string($db, $lname), ENT_QUOTES);
			$sql = "UPDATE User SET LastName = '{$lname}' WHERE UserID = {$uid}";
			if(!mysqli_query($db, $sql)) { return false; }
		}
		return true;
	}
	function updateLocation($uid, $city, $state, $db) {
		if(!is_null($city)) {
			$city = htmlspecialchars(mysqli_escape_string($db, $city), ENT_QUOTES);
			$sql = "UPDATE User SET City = '{$city}' WHERE UserID = {$uid}";
			if(!mysqli_query($db, $sql)) { return false; }
		}
		if(!is_null($state)) {
			$state = htmlspecialchars(mysqli_escape_string($db, $state), ENT_QUOTES);
			$sql = "UPDATE User SET State = '{$state}' WHERE UserID = {$uid}";
			if(!mysqli_query($db, $sql)) { return false; }
		}
		return true;
	}
	function updateDateformat($uid, $df, $db) {
		/* Updates the Date format field of a user of provided User ID. */
		$df = mysqli_escape_string($db, $df);
		$sql = "UPDATE User SET DateFormat = '{$df}' WHERE UserID = {$uid}";
		if(!mysqli_query($db, $sql)) { return false; }
		return true;
	}
	function updateTimezone($uid, $tz, $db) {
		/* Updates the Timezone field of a user of provided User ID. */
		$tz = mysqli_escape_string($db, $tz);
		$sql = "UPDATE User SET TimeZone = '{$tz}' WHERE UserID = {$uid}";
		if(!mysqli_query($db, $sql)) { return false; }
		return true;
	}
    function updateUserPassword($uid, $password, $db) {
		/* Updates the Password field of a user of provided User ID. */
    	$sql = "UPDATE User SET Password = '{$password}' WHERE UserID = {$uid}";
    	if(!mysqli_query($db, $sql)) { return false; }
		return true;
    }
    function updateEmail($uid, $email, $db) {
		/* Updates the Email field of a user of provided User ID. */
    	$sql = "UPDATE User SET Email = '{$email}' WHERE UserID = {$uid}";
    	if(!mysqli_query($db, $sql)) { return false; }
		return true;
    }
    function deleteUser($uid, $db) {
    	/* Removes user of matching user ID from the database. */
    	$sql = "DELETE FROM User WHERE UserID = {$uid}";
    	if(!mysqli_query($db, $sql)) { return false; }
		return true;
    }
?>
