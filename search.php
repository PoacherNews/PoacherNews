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
                <form class="search" action="">
                    <input type="text" id="searchInput" placeholder="What are you looking for?" name="searchBar">
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
        include('dp.php');
        if($db->connect_error) {
            die("Connection failed: ". $db->connect_error);
        }
        
        
        
        ?>  
    </body>
</html>