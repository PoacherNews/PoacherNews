<?php
include 'util/userUtils.php';
include 'util/loginCheck.php';
$username = $_GET['Username'];
    
include 'util/db.php';
    
$sql = "SELECT * FROM User WHERE Username = '".$username."'";
$result = $db->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$userid = $row['UserID'];  

/*
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
*/

/*
// displays Users as a table
function list_favorites()
{
	$username = $_GET['Username'];
    include 'util/db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    $sql = "SELECT * FROM User WHERE Username = '".$username."'";
    $result = $db->query($sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $userid = $row['UserID'];    
    // query Users
    $query = "SELECT Favorite.ArticleID FROM Favorite LEFT JOIN Article ON Article.UserID = Favorite.UserID WHERE Favorite.UserID = '".$userid."'";
    echo $query;
    // display
    display_table($db, $query, "Favorites");
    // done
}
*/
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
 		$favorites = getUserFavorites($userid, null, $db);
    	if($favorites === null) {
                print "<div class=\"columnError\">No favorites yet.</div>";
                return;
        }
             
 		foreach($favorites as $article) {
        print "
        <article>
			<div class=\"thumbnailSecondary\">
				<a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"{$article['Image']}\" width=\"150\" height=\"100\"></a>
					</div>
                    <div class=\"textSecondary\">
                            <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                        	<p>".substr($article['Body'], 0, 75)."...</p>                  
                    </div>
        </article>"; }   
		?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>