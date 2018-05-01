<!DOCTYPE html>
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
        ?>
        
        <?php include('util/db.php'); ?>
        <?php 
            //echo "ARTICLE ID IN URL: ".$_GET['articleid']
            date_default_timezone_set('America/Chicago');
            $sql = "SELECT * FROM Articles WHERE ArticleID = ".$_GET['articleid'];
            $result = mysqli_query($db, $sql);
            if(mysqli_num_rows($result) == 0) {
                print "Error! ArticleID not found!";
            }
            $articleData = mysqli_fetch_assoc($result);
        ?>
        <div class="articleColumns">
            <section id="articleColumn">
                <span class="articleDetails">
                    <h1><?php print $articleData['Headline'] ?></h1>
                    <p><?php print $articleData['Author'] ?></p>
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
            <?php echo "Article cat: ".$articleData['Views']; ?>
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
