<!DOCTYPE>
<html>
<head>
    <title>Search results</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="res/css/search.css">
    
    <?php include 'includes/globalHead.html' ?>
    <?php
    mysql_connect("poacherdatabase.ccbtf4xhozoc.us-east-2.rds.amazonaws.com", "mysqladmin", "Hunter1234") or die("Error connecting to database: ".mysql_error());
         
    mysql_select_db("PoacherNews") or die(mysql_error());
    ?>
</head>
<body>
    <?php
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div class="flexRow">
        <div class="searchFlex">
            <h1>Search Results</h1>
            <form class="search" action="" method="GET">
                <input type="text" id="searchInput" placeholder="What are you looking for?" name="query"/>
                <div class="buttonFlex">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="dropdown">
            <div class="dropbtn">Sort by 
                <i class="fa fa-caret-down"></i>
            </div>
            <div class="dropdown-content">
                <a href="search.php?sort=Relevancy">Relevancy</a>
                <a href="search.php?sort=Name">Name</a>
                <a href="search.php?sort=Views">Views</a>
            </div>
        </div> 
        
    </div>
    
    <div class="resultFlex">
            <div class="testFlex">
            <?php
                $query = $_GET['query'];
                $sort = $_GET['sort'];
                $min_length = 1;
                $newResults = array();
                if(strlen($query) >= $min_length){ 
                    $query = htmlspecialchars($query); 
                    $query = mysql_real_escape_string($query);
                    $raw_results = mysql_query("SELECT * FROM Articles
                    WHERE (`Headline` LIKE '%".$query."%') OR (`Img` LIKE '%".$query."%')") or  die(mysql_error());
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
                echo "</div>";
                // TESTING THE SORT FUNCTION
                if($query === is_null) {
                    echo "Nothing to be sorted...";
                } else {
                    switch($sort) {
                        case "Relevancy":
                            $raw_results = mysql_query("SELECT * FROM Articles ORDER BY PublishDate DESC") or die(mysql_error());
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
                            
                            break;
                            
                        case "Name":
                            $raw_results = mysql_query("SELECT * FROM Articles ORDER BY Headline DESC") or die(mysql_error());
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
                            break;
                            
                        case "Views":
                            $raw_results = mysql_query("SELECT * FROM Articles ORDER BY Views DESC") or die(mysql_error());
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
                            break;
                    }
                }
            ?>
            </div>
    </div>
<?php include('includes/footer.html'); ?>