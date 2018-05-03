<!DOCTYPE html>
<html>
    <head>
	    <?php include 'includes/globalHead.html'
      <script type="text/javascript">
        var control = document.getElementById("epUploadTextButton");
        control.addEventListener("change", function(event){
          var reader = new FileReader();
          reader.onload = function(event){
            var contents = event.target.result;
            document.getElementById('epArticleBody').value = contents;
          };
          reader.onerror = function(event){
            console.error("File could not be read! Code " + event.target.error.code);
          };
          reader.readAsText(control.files[0]);
        }, false);

      </script>
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

        <form id="epEditor" action="submitArticle.php" method="post" enctype="multipart/form-data">
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
                    <input id="epImageUploadButton" type="file" value="Upload Image" name="image">
                    <input id="epUploadTextButton" type="file" value="Upload Text File">
                </div>
                <div class="epButton-column">
                    <input id ="epPreviewButton" type="button" value="Preview">
                    <input id="epSubmit" type="button" value="Submit" name="submit">
                </div>
            </div>
        </form>
    </div>
    </body>
</html>
