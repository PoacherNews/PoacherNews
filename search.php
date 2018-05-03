<!DOCTYPE html>
<html>
    <head>
        <title>The Poacher | Search</title>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/search.css">
    </head>
    <body>
        <?php 
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        <div class="flexRow">
            <div class="searchFlex">
                <h2>Search Results</h2>
                <form class="search" action="search.php" method="GET">
                    <input type="text" id="searchInput" placeholder="What are you looking for?" name="query">
                    <button type="submit"><i class="fa fa-search"></i></button>
                    <p id="filter">Filter</p>
                </form>
                <div class="testFlex">
                    <div id="divTest">This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. This is a test. </div>
                    <div id="divFilter">
                        <p><b>Sort by:</b></p>
                        <p>Relevancy</p>
                        <p>Views</p>
                        <p>Name</p>
                    
                    </div>
                </div>
            </div>
            <div class="adFlex">
                <div id="divSideAds"></div>
            </div>
        </div>
        <!-- JAVASCRIPT FUNCTIONALITY -->
        <script>
            
            $(document).ready(function(){
                $("#filter").hover(function(){
                    $("#divFilter").slideDown("slow");
                });
            });
        </script>
        <?php
        /*Include standard database*/
        include('includes/footer.html');
        include('util/db.php');
        if($db->connect_error) {
            die("Connection failed: ". $db->connect_error);
        }
        $query = $_GET['query'];
        
        $min_length = 3;
        
        if(strlen($query) >= $min_length) {
            $query = htmlspecialchars($query);
            
            $query = mysql_real_escape_string($query);

            $raw_results = mysql_query("SELECT * FROM Articles WHERE ('Headline' LIKE  '%".$query."%')") or die(mysql_eror());
        }
        
        if(mysql_num_rows($raw_results) > 0) {
            while($results = mysql_fetch_array($raw_results)) {
                echo "<p><h3>".$results['Headline']."</h3></p>";
            }
        } 
        ?>  
    </body>
</html>