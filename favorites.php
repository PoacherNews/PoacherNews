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