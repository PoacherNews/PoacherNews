<?php
include 'util/loginCheck.php';
// quit if not an admin or not logged in
if (!$loggedin || !($_SESSION['usertype'] == 'A'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an administrator. Redirecting in 1 second...";
    echo '<meta http-equiv="refresh" content="1; url=index.php">';
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
    echo "<caption>$tablename in DB</caption>\n";
    echo "<thead>\n";
    echo "<tr>\n";
    foreach ($fields as $field)
    {
        if($field->name != 'UserID')
        {
            if($field->name != 'ArticleID')
            {
                echo "<th>$field->name</th>";
            }
        }
    }
    echo "<tr>\n";
    echo "</thead>\n<tbody>\n";
    // get row as an array
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>\n";
        foreach ($row as $key => $r)
        {
            if($key == 'UserID')
            {
                $userid = $r;
            }
            if($key == 'ArticleID')
            {
                $articleid = $r;
            }
            
            if($key != 'UserID' && $key != 'ArticleID')
            {
                echo '<td>';
                if ($key == 'Username')
                {
                    echo "<a href='util/editUser.php?UserID=$userid'>";
                }
                if ($key == 'Headline')
                {
                    echo "<a href='util/editArticle.php?ArticleID=$articleid'>";
                }
                    echo $r;
                
                if($key == 'CommentText' && $r == null)
                {
                    echo "[deleted]";
                }
                
                if ($key == 'Username' || $key == 'Headline')
                {
                    echo '</a>';
                }
                echo '</td>';
            }
        }	
        echo "</tr>\n";
    }
    // close table
    echo "</tbody>\n</table>\n";
    $result->free();
}

// displays Users as a table
function list_comments()
{
    include 'util/db.php';
    // query Users
    $query = "SELECT CommentID, ReplyToID, Comment.ArticleID, Headline, Comment.UserID, Username, CommentText, CommentDate FROM Comment JOIN User ON Comment.UserID = User.UserID JOIN Article ON Comment.ArticleID = Article.ArticleID";
    // display
    display_table($db, $query, "Comments");
    // done
    $db->close();
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
h1 {
	text-align: center;
}

table {
	margin-left:auto;
	margin-right:auto;
	border-collapse:collapse;
}

table, th, td {
	border: 1px solid black;
}
</style>

    <body>
        <?php 
	    	include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="nav">
            <?php
                $current = 'manageComments';
                include 'includes/toolsNav.php';
            ?>
        </div>
        
        <div class="display">
        <main>
            <h1>Manage Comments</h1>
            <div>
                <?php list_comments(); ?>
            </div>
        </main>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>
