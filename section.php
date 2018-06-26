<?php 
    session_start();
    $defaultCategory = "Politics";
    if(empty($_GET['Category'])) {
        header('Location: section.php?Category='.$defaultCategory);
        exit();
    }
    include('util/db.php');
    include('util/articleUtils.php');
    include('util/userUtils.php');
?>
<!-- TODO: 
Fix css (Resizing issues / min-width)
-->
<!DOCTYPE html>
<html>
<head>
   <?php include 'includes/globalHead.html' ?>
   <link rel="stylesheet" href="res/css/section.css">
   <script>
        function serializeDate(date) {
            // Will convert a date to YYYY-MM-DD HH:MM:SS format.
            $d = new Date(date);
            $tmp = $d.getFullYear()+"-"+($d.getMonth()+1)+"-"+$d.getDate()+" "+$d.getHours()+":"+$d.getMinutes()+":"+$d.getSeconds();
            console.log($tmp);
            return $tmp;
        }
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

            $article.append($("<span/>", {
                'class' : "publishDate", /* TODO: Add hidden class */
                'text': serializeDate(rowData['PublishDate'])
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

    <!-- containerPrimary Head -->
    <section class="secPrimary">
    <div class="secContainerPrimary">
    <div class="secRowPrimary">
        <div class="secBorderPrimary"></div>
        <div class="secColumnPrimary">
            <div id="articleList">
            <script>
                $.getJSON("util/sectionHandler.php", {
                    'category' : "<?php echo $_GET['Category'] ?>",
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
                    //console.log(getLastPublishDate());
                    $.getJSON("util/sectionUtil.php", {
                        //'offset' : getLastPublishDate(),
                        'Category' : "<?php echo $_GET['Category'] ?>",
                    }).done(function(data) { /*
                        $.each(data, function(i, row) {
                            // $("#secondary-section").children().remove('.loader');
                            $("#articleList").append(createStackedArticle(row));
                        }); */
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
                <?php
                    $picks = getEditorPicks($db);
                    foreach($picks as $article) {
                        print "<article>
                            <div class=\"thumbnailSecondary\">
                                <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"{$article['Image']}\" width=\"150\" height=\"100\"></a>
                            </div>
                            <div class=\"textSecondary\">
                                <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                                <p>".substr($article['Body'], 0, 75)."...</p>                    
                            </div>
                        </article>";
                    }
                ?>
            </div>
            <div class="secBorderSecondary"></div>
            <div class="secColumnMiddleSecondary">
                <h1 class="secCategorySecondary">Trending</h1>
                <?php
                    $trending = getTrendingArticles(3, $db);
                    if($trending === null) {
                        print "<div class=\"columnError\">No trending articles to show.</div>";
                    } else {
                        foreach($trending as $article) {
                            print "<article>
                                <div class=\"thumbnailSecondary\">
                                    <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"{$article['Image']}\" width=\"150\" height=\"100\"></a>
                                </div>
                                <div class=\"textSecondary\">
                                    <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                                    <p>".substr($article['Body'], 0, 75)."...</p>
                                </div>
                            </article>";
                        }
                    }
                ?>
            </div>
            <div class="secBorderSecondary"></div>
            <div class="secColumnRightSecondary">
                <h1 class="secCategorySecondary">User Favorites</h1>
                <?php
                    if(!isset($_SESSION['loggedin'])) {
                        print "<div class=\"columnError\">Log in to see your favorites.</div>";
                    } else {
                        $favorites = getUserFavorites($_SESSION['userid'], 3, $db);
                        if($favorites === null) {
                            print "<div class=\"columnError\">No favorites yet.</div>";
                            return;
                        }
                        foreach($favorites as $article) {
                            print "<article>
                                <div class=\"thumbnailSecondary\">
                                    <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"{$article['Image']}\" width=\"150\" height=\"100\"></a>
                                </div>
                                <div class=\"textSecondary\">
                                    <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                                    <p>".substr($article['Body'], 0, 75)."...</p>                  
                                </div>
                            </article>";
                        }
                    }
                ?> 
            </div>
            <div class="secBorderSecondary"></div>
        </div>
        </div>
    </section>
    <!-- containerSecondary Tail -->

    <?php include('includes/footer.html'); ?>
</body>
</html>
