<?php
include 'util/loginCheck.php';
// quit if not an admin or not logged in
if (!$loggedin || ($_SESSION['usertype'] == 'U'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an administrator. Redirecting in 1 second...";
    echo '<meta http-equiv="refresh" content="1; url=/index.php">';
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
	    <?php include 'includes/globalHead.html'?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    </head>
	<style>
		.epWrapper {
			display: flex;
			flex-direction: column;
			width: 100%;
		}

		.epWrapper > #epEditor { order: 2; }

		#epEditor {
			width: 85%;
			margin: 0px auto;
			padding: 25px;
			font-family: "Helvetica Nue",Helvetica,sans-serif;
		}

		#epArticle-body > textarea {
			min-width: 100%;
			min-height: 500px;
		}

		#epButtons > .epButton-column {
			float: left;
			min-width: 10%;
		}

		#epBbuttons > .epButton-column > input {
			min-width: 150px;
			margin: 10px;
			padding: 5px;
			display: block;
		}
	</style>
    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="user">
            <div class="picture">
                (Profile Picture)
            </div>
            
            <div class="info">
                (User Information)
            </div>
        </div>
        
        <div class="nav">
            <?php
                $current = 'editorPage';
                include 'includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
            
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
    </div>
        
    <?php include('includes/footer.html'); ?>
    </body>
</html>
