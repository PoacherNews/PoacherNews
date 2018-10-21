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
                        
                        print "<input type=\"text\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query\" value={$_GET['query']}>";
                        //print "<input type=\"date\" id=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query2\" value={$_GET['query2']}>";         
                    ?>
                    
                    <br>
                    <br>
                    <br>
                    <br>
                    
                    <div id="advancedSearch">
                        <?php
                            print "Refine Search Date";
                            print "<input type=\"date\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query2\" value={$_GET['query2']}>";
                            print "<input type=\"date\" class=\"searchInput\" placeholder=\"What are you looking for?\" name=\"query3\" value={$_GET['query3']}>";
                            
                        ?>
                    </div>
                    
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
				<?php
                    
                    print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Relevancy\">Relevancy</a>";   
                    print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Name\">Name</a>";
                    print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Views\">Views</a>";
                    print "<a href=\"search.php?query={$_GET['query']}&query2={$_GET['query2']}&query3={$_GET['query3']}&sort=Date\">Date</a>";
				?>
                </div>
            </div> 
        </div>
        <div class="advancedSearchButton">
        <button type: "button" onclick="showAdvancedSearch()">Advanced Search</button>
        </div>
        
        <script>
            function showAdvancedSearch(){
                var x = document.getElementById("advancedSearch");
                if (x.style.display === "none") {
                    x.style.display = "flex"
                } else {
                    x.style.display = "none";
                }
            }
        
        </script>

        <div class="resultFlex">
                <div class="testFlex">
                <?php
                    $query = $_GET['query'];
                    $query2 = $_GET['query2'];
                    $query3 = $_GET['query3'];
                    if(!isset($_GET['sort'])){
                        $sort="default";
                    }
                    else{
                        $sort = $_GET['sort'];
                    }

                    $min_length = 1;
					if(!(isset($_GET['sort']))) {
						if(strlen($query) >= $min_length){ 
							$query = htmlspecialchars($query); 
							$query = mysqli_real_escape_string($db, $query);

                            $raw_results = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (PublishDate BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%'))";          
                            
                            
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
							}
							echo "</div>";
						}
                    
                    // TESTING THE SORT FUNCTION
                    } else {
                        switch($sort) {
                            case "Relevancy":
                                 $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (PublishDate BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%')) ORDER BY PublishDate DESC") or die(mysql_error());
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
                                $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (PublishDate BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%')) ORDER BY Headline ASC") or die(mysql_error());
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
                               $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') AND (PublishDate BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%')) ORDER BY Views DESC") or die(mysql_error());
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
                               
                            case "Date":
                               $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%')AND (PublishDate BETWEEN '$query2' AND '$query3') OR (`ArticleImage` LIKE '%".$query."%')) ORDER BY PublishDate DESC") or die(mysql_error());
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
                ?>
                    
                </div>
        </div>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>