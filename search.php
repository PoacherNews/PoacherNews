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
                <input type="text" id="searchInput" onkeyup="searchFilter()" placeholder="What are you looking for?" name="query"/>
                <div class="buttonFlex">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <!--
            <div class="filterFlex">
                <p id="filter">Filter</p>
            </div>
            -->
            <div class="testFlex">
            <?php
            $query = $_GET['query'];
            $min_length = 1;
     
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
                                <br>
                                <hr class='searchHr'>";
                    }
                }
                else{
                    print "<b>No results.</b>";
                }
            }
            else{
            //echo "Minimum length is ".$min_length;
            }
            echo "</div>";
            ?>
                <div id="divFilter">
                        <p><b>Sort by:</b></p>
                        <p>Relevancy</p>
                        <p>Views</p>
                        <p>Name</p>
                </div>
            </div>
        </div>
        <div class="adFlex">
            <!--
                <div id="divSideAds"></div>
            -->
            </div>
    </div>
    <script> 
        /* FOR THE DIV FILTER */
        $(document).ready(function(){
            $("#filter").hover(function(){
                $("#divFilter").slideDown("slow");
            });
        });
        
        
        $(function() {
                function showResults(message) {
                    $("<div>").text(message).prependTo("#divTest");
                    $("#divTest").scrollTop(0);
                }

            $("#searchInput").autocomplete({
                source: "search.php",
                minLength: 1,
                select: function(event, ui) {
                    showResults("Selected:" + ui.item.value + " aka " + ui.item.id);
                }
            });
        });
        /* FOR THE INPUT/SEARCH FILTER */
        function searchFilter() {
            
            var input, filter, span, a, img, i;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            img = document.getElementsByClassName("image"); 
            span = document.getElementsByClassName("tip"); 
            
            // FOR SPAN
            for(i = 0; i < span.length; i++) {
                a = span[i].getElementsByTagName("a")[0];
                if(span.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    span[i].style.display = "";
                } else {
                    span[i].style.display = "none";
                }
            }
            // FOR IMAGE
            
        }
    </script>
    <?php include('includes/footer.html'); ?>
</body>
</html>