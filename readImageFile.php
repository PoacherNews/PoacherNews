<?php
	$files = empty($_FILES['image'] ? '' : $_FILES['image']);
	/* Calls input[name = image] from editorpage.php and calls readImageFile() if success, 
	otherwise prints error */
	if(isset($files)) {
		readImageFile();
	} else {
		print "File has not been selected";
	}
	
	function readImageFile() {
		/* Reads in uploaded file via ajax POST and uploads the contents of the image. Only takes
		.jpeg, .jpf, and .png files. NOTE: May be updated in later versions */
		if(isset($_FILES['image'])) {
			$imageFileType = basename($_FILES['image']['type']);
			$extensions_arr = ["jpeg", "jpg", "png"];
			if(!in_array($imageFileType, $extensions_arr)) {
				print "falseType";
			} else if($_FILES['image']['size'] < 0 || $_FILES['image']['size'] > 250000) {
				print "falseSize";
			}
		} else {
			return false;
		}
	}

?>