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
				<?php
                    print "<a href=\"search.php?query={$_GET['query']}&sort=Relevancy\">Relevancy</a>";
                    print "<a href=\"search.php?query={$_GET['query']}&sort=Name\">Name</a>";
                    print "<a href=\"search.php?query={$_GET['query']}&sort=Views\">Views</a>";
				?>
                </div>
            </div> 

        </div>

        <div class="resultFlex">
                <div class="testFlex">
                <?php
                    $query = $_GET['query'];
                    $sort = $_GET['sort'];
                    $min_length = 1;
					if(!(isset($_GET['sort']))) {
						if(strlen($query) >= $min_length){ 
							$query = htmlspecialchars($query); 
							$query = mysqli_real_escape_string($db, $query);

							$raw_results = "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') OR (`Image` LIKE '%".$query."%'))";
							$test = mysqli_query($db, $raw_results);
							echo "<div class='search-content'>";

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
							echo "</div>";
						}
                    
                    // TESTING THE SORT FUNCTION
                    } else {
                        switch($sort) {
                            case "Relevancy":
                                $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') OR (`Image` LIKE '%".$query."%')) ORDER BY PublishDate DESC") or die(mysql_error());
                                echo "<div class='search-content'>";
                                if(mysqli_num_rows($raw_results) > 0){ 
                                    while($results = mysqli_fetch_array($raw_results)){
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
                                $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') OR (`Image` LIKE '%".$query."%')) ORDER BY Headline ASC") or die(mysql_error());
                                echo "<div class='search-content'>";
                                if(mysqli_num_rows($raw_results) > 0){ 
                                    while($results = mysqli_fetch_array($raw_results)){
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
                               $raw_results = mysqli_query($db, "SELECT * FROM Article WHERE IsSubmitted = 1 AND IsDraft = 0 AND ((`Headline` LIKE '%".$query."%') OR (`Image` LIKE '%".$query."%')) ORDER BY Views DESC") or die(mysql_error());
                                echo "<div class='search-content'>";
                                if(mysqli_num_rows($raw_results) > 0){ 
                                    while($results = mysqli_fetch_array($raw_results)){
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