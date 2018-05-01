<!-- TODO: 
Fix css (Resizing issues / min-width)
-->

<!DOCTYPE html>
<html>
    <head>
	   <?php include 'includes/globalHead.html' ?>
    </head>
    
    <body>
        <?php 
        include 'loginCheck.php';
        if($loggedin) {
            include 'includes/header_internal.php';
        }
        else {
            include 'includes/header.php';
        }
            include 'includes/nav.php';
            //include 'includes/footer.html';
        ?>
        
        <p id="secName">Section</p>

        <!-- containerPrimary Head -->
        <section class="secPrimary">
        <div class="secContainerPrimary">
        <div class="secRowPrimary">
            <div class="secBorderPrimary"></div>

            <div class="secColumnPrimary">
            <article>
                <div class="thumbnailPrimary">
                    <img src="moon.jpg" width="350" height="250">
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
                
            <article>
                <div class="thumbnailPrimary">
                    <img src="" width="350" height="250">
                </div>
                <div class="textPrimary">
                    <h1 class="secHeadlinePrimary"><a href="">(Headline Placeholder)</a></h1>
                    <p>(Filler Text Filler Text Filler Text)</p>
                </div>
            </article>

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

    </body>
</html>
