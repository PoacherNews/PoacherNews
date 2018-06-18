<?php
    include 'util/loginCheck.php';
    include 'util/userUtils.php';

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

function list_headlines()
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
    $query = "SELECT Headline FROM Comment LEFT JOIN Article ON Comment.ArticleID = Article.ArticleID WHERE Comment.UserID = '".$userid."'";
    echo $query;
    // display
    display_table($db, $query, "Comments");
    // done
}

// displays Users as a table
function list_comments()
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
    $query = "SELECT commentText FROM Comment LEFT JOIN User ON Comment.UserID = User.UserID WHERE Comment.UserID = '".$userid."'";
    echo $query;
    // display
    display_table($db, $query, "Comments");
    // done
}
*/

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile | Comments</title>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
    </head>

    <body>
        <?php
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
                $current = 'comments';
                include 'includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
        <h1>Comments</h1> 
        <?php
 		$commentArticles = getUserCommentArticles($userid, null, $db);
  		$comments = getUserComments($userid, null, $db);

    	if($commentArticles === null) {
                print "<div class=\"columnError\">No comments yet.</div>";
                return;
        }

 		foreach($commentArticles as $article) {
        print "
        <article>
			<div class=\"thumbnailSecondary\">
				<a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"{$article['Image']}\" width=\"150\" height=\"100\"></a>
					</div>
                    <div class=\"textSecondary\">
                            <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                        	<p>".substr($article['Body'], 0, 75)."...</p> 
                    </div>                
        </article>"; 
        
        // Print comments if Article.ArticleID = Comment.ArticleID
        	foreach($comments as $r)
        	{
        		if($article['ArticleID'] == $r['ArticleID'])
            	print "<p>{$r['commentText']}</p> ";             
			}
		} 
        
          
		?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>