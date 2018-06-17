<?php
include 'util/loginCheck.php';
$username = $_GET['Username'];
// quit if not an admin or not logged in
if (!$loggedin)
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be logged in.";
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
            echo '<td>';
            echo $r;
            echo '</td>';
        }	
        echo "</tr>\n";
    }
    // close table
    echo "</tbody>\n</table>\n";
    $result->free();
}

// displays Users as a table
function list_favorites()
{
    $userid = $_SESSION['userid'];
    include 'util/db.php';
    // query Users
    $query = "SELECT Headline FROM Article JOIN Favorite ON Favorite.UserID = Article.UserID WHERE Favorite.UserID = '$userid'";
    echo $query;
    // display
    display_table($db, $query, "Favorites");
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
                <?php 
                	echo "<h3>$username</h3>";
                ?>            
            </div>
        </div>
        
        <div class="nav">
            <?php
                $current = 'favorites';
                include 'includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
        <h1>Favorites</h1> 
        <?php
            echo "unfinished. Action to favorite articles must be implemented first.";
            echo '<br>';
            list_favorites();
        ?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>