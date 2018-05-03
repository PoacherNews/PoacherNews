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
            <h2>Search Results</h2>
            <form class="search" action="" method="GET">
                <input type="text" id="searchInput" placeholder="What are you looking for?" name="query"/>
                <button type="submit"><i class="fa fa-search"></i></button>
                <p id="filter">Filter</p>
            </form>
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
                        echo "<img src=".$results['Img']." class='image img' height='100' width='100'>"."<span class='tip span'>".$results['Headline']."</span>";
                        echo "<br>";
                    }
                }
                else{
                    echo "No results.";
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
        $(document).ready(function(){
            $("#filter").hover(function(){
                $("#divFilter").slideDown("slow");
            });
        });
    </script>
    <?php include('includes/footer.html'); ?>
</body>
</html>