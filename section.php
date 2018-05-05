<?php session_start(); ?>
<!-- TODO: 
Fix css (Resizing issues / min-width)
-->
<!DOCTYPE html>
<html>
    <head>
	   <?php include 'includes/globalHead.html' ?>
    </head>
    
    <body>
        <?php 
            include 'includes/header.php';
            include 'includes/nav.php';
            include 'includes/db.php';
        ?>
            <?php
                if(strlen($query) >= $min_length){ 
                    
                    print "<p id="secName">$section</p>";
                    
                    $section = $_GET['section'];
                                                    
                    $query = htmlspecialchars($query); 
                    $query = mysql_real_escape_string($query);
                    $raw_results = mysql_query("SELECT * FROM Articles
                    WHERE (`CATEGORY` LIKE '%".$section."%')) or  die(mysql_error());
                    echo "<div id='divTest'>";
                    if(mysql_num_rows($raw_results) > 0){ 
                        while($results = mysql_fetch_array($raw_results)){
                            print   "<div class='flexRow'>
                                        <div class='imgFlex'>
                                            <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                <img src=".$results['Img']." class='image' height='120' width='140'>
                                            </a>
                                        </div>
                                        <div class='spanFlex'>
                                            <span class='tip'>
                                                <a href=\"article.php?articleid={$results['ArticleID']}\">{$results['Headline']}</a>
                                            </span>
                                        </div>
                                    </div>
                                    <hr class='searchHr'>";   
                        }
                    }
                    else{
                        print "<b>No results.</b>";
                    }
                    
                }
            ?>
    </section>
        <!-- containerSecondary Tail -->      
    <?php include('includes/footer.html'); ?>
    </body>
</html>
