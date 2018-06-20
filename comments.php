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
            foreach($commentArticles as $article) 
            {
                if($article['ArticleID'] == $r['ArticleID']) 
                {
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
                }                
            }
            print "<p>{$r['commentText']} -&nbsp;";
            print date("l, F j Y g:i a", strtotime($r['CommentDate']));
            print "</p>";
        }
    ?>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>