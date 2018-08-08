<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="/res/css/homepage.css">
    <!-- Javascript/Jquery imports here -->
    <script>
        function logError(e) {
            console.log("ERROR [ErrorID: "+e['errorId']+"] // "+e['errorString']+"\nError details: \n\tAttempted SQL string: "+e['sqlQuery']+"\n\tRequest: "+e['request']);
        }

        function createColumnArticle(rowData) {
            if($.isArray(rowData)) { // This is an encapsulated JSON object
                rowData = rowData[0];
            }
            if(rowData.hasOwnProperty('errorId')) {
                logError(rowData);
                return;
            }
            /* Makes an article DOM object and populates elements for page display.
               Accepts a JSON formatted string object for parsing.
            */
            var $previewCharLimit = 375;
            var $continueReading = $("<a/>", {
                'class' : "continue-reading",
                'href' : "article.php?articleid="+rowData['ArticleID'],
                'text' : "Continue Reading"
            });
            var $article = $("<article/>");
            
            $imageWrap = $("<a/>", {
                'href' : "article.php?articleid="+rowData['ArticleID'],
            });
            $imageWrap.append($("<img/>", {
                'src' : rowData['ArticleImage'],
                'class': "articleImage"
            }));
            $article.append($imageWrap);

            var $headerWrap = $("<a/>", {
                'href' : "article.php?articleid="+rowData['ArticleID']
            });
            $headerWrap.append($("<h1/>", {
                'text' : rowData['Headline']
            }));
            $article.append($headerWrap);
            $article.append($("<p/>", {
                // Trim article contents to a set length, add ellipsis to denote continuation in article page
                'text' : rowData['Body'].substring(0, $previewCharLimit)+"..."
            }));
            $article.append($continueReading);
            
            return $article;
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
                'src' : rowData['ArticleImage'],
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
    <div class="pageContent">
        <section id="editorPicks">
            <h1 class="sidebar-heading">Editor Picks</h1>
            <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'editorpicks'
                    }).done(function(data) {
                        $("#editorPicks").children().remove('.loader');
                        if(!$.isArray(data)) {
                            createColumnArticle(data);
                            return;
                        } else {
                            $.each(data, function(i, row) {
                                $("#editorPicks").append(createColumnArticle(row));
                            });
                        }
                    });
                </script>
                <div class="loader"></div>
        </section>

        <div class="divider"></div>

        <section id="mainArticle">
            <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'main'
                    }).done(function(data) {
                        $("#mainArticle").children().remove('.loader');
                        $("#mainArticle").append(createColumnArticle(data));
                    });
                </script>
                <div class="loader"></div>
        </section>

        <div class="divider"></div>

        <section id="trending">
            <h1 class="sidebar-heading">Trending</h1>
                <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'trending'
                    }).done(function(data) {
                        $("#trending").children().remove('.loader');
                        if(data === null) {
                            $("#trending").append($("<div/>", {
                                'class' : "columnError",
                                'text' : "No trending articles to show."
                            }));
                            return;
                        }
                        
                        if(!$.isArray(data)) {
                            createColumnArticle(data);
                            return;
                        } else {
                            $.each(data, function(i, row) {
                                $("#trending").append(createColumnArticle(row));
                            });
                        }
                    })
                </script>
                <div class="loader"></div>
        </section>

        <section class="bannerAd">
            <a href="advertising.php">Advertise with us!</a>
        </section>

        <section id="secondaryarticles" class="stackedArticles">
            <article>
                 <a class="stacked-thumbnail" href="article.php?articleid=87">
                    <img src="https://i.imgur.com/U469uHI.jpg" style="height: 217px; width: 325px;">
                </a>
                <div class="stacked-text">
                    <a href="article.php?articleid=87"><h1>I am never at home on Sundays!!!!!!!</h1></a>
                    <p>TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY TEST BODY ...</p>
                    <a class="continue-reading" href="article.php?articleid=87">Continue Reading</a>
                </div>
            </article>
            <script>
                 $.getJSON("util/homepage.php", {
                    'request' : 'secondaryarticles'
                }).done(function(data) {
                    if(data.hasOwnProperty('errorId')) {
                        logError(data);
                        return;
                    }
                    $.each(data, function(i, row) {
                        $("#secondaryarticles").children().remove('.loader');
                        $("#secondaryarticles").append(createStackedArticle(row));
                    });
                });
            </script>
            <div class="loader"></div>
        </section>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>
