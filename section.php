<?php session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 ?>
<!-- TODO: 
Fix css (Resizing issues / min-width)
-->
<!DOCTYPE html>
<html>
    <head>
	   <?php include 'includes/globalHead.html' ?>
    </head>
    <style>
		body {
			height: 100%;
			margin: 0;
			overflow-x:scroll;   
		}    

		a {
			color: black;
			text-decoration: none;
		}   

		#secName {
			font-size: 30px;
			text-align: center;
		}

		.secContainerPrimary .secRowPrimary .secColumnPrimary article {
			display: inline-flex;
			word-wrap: break-word;
			border-bottom: 1px solid #3d9be9;
			padding-left: 50px;
			padding-bottom: 30px;
			margin-left: 100px;
			margin-right: 100px;
		}    

		.secContainerPrimary .secRowPrimary .secColumnPrimary .thumbnailPrimary {
			margin-top: 30px;
			margin-right: 30px;
		}

		.secBorderPrimary {
			border: 1px solid #3d9be9;
			height: auto;
		}

		.secContainerPrimary {
			display: flex;
			justify-content: center;
			width: 100%;
			height: 100%;
		}

		.secRowPrimary {
			display: flex;
			flex-direction: row;
			justify-content: center;
			width: 80%;
			height: 100%;
		}

		.secColumnPrimary {
			display: flex;
			flex-direction: column;
			justify-content: center;
			flex: 100%;
			background-color: azure;
		}

		.secHeadlinePrimary {
			font-size: 30px;
		}

		.showMore {
			text-align: center;
			margin-top: 10px;
		}

		.banner-ad { 
			width: 100%;
			margin: 15px auto 15px auto;
			padding: 15px;
			border-top: 2px solid #3d9be9;
			border-bottom: 2px solid #3d9be9;
		}

		.ad { /* Temporary styling for placeholder purposes */
			margin: 0px auto;
			text-align: center;
			font-family: sans-serif;
			font-weight: bold;
			font-size: 1.5em;
		}    

		.containerSecondary .secRowSecondary article {
			display: inline-flex;
			padding-left: 20px;
			padding-right: 10px;
			padding-bottom: 30px;
			padding-top: 10px;
		}        

		.containerSecondary .secRowSecondary .thumbnailSecondary {
			margin-right: 10px;
		}

		.containerSecondary {
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			height: 100%;
			}

		.secRowSecondary {
			display: flex;
			flex-direction: row;
			justify-content: center;
			width: 80%;
			background-color: bisque;
		}

		.secColumnLeftSecondary {
			display: flex;
			flex-direction: column;
			flex: 33%;
		}

		.secColumnMiddleSecondary {
			display: flex;
			flex-direction: column;
			flex: 34%
		}

		.secColumnRightSecondary {
			display: flex;
			flex-direction: column;
			flex: 33%;
		}

		h2 {
			display: inline;
			font-size: 18px;
		}

		.secBorderSecondary {
			border: 1px solid #3d9be9;
			height: auto;
		}

		.secCategorySecondary {
			font-size: 24px;
			text-decoration: underline;
			text-align: center;
		}
	</style>
    <body>
        <?php 
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <p id="secName">Section</p>

        <!-- containerPrimary Head -->
        <section class="secPrimary">
        <div class="secContainerPrimary">
        <div class="secRowPrimary">
            <div class="secBorderPrimary"></div>

            <div class="secColumnPrimary">
<?php
include 'util/db.php';
$query = "SELECT * FROM Article WHERE Category='" . mysqli_real_escape_string($db, $_GET['Category']) . "' ORDER BY PublishDate DESC LIMIT 3";
$results = mysqli_query($db, $query) or die (mysqli_error());

while($row = mysqli_fetch_assoc($results)){
if($row['IsSubmitted'] == 1) {
$substr_value = substr($row['Body'],0,200).'...';
          echo "<article>
                <div class='thumbnailPrimary'>
                    <img src='{$row['Image']}' width='350' height='250'>
                </div>
                <div class='textPrimary'>
                    <h1 class='secHeadlinePrimary'><a href='/article.php?articleid={$row['ArticleID']}'>{$row['Headline']}</a></h1>
                    <p>$substr_value</p>
                </div>
            </article>";
}
}
?>

<!--      
            <article>
                <div class="thumbnailPrimary">
                    <img src="" width="350" height="250">
                </div>
                <div class="textPrimary">
                    <h1 class="secHeadlinePrimary"><a href="">(Headline Placeholder)</a></h1>
                    <p>(Filler Text Filler Text Filler Text)</p>
                </div>
            </article>    
                
            <article>
                <div class="thumbnailPrimary">
                    <img src="" width="350" height="250">
                </div>
                <div class="textPrimary">
                    <h1 class="secHeadlinePrimary"><a href="">(Headline Placeholder)</a></h1>
                    <p>(Filler Text Filler Text Filler Text)</p>
                </div>
            </article>
-->

            <!-- Show More -->
            <div class="showMore">
                <a href="">Show More</a>
            </div>
                
            </div>
            <div class="secBorderPrimary"></div>
        </div>
        </div>
        </section>
        <!-- containerPrimary Tail -->
        
        <!-- Banner-Ad -->
        <section class="banner-ad">
            <div class="ad">
                <p>AD</p>
            </div>
        </section>

        <!-- containerSecondary Head -->
        <section class="secSecondary">
        <div class="containerSecondary">
        <div class="secRowSecondary">
        <div class="secBorderSecondary"></div>
            <div class="secColumnLeftSecondary">
                <h1 class="secCategorySecondary">Editor's Picks</h1>
                <article>
                    <div class="thumbnailSecondary">
                        <img src="" width="120" height="100">
                    </div>
                    <div class="textSecondary">
                        <h2 class="secHeadlineSecondary"><a href="">(Headline Placeholder)</a></h2>
                        <p>(Filler Text Filler Text Filler Text)</p>                    
                    </div>
                </article>
                <article>
                    <div class="thumbnailSecondary">
                        <img src="" width="120" height="100">
                    </div>
                    <div class="textSecondary">
                        <h2 class="secHeadlineSecondary"><a fref="">(Headline Placeholder)</a></h2>
                        <p>(Filler Text Filler Text Filler Text)</p>                    
                    </div>
                </article>
            </div>
        <div class="secBorderSecondary"></div>
            <div class="secColumnMiddleSecondary">
                <h1 class="secCategorySecondary">Trending</h1>
                <article>
                    <div class="thumbnailSecondary">
                        <img src="" width="120" height="100">
                    </div>
                    <div class="textSecondary">
                        <h2 class="secHeadlineSecondary"><a href="">(Headline Placeholder)</a></h2>
                        <p>(Filler Text Filler Text Filler Text)</p>                    
                    </div>
                </article>
                <article>
                    <div class="thumbnailSecondary">
                        <img src="" width="120" height="100">
                    </div>
                    <div class="textSecondary">
                        <h2 class="secHeadlineSecondary"><a href="">(Headline Placeholder)</a></h2>
                        <p>(Filler Text Filler Text Filler Text)</p>                    
                    </div>
                </article>
            </div>
        <div class="secBorderSecondary"></div>
            <div class="secColumnRightSecondary">
                <h1 class="secCategorySecondary">User Favorites</h1>   
                <article>
                    <div class="thumbnailSecondary">
                        <img src="" width="120" height="100">
                    </div>
                    <div class="textSecondary">
                        <h2 class="secHeadlineSecondary"><a href="">(Headline Placeholder)</a></h2>
                        <p>(Filler Text Filler Text Filler Text)</p>                    
                    </div>
                </article>
                <article>
                    <div class="thumbnailSecondary">
                        <img src="" width="120" height="100">
                    </div>
                    <div class="textSecondary">
                        <h2 class="secHeadlineSecondary"><a href="">(Headline Placeholder)</a></h2>
                        <p>(Filler Text Filler Text Filler Text)</p>                    
                    </div>
                </article>
            </div>
        <div class="secBorderSecondary"></div>
        </div>
    </div>
    </section>
        <!-- containerSecondary Tail -->      
    <?php include('includes/footer.html'); ?>
    </body>
</html>
