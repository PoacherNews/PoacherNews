<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'includes/globalHead.html' ?>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
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
                'src' : "https://poachernews.com/res/img/articlePictures/"+rowData['ArticleID']+"/"+rowData['ArticleImage'],
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
                'src' : "https://poachernews.com/res/img/articlePictures/"+rowData['ArticleID']+"/"+rowData['ArticleImage'],
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
        <div id="adsenseBanner">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Large banner ad -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:970px;height:90px"
                 data-ad-client="ca-pub-3927571828981469"
                 data-ad-slot="9395185359"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
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
            <!--<a href="advertising.php">Advertise with us!</a>-->
		<div id="adsenseBanner">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Large banner ad -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:970px;height:90px"
                     data-ad-client="ca-pub-3927571828981469"
                     data-ad-slot="9395185359"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </section>

        <section id="secondaryarticles" class="stackedArticles">    
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
