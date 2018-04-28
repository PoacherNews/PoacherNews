<?php
/*
Takes the following arguments as specified in a GET request in 'request':
    - "trending" : Returns the articles within the specified date range from that day and a past cutoff (eg. articles in the past 3 days) that meet a minimum number of views.
    - "main" : Returns the article whose ArticleID matches one manually set in $mainArticleId.
    - "editorpicks" : Returns the articles whose ArticleID matches the ones set in the $editorPicks array.
    - "secondaryarticles" : Returns up to $secondaryResultCap articles from the last week for use in the secondary stacked article section.
Returns:
    - Will return a JSON serialized object of the MYSQL results on success.
    - If no rows are returned, a "No rows returned from database" error string will be returned.
    - If an invalid request is send in 'request' (ie. not "trending", "main", or "editorpicks"), a "Empty or invalid response" error string will be returned.
*/
include("db.php");

// Rules for trending article results
$trendingDaySpan = 3;
$trendingViewThreshold = 50;
$trendingResultCap = 3;
// Rules for secondary stacked article results
$secondaryResultCap = 4;
// Manually assigned main article id
$mainArticleId = 97;
// Manually assigned set of article ids picked by the editors
$editorPicks = array(13, 20);

if(!empty($_GET)) {
    if($_GET['request'] === "trending") {
        $sql = "SELECT * FROM Articles WHERE PublishDate >= DATE(NOW()) - INTERVAL ".$trendingDaySpan." DAY AND Views > ".$trendingViewThreshold." LIMIT ".$trendingResultCap.";";
    } else if($_GET['request'] === "main") {
        $sql = "SELECT * FROM Articles WHERE ArticleID = ".$mainArticleId.";";
    } else if($_GET['request'] === "editorpicks") {
        $sql = "SELECT * FROM Articles WHERE ArticleID = ".$editorPicks[0]." ";
        $a = array_slice($editorPicks, 1); // Use the rest of the picks, except for the first item
        foreach($a as &$val) { // Gather results for all other specified editor picks
            $sql .= "OR ArticleID = ".$val." ";
        }
        $sql .= ";";
    } else if($_GET['request'] === "secondaryarticles") {
        $sql = "SELECT * FROM Articles WHERE PublishDate >= DATE(NOW()) - INTERVAL 7 DAY LIMIT ".$secondaryResultCap.";";
    } else {
        print "Error: Empty or invalid request.";
        exit;
    }
}

$result = mysqli_query($db, $sql);
if(mysqli_num_rows($result) == 0) {
    print "Error: No rows returned from database.";
    exit;
}

$data = array();
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
    $data[] = $row;
}
print json_encode($data); // Encode PHP array of db rows as JSON
?>
