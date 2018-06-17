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

	function getUserFavorites($uid, $limit=NULL, $db) {
		/* Returns a `limit` length array of a provided user's favorite articles. */
		$favorites = getFavoriteIDs($uid, $db);
	    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ArticleID = {$favorites[0]} ";
	    foreach(array_slice($favorites, 1) as &$val) { // Gather results for all other specified editor picks
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

	function addToFavorites($uid, $aid, $db) {
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
?>
