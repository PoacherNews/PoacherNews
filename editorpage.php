<?php
    include 'util/loginCheck.php';
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
        ?>
        
        <div class="nav">
            <?php
                $current = 'editorPage';
                include 'includes/toolsNav.php';
            ?>
        </div>
        <div class="editor-menu">
			<div class="em-row">
				<form action="/submitAricle.php" method="post" enctype="multipart/form-data">
					<div class="em-col">
						<p>Article Details</p>
						<input onclick="focusTitle()" type="text" id="article-title" placeholder="Article Title" name="title">
						<br>
						<input onclick="focusCategory()" list="category" id="category-title" placeholder="Category" name="category">
						<datalist id="category">
							<option value="Politics">
							<option value="Sports">
							<option value="Entertainment">	
							<option value="Video">
							<option value="Local">
							<option value="Opinion">
						</datalist>
					</div>
					<div class="em-col">
						<p>Choose Image</p>
						<input type="file" value="Upload Image" name="image">
						<p>Choose Text File</p>
						<input type="file" value="Upload Text File" name="file">
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
						<input type="button" id="save-button" value="Save" name="action">
					</div>
				</form>
			</div>
        </div>
		<div class="menubar-row">
			<ul class="editor-menubar">
				<li><button onclick="document.execCommand('undo', false, null);" id="undo">Undo</button></li>
				<li><button onclick="document.execCommand('redo', false, null);" id="redo">Redo</button></li>
				<li><button>100%</button></li>
				<li><div id="font-family" contenteditable="false">Helvetica</div></li>
				<li><div id="font-size" contenteditable="false">13</div></li>
				<li><button onclick="boldIcon()" id="bold">B</button></li>
				<li><button onclick="italicsIcon()" id="italics">I</button></li>
				<li><button onclick="underlineIcon()" id="underline">U</button></li>
				<li>
					<div class="link-row">
						<button onclick="linkBox()" id="insert">Insert Link</button>
					</div>
					<div id="insert-link">
						<input id="input-text" type="text" name="text" placeholder="Insert name">
						<input id="input-link" type="text" name="link" placeholder="Insert Link">
						<br>
						<input onclick="addLink()" type="button" name="set" value="Set">
					</div>
				</li>
				<!--
				<li><div contenteditable="false"><i class="fa fa-align-left" style="font-size:20px;"></i></div></li>
				-->
				<li><div id="line-spacing" contenteditable="false">1.15</div></li>
				<!--
				<li><button onclick="document.execCommand('insertOrderedList', false, null);"><i class="fa fa-list-ol" style="font-size:20px;"></i></button></li>
				<li><button onclick="document.execCommand('insertUnorderedList', false, null);"><i class="fa fa-list-ul" style="font-size:20px;"></i></button></li>
				-->
				<li><button onclick="previewButton(this)" id="preview">Preview</button></li>
				<li><button onclick="hideMenu()" id="hidemenu-caret">^</button></li>
        	</ul>
		</div>
        
        <div onclick="focusEditor()" class="text-editor" contenteditable="true"></div>
    </body>
	<script>
		// Keeps the cursor in the rich text editor
		var textFocus;
		function focusEditor() {
			textFocus = setInterval(function(){
				$('.text-editor').focus();
			});
		}
		
		function focusTitle() {
			clearInterval(textFocus);
		}
		
		function focusCategory() {
			clearInterval(textFocus);
		}
	
		
		// Get the modal
		var modal = document.getElementById('submit-draft');
		
		// Cancels the submission of the article (No) button
		function cancel() {
			var cancelSubmit = document.getElementById("cancel-submit"); 
			modal.style.display = "none";
		}
		
		// When the user clicks, open the submit modal
		function submitBtn() {
			var submitBtn = document.getElementById("submit-button");
			var articleTitle = document.getElementById("article-title").value;
			var categoryTitle = document.getElementById("category-title").value;
			if(articleTitle.length == 0) {
				document.write('Invalid requirements.');
				//articleTitle.style.borderColor = "red";
			} else if(categoryTitle.length == 0) {
				// STILL NEED TO FIX
				if(categoryTitle !== "Politics" || categoryTitle !== "Entertainment" || categoryTitle !== "Sports" ||
				   categoryTitle !== "Local" || categoryTitle !== "Video" || categoryTitle !== "Opinion") {
					document.write('Invalid requirements.');
				}
			} else {
				modal.style.display = "block";
			}
		}

		// When the user clicks on (x), close the modal
	 	function exitModal() {
			var span = document.getElementsByClassName("close")[0];
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the submit modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
		
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
			var https = "https://";
			// Either print out the link itself or the text to give the link a specific name
			if(inputText === '') {
				var linkText = document.createTextNode(inputLink);
				a.appendChild(linkText)
				a.href = https+inputLink;
				texteditor[0].appendChild(a);
				$('#insert-link').hide();
			} else {
				var linkText = document.createTextNode(inputText);
				a.appendChild(linkText)
				a.href = https+inputLink;
				texteditor[0].appendChild(a);
				$('#insert-link').hide();
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
			var itaclics = document.getElementById('italics');
			// Toggling the button to highlight it
			if(italics.className !== 'toggled') {
				italics.className = 'toggled';
				italics.style.backgroundColor = "lightgrey";
			} else {
				italics.className = '';
				italics.style.backgroundColor = "#fff";
			}

			// Setting text to italics
			document.execCommand('italic', false, null); 
		}
		
		// Underline the text
		function underlineIcon() {
			var underline = document.getElementById('underline');
			// Toggling the button to highlight it
			if(underline.className !== 'toggled') {
				underline.className = 'toggled';
				underline.style.backgroundColor = "lightgrey";
			} else {
				underline.className = '';
				underline.style.backgroundColor = "#fff";
			}

			//Setting text to underline
			document.execCommand('underline', false, null);
		}

		// Preview the rich text editor
		function previewButton(button) {
			// Disbale the rich text editor and toggle button to edit
			var editor = document.getElementsByClassName('text-editor');
			
			var undo = document.getElementById('undo');
			var redo = document.getElementById('redo');
			
			if(editor[0].contentEditable == "true") {
				editor[0].contentEditable = "false";
				button.innerHTML = "Edit";
				// Disable all other buttons
				$('#bold').hide();
				$('#italics').hide()
				$('#underline').hide();
				$('#insert').hide();
				undo.style.opacity = 0.5;
				redo.style.opacity = 0.5;
				undo.style.cursor = "default";
				redo.style.cursor = "default";
				$('#undo').hover(function() {
					undo.style.background = "#fff";
				});
				$('#redo').hover(function() {
					redo.style.background = "#fff";
				});
			} else {
				editor[0].contentEditable = "true";
				button.innerHTML = "Preview";
				$('#bold').show();
				$('#italics').show()
				$('#underline').show();
				$('#insert').show();
				undo.style.opacity = 1;
				redo.style.opacity = 1;
				undo.style.cursor = "default";
				redodo.style.cursor = "default";
				$('#undo').hover(function() {
					undo.style.background = "lightgrey";
				});
				$('#redo').hover(function() {
					redo.style.background = "lightgrey";
				});
			}
		}

		// Hide the menu bar (toggle)
		function hideMenu() {
			$('.editor-menu').slideToggle(500);
		}
		
		// Allow tabs
		$('.text-editor').on('keydown', function(e){
			if(e.keyCode == 9){
				e.preventDefault();
				document.execCommand('insertHTML', false, '&#009');
			}
		}).css('white-space', 'pre-wrap')
		
	</script>
</html>