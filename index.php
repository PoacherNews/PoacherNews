<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
        <!-- Javascript/Jquery imports here -->
        <script>
            function logError(e) {
                console.log("ERROR [ErrorID: "+e['errorId']+"] // "+e['errorString']+"\nError details: \n\tAttempted SQL string: "+e['sqlQuery']+"\n\tRequest: "+e['request']);
            }

            function createColumnArticle(rowData) {
                console.log(rowData);
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
                
                $article.append($("<img/>", {
                    'src' : rowData['Img'],
                    'width' : "600",
                    'height' : "430"
                }));
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
                var $thumbnail = $("<div/>", {
                    'class' : "stacked-thumbnail"
                });
                var $text = $("<div/>", {
                    'class' : 'stacked-text'
                });
                
                $thumbnail.append($("<img/>", {
                    'src' : rowData['Img'],
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
<body class="hpBody">
    
    <?php
        include 'includes/header.php';
        include 'includes/nav.php';
        //include 'includesfooter.html'; // Disabled until bugfix completed by Roland
    ?>
    <div class="wrapper">
        <!-- Three column section -->
        <section id="primary-section">
            <div class="main-article">
                <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'main'
                    }).done(function(data) {
                        $(".main-article").children().remove('.loader');
                        $(".main-article").append(createColumnArticle(data));
                    });
                </script>
                <div class="loader"></div>
            </div>
            <div class="divider left-divider"></div>
            <div id="leftSidebar" class="sidebar left-sidebar">
                <h1 class="sidebar-heading">Editor Picks</h1>
                <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'editorpicks'
                    }).done(function(data) {
                        $("#leftSidebar").children().remove('.loader');
                        if(!$.isArray(data)) {
                            createColumnArticle(data);
                            return;
                        } else {
                            $.each(data, function(i, row) {
                                $("#leftSidebar").append(createColumnArticle(row));
                            });
                        }
                    });
                </script>
                <div class="loader"></div>
            </div>
            <div class="divider right-divider"></div>
            <div id="rightSidebar" class="sidebar right-sidebar">
                <h1 class="sidebar-heading">Trending</h1>
                <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'trending'
                    }).done(function(data) {
                        $("#rightSidebar").children().remove('.loader');
                        if(!$.isArray(data)) {
                            createColumnArticle(data);
                            return;
                        } else {
                            $.each(data, function(i, row) {
                                $("#rightSidebar").append(createColumnArticle(row));
                            });
                        }
                    })
                </script>
                <div class="loader"></div>
            </div>
        </section>

        <!-- Banner ad section -->
        <section id="hpAdvert">
            <div class="ad">
                <p>AD</p>
            </div>
        </section>
        
        <!-- Stacked article section -->
        <section id="secondary-section">
            <script>
                 $.getJSON("util/homepage.php", {
                    'request' : 'secondaryarticles'
                }).done(function(data) {
                    $.each(data, function(i, row) {
                        $("#secondary-section").children().remove('.loader');
                        $("#secondary-section").append(createStackedArticle(row));
                    });
                });
            </script>
            <div class="loader"></div>
        </section>
    </div>
</body>
</html>
