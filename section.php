<?php session_start(); ?>
<!-- TODO: 
Fix css (Resizing issues / min-width)
-->
<!DOCTYPE html>
<html>
<head>
   <?php include 'includes/globalHead.html' ?>
   <link rel="stylesheet" href="res/css/section.css">
   <script>
        function createStackedArticle(rowData) {
            /* Makes an article DOM object and populates elements for page display.
               Accepts a JSON formatted string object for parsing.
            */                
            if($.isArray(rowData)) { // In case this is an encapsulated JSON object
                rowData = rowData[0];
            }
            if(rowData.hasOwnProperty('errorId')) {
                logError(rowData);
                return;
            }
            var $previewCharLimit = 400;
            
            var $continueReading = $("<a/>", {
                'class' : "continue-reading",
                'href' : "article.php?articleid="+rowData['ArticleID'],
                'text' : "Continue Reading"
            });
            var $article = $("<article/>");
            $article.append($("<div/>", {
                'class' : "publishDate hidden",
                'text': rowData['PublishDate']
            }));
            var $thumbnail = $("<div/>", {
                'class' : "stacked-thumbnail"
            });
            var $text = $("<div/>", {
                'class' : 'stacked-text'
            });
            
            $thumbnail.append($("<img/>", {
                'src' : rowData['Image'],
                'height' : "217",
                'width' : "325",
            }));
            var $headerWrap = $("<a/>", {
                'href' : "article.php?articleid="+rowData['ArticleID']
            });
            $headerWrap.append($("<h1/>", {
                'text' : rowData['Headline']
            }));
            $text.append($headerWrap);
            $text.append($("<p/>", {
                // Trim article contents to a set length, add ellipsis to denote continuation in article page
                'text' : rowData['Body'].substring(0, $previewCharLimit)+"..."
            }));
            $text.append($continueReading);
            $article.append([$thumbnail, $text]);
            
            return $article;
        }
    </script>
</head>
    
<body>
    <?php 
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    
    <p id="secName"><?php echo $_GET['Category'] ?></p>

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

    <!-- containerPrimary Head -->
    <section class="secPrimary">
    <div class="secContainerPrimary">
    <div class="secRowPrimary">
        <div class="secBorderPrimary"></div>
        <div class="secColumnPrimary">
            <div id="articleList">
            <script>
                $.getJSON("util/sectionUtil.php", {
                    'Category' : "<?php echo $_GET['Category'] ?>",
                }).done(function(data) {
                    $.each(data, function(i, row) {
                        // $("#secondary-section").children().remove('.loader');
                        $("#articleList").append(createStackedArticle(row));
                    });
                });
            </script>
            </div>
            <div class="showMore">Show More</div>
            <script>
                function getLastPublishDate() {
                    var $lastArticle = $("#articleList").children("article");
                    return $lastArticle.children(".publishDate").text();
                };
                $(".showMore").click(function() {
                    console.log("Clicked"); //DEBUG
                    getLastPublishDate();
                    $.getJSON("util/sectionUtil.php", {
                        'offset' : getLastPublishDate(),
                        'Category' : "<?php echo $_GET['Category'] ?>",
                    }).done(function(data) {
                        $.each(data, function(i, row) {
                            // $("#secondary-section").children().remove('.loader');
                            $("#articleList").append(createStackedArticle(row));
                        });
                    });
                });
            </script>
            <!-- <div class="loader"></div> -->
        </div>

        <div class="secBorderPrimary"></div>
    </div>
    </div>
    </section>
        <!-- containerPrimary Tail -->
        


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
