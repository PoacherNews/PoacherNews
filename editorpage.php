<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
	    <?php include 'includes/globalHead.html'?>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    </head>
    <body>
        <?php
        	include 'util/loginCheck.php';
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
    <div class="epWrapper">

        <form id="epEditor" action="submitArticle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="saveArticle">
            <div>
                <h1>Article Details</h1>
                <input type="text" placeholder="Article Title" id="epArticleTitle" name="title">
                <input list="category" name="category">
                <datalist id="category">
                    <option value="politics">
                    <option value="sports">
                    <option value="entertainment">
                    <option value="video">
                    <option value="local">
                    <option value="opinion">
                </datalist>
            </div>
            <div id="epArticle-body">
                <h1>Article Contents</h1>
                <textarea id="epArticleBody" placeholder="Article contents.." name="body"></textarea>
            </div>

            <div id="epButtons">
                <div class="epButton-column">
                    <p>Choose Image</p>
                    <input id="epImageUploadButton" type="file" value="Upload Image" name="image">
                    <p>Choose Text File</p>
                    <input id="epUploadTextButton" type="file" name="file" value="Upload Text File">
                </div>
                <div class="epButton-column">
                    <input id="epPreviewButton" type="submit" value="Preview">
                    <input id="epSubmit" type="submit" value="Submit" name="submit">
                </div>
            </div>
        </form>
    </div>
    <div id="preview">
    </div>
    </body>
</html>
