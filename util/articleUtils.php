<?php

function mysqliToArray($a) {
    $data = array();
    while($row = mysqli_fetch_array($a, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
        $data[] = $row;
    }
    return $data;
}

function getArticleByID($id, $db) {
	/* Returns a PHP associative array from the MYSQL result for the article of the specified ID.
	   Returns null if there does not exist an article with the provided ID.
	*/
  	$sql = "SELECT * FROM Article WHERE ArticleID = ".$id.";";
    $result = mysqli_query($db, $sql);
    if(mysqli_num_rows($result) == 0) {
        return null;
    }
    return mysqli_fetch_assoc($result);
}

function getAuthorByID($id, $db) {
    /* Returns an associative array representation of the MYSQL result for the author of the provided id. */
    $sql = "SELECT * FROM User WHERE UserID = {$id};";
    return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

function getSectionArticles($category, $limit=NULL, $offset=NULL, $db) {
    /* Returns an array of articles in a provided category.
       Optionally can provide only a provided limit of articles, and/or optionally return articles from after a given offset.
    */
    $sql = "SELECT * FROM Article WHERE IsDraft = 0 AND IsSubmitted = 1 AND Category = '{$category}' ";
    if(!is_null($limit)) {
        $sql .= "LIMIT {$limit}";
    }
    if(!is_null($offset)) {
        $sql .= " OFFSET {$offset}";
    }
    $result = mysqli_query($db, $sql);
    return mysqliToArray($result);
}

function getRelatedArticles($category, $excludeId, $db) {
	/* Returns an array of MYSQL results.
	   Intended for display in the three-column "Further Reading" section of the article page.
	*/
	$sql = "SELECT ArticleId,Headline,Body FROM Article WHERE IsDraft = 0 AND IsSubmitted = 1 AND ArticleId != {$excludeId} AND Category = '{$category}' ORDER BY PublishDate DESC LIMIT 6;";
	$result = mysqli_query($db, $sql);

    return mysqliToArray($result);
}

function getEditorPickIDs($db) {
    /* Returns an array of article IDs that are labled as 'EditorPick'. */
    $sql = "SELECT ArticleID FROM Featured WHERE FeaturedType = 'EditorPick';";
    $result = mysqli_query($db, $sql);

    $data = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
        $data[] = $row['ArticleID'];
    }
    return $data;
}

function getEditorPicks($category=NULL, $limit=NULL, $db) {
    /* Returns an array of editor picks, optionally from a provided section in `category`.
       If `limit` is specified, it will return an array of that size. */
    $editorPicks = getEditorPickIDs($db);
    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0";
    if(!is_null($category)) {
        $sql .= " AND Category = '{$category}'";
    }

    $sql .= " AND ArticleID = {$editorPicks[0]} ";
    foreach(array_slice($editorPicks, 1) as &$val) { // Gather results for all other specified editor picks
        $sql .= "OR ArticleID = {$val} ";
    }

    if(!is_null($limit)) {
        $sql .= "LIMIT {$limit}";
    }

    $result = mysqli_query($db, $sql);
    if(!$result || mysqli_num_rows($result) == 0) {
        return null;
    }
    return mysqliToArray($result);
}

function getMainArticleID($db) {
    /* Returns the ID of the article labled as 'Main */
    $sql = "SELECT ArticleID FROM Featured WHERE FeaturedType = 'Main';";
    $result = mysqli_query($db, $sql);

    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['ArticleID'];
}

function getMainArticle($db) {
    /* Returns the current main article. */
    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ArticleID = ".getMainArticleID($db).";";
    $result = mysqli_query($db, $sql);

    $data[] = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $data;
}

function getTrendingArticles($category=NULL, $limit, $db) {
    /* Returns a `limit` length array of trending articles.
       If `category` is specified, it will return trending articles only from the provided section. */
    $trendingDaySpan = 25;
    $trendingViewThreshold = 0;

    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ArticleId != ".getMainArticleID($db);
    if(!is_null($category)) {
        $sql .= " AND Category = '{$category}'";
    }

    foreach(getEditorPickIDs($db) as &$val) {
        $sql .= " AND ArticleID != ".$val." ";
    }
    $sql .= "AND PublishDate >= DATE(NOW()) - INTERVAL ".$trendingDaySpan." DAY AND Views > ".$trendingViewThreshold." ";
    $sql .= "LIMIT ".$limit.";";

    $result = mysqli_query($db, $sql);
    if(!$result || mysqli_num_rows($result) == 0) {
        return null;
    }
    return mysqliToArray($result);
}

function increaseViewCount($id, $db) {
    $sql = "UPDATE Article SET Views = Views + 1 WHERE ArticleId = {$id};";
    $db->query($sql);
}

function getNumFavorites($aid, $db) {
    /* Returns an integer value representing the number of favorites an article given by a provided ArticleID has. */
    $sql = "SELECT * FROM Favorite WHERE ArticleID = {$aid}";
    $result = mysqli_query($db, $sql);
    return mysqli_num_rows($result);
}

function getRatingByID($aid, $db) {
    $sql = "SELECT AVG(Score) FROM Rating WHERE ArticleID = {$aid}";
    $result = mysqli_fetch_array(mysqli_query($db, $sql));
    return $result['AVG(Score)'];
}

?>
