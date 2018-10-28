<?php 
    session_start();
    $defaultCategory = "Politics";
    $validCategories = array("Politics", "Sports", "Entertainment", "Video", "Local", "Opinion");
    if(empty($_GET['Category']) || !in_array($_GET['Category'], $validCategories)) {
        header('Location: section.php?Category='.$defaultCategory);
        exit();
    }
    include('util/db.php');
    include('util/articleUtils.php');
    include('util/userUtils.php');

    $threeColLimit = 3; // Defined limit to the length of the lists in the 3-column sections
?>
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
            if($.isArray(rowData)) { // This is an encapsulated JSON object
                rowData = rowData[0];
            }

            var $previewCharLimit = 400;
            var $continueReading = $("<a/>", {
                'class' : "continue-reading",
                'href' : "article.php?articleid="+rowData['ArticleID'],
                'text' : "Continue Reading"
            });
            var $article = $("<article/>");

            $imageWrap = $("<a/>", {
                'href' : "article.php?articleid="+rowData['ArticleID'],
            });
            var $thumbnail = $("<div/>", {
                'class' : "stacked-thumbnail"
            });
            $imageWrap.append($("<img/>", {
                'src' : "/res/img/articlePictures/"+rowData['ArticleID']+"/"+rowData['ArticleImage'],
                'height' : "217",
                'width' : "325",
            }));
            $thumbnail.append($imageWrap);
            var $text = $("<div/>", {
                'class' : 'stacked-text'
            });
            
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
    <div id="mainContent">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Large banner ad -->
    <ins class="adsbygoogle"
         style="display:inline-block;width:970px;height:90px"
         data-ad-client="ca-pub-3927571828981469"
         data-ad-slot="9395185359"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    <p id="secName"><?php echo $_GET['Category'] ?></p>

    <!-- containerPrimary Head -->
    <section class="secPrimary">
    <div class="secContainerPrimary">
    <div class="secRowPrimary">
        <div class="secBorderPrimary"></div>
        <div class="secColumnPrimary">
            <div id="articleList" class="stackedArticles">
                <script>
                    $.getJSON("util/sectionHandler.php", {
                        'category' : "<?php echo $_GET['Category'] ?>",
                    }).done(function(data) {
                        $.each(data, function(i, row) {
                            $("#articleList").children().remove('.loader');
                            $("#articleList").append(createStackedArticle(row));
                        });
                    });
                </script>
                <div class="loader"></div>
            </div>
            <div class="showMore">Show More</div>
            <script>
                $(".showMore").data('offset', 1); // Initial offset of 1
                $(".showMore").click(function() {
                    console.log("Clicked"); //DEBUG
                    console.log("Offset:");
                    console.log($(this).data('offset'));
                    
                    $.getJSON("util/sectionHandler.php", {
                        'category' : "<?php echo $_GET['Category'] ?>",
                        'offset' : $(this).data('offset'),
                    }).done(function(data) {
                        $.each(data, function(i, row) {
                            $("#articleList").append(createStackedArticle(row));                          
                        });
                        $('.showMore').data('offset', $('.showMore').data('offset') + 1); // Increase offset so we get new records next time we're clicked
                        $('html, body').animate({ // Scroll the page to the newly loaded articles
                                scrollTop: ($('#articleList article:nth-last-child(3)').offset().top - 100) // -100 to account for nav bar space
                        }, 500);
                    });
                    
                });
            </script>
        </div>

        <div class="secBorderPrimary"></div>
    </div>
    </div>
    </section>
        <!-- containerPrimary Tail -->
        
    <section class="bannerAd">
            <a href="advertising.php">Advertise with us!</a>
    </section>

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Large banner ad -->
    <ins class="adsbygoogle"
         style="display:inline-block;width:970px;height:90px"
         data-ad-client="ca-pub-3927571828981469"
         data-ad-slot="9395185359"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>

    <!-- containerSecondary Head -->
    <section class="secSecondary">
        <div class="containerSecondary">
        <div class="secRowSecondary">
            <div class="secBorderSecondary"></div>
            <div class="secColumnLeftSecondary">
                <h1 class="secCategorySecondary">Editor's Picks</h1>
                <?php
                    $ep = getEditorPicks($_GET['Category'], NULL, $db);
                    if($ep === null) {
                        print "<div class=\"columnError\"><p>No editor picks in this section.</p></div>";
                    } else {
                        $picks = array_slice(getEditorPicks($_GET['Category'], NULL, $db), 0, $threeColLimit);
                        foreach($picks as $article) {
                            print "<article>
                                <div class=\"thumbnailSecondary\">
                                    <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"/res/img/articlePictures/{$article['ArticleID']}/{$article['ArticleImage']}\" width=\"150\" height=\"100\"></a>
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
            <div class="secColumnMiddleSecondary">
                <h1 class="secCategorySecondary">Trending</h1>
                <?php
                    $trending = getTrendingArticles($_GET['Category'], $threeColLimit, $db);
                    if($trending === null) {
                        print "<div class=\"columnError\"><p>No trending articles in this section.</p></div>";
                    } else {
                        foreach($trending as $article) {
                            print "<article>
                                <div class=\"thumbnailSecondary\">
                                    <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"/res/img/articlePictures/{$article['ArticleID']}/{$article['ArticleImage']}\" width=\"150\" height=\"100\"></a>
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
                <h1 class="secCategorySecondary">User Bookmarks</h1>
                <?php
                    if(!isset($_SESSION['loggedin'])) {
                        print "<div class=\"columnError\"><p>Log in to see your bookmarks.</p></div>";
                    } else {
                        $bookmarks = getUserBookmarks($_SESSION['userid'], $_GET['Category'], $threeColLimit, $db);
                        if($bookmarks === null) {
                            print "<div class=\"columnError\"><p>No bookmarks in this section.</p></div>";
                        } else {
                            foreach($bookmarks as $article) {
                                print "<article>
                                    <div class=\"thumbnailSecondary\">
                                        <a href=\"article.php?articleid={$article['ArticleID']}\"><img src=\"/res/img/articlePictures/{$article['ArticleID']}/{$article['ArticleImage']}\" width=\"150\" height=\"100\"></a>
                                    </div>
                                    <div class=\"textSecondary\">
                                        <h2 class=\"secHeadlineSecondary\"><a href=\"article.php?articleid={$article['ArticleID']}\">{$article['Headline']}</a></h2>
                                        <p>".substr($article['Body'], 0, 75)."...</p>                  
                                    </div>
                                </article>";
                            }
                        }
                    }
                ?> 
            </div>
            <div class="secBorderSecondary"></div>
        </div>
        </div>
    </section>
    <!-- containerSecondary Tail -->

    </div>
    <?php include 'includes/footer.html'; ?>
</body>
</html>
