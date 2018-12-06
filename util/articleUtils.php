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

function decodeArticleBodyFormatting($body) {
    $output = preg_replace('/&quot;/', '"', $body); // Replaces html &quot; with actual quotes (")
    $output = preg_replace('/\{a\s+href="([^"]*)"\s+target="([^"]*)"\}([\w|\s]+)\{\/a\}/', '<a href="http://$1" target="$2">$3</a>', $output); // Repalces {a href="foo" target="bar"}baz{/a} to <a href="foo" target="bar">baz</a>
    $output = preg_replace('/\{(\/)?(\w+)\}/', '<$1$2>', $output); // Replace {foo} with <foo> and {/foo} with </foo>
    return $output;
}

function getAuthorByID($id, $db) {
    /* Returns an associative array representation of the MYSQL result for the author of the provided id. */
    $sql = "SELECT * FROM User WHERE UserID = {$id};";
    $result = mysqli_query($db, $sql);
    if($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return NULL;
    }
}

function getSectionArticles($category, $sort, $limit=NULL, $offset=NULL, $db) {
    /* Returns an array of articles in a provided category arranged by a sort parameter.
       Optionally can provide only a provided limit of articles, and/or optionally return articles from after a given offset.
    */
    /* Handles sort parameter for sql query */
    switch($sort) {
        case "Newest":
            $sql = "SELECT * FROM Article WHERE( IsDraft = 0 AND IsSubmitted = 1 AND Category = '{$category}') ORDER BY PublishDate DESC";

            break;

        case "Views":
           $sql = "SELECT * FROM Article WHERE( IsDraft = 0 AND IsSubmitted = 1 AND Category = '{$category}') ORDER BY Views DESC";

            break;
            
        case "Name":
            $sql = "SELECT * FROM Article WHERE( IsDraft = 0 AND IsSubmitted = 1 AND Category = '{$category}') ORDER BY Headline ASC";
            
            break;

        default:
            print "<b>No results.</b>";

            break;

    }//End of switch
    
    if(!is_null($offset)) {
        $sql .= " LIMIT {$offset},";
    }
    if(!is_null($limit)) {
        $sql .= " {$limit}";
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

function getEditorPickIDs($limit=NULL, $db) {
    /* Returns an array of article IDs that are labled as 'EditorPick'. */
    $sql = "SELECT * FROM Featured WHERE FeaturedType='EditorPick' ORDER BY FeaturedID DESC";
    if(!is_null($limit)) {
        $sql .= " LIMIT {$limit}";
    }
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
    $editorPicks = getEditorPickIDs($limit, $db);
    $sql = "SELECT * FROM (SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0";
    $sql .= " AND ArticleID = {$editorPicks[0]} ";
    foreach(array_slice($editorPicks, 1) as &$val) { // Gather results for all other specified editor picks
        $sql .= "OR ArticleID = {$val} ";
    }
    $sql .= ") AS A";
    if(!is_null($category)) {
        $sql .= " WHERE Category = '{$category}'";
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

    $sql = "SELECT * FROM (SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ArticleId != ".getMainArticleID($db);
    

    foreach(getEditorPickIDs(NULL, $db) as &$val) {
        $sql .= " AND ArticleID != ".$val." ";
    }
    $sql .= "AND PublishDate >= DATE(NOW()) - INTERVAL ".$trendingDaySpan." DAY AND Views > ".$trendingViewThreshold." ";
    $sql .= ") AS A";

    if(!is_null($category)) {
        $sql .= " WHERE Category = '{$category}'";
    }
    
    $sql .= " LIMIT ".$limit.";";

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

function getNumBookmarks($aid, $db) {
    /* Returns an integer value representing the number of bookmarks an article given by a provided ArticleID has. */
    $sql = "SELECT * FROM Bookmark WHERE ArticleID = {$aid}";
    $result = mysqli_query($db, $sql);
    return mysqli_num_rows($result);
}

function getRatingByID($aid, $db) {
    $sql = "SELECT AVG(Score) FROM Rating WHERE ArticleID = {$aid}";
    $result = mysqli_fetch_array(mysqli_query($db, $sql));
    return $result['AVG(Score)'];
}

?>
