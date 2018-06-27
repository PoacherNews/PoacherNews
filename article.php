<?php 
ob_start();
session_start(); ?>
<!DOCTYPE html>
<?php 
    function redirectHome() {
        header('Location: index.php');
    }
?>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="res/css/articlepage.css">
    </head>
    <body>
        <?php 
            include('includes/header.php');
            include('includes/nav.php');
            include('util/db.php');
            include('util/articleUtils.php');

            date_default_timezone_set('America/Chicago');
            $articleData = getArticleByID($_GET['articleid'], $db);
            if(!$articleData) {
                redirectHome();
            }
            $isAuthor = ($articleData['UserID'] == $_SESSION['userid'] ? TRUE : FALSE);
            $isAdmin = ($_SESSION['usertype'] == "A" ? TRUE : FALSE);
            $isDraft = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 0 ? TRUE : FALSE);
            $isPending = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 1 ? TRUE : FALSE);

            if($isDraft || $isPending) {
                if(!($isAuthor || $isAdmin)) { // Allow admins and the article's author to view drafts and their own pending articles
                    redirectHome();
                }
            } else { // Article is published, so increase view count
                increaseViewCount($articleData['ArticleID'], $db);
            }
            
            if($isDraft) { print "<div id=\"infoBanner\">DRAFT</div>"; }
            if($isPending) { print "<div id=\"infoBanner\">PENDING APPROVAL</div>"; }
        ?>

        
        <div class="articleColumns">
            <section id="articleColumn">
                <span class="articleDetails">
                    <span style="display: none" id="aid"><?php print $articleData['ArticleID'] ?></span> <!-- Hidden field to track article ID -->
                    <h1><?php print $articleData['Headline'] ?></h1>
                    <p> <!-- TODO: Link this to the author's profile -->
                        <?php
                            $authorData = getAuthorByID($articleData['UserID'], $db);
                            print "Written by <a href=\"/profile.php?uid={$articleData['UserID']}\">{$authorData['FirstName']} {$authorData['LastName']}</a>";
                        ?>
                    </p>
                    <p id="pubDate">Published on <?php print date("l, F j Y g:i a", strtotime($articleData['PublishDate']))?></p>
                    <p id="pubDetails">
                        <?php print "<a href=\"section.php?Category={$articleData['Category']}\">{$articleData['Category']}</a>" ?>
                        &mdash;
                        <?php print "{$articleData['Views']} ".($articleData['Views'] === 1 ? "View" : "Views") ?>,
                        <?php
                            $numFaves = getNumFavorites($articleData['ArticleID'], $db);
                            print "{$numFaves} ".($numFaves === 1 ? "Favorite" : "Favorites");
                        ?>
                        <span class="rightIcons"">

                            <?php
                                if(!isset($_SESSION['loggedin'])) {
                                    print "<a href=\"login.php\">Log in</a> to favorite articles";
                                } else {
                                    include('util/userUtils.php');
                                    if(isFavorite($_SESSION['userid'], $articleData['ArticleID'], $db)) { // If it's favorited
                                        print '<i id="unfavorite" title="Click to Unfavorite" class="fas fa-heart"></i>';
                                    } else { // If it hasn't been favorited
                                        print '<i id="favorite" title="Click to Favorite" class="far fa-heart"></i>';
                                    }
                                }
                            ?>
                        </span>
                        <script>
                            $("#favorite").click(function() {
                                $.get('util/articleHandler.php', {
                                    'request' : "favorite",
                                    'aid' : $("#aid").text(),
                                }).done(function(data) {
                                    location.reload();
                                });
                            });
                            $("#unfavorite").click(function() {
                                $.get('util/articleHandler.php', {
                                    'request' : "unfavorite",
                                    'aid' : $("#aid").text(),
                                }).done(function(data) {
                                    location.reload();
                                });
                            });
                         </script>
                    </p>
                </span>
                <div class="articleImage">
                    <img src="<?php print $articleData['Image'] ?>"/>
                </div>
                <div class="articleBody">
                    <p><?php print nl2br($articleData['Body']); ?></p>
                </div>
            </section>
            <section id="articleAdColumn">
                <div class="ad"><p>AD</p></div>
                <div class="ad"><p>AD</p></div>
                <div class="ad"><p>AD</p></div>
            </section>
        </div>
        <div id="further-reading">
            <h1>Further reading</h1>
            <hr/>
        </div>
        <div class="articleColumns">
            <?php
                $categoryArticles = array_chunk(getRelatedArticles($articleData['Category'], $articleData['ArticleID'], $db), 2);
                foreach ($categoryArticles as $key => $categorySet) {
                    print '<section class="threeColumnSection '.($key == 1 ? 'center' : '').'">';
                    foreach($categorySet as $key => $article) {
                    print   "<article>
                                <div class=\"articleBody\">
                                    <h1><a href=\"article.php?articleid={$article['ArticleId']}\">{$article['Headline']}</a></h1>
                                    <p>".substr($article['Body'], 0, 100)."...</p>
                                    <a class=\"continue-reading\" href=\"article.php?articleid={$article['ArticleId']}\">Continue Reading</a>
                                </div>
                            </article>";
                    }
                    print '</section>';
                }
            ?>
        </div>
        <?php include('includes/footer.html'); ?>
    </body>
</html>
