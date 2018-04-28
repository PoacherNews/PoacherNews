<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
        <!-- Javascript/Jquery imports here -->
        <script>
            function createColumnArticle(rowData) {
                console.log(rowData); //DEBUG
                /* Makes an article DOM object and populates elements for page display.
                   Accepts a JSON formatted string object for parsing.
                */
                var $previewCharLimit = 375;
                var $continueReading = $("<a/>", {
                    'class' : "continue-reading",
                    'href' : "#", //TODO: Figure out how to determine the article URL
                    'text' : "Continue Reading"
                });
                var $article = $("<article/>");
                
                $article.append($("<img/>", {
                    'src' : rowData['Img'],
                    'width' : "600",
                    'height' : "430"
                }));
                $article.append($("<h1/>", {
                    'text' : rowData['Headline']
                }));
                $article.append($("<p/>", {
                    // Trim article contents to a set length, add ellipsis to denote continuation in article page
                    'text' : rowData['Body'].substring(0, $previewCharLimit)+"..."
                }));
                $article.append($continueReading);
                
                return $article;
            }
            
            function createStackedArticle(rowData) {
                console.log(rowData); //DEBUG
                /* Makes an article DOM object and populates elements for page display.
                   Accepts a JSON formatted string object for parsing.
                */
                var $previewCharLimit = 400;
                var $continueReading = $("<a/>", {
                    'class' : "continue-reading",
                    'href' : "#", //TODO: Figure out how to determine the article URL
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
                $text.append($("<h1/>", {
                    'text' : rowData['Headline']
                }));
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
                        $(".main-article").append(createColumnArticle(data[0]));
                    });
                </script>
            </div>
            <div class="divider left-divider"></div>
            <div id="leftSidebar" class="sidebar left-sidebar">
                <h1 class="sidebar-heading">Editor's Choice</h1>
                <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'editorpicks'
                    }).done(function(data) {
                        $.each(data, function(i, row) {
                            $("#leftSidebar").append(createColumnArticle(row));
                        });
                    });
                </script>
            </div>
            <div class="divider right-divider"></div>
            <div id="rightSidebar" class="sidebar right-sidebar">
                <h1 class="sidebar-heading">Trending</h1>
                <script>
                    $.getJSON("util/homepage.php", {
                        'request' : 'trending'
                    }).done(function(data) {
                        $.each(data, function(i, row) {
                            $("#rightSidebar").append(createColumnArticle(row));
                        });
                    })
                </script>             
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
                        $("#secondary-section").append(createStackedArticle(row));
                    });
                });
            </script>
        </section>
    </div>
</body>
</html>
