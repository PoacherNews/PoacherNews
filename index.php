<?php session_start(); ?>
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
                    'src' : rowData['Image'],
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
	<style>

		#hpBody {
			overflow-x: scroll;
		}

		.wrapper {
			display: flex;
			flex-flow: row wrap;
			margin: 0px auto;
			min-width: 1500px;
			overflow-x: hidden;
		}

		.wrapper > #primary-section { order: 1; }
		.wrapper > #hpAdvert { order: 2; }
		.wrapper > #secondary-section { order: 3; }



		article { padding: 20px; }
		article > img {
			min-width: 100%;
			display: block;
			margin: 0px auto;
		}

		section {
		/*     width: 80%;  */
			margin: 0px auto; 
			margin: auto 10% auto 10%;
			display: flex;
			flex-wrap: wrap;
			align-content: center;
		}

		#primary-section { 
			margin: 0px auto;
			min-width: 1000px;
		}

		#primary-section > .left-sidebar   { order: 1; }
		#primary-section > .left-divider   { order: 2; }
		#primary-section > .main-article   { order: 3; }
		#primary-section > .right-divider  { order: 4; }
		#primary-section > .right-sidebar  { order: 5; }

		#secondary-section {
			flex-direction: column;
			border-left: 1px solid #3d9be9;
			border-right: 1px solid #3d9be9;
			margin-bottom: 50px;
		}

		#secondary-section article {
			display: flex;
			width: 80%;
			margin: 15px auto 15px auto;
			border-bottom: 2px solid #3d9be9;
		}

		#secondary-section .stacked-thumbnail {
			min-width: 315px;
		}

		#secondary-section article > img {
			display: block;
			margin: 0px auto;
		}

		#secondary-section .stacked-text {
			margin-left: 15px;
			display: inline;
		}

		#secondary-section .stacked-text > a > h1 {
			display: block;
			font-size: 30px;
			font-family: "Helvetica Neue",Helvetica,sans-serif;
			font-weight: normal;
			margin: 5px auto 0px auto;
		}

		#secondary-section .stacked-text > h1,p {
			word-wrap: break-word;
		}

		#secondary-section .stacked-text > p { font-family: Arial,Helvetica,sans-serif; }

		.loader {
			margin: 50% auto;
			border: 10px solid #f3f3f3; /* Light grey */
			border-top: 10px solid black;
			border-radius: 50%;
			width: 50px;
			height: 50px;
			animation: spin 2s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		.divider {
			flex-shrink: 1;
			width: 1px;
			margin: auto 0px;
			height: 80%;
			background: #3d9be9;
		}

		.main-article {
			min-width: 500px;
			max-width: 700px;
			flex-grow: 2;
			min-height: 500px;
		}
		.main-article > article {
			margin: auto 20px auto 20px;
		}
		.main-article > article > a > h1 {
			margin: 10px auto 0px auto;
			font-family: Arial black, Arial, Helvetiva, sans-serif;
			font-size: 40px;
			color: #414141;
			font-weight: bold;
		}

		.sidebar {
			min-width: 250px;
			max-width: 365px;
			min-height: 0px;
		}

		.sidebar > article > img {
			max-width: 325px;
			max-height: 217px;
		}

		.sidebar > article > a > h1 {
			margin: 10px auto 0px auto;
			font-size: 40px;
			font-weight: bold;
			color: #414141;
			font-family: Arial,Helvetica,sans-serif;
		}

		#primary-section p {
			word-wrap: break-word;
			color: #414141;
			font-family: Arial,Helvetica,sans-serif;
		}

		#hpAdvert { 
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

		.continue-reading {
			display: block;
			width: 100%;
			text-align: center;
			text-decoration: none;
			font-family: Arial,Helvetica,sans-serif;
			color: #83A8F0;
			font-size: 17px;
		}

		.sidebar-heading {
			margin: 10px auto 5px auto;
			font-size: 40px;
			font-weight: bold;
			font-family: Arial,Helvetica,sans-serif;
			text-align: center;
			text-decoration: underline;
			color: #414141;
		}
	</style>
<body id="hpBody">
    
    <?php
        include 'includes/header.php';
        include 'includes/nav.php';
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
    <?php include('includes/footer.html'); ?>
</body>
</html>
