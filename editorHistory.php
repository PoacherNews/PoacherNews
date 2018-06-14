<?php
// Redirect draft links to editorPage

include 'util/loginCheck.php';
// quit if not an admin or not logged in
if (!$loggedin || !($_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an editor or administrator.";
    echo '<meta http-equiv="refresh" content="1; url=/index.php">';
    exit;
}

function display_table($db, $query, $tablename)
{
    // execute query
    if (!$result = $db->query($query))
    {
        echo "Error executing statement: <br>";
        echo $db->error;
        return;
    }
    // print table
    $fields = $result->fetch_fields();
    echo "\n<table>\n";
    echo "<thead>\n";
    echo "<tr>\n";
    echo "<tr>\n";
    echo "</thead>\n<tbody>\n";
    // get row as an array
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>\n";
        foreach ($row as $key => $r)
        {
            foreach ($row as $subKey => $subR)
            { 
            if ($key == 'Headline' && $subKey == 'ArticleID')
            {
                echo '<td>';
                echo "<a href='article.php?articleid=$subR'>";
                echo $r;
                echo '</a>';
                echo '</td>';
            }
        }
    }	
        echo "</tr>\n";
}
    // close table
    echo "</tbody>\n</table>\n";
    $result->free();
}

// displays Users as a table
function list_drafts()
{
    $userid = $_SESSION['userid'];
    include 'util/db.php';
    // query Users
    $query = "SELECT ArticleID, Headline FROM Article JOIN User ON Article.UserID = User.UserID WHERE (IsDraft=1 AND IsSubmitted=0) AND Article.UserID = '$userid'";
    //  AND Article.UserID = '$username'
    // display
    display_table($db, $query, "Drafts");
    // done
}

function list_pending()
{
    $userid = $_SESSION['userid'];
    include 'util/db.php';
    // query Users
    $query = "SELECT ArticleID, Headline FROM Article JOIN User ON User.UserID = Article.UserID WHERE (IsDraft=1 AND IsSubmitted=1) AND Article.UserID = '$userid'";
    //  AND Article.UserID = '$username'
    // display
    display_table($db, $query, "Pending");
    // done
}

function list_approved()
{
    $userid = $_SESSION['userid'];
    include 'util/db.php';
    // query Users
    $query = "SELECT ArticleID, Headline FROM Article INNER JOIN User ON User.UserID = Article.UserID WHERE (IsDraft=0 AND IsSubmitted=1) AND Article.UserID = '$userid'";
    //  AND Article.UserID = '$username'
    // display
    display_table($db, $query, "Approved");
    // done
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
    </head>

<style>
</style>

    <body>
        <?php 
    		include 'util/loginCheck.php';
            if (!$loggedin)
            {
                echo '<meta http-equiv="refresh" content="0; url=index.php">';
                exit;
            }
	    	include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="user">
            <div class="picture">
                (Profile Picture)
            </div>
            
            <div class="info">
                (User Information)
            </div>
        </div>
        
        <div class="nav">
            <?php
                $current = 'editorHistory';
                include 'includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
        <h1>Drafts</h1> 
            <?php list_drafts(); ?>
        <h1>Pending</h1> 
            <?php list_pending(); ?>
        <h1>Approved</h1>
        <?php list_approved(); ?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>