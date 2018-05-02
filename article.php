<!DOCTYPE html>
<!-- TODO:
    * Get author data
    * Incriment views on page load
    * Ensure the article requested is not unPublished
        - Check if user logged in is an admin?
    * Do checking on required fields to see if anything is empty
    * Ensure category is given along with rest of data
    * Get the bottom six articles from this article's category and display them
-->
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
            //include 'includes/footer.html';
            include('util/db.php');
            include('util/articleUtils.php');
        ?>

        <?php 
            date_default_timezone_set('America/Chicago');
            $articleData = getArticleByID($_GET['articleid'], $db);
            if(!$articleData) {
                redirectHome();
            }
        ?>
        <div class="articleColumns">
            <section id="articleColumn">
                <span class="articleDetails">
                    <h1><?php print $articleData['Headline'] ?></h1>
                    <p>By George Washington<?php print $articleData['Author'] ?></p>
                    <p>Published on <?php print date(DATE_RFC850, $articleData['PublishDate'])." &mdash; ".$articleData['Views']." Views"; ?></p>
                </span>
                <div class="articleImage">
                    <img src="<?php print $articleData['Img'] ?>"/>
                </div>
                <div class="articleBody">
                    <p><?php print $articleData['Body'] ?></p>
                </div>
            </section>
            <section id="adColumn">
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
            <?php echo "Article cat: ".$articleData['Category']; ?>
            <section class="threeColumnSection">
                <article>
                    <div class="articleBody">
                        <h1>Headline</h1>
                        <p>Foo</p>
                        <a class="continue-reading" href="#">Continue Reading</a>
                    </div>
                </article>
                <article>
                    <div class="articleBody">
                        <h1>Headline</h1>
                        <p>Foo</p>
                        <a class="continue-reading" href="#">Continue Reading</a>
                    </div>
                </article>
            </section>
            <section class="threeColumnSection center">
                <article>
                    <div class="articleBody">
                        <h1>Headline</h1>
                        <p>Foo</p>
                        <a class="continue-reading" href="#">Continue Reading</a>
                    </div>
                </article>
                <article>
                    <div class="articleBody">
                        <h1>Headline</h1>
                        <p>Foo</p>
                        <a class="continue-reading" href="#">Continue Reading</a>
                    </div>
                </article>
            </section>
            <section class="threeColumnSection">
                <article>
                    <div class="articleBody">
                        <h1>Headline</h1>
                        <p>Foo</p>
                        <a class="continue-reading" href="#">Continue Reading</a>
                    </div>
                </article>
                <article>
                    <div class="articleBody">
                        <h1>Headline</h1>
                        <p>Foo</p>
                        <a class="continue-reading" href="#">Continue Reading</a>
                    </div>
                </article>
            </section>
        </div>

    </body>
</html>
