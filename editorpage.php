<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
    <body>
        <?php
        include 'loginCheck.php';
        if($loggedin) {
            include 'includes/header_internal.php';
        }
        else {
            include 'includes/header.php';
        }
            include 'includes/nav.php';
            //include 'includes/footer.html';
        ?>
    <div class="epWrapper">

        <form id="epEditor">
            <div>
                <h1>Article Details</h1>
                <input type="text" placeholder="Article Title" id="epArticleTitle">
                <input type="text" placeholder="Image path" id="img">
                <select name="category">
                    <option selected>Category</option>
                    <option value="politics">Politics</option>
                    <option value="sports">Sports</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="video">Video</option>
                    <option value="local">Local</option>
                    <option value="opinion">Opinion</option>
                </select>
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
