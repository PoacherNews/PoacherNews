<?php

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
	$sql = "SELECT ArticleId,Headline,Body FROM Article WHERE IsPublished = 1 AND ArticleId != {$excludeId} AND Category = '{$category}' ORDER BY PublishDate DESC LIMIT 6;";
	$result = mysqli_query($db, $sql);
	
	$data = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
        $data[] = $row;
    }
    return $data;
}

function increaseViewCount($id, $db) {
    $sql = "UPDATE Article SET Views = Views + 1 WHERE ArticleId = {$id};";
    $db->query($sql);
}

?>
