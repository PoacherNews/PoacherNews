<?php

function getArticleByID($id, $db) {
	/* Returns a PHP associative array from the MYSQL result for the article of the specified ID.
	   Returns null if there does not exist an article with the provided ID.
	*/
  	$sql = "SELECT * FROM Articles WHERE ArticleID = ".$id.";";
    $result = mysqli_query($db, $sql);
    if(mysqli_num_rows($result) == 0) {
        return null;
    }
    return mysqli_fetch_assoc($result);
}

function getAuthorByID() {
	// TODO: Wait for Bruce or Kirtis (mainly bruce cause kirits doesnt know what the fuck to do) to fix the database!
}


function getRelatedArticles($category) {
	/* Returns a 3-item array, each containing an array of two JSON-encoded MYSQL results.
	   Intended for display in the three-column "Further Reading" section of the article page.
	*/
}

//getArticleByID(97);

?>