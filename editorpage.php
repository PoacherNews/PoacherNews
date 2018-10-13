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
                <label style="font-size: 13pt;">Title</label>
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
                <div class="editorNav">
                    <ul>
                        <li onclick="setUndo()" id="undo"><i class="fas fa-undo-alt"></i></li>
                        <li onclick="setRedo()" id="redo"><i class="fas fa-redo-alt"></i></li>
						<!-- TODO: V 2.10
                        <li id="boldLbl" onclick="setBold()">
    						<label onclick="setBold()" for="bold"><i class="fas fa-bold"></i></label>
    						<input id="bold" type="button"/>
    					</li>
                        <li id="italicLbl" onclick="setItalic()">
    						<label onclick="setItalic()" for="italic"><i class="fas fa-italic"></i></label>
    						<input id="italic" type="button"/>
    					</li>
    					<li id="underlineLbl" onclick="setUnderline()">
    						<label onclick="setUnderline()" for="underline"><i class="fas fa-underline"></i></label>
    						<input id="underline" type="button"/>
    					</li>
                        <li onclick="linkBox()"><i id="insert" class="fas fa-link"></i></li>
                        <div id="link-modal" class="insert-link">
                            <div class="link-content">
                                <span onclick="exitLink()" class="close">&times;</span>
                                <span>Hyperlink</span><br>
                                <input type="text" id="input-text" placeholder="Text">
                                <input type="text" id="input-link" placeholder="Paste a link">
                                <input type="button" id="set-link" onclick="addLink()" value="Set Link"/>
                            </div>
                        </div>
						-->
                        <li>
                            <label for="upload-document" id="custom-file-upload">
                                <i class="fas fa-cloud-upload-alt"></i> Upload File
                            </label>
                            <input onchange="uploadFile()" id="upload-document" type="file" name="document"/>
                        </li>
                    </ul>
                </div>
				<div name="body" id="editor" contenteditable="true"><?php if($articleData['Body'])print $articleData['Body'];?></div>
            </div>

            <div id="picture" class="tabcontent">
                <h3>Choose a picture</h3>
                <div class="editor-image">
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
						<!--
                        <img id="image" src= alt="Image" width="650" height="434"/>
						-->
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
            
            function getInfo() { // Gets info from the article and displays
                var title = document.getElementById('title').value;
                document.getElementById('getTitle').value = title;
                var category = document.getElementById('category').value;
                document.getElementById('getCategory').value = category;
            }
            
/******************************* TEXT FORMATTING (TODO) *******************************/
			/* TODO: V 2.10
			setInterval(function () {
                var boldLbl, italicLbl, underlineLbl;
                var idName = ['boldLbl', 'italicLbl', 'underlineLbl'];
                var varName = [bold, italic, underline];
                var formatType = ['bold', 'italic', 'underline']; 
                checkFormat(idName, varName, formatType);
            }, 100);
            
            function checkFormat(id, variable, format) {
                for(var i = 0; i < id.length; i++) {
                    variable[i] = document.getElementById(id[i]);
                    if(document.queryCommandState(format[i]) == true) {
                        variable[i].style.backgroundColor = "lightgrey";
                    } else {
                        variable[i].style.backgroundColor = "#fff";
                    }
                }
            }
			*/
            
            function setUndo() {
                document.execCommand("Undo", false, null);
            }
            function setRedo() {
                document.execCommand("Redo", false, null);
            }
			/* TODO: V 2.10
            function setBold() {
				document.execCommand("Bold", false, null);
			}
            function setItalic() {
				document.execCommand("Italic", false, null);
			}
			function setUnderline() {
				document.execCommand("Underline", false, null);
			}
            */
            var linkModal = document.getElementById('link-modal');
			
			function linkBox() { // When the user clicks, open up the link box
				linkModal.style.display = "block";
                document.getElementById('input-text').value = '';
				document.getElementById('input-link').value = '';
                document.getElementById('set-link').disabled = true;
			}
			
			function addLink() { // Add the link to the rich text editor with valid requiremnents
				var texteditor = document.getElementById('editor');
				var a = document.createElement('a');
				var inputText = document.getElementById('input-text').value;
				var inputLink = document.getElementById('input-link').value;
				if(inputText === '') { // Print out the or the name of link
					var linkText = document.createTextNode(inputLink);
					a.appendChild(linkText)
					a.href = inputLink;
					texteditor.appendChild(a);
					linkModal.style.display = "none";
				} else {
					var linkText = document.createTextNode(inputText);
					a.appendChild(linkText)
					a.href = inputLink;
					texteditor.appendChild(a);
					linkModal.style.display = "none";
			 	}
			}
            
            function exitLink() {// Close the link via exit button
                var span = document.getElementsByClassName("close")[0];
                linkModal.style.display = "none";
            }
            setInterval(function () {// Valid configurations to set the link in editor
                var inputLink = document.getElementById('input-link').value;
                var setLink = document.getElementById('set-link');
                inputLink = inputLink.replace(/^\s+|\s+$/g, '');
                if(inputLink.length == 0) {
                    document.getElementById('set-link').disabled = true;
                    document.getElementById('set-link').style.opacity = "0.5";
                } else {
                    document.getElementById('set-link').disabled = false;
                    document.getElementById('set-link').style.opacity = "1";
                }
            }, 100);
 /******************************* EDITIOR COMPATIBILITY *******************************/
            document.getElementById('editor').addEventListener('paste', function(event) { // All pasted text converted to default font
                event.preventDefault();
                var text = event.clipboardData.getData('text/plain');
                document.execCommand('insertHTML', false, text);
            });
			/*
            $(function() {
                $('#editor').focus();
            });
            
            function editorFocus() {
                var editor = document.getElementById('editor');
                editor.focus();
            }
            //var focus 
            $('#editor').blur(function () { // TODO
                //$(this).focus();
				
            })
			*/
/******************************* SUBMISSION *******************************/
            $(document).ready(function() { // Submitting articles
               $("#action-form").on("submit", function () {
                    var hvalue = $('#editor').text();
                    $(this).append("<input type='hidden' name='body' value=' " + hvalue + " '/>");
                });
            });
            
            var modal = document.getElementById('submit-draft');
            
            function submitBtn() { // Submit button; Returns false if NOT successful otherise true
                var submitBtn = document.getElementById("submit-button");
                var getTitle = document.getElementById("getTitle");
				/*
                if(getTitle.value.length == 0) {
                    getTitle.style.border = "2px solid red";
                    getTitle.placeholder = "Invalid requirements";
					return false;
                } else {
                    modal.style.display = "block";
					return true;
                }
				*/
				modal.style.display = "block";
				return true;
            }
            
            function saveBtn() { // Save button; Returns false if NOT successful otherise true
                var saveBtn = document.getElementById('save-button');
                var getTitle = document.getElementById('getTitle');

                if(articleTitle.value.length == 0) {
                    getTitle.style.border = "2px solid red";
                    getTitle.placeholder = "Invalid requirements";
                    return false;
                } else {
                    return true;
                }
            }
            
            function cancel() { // Cancels the submission of the article (No) button
                modal.style.display = "none";
            }
        
            window.onclick = function(event) { // Close link via 'blur'-ing
                if (event.target == linkModal) {
                    linkModal.style.display = "none";
                }
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
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
		</script>
	</body>
</html>