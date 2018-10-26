<?php
	ob_start();
    session_start(); 
	function redirectHome() {
		header('Location: index.php');
	}
    include 'util/loginCheck.php';
    include('util/articleUtils.php');
    include('util/userUtils.php');
    include('util/db.php');
    if (!$loggedin || ($_SESSION['usertype'] == 'U')) {
        header("HTTP/1.1 403 Forbidden", true, 403);
        echo "You must be an administrator. Redirecting in 1 second...";
        echo '<meta http-equiv="refresh" content="1; url=/index.php">';
        exit;
	}
	if(isset($_GET['articleid'])) {
		/* Get articleid if the URL paramater exists */ 
		$articleData = getArticleByID($_GET['articleid'], $db);
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>The Poacher | Editor Page</title>
        <link rel="stylesheet" href="res/css/editorpage.css">
        <link rel="stylesheet" href="res/css/tools.css"/>
        <?php include 'includes/globalHead.html'?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- Include the Quill librarys -->
		<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
		<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    </head>
    <body>
        <?php
	    	include 'includes/header.php';
            include 'includes/nav.php';
            include 'includes/toolsNav.php';
        ?>
		<div class="editor-tab">
			<button class="tablinks" onclick="editorTab(event, 'article');editorFocus();"><i class="fas fa-pencil-alt"></i></button>
			<button class="tablinks" onclick="editorTab(event, 'picture');"><i class="fas fa-camera"></i></button>
			<button class="tablinks" onclick="editorTab(event, 'save');getInfo();"><i class="fas fa-save"></i></button>
		</div>
        <form id="action-form" action="/submitArticle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action">
            <div id="article" class="tabcontent">
                <?php
					$isDraft = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 0 ? TRUE : FALSE);
					$isAuthor = ($articleData['UserID'] == $_SESSION['userid'] ? TRUE : FALSE);
					$draftOk = TRUE;
					if($articleData || isset($_GET['articleid'])) {
						if(!$isAuthor || !$isDraft) {
							redirectHome();
							$draftOk = FALSE;
						} else {
							/* Grab ArticleID via POST method */
							print "<input type=hidden name=article_id value={$articleData['ArticleID']}>";
							$draftOk = TRUE;
						}
					}
                ?>
				
				<input type="text" id="title" placeholder="Title" name="title" value="<?php if($draftOk){print $articleData['Headline'];}?>" required/>
                <select name="category" id="category">
                    <option value="Politics"<?php if($articleData['Category'] == "Politics"){print "selected";}?>>Politics</option>
                    <option value="Sports"<?php if($articleData['Category'] == "Sports"){print "selected";}?>>Sports</option>
                    <option value="Entertainment"<?php if($articleData['Category'] == "Entertainment"){print "selected";}?>>Entertainment</option>
                    <option value="Video"<?php if($articleData['Category'] == "Video"){print "selected";}?>>Video</option>
                    <option value="Local"<?php if($articleData['Category'] == "Local"){print "selected";}?>>Local</option>
                    <option value="Opinion"<?php if($articleData['Category'] == "Opinion"){print "selected";}?>>Opinion</option>
                </select>
				<!-- EDITOR -->
                <div id="editor" style="height: 1000px;"><?php if($articleData['Body'])print $articleData['Body'];?></div>
            </div>

            <div id="picture" class="tabcontent">
                <h3>Choose a picture</h3>
                <div class="editor-image">
					<!-- TODO -->
                    <input onchange="uploadImage(this);" id="upload-image" type='file' name="image" required/>
                    <div id="picture-content">
						<?php 
							$isDraft = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 0 ? TRUE : FALSE);
							if($isDraft) {
								print "<img id=image src=/res/img/articlePictures/{$articleData['ArticleID']}/{$articleData['ArticleImage']} alt=Image width=650 height=434/>";
							} else {
								print "<img id=image src=/# alt=Image width=650 height=434/>";
							}
						?>
                    </div>
                </div>
            </div>

            <div id="save" class="tabcontent" onclick="getInfo()">
                <h3>Save/Submit</h3>
                <div class="get-info">
                    <h3>Article Info</h3>
                    <label>Title: </label>
                    <input type="text" id="getTitle" readonly/><br>
                    <label>Category: </label>
                    <input type="text" id="getCategory" readonly/><br>
					<!-- TODO: V 2.10
                    <label>Date: </label>
                    <input type="text" id="getDate" readonly>
                    <label>Picture: </label>
                    <input type="text" id="getImage" readonly>
					-->
                    <input onclick="submitBtn()" type="button" id="submit-button" value="Submit"><br>
                    <div id="submit-draft" class="submit">
                          <div class="modal-content">
                            <span onclick="exitModal()" class="close">&times;</span>
                              <p>Are you sure you want to submit?</p>
                              <input type="submit" id="verify-submit" value="Yes" name="submit">
                              <input onclick="cancel()" type="button" id="cancel-submit" value="No" name="no-submit">
                          </div>
                    </div>
                    <!-- Saving Article -->
                    <input onclick="saveBtn()" type="submit" id="save-button" value="Save" name="save">
                </div>
            </div>
		</form>
		<script type="text/javascript">
/******************************* TABS *******************************/
			/* Upload text editor */
			var quill = new Quill('#editor', {
				theme: 'snow', 
			});
			
            function loadArticle() {
                document.getElementById('article').style.display = "block";
            }
            window.onload = loadArticle();
			function editorTab(evt, tabName) { // Tabs for article content, choosing a picture, and save/submit
				var i, tabcontent, tablinks;
				tabcontent = document.getElementsByClassName('tabcontent');
				for(i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none"
				}
				tablinks = document.getElementsByClassName('tablinks');
				for(i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				document.getElementById(tabName).style.display = "block";
				evt.currentTarget.className += " active";
			}
            
            function getInfo() { 
				/* Gets info from the article and displays it in 'Article Info' */
                var title = document.getElementById('title').value;
                document.getElementById('getTitle').value = title;
                var category = document.getElementById('category').value;
                document.getElementById('getCategory').value = category;
            }
 /******************************* EDITIOR COMPATIBILITY *******************************/
			document.getElementById('editor').addEventListener('paste', function(event) { // All pasted text converted to default font
                event.preventDefault();
                var text = event.clipboardData.getData('text/plain');
                document.execCommand('insertHTML', false, text);
			});
/******************************* SUBMISSION *******************************/
			function removeEditorTags() {
				$('.ql-editor').attr('contentEditable', false);
				$('.ql-clipboard').attr('contentEditable', false);
				$('.ql-tooltip').remove();
				$('.ql-hidden').remove();
				$('.ql-preview').remove();
				$('.ql-action').remove();
				$('.ql-remove').remove();
			}
			
            $(document).ready(function() { // Submitting articles
               $("#action-form").on("submit", function () {
				   removeEditorTags();
                   var hvalue = $('#editor').html();
                   $(this).append("<input type='hidden' name='body' value=' " + hvalue + " '/>");
                });
            });
			
            var modal = document.getElementById('submit-draft');
            
            function submitBtn() { // Submit button; Returns false if NOT successful otherise true
				modal.style.display = "block";
            }
            
            function cancel() { // Cancels the submission of the article (No) button
                modal.style.display = "none";
            }
        
            window.onclick = function(event) { // Close link via 'blur'-ing
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            
            function exitModal() {
                var span = document.getElementsByClassName("close")[0];
                modal.style.display = "none";
            }
/******************************* FILE UPLOADING *******************************/
            function uploadFile() {
                var formData = new FormData(); 
                formData.append('document', $('#upload-document')[0].files[0]); 
                $.ajax({
                    url: 'readTextFile.php',
                    type: 'POST',
                    data: formData,
                    success: function (output) {
                        $('#editor').html(output);
						// TODO
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
			
			function uploadImage(event) {
				/* Calls a PHP Ajax request to see if the image is in the correct format. If it isn't it will alert
				an error otherwise display the image. */
				var formData = new FormData(); 
                formData.append('image', $('#upload-image')[0].files[0]); 
                $.ajax({
                    url: 'readImageFile.php',
                    type: 'POST',
                    data: formData,
                    success: function (output) {
						switch(output) {
							case 'falseType':
								alert('Invalid file input. Please use .jpeg, .jpg, or .png files.');
								document.getElementById('upload-image').value = null;
								$('#image').attr('src', '');
								break;
								
							case 'falseSize':
								alert('Invalid file size. Please try again.');
								document.getElementById('upload-image').value = null;
								$('#image').attr('src', '');
								break;
								
							default:
								readURL(event);
								break;
						}
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });	
            }
			
			function readURL(input) {
				/* Reads in an image using FileReader then displays it in the image box. Removes image if contents are
				not present. */
                var upload_image = document.getElementById('upload-image').value;
				if(!(upload_image.length == 0)) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.onload = function(event) {
							$('#image').attr('src', event.target.result);
						}
						reader.readAsDataURL(input.files[0]);
					}
				} else {
					$('#image').attr('src', '');
				}
			}
            $("#upload-image").change(function(){readURL(this);});
		</script>
	</body>
</html>