<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
    </head>

    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
            include 'includes/profileHeader.php';
        ?>
        
        <div class="nav">
            <?php
                $current = 'comments';
                include 'includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
        <h1>Comments</h1> 
    <?php
  		$comments = getUserComments($userid, null, $db);
 		$commentArticles = getUserCommentArticles($userid, null, $db);
    	if($comments === null) 
        {
                print "<div class=\"columnError\">No comments yet.</div>";
                return;
        }
        // Sort comments by DESC
        foreach ($comments as $key => $r) {
            $sort[$key] = strtotime($r['CommentDate']);
        }
        array_multisort($sort, SORT_DESC, $comments);
            
        foreach ($comments as $key => $r) 
        {
            if($r['CommentText'] != NULL)
            {
                foreach($commentArticles as $article) 
                {
                    if($article['ArticleID'] == $r['ArticleID']) 
                    {
                        print "
                            <article>
                                <div class=\"thumbnailSecondary\">
                                    <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"{$article['ArticleImage']}\" width=\"150\" height=\"100\"></a>
                                </div>
                                <div class=\"textSecondary\">
                                    <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                                    <p>".substr($article['Body'], 0, 75)."...</p> 
                                </div>                
                            </article>"; 
                    }                
                }
                print "<p>{$r['CommentText']} -&nbsp;";
                print date("l, F j Y g:i a", strtotime($r['CommentDate']));
                print "</p>";
    ?>
    <?php
        if((strtolower($username) == strtolower($_SESSION['username'])) || $_SESSION['usertype'] == 'A')
        {?>
            <form method="post" action="">
<div>
<input type="radio" name="delete" class="deleteComment" value="1" /><label for="delete">Delete</label><br />
</div>

<div>
<input type="submit" name="deleteSubmit[<?php echo $r['CommentID']; ?>]" id="submit" value="Submit" />
</div>
</form>

<?php
if(isset($_POST['deleteSubmit'][''.$r['CommentID'].''])){ 
$selected_radio = $_POST['delete'];
    
include 'util/db.php';

$query = "UPDATE Comment SET CommentText = NULL WHERE CommentText = '".$r['CommentText']."' AND UserID = ?";

// Refresh
echo "<meta http-equiv='refresh' content='0'>";

// prepare statement
$stmt = $db->stmt_init();
if (!$stmt->prepare($query))
{
    echo "Error preparing statement: <br>";
    echo nl2br(print_r($stmt->error_list, true), false);
    return;
}
// bind username
if (!$stmt->bind_param('s', $_SESSION['userid']))
{
    echo "Error binding parameters: <br>";
    echo nl2br(print_r($stmt->error_list, true), false);
    return;
}
// query database
if (!$stmt->execute())
{
    echo "Error executing query: <br>";
    echo nl2br(print_r($stmt->error_list, true), false);
    return;
}
// done
$stmt->close();
$db->close();
}            
        }
        }
        }
    ?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>
