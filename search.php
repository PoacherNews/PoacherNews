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
    
        //SQL Injection Validation
        if(isset($_GET['query'])) {
            $check = $_GET['query'];
            $_GET['query'] = mysqli_real_escape_string($db, $check);
        }
            if(isset($_GET['query2'])) {
            $check = $_GET['query2'];
            $_GET['query2'] = mysqli_real_escape_string($db, $check);
        }
        if(isset($_GET['query3'])) {
            $check = $_GET['query3'];
            $_GET['query3'] = mysqli_real_escape_string($db, $check);
        }
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
                        print "<input type=\"text\" id=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query\"";
                        if(isset($_GET['query'])) {
                            print " value={$_GET['query']}>";
                        } else {
                            print ">";
                        }
                    ?>

                    
                    <div class="buttonFlex">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                    
                
                    <div id="advancedSearch">
                        <?php
                            print "Refine Search Date ";
                            print "<input type=\"date\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query2\" value={$_GET['query2']}>";
                            print "<input type=\"date\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query3\" value={$_GET['query3']}>";
                            
                        ?>
                    </div>
                </form>
            </div>
            <div class="dropdown">
                <div class="dropbtn">Sort by 
                    <i class="fa fa-caret-down"></i>
                </div>
                <div class="dropdown-content">
				<?php
                    if(!($_GET['query'] == '')) {    
                        print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Relevancy\">Relevancy</a>";
                        print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Views\">Views</a>";
                        print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Name\">Name</a>";
                                    
                    } else if (!(isset($_GET['query'])) || $_GET['query'] == '') {
                        print "<a href=\"search.php?query=\">Relevancy</a>";
                        print "<a href=\"search.php?query=\">Name</a>";
                        print "<a href=\"search.php?query=\">Views</a>";
                        print "<a href=\"search.php?query=\">Date</a>";
                    }
				?>
                    
                </div>
                
            
            </div> 

        </div>
            <div id="advancedSearch">
                <?php
                    print "Refine Search Date";
                    print "<input type=\"date\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query2\" value={$_GET['query2']}>";
                    print "<input type=\"date\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query3\" value={$_GET['query3']}>";
                        
                 ?>
            </div>
        
            <div class="advancedSearchButton">
        <button type: "button" onclick="showAdvancedSearch()">Refine Search Date</button>
        </div>
        
        <script>
            function showAdvancedSearch(){
                var x = document.getElementById("advancedSearch");
                if (x.style.display === "none") {
                    x.style.display = "block"
                } else {
                    x.style.display = "none";
                }
            }
        
        </script>
        
        
        <div class="resultFlex">
                <div class="testFlex"> 
                <?php
                    if(isset($_GET['query'])) {
                        $query = $_GET['query'];
                        if(isset($_GET['sort'])) {
                            $sort = $_GET['sort'];
                        }
                      
                    if(isset($_GET['query'])) {
                        $query2 = $_GET['query2'];
                        }
                        
                    if(isset($_GET['query3'])) {
                        $query3 = $_GET['query3'];
                        }   
                    
                    if(empty($query2)){
                        $query2 = '2000-11-11';
                    }
                    if(empty($query3)){
                        $query3 = '2020-11-11';
                    }
                        
                        $min_length = 1;
                        
                        $pageCheck = 0;

                        /* ----- Pagination Paramater Storage ----- */
                        $articlesPerPage = 5;
                        $numResults = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') OR (`ArticleImage` LIKE '%".$query."%'))";
                        $totalArticles = mysqli_query($db, $numResults);
                        $totalPages = ceil($totalArticles->num_rows / $articlesPerPage);

                        if(!isset($_GET{'page'})) {
                            $_GET['page'] = 0;
                        } else {
                            $_GET['page'] = (int)$_GET['page'];
                        }

                        if($_GET['page'] < 1) {
                            $_GET['page'] = 1;
                        } else if($_GET['page'] > $totalPages) {
                            $_GET['page'] = $totalPages;
                        }

                        $startArticle = ($_GET['page'] - 1) * $articlesPerPage;

                        // Test
                        if(!(isset($_GET['sort']))) {
                            if(strlen($query) >= $min_length){ 
                                $query = htmlspecialchars($query); 
                                $query = mysqli_real_escape_string($db, $query);

                                $raw_results = "SELECT * FROM Article LEFT JOIN ArticleTag ON ArticleTag.ArticleID = Article.ArticleID LEFT JOIN Tag ON Tag.TagID = ArticleTag.TagID WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (`PublishDate` BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%') OR (`TagName` LIKE '%".$query."%')) LIMIT ".$startArticle.", ".$articlesPerPage."";
                                
                                $test = mysqli_query($db, $raw_results);
                                echo "<div class='search-content'>";

                                if(mysqli_num_rows($test) > 0){ 
                                    while($results = mysqli_fetch_array($test, MYSQLI_ASSOC)){
                                        print   "<div class='flexRow'>
                                                    <div class='imgFlex'>
                                                        <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                            <img src=".$results['ArticleImage']." class='image' height='120' width='140'>
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
                                    $pageCheck = 1;
                                }
                                echo "</div>";
                            }

                        // TESTING THE SORT FUNCTION
                        } else {
                            switch($sort) {
                                case "Relevancy":

                                    $raw_results = mysqli_query($db, "SELECT * FROM Article LEFT JOIN ArticleTag ON ArticleTag.ArticleID = Article.ArticleID LEFT JOIN Tag ON Tag.TagID = ArticleTag.TagID WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (`PublishDate` BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%') OR (`TagName` LIKE '%".$query."%')) ORDER BY PublishDate DESC LIMIT ".$startArticle.", ".$articlesPerPage."") or die(mysql_error());

                                    echo "<div class='search-content'>";
                                    if(mysqli_num_rows($raw_results) > 0){ 
                                        while($results = mysqli_fetch_array($raw_results)){
                                            print   "<div class='flexRow'>
                                                        <div class='imgFlex'>
                                                            <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                                <img src=".$results['ArticleImage']." class='image' height='120' width='140'>
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

                                    $raw_results = mysqli_query($db, "SELECT * FROM Article LEFT JOIN ArticleTag ON ArticleTag.ArticleID = Article.ArticleID LEFT JOIN Tag ON Tag.TagID = ArticleTag.TagID WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (`PublishDate` BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%') OR (`TagName` LIKE '%".$query."%')) ORDER BY Headline ASC LIMIT ".$startArticle.", ".$articlesPerPage."") or die(mysql_error());

                                    echo "<div class='search-content'>";
                                    if(mysqli_num_rows($raw_results) > 0){ 
                                        while($results = mysqli_fetch_array($raw_results)){
                                            print   "<div class='flexRow'>
                                                        <div class='imgFlex'>
                                                            <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                                <img src=".$results['ArticleImage']." class='image' height='120' width='140'>
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

                                   $raw_results = mysqli_query($db, "SELECT * FROM Article LEFT JOIN ArticleTag ON ArticleTag.ArticleID = Article.ArticleID LEFT JOIN Tag ON Tag.TagID = ArticleTag.TagID WHERE IsSubmitted = 1 AND IsDraft = 0 AND (`PublishDate` BETWEEN '$query2' AND '$query3') AND ((`Headline` LIKE '%".$query."%') OR (`ArticleImage` LIKE '%".$query."%') OR (`TagName` LIKE '%".$query."%')) ORDER BY Views DESC LIMIT ".$startArticle.", ".$articlesPerPage."") or die(mysql_error());

                                    echo "<div class='search-content'>";
                                    if(mysqli_num_rows($raw_results) > 0){ 
                                        while($results = mysqli_fetch_array($raw_results)){
                                            print   "<div class='flexRow'>
                                                        <div class='imgFlex'>
                                                            <a href=\"article.php?articleid={$results['ArticleID']}\">
                                                                <img src=".$results['ArticleImage']." class='image' height='120' width='140'>
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

                                     
                                    else {
                                        print "<b>No results.</b>";
                                    }
                                    break;
                            }//End of switch
                        }//End of else
                    }
                ?>
                </div>
        </div>
        <!----- Pagination Links ----->
        <?php
            if(!(isset($_GET['query'])) || $_GET['query'] == '' || $pageCheck == 1) {
                echo '<!--';
            }
            if(isset($_GET['query'])) {
                echo '<div class="pagination">';

                echo '<a href="search.php?query=' . $_GET["query"] . '&query2=' . $_GET["query2"] . '&query3=' . $_GET["query3"] .  '&page=1">&laquo;</a>';
                foreach(range(1, $totalPages) as $page) {
                    if($page == $_GET['page']) {
                        echo '<a class="active" href="search.php?query=' . $_GET["query"] . '&query2=' . $_GET["query2"] . '&query3=' . $_GET["query3"] . '&page=' . $page . '">' . $page . '</a>';
                    } else {
                        echo '<a href="search.php?query=' . $_GET["query"] . '&query2=' . $_GET["query2"] . '&query3=' . $_GET["query3"] . '&page=' . $page . '">' . $page . '</a>';
                    }
                }
                
                echo '<a href="search.php?query=' . $_GET["query"] . '&page=' . $totalPages . '">&raquo;</a>';

                echo '</div>';
            }
            if(!(isset($_GET['query'])) || $_GET['query'] == '' || $pageCheck == 1) {
                echo '-->';
            }
        ?>
    </div>
    
    <?php include('includes/footer.html'); ?>
</body>
</html>
