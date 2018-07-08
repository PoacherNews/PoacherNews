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
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
            $articleData = getArticleByID($_GET['articleid'], $db);
            $articleID = $articleData['ArticleID'];
            if($articleID == NULL) {
                echo '<meta http-equiv="refresh" content="0; url=/index.php">';
            }
        ?>
        
        <div class="nav">
            <?php
                $current = 'editorPage';
                include 'includes/toolsNav.php';
            ?>
        </div>
        <div class="editor-menu">
			<div class="em-row">
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
                    <div class="emb-row">
                        <div class="em-col">
                            <p>Article Details</p>
                            <?php
                                if(isset($_POST['draft'])) {
                                    print "<input type=\"text\" id=\"article-title\" placeholder=\"Article Title\" name=\"title\" value=\"{$_POST['title']}\"";
                                } else {
                                    print "<input type=\"text\" id=\"article-title\" placeholder=\"Article Title\" name=\"title\">";
                                }
                                print "<br>";
                            ?>
                            <select name="category" id="category-title">
                                <option value="Politics">Politics</option>
                                <option value="Sports">Sports</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Video">Video</option>
                                <option value="Local">Local</option>
                                <option value="Opinion">Opinion</option>
                            </select>
                        </div>
                        <div class="em-col">
                            <p>Choose Image</p>
                            <input type="file" value="Upload Image" name="image">
                            <p>Choose Text File</p>
                            <input onchange="onUploadFile()" id="upload-document" type="file" value="Upload Document" name="document">
                            
                            <div id="override-draft" class="override">
                                <div class="override-content">
                                    <span onclick="exitOverride()" class="close">&times;</span>
                                    <p>Would you like to override the content?</p>
                                    <input onclick="approveOverride()" type="button" id="verify-submit" value="Yes">
                                    <input onclick="cancelOverride()" type="button" id="cancel-override" value="No">
                                </div>
                            </div>
                            
                        </div>
                        <div class="em-col">
                            <input onclick="submitBtn()" type="button" id="submit-button" value="Submit">
                            <div id="submit-draft" class="submit">
                                  <div class="modal-content">
                                    <span onclick="exitModal()" class="close">&times;</span>
                                      <p>Are you sure you want to submit?</p>
                                      <input type="submit" id="verify-submit" value="Yes" name="submit">
                                      <input onclick="cancel()" type="button" id="cancel-submit" value="No" name="no-submit">
                                  </div>
                            </div>
                            <br>
                            <input type="submit" id="save-button" value="Save" name="save">
                        </div>
                    </div>
                    <div class="menubar-row">
                        <ul class="editor-menubar">
                            <li><input type="button" onclick="undoIcon()" id="undo" value="Undo"></li>
                            <li><input type="button" onclick="redoIcon()" id="redo" value="Redo"></li>
                            <li><div id="font-family" contenteditable="false">Helvetica</div></li>
                            <li><div id="font-size" contenteditable="false">13</div></li>
                            <li><input type="button" onclick="boldIcon()" id="bold" value="B"></li>
                            <li><input type="button" onclick="italicsIcon()" id="italics" value="I"></li>
                            <li><input type="button" onclick="underlineIcon()" id="underline" value="U"></li>
                            <li>
                                <div class="link-row">
                                    <input type="button" onclick="linkBox()" id="insert" value="Insert Link">
                                </div>
                                <div id="insert-link">
                                    <input id="input-text" type="text" name="text" placeholder="Insert name">
                                    <input onkeydown="enableSet()" id="input-link" type="text" name="link" placeholder="Insert Link" value="https://">
                                    <br>
                                    <input onclick="addLink()" id="set-link" type="button" name="set" value="Set" disabled>
                                </div>
                            </li>
                            <li><div id="line-spacing" contenteditable="false">1.15</div></li>
                        </ul>
                    </div>
                    <div class="text-editor" contenteditable="true"><?php 
                            if(isset($_POST['draft'])) {
                                print($_POST['body']);
                                $articleData = getArticleByID($_GET['articleid'], $db);
                                //print $articleData['ArticleID'];
                            }
                    ?></div>
				</form>
			</div>
        </div>
    </body>
	<script>
        //Getting text from div and sending it post method on submission
        $(document).ready(function(){
           $("#action-form").on("submit", function () {
                var hvalue = $('.text-editor').text();
                $(this).append("<input type='hidden' name='body' value=' " + hvalue + " '/>");
            });
        });
		
		
		// Keeps the cursor in the rich text editor
        /*
		var textFocus;
		function focusEditor() {
			textFocus = setInterval(function(){
				$('.text-editor').focus();
			});
		}
		*/
        
        // Hide the menu bar (toggle)
		function hideMenu() {
			$('.emb-row').slideToggle(500);
		}
		
        
        /* ************************** Menubar Options ************************** */

		function focusTitle() {
			clearInterval(textFocus);
		}
		
		function focusCategory() {
			clearInterval(textFocus);
		}
	   
        // Disabling the rich text editor and
        // overriding text when uploading a file
        function onUploadFile() {
            // Check the input file 
            checkInputFile();
            if($('.text-editor').text().length > 0) {
               $('.override').show();
            }	
        }
        
		// Get the modal
		var modal = document.getElementById('submit-draft');
		
		// Cancels the submission of the article (No) button
		function cancel() {
			// var cancelSubmit = document.getElementById("cancel-submit"); 
			modal.style.display = "none";
            
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

		// When the user clicks anywhere outside of the submit modal, close it
		window.onclick = function(event) {
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
        
        // Clears the file path of the uploaded file
        function clearInputFile() {
            document.getElementById('upload-document').value = null;
        }
		
		// When the user clicks, open the submit modal
		function submitBtn() {
			var submitBtn = document.getElementById("submit-button");
			var articleTitle = document.getElementById("article-title");
			var uploadDocument = document.getElementById("upload-document");
			
			if(articleTitle.value.length == 0) {
				articleTitle.style.border = "2px solid red";
				articleTitle.placeholder = "Invalid requirements";
			} else {
				modal.style.display = "block";
			}
			
			
		}

		/* ************************** Text Formatting ************************** */
		
		// When the user clicks, open up the link box
		function linkBox() {
			// Having the link box appear
			if($('#insert-link').is(':hidden')) {
				$('#insert-link').show();
			} else {
				$('#insert-link').hide();	
			}
		}
		
		// Add the link to the rich text editor with valid requiremnents
		function addLink() {
			var texteditor = document.getElementsByClassName('text-editor');
			var a = document.createElement('a');
			var inputText = document.getElementById('input-text').value;
			var inputLink = document.getElementById('input-link').value;
			
			// Either print out the link itself or the text to give the link a specific name
			if(inputText === '') {
				var linkText = document.createTextNode(inputLink);
				a.appendChild(linkText)
				a.href = inputLink;
				texteditor[0].appendChild(a);
				$('#insert-link').hide();
			} else {
				var linkText = document.createTextNode(inputText);
				a.appendChild(linkText)
				a.href = inputLink;
				texteditor[0].appendChild(a);
				$('#insert-link').hide();
			}
		}
        
        function enableSet() { //TODO: Clean up
            if(document.getElementById('input-link').value.length <= 7) {
                document.getElementById('set-link').disabled = true;
                document.getElementById('set-link').style.opacity = "0.5";
            } else {
                document.getElementById('set-link').disabled = false;
                document.getElementById('set-link').style.opacity = "1";
            }
        }
		
		// Bold the text
		function boldIcon() {
			document.execCommand('bold', true, null);
			var state = document.queryCommandState('bold');
			if(state == true) {
				bold.style.backgroundColor = "lightgrey";
			} else {
				bold.style.backgroundColor = "#fff";
			}
		}

		// Italicize the text
		function italicsIcon() {
			document.execCommand('italic', true, null);
			var state = document.queryCommandState('italic');
			if(state == true) {
				italics.style.backgroundColor = "lightgrey";
			} else {
				italics.style.backgroundColor = "#fff";
			}
		}
		
		// Underline the text
		function underlineIcon() {
			document.execCommand('underline', true, null);
			var state = document.queryCommandState('underline');
			if(state == true) {
				underline.style.backgroundColor = "lightgrey";
			} else {
				underline.style.backgroundColor = "#fff";
			}
		}
		
        
        function undoIcon() {
            document.execCommand('undo', false, null);
        }
        
        function redoIcon() {
            document.execCommand('redo', false, null);
        }

		// Allow text formatting via key commands
		$('.text-editor').on('keydown', function(e){
            // For bold
			if((e.keyCode == 91 || e.keyCode == 93) && e.keyCode == 66){
				document.execCommand('bold', true, null);
			}
            // For italic
            if((e.keyCode == 91 || e.keyCode == 93) && e.keyCode == 73){
				document.execCommand('italic', true, null);
			}
            // For underline
            if((e.keyCode == 91 || e.keyCode == 93) && e.keyCode == 85){ //TODO
				document.execCommand('underline', true, null);
			}
		}).css('white-space', 'pre-wrap')
		
        /* ************************** Error Handling ************************** */
        
        
        
	</script>
</html>