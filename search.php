<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE>
<html>
<head>
    <title>Search results</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="res/css/search.css">
    
    <?php 
        include 'includes/globalHead.html'; 
        include 'util/db.php';    
    ?>
</head>
<body>
    <?php
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div class="mainFlex">
        <div class="flexRow">
            <div class="searchFlex">
                <h1>Search Results</h1>
                <form class="search" action="" method="GET">
                    <?php
                        print "<input type=\"text\" id=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query\" value={$_GET['query']}>";
                    ?>
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
                    if(strlen($query) >= $min_length){ 
                        $query = htmlspecialchars($query); 
                        $query = mysqli_real_escape_string($db, $query);
                        $raw_results = "SELECT * FROM Article
                        WHERE IsSubmitted = 1 AND ((`Headline` LIKE '%".$query."%') OR (`Body` LIKE '%".$query."%'))";
                        $test = mysqli_query($db, $raw_results);
                        echo "<div id='divTest'>";
                        
                        if(mysqli_num_rows($test) > 0){ 
                            while($results = mysqli_fetch_array($test, MYSQLI_ASSOC)){
                                print   "<div class='flexRow'>
                                            <div class='imgFlex'>
                                                <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                    <img src=".$results['Image']." class='image' height='120' width='140'>
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
                                $raw_results = mysql_query("SELECT * FROM Article ORDER BY PublishDate DESC") or die(mysql_error());
                                echo "<div id='divTest'>";
                                if(mysql_num_rows($raw_results) > 0){ 
                                    while($results = mysql_fetch_array($raw_results)){
                                        print   "<div class='flexRow'>
                                                    <div class='imgFlex'>
                                                        <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                            <img src=".$results['Image']." class='image' height='120' width='140'>
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
                                $raw_results = mysql_query("SELECT * FROM Article ORDER BY Headline DESC") or die(mysql_error());
                                echo "<div id='divTest'>";
                                if(mysql_num_rows($raw_results) > 0){ 
                                    while($results = mysql_fetch_array($raw_results)){
                                        print   "<div class='flexRow'>
                                                    <div class='imgFlex'>
                                                        <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                            <img src=".$results['Image']." class='image' height='120' width='140'>
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
                                $raw_results = mysql_query("SELECT * FROM Article ORDER BY Views DESC") or die(mysql_error());
                                echo "<div id='divTest'>";
                                if(mysql_num_rows($raw_results) > 0){ 
                                    while($results = mysql_fetch_array($raw_results)){
                                        print   "<div class='flexRow'>
                                                    <div class='imgFlex'>
                                                        <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                            <img src=".$results['Image']." class='image' height='120' width='140'>
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
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>