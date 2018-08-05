<?php
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
?>

<!DOCTYPE html>
<html>
    <head>
        <title>The Poacher | Editor Page</title>
        <link rel="stylesheet" href="res/css/editorpage.css">
        <?php include 'includes/globalHead.html'?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <?php 
	    	include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="nav">
            <?php
                include 'includes/toolsNav.php';
            ?>
        </div>
		<div class="editor-tab">
			<button class="tablinks" onclick="editorTab(event, 'article')"><i class="fas fa-pencil-alt"></i></button>
			<button class="tablinks" onclick="editorTab(event, 'picture')"><i class="fas fa-camera"></i></button>
			<button class="tablinks" onclick="editorTab(event, 'save');getInfo();"><i class="fas fa-save"></i></button>
		</div>
		<?php 
            if(isset($_POST['draft'])) {
                $isDraft = ($articleData['IsDraft'] == 1 && $articleData['IsSubmitted'] == 0 ? TRUE : FALSE);
                if($isDraft) {
                    print "<form id=\"action-form\" action=\"/submitArticle.php?articleid={$articleData['ArticleID']}\" method=\"post\" enctype=\"multipart/form-data\">";
                } 
            } else {
                    print "<form id=\"action-form\" action=\"/submitArticle.php\" method=\"post\" enctype=\"multipart/form-data\">";
                }
        ?>
        <!--
        <form id="action-form" action="/submitArticle.php" method="post" enctype="multipart/form-data">
        -->
            <input type="hidden" name="action">
            <div id="article" class="tabcontent">
                <label style="font-size: 13pt;">Title</label>
                <?php
                    if(isset($_POST['draft'])) {
                        print "<input type=\"text\" id=\"title\" placeholder=\"Title\" name=\"title\" value=\"{$_POST['title']}\"";
                    } else {
                        print "<input type=\"text\" id=\"title\" placeholder=\"Title\" name=\"title\">";
                    }
                ?>
                <select name="category" id="category">
                    <option value="Politics">Politics</option>
                    <option value="Sports">Sports</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Video">Video</option>
                    <option value="Local">Local</option>
                    <option value="Opinion">Opinion</option>
                </select>
                <ul>
                    <li onclick="setUndo()" id="undo"><i class="fas fa-undo-alt"></i></li>
                    <li onclick="setRedo()" id="redo"><i class="fas fa-redo-alt"></i></li>
                    <li onclick="setBold()" id="bold"><i class="fas fa-bold"></i></li>
                    <li onclick="setItalic()" id="italic"><i class="fas fa-italic"></i></li>
                    <li onclick="setUnderline()" id="underline"><i class="fas fa-underline"></i></li>
                    <li onclick="linkBox()"><i id="insert" class="fas fa-link"></i></li>
                    <div id="link-modal" class="insert-link">
                        <div class="link-content">
                            <span onclick="exitLink()" class="close">&times;</span>
                            <span>Hyperlink</span><br>
                            <input type="text" id="input-text" placeholder="Text"><br>
                            <input type="text" id="input-link" placeholder="Paste a link" onkeydown="enableSetBtn()"><br>
                            <button id="set-link" onclick="addLink()">Set Link</button>
                        </div>
                    </div>
                    <li>
                        <label for="upload-document" id="custom-file-upload">
                            <i class="fas fa-file-upload"></i>
                        </label>
                        <input onchange="onUploadFile()" id="upload-document" type="file" name="document">
                        <div id="override-draft" class="override">
                            <!-- Override draft-->
                            <div class="override-content">
                                <span onclick="exitOverride()" class="close">&times;</span>
                                <p>Would you like to override the content?</p>
                                <input onclick="approveOverride()" type="button" id="verify-submit" value="Yes">
                                <input onclick="cancelOverride()" type="button" id="cancel-override" value="No">
                            </div>
                        </div>
                    </li>
                </ul>
                <div id="editor" contenteditable="true" spellcheck="true"><?php 
                                if(isset($_POST['draft'])) {
                                    print($_POST['body']);
                                }
                        ?></div>
            </div>

            <div id="picture" class="tabcontent">
                <h3>Choose a picture</h3>
                <div class="editor-image">
                    <input id="imgInp" type='file' onchange="readURL(this);"/>
                    <div id="picture-content">
                        <img id="image" src="#" alt="Image" width="650" height="434"/>
                    </div>
                </div>
            </div>

            <div id="save" class="tabcontent" onclick="getInfo()">
                <h3>Save/Submit</h3>
                <div class="get-info">
                    <h3>Article Info</h3>
                    <label>Title: </label>
                    <input type="text" id="getTitle" readonly><br>
                    <label>Category: </label>
                    <input type="text" id="getCategory" readonly><br>
                    <label>Date: </label>
                    <input type="text" id="getDate" readonly><br>
                    <label>Picture: </label>
                    <input type="text" id="getImage" readonly><br>
                    <!-- Submitting Article -->
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
		<script>
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
            
            function readURL(input) { // Reads in a picture and displays it
                var imgInp = document.getElementById('imgInp').value;
                if(!(imgInp.length == 0)) { // Display image
                    if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                } else { // Remove image
                    $('#image').attr('src', '');
                }
            }
            $("#imgInp").change(function(){readURL(this);});
            
            function getInfo() { // Gets info from the article and displays
                var title = document.getElementById('title').value;
                document.getElementById('getTitle').value = title;
                var category = document.getElementById('category').value;
                document.getElementById('getCategory').value = category;
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();
                if(dd<10) dd = '0'+dd;
                if(mm<10) mm = '0'+mm; 
                today = mm + '/' + dd + '/' + yyyy;
                document.getElementById('getDate').value = today;
                document.getElementById('getImage').value = "TODO";
            }
            
/******************************* TEXT FORMATTING *******************************/
            function setUndo() {
                document.execCommand('undo', true, null);
                setEndOfContenteditable(editor);
            }
            
            function setRedo() {
                document.execCommand('redo', true, null);
                setEndOfContenteditable(editor);
            }
            
            function setBold() {
			document.execCommand('bold', true, null);
			var state = document.queryCommandState('bold');
			if(state == true) {
				bold.style.backgroundColor = "lightgrey";
			} else {
				bold.style.backgroundColor = "#fff";
			}
		}

		// Italicize the text
		function setItalic() {
			document.execCommand('italic', true, null);
			var state = document.queryCommandState('italic');
			if(state == true) {
				italics.style.backgroundColor = "lightgrey";
			} else {
				italics.style.backgroundColor = "#fff";
			}
		}
		
		// Underline the text
		function setUnderline() {
			document.execCommand('underline', true, null);
			var state = document.queryCommandState('underline');
			if(state == true) {
				underline.style.backgroundColor = "lightgrey";
			} else {
				underline.style.backgroundColor = "#fff";
			}
		}
            
            function setEndOfContenteditable(contentEditableElement) {
                var range, selection;
                contentEditableElement.focus();
                if(document.createRange) { // Firefox, Chrome, Opera, Safari, IE 9+
                    range = document.createRange();
                    range.selectNodeContents(contentEditableElement);
                    range.collapse(false);
                    selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
                else if(document.selection) { // IE 8 and lower
                    range = document.body.createTextRange();
                    range.moveToElementText(contentEditableElement);
                    range.collapse(false);
                    range.select();
                }
            }
            var linkModal = document.getElementById('link-modal');
			
			function linkBox() { // When the user clicks, open up the link box
				linkModal.style.display = "block";
                resetLink();
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
                setEndOfContenteditable(texteditor);
			}
            /* TODO: inlcude window function for linkModal (onblur)*/
            function exitLink() { // Close the link via exit button
                var span = document.getElementsByClassName("close")[0];
                linkModal.style.display = "none";
            }
            function enableSetBtn() { // Valid configurations to set the link in editor
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
            }
            function resetLink() { // Reset default inputs when user opens/closes hyperlink tab
                document.getElementById('input-text').value = '';
				document.getElementById('input-link').value = '';
                document.getElementById('set-link').disabled = true;
            }
            
 /******************************* EDITIOR COMPATIBILITY *******************************/
            document.getElementById('editor').addEventListener('paste', function(event) { // All pasted text converted to default font
                event.preventDefault();
                var text = event.clipboardData.getData('text/plain');
                document.execCommand('insertHTML', false, text);
            });
/******************************* Testing *******************************/
        // When the user clicks, open the submit modal
		function submitBtn() {
			var submitBtn = document.getElementById("submit-button");
			var articleTitle = document.getElementById("title");
			var uploadDocument = document.getElementById("upload-document");
			
			if(articleTitle.value.length == 0) {
				articleTitle.style.border = "2px solid red";
				articleTitle.placeholder = "Invalid requirements";
			} else {
				modal.style.display = "block";
			}
			
			
		}
		
		//When the user clicks, restrictions on the save button if invalid credentials
		function saveBtn() {
			var saveBtn = document.getElementById('save-button');
			var articleTitle = document.getElementById('title');
			
			if(articleTitle.value.length == 0) {
				articleTitle.style.border = "2px solid red";
				articleTitle.placeholder = "Invalid requirements";
				return false;
			} else {
				return true;
			}
		}
            
        var modal = document.getElementById('submit-draft');
		
            // Cancels the submission of the article (No) button
            function cancel() {
                // var cancelSubmit = document.getElementById("cancel-submit"); 
                modal.style.display = "none";

            }
        
            window.onclick = function(event) { // Close link via 'blur'-ing
                if (event.target == linkModal) {
                    linkModal.style.display = "none";
                }
                
                if (event.target == modal) {
                    modal.style.display = "none";
                }
                if (event.target == override) {
                    override.style.display = "none";
                    // Remove the file path
                    clearInputFile();
                    // Check the input file 
                    checkInputFile();
                }
            }
            
        var override = document.getElementById('override-draft');
        
        function checkInputFile() {
            var editor = document.getElementsByClassName('text-editor');
                if(document.getElementById('upload-document').value.length == 0) {
                    editor[0].contentEditable = "true";
                    editor[0].style.opacity = "1";
                } else {
                    editor[0].contentEditable = "false";
                    editor[0].style.opacity = "0.5";
                }
        }
        
        // Canceling the override and erasing the file upload
        function cancelOverride() {
            override.style.display = "none";
            // Remove the file path
            clearInputFile();
            // Check the input file 
            checkInputFile();
        }
        
        // Approving the override and disabling the rich text editor
        function approveOverride() {
            override.style.display = "none";
            var editor = document.getElementsByClassName('text-editor');
            editor[0].innerHTML = '';
            editor[0].contentEditable = "false";
        }
        
        // When the user clicks on (x), close the modal
	 	function exitModal() {
			var span = document.getElementsByClassName("close")[0];
			modal.style.display = "none";
		}
        
        function exitOverride() {
            var span = document.getElementsByClassName("close")[0];
            override.style.display = "none";
            // Remove the file path
            clearInputFile();
            // Check the input file 
            checkInputFile();
        }
            
        function onUploadFile() {
            // Check the input file 
			/*
            checkInputFile();
            if($('.text-editor').text().length > 0) {
               $('.override').show();
            }
			*/
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