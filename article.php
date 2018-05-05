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
            include 'includes/header.php';
            include 'includes/nav.php';
            include('util/db.php');
            include('util/articleUtils.php');
        ?>

        <?php 
            date_default_timezone_set('America/Chicago');
            $articleData = getArticleByID($_GET['articleid'], $db);
            if(!$articleData) {
                redirectHome();
            }
            if($articleData['IsPublished'] == 0 && $_SESSION['usertype'] != "A") { // Only allow admins to view unPublished articles
                redirectHome();
            }
            
            increaseViewCount($articleData['ArticleID'], $db);
        ?>
        <div class="articleColumns">
            <section id="articleColumn">
                <span class="articleDetails">
                    <h1><?php print $articleData['Headline'] ?></h1>
                    <p> <!-- TODO: Link this to the author's page (or other articles by them? -->
                        <?php
                            $authorData = getAuthorByID($articleData['AuthorID'], $db);
                            print "Written by {$authorData['FirstName']} {$authorData['LastName']}";
                        ?>
                    </p>
                    <p>Published on <?php print date(DATE_RFC850, strtotime($articleData['PublishDate']))." &mdash; ".$articleData['Views']." Views"; ?></p>
                </span>
                <div class="articleImage">
                    <img src="<?php print $articleData['Img'] ?>"/>
                </div>
                <div class="articleBody">
                    <p><?php print $articleData['Body'] ?></p>
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
