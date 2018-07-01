<?php
// TODO:
// Redirect draft links to editorPage
include 'util/loginCheck.php';

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
	$userid = $_GET['uid'];
    include 'util/db.php';
    // query Users
    if($userid != null)
    {
        $query = "SELECT ArticleID, Headline FROM Article JOIN User ON Article.UserID = User.UserID WHERE (IsDraft=1 AND IsSubmitted=0) AND Article.UserID = '".$userid."'";
    }
    else
    {
        $username = $_SESSION['username'];
        $query = "SELECT ArticleID, Headline FROM Article JOIN User ON Article.UserID = User.UserID WHERE (IsDraft=1 AND IsSubmitted=0) AND User.Username = '".$username."'";
    }
    //  AND Article.UserID = '$username'
    // display
    display_table($db, $query, "Drafts");
    // done
}

function list_pending()
{
	$userid = $_GET['uid'];
    include 'util/db.php';
    // query Users
    if($userid != null)
    {
        $query = "SELECT ArticleID, Headline FROM Article JOIN User ON User.UserID = Article.UserID WHERE (IsDraft=1 AND IsSubmitted=1) AND Article.UserID = '$userid'";
    }
    else
    {
        $username = $_SESSION['username'];
        $query = "SELECT ArticleID, Headline FROM Article JOIN User ON Article.UserID = User.UserID WHERE (IsDraft=1 AND IsSubmitted=1) AND User.Username = '".$username."'";
    }
    //  AND Article.UserID = '$username'
    // display
    display_table($db, $query, "Pending");
    // done
}

function list_approved()
{
	$userid = $_GET['uid'];
    include 'util/db.php';
    // query Users
    if($userid != null)
    {
        $query = "SELECT ArticleID, Headline FROM Article JOIN User ON User.UserID = Article.UserID WHERE (IsDraft=0 AND IsSubmitted=1) AND Article.UserID = '".$userid."'";
    }
    else
    {
        $username = $_SESSION['username'];
        $query = "SELECT ArticleID, Headline FROM Article JOIN User ON Article.UserID = User.UserID WHERE (IsDraft=0 AND IsSubmitted=1) AND User.Username = '".$username."'";
    }
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
        <link rel="stylesheet" href="/res/css/profile.css">
        <link rel="stylesheet" href="/res/css/profileNav.css">
    </head>

<style>
</style>

    <body>
        <?php 
            $userid = $_GET['uid'];

	    	include 'includes/header.php';
            include 'includes/nav.php';
            if($userid != null)
            {
                include 'includes/profileHeader.php';
            }
            // Redirect to index for editorHistory?Username= on usertype U
            if($usertype == 'U')
            {
                echo '<meta http-equiv="refresh" content="0; url=index.php">';
                exit;
            }
        ?>
        
        <div class="nav">
            <?php
            if($userid != null)
            {
                $current = 'editorHistoryProfile';
                include 'includes/profileNav.php';
            }
            else
            {
                $current = 'editorHistoryTools';
                include 'includes/toolsNav.php';
            }
            ?>
        </div>
        
        <div class="display">
        <?php
        if($userid != null)
        {
            if(($userid == $_SESSION['userid']) || $_SESSION['usertype'] == 'A')
            {
                echo "<h1>Drafts</h1>";
                echo list_drafts();
                echo "<h1>Pending</h1>";
                echo list_pending();
            }
                echo "<h1>Approved</h1>";
                echo list_approved();
        }
        else
        {
            echo "<h1>Drafts</h1>";
            echo list_drafts();
            echo "<h1>Pending</h1>";
            echo list_pending();
            echo "<h1>Approved</h1>";
            echo list_approved();
        }
       	?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>