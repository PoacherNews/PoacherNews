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
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_assoc($result);
}

function getRelatedArticles($category, $excludeId, $db) {
	/* Returns an array of MYSQL results.
	   Intended for display in the three-column "Further Reading" section of the article page.
	*/
	$sql = "SELECT ArticleId,Headline,Body FROM Article WHERE IsSubmitted = 1 AND ArticleId != {$excludeId} AND Category = '{$category}' ORDER BY PublishDate DESC LIMIT 6;";
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

function getEditorPicks($db) {
    $editorPicks = getEditorPickIDs($db);
    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ArticleID = {$editorPicks[0]} ";
    foreach(array_slice($editorPicks, 1) as &$val) { // Gather results for all other specified editor picks
        $sql .= "OR ArticleID = {$val} ";
    }
    $sql .= ";";

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

function getTrendingArticles($limit, $db) {
    /* Returns an array of trending articles. */
    $trendingDaySpan = 25;
    $trendingViewThreshold = 0;

    $sql = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ArticleId != ".getMainArticleID($db)." ";
    foreach(getEditorPickIDs($db) as &$val) {
        $sql .= "AND ArticleID != ".$val." ";
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

?>
