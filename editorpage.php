<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
    <body>
        <?php 
            include 'includes/header.php';
            include 'includes/nav.php';
            //include 'includes/footer.html';
        ?>
    <div class="epWrapper">

        <form id="epEditor">
            <div>
                <h1>Article Title</h1>
                <input type="text" placeholder="Article Title" id="epArticleTitle">
            </div>
            <div id="epArticle-body">
                <h1>Article Contents</h1>
                <textarea id="epArticleBody" placeholder="Article contents.."></textarea>
            </div>

            <div id="epButtons">
                <div class="epButton-column">
                    <input id="epImageUploadButton" type="button" value="Upload Image">
                    <input id="epUploadTextButton" type="button" value="Upload Text File">
                </div>
                <div class="epButton-column">
                    <input id ="epPreviewButton" type="button" value="Preview">
                    <input id="epSubmit" type="button" value="Submit">
                </div>
            </div>
        </form>
    </div>
    </body>
</html>
