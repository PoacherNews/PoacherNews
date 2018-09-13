<?php
	ob_start();
	session_start();
	function redirectTools() {
	  //header("Location: ../tools.php");
	}
	$action = empty($_POST['action']) ? '' : $_POST['action'];
	$submit = empty($_POST['submit']) ? '' : $_POST['submit'];
	$save = empty($_POST['save']) ? '' : $_POST['save'];
	$articleid = empty($_POST['articleid']) ? '' : $_POST['articleid'];

	include('util/articleUtils.php');

	function dbConnect() {
	include 'util/db.php';

		if ($db->connect_error) { // Check connection
		   die("Connection failed: " . $db->connect_error);
		} else{
			return $db;
		}
	} // End of dbConnect

	if(isset($action)){
		if($submit == "Yes") { // If the user wants to submit
			submitArticle();
		} else if($save == "Save") { // If the user wants to save
			saveArticle();
		}
	} else {
		redirectTools(); //Go back to /tools.php
	}

	function submitArticle() {
	  	$title = empty($_POST['title']) ? '' : $_POST['title'];
	  	$category = empty($_POST['category']) ? '' : $_POST['category'];
	  	$body = empty($_POST['body']) ? '' : $_POST['body'];
	  	$authorid = getAuthorID();
	  	// upload image
	  	$image = getImage();
	  	$is_draft = 1; // true
	  	$is_submitted = 1; // true
		
		if(isset($_POST['articleid'])) { // Thus the article is a draft
			$db = dbConnect();
			$stmt = $db->stmt_init();
		  	$articleData = getArticleByID($articleid, $db);
		  	$isAuthor = ($articleData['UserID'] && $_SESSION['userid'] ? TRUE : FALSE);
	  	}

	  	if($articleData['ArticleID'] && $isAuthor) { // The draft was saved upon edit
			updateArticle($articleData['ArticleID'], $title, $body, $category, $image, $is_submitted, $db, $stmt);
			
	  	} else { // The draft is new and was not saved upon edit
		  	insertArticle($authorid, $title, $body, $category, $image, $is_draft, $is_submitted);
	  	}
	} //End of submitArticle

	function saveArticle() {	
	    $title = empty($_POST['title']) ? '' : $_POST['title'];
	    $category = empty($_POST['category']) ? '' : $_POST['category'];
	    $body = empty($_POST['body']) ? '' : $_POST['body'];
	    $authorid = getAuthorID();
	    // upload image
		$image = getImage();
	  	$is_draft = 1;
	  	$is_submitted = 0;

	  	if(isset($_POST['articleid'])) { // Thus the article is a draft
			$db = dbConnect();
			$stmt = $db->stmt_init();
		  	$articleData = getArticleByID($articleid, $db);
		  	$isAuthor = ($articleData['UserID'] && $_SESSION['userid'] ? TRUE : FALSE);
	  	}
		
	  	if($articleData['ArticleID'] && $isAuthor) { // The draft was saved upon edit
			updateArticle($articleData['ArticleID'], $title, $body, $category, $image, $is_submitted, $db, $stmt);

	  	} else { // The draft is new and was not saved upon edit
			insertArticle($authorid, $title, $body, $category, $image, $is_draft, $is_submitted);
	  	}
	} //End of saveArticle

	function insertArticle($authorid, $title, $body, $category, $image, $is_draft, $is_submitted) {
		$db = dbConnect();
	  	$stmt = $db->stmt_init();
		if (!$stmt->prepare("INSERT INTO Article(UserID, Headline, Body, Category, ArticleImage, IsDraft, IsSubmitted) VALUES(?, ?, ?, ?, ?, ?, ?)")) {
			  	echo "Error preparing statement: \n";
			  	print_r($stmt->error_list);
			  	exit;
		}
		if (!$stmt->bind_param('issssii', $authorid, $title, $body, $category, $image, $is_draft, $is_submitted)) {
			echo "Error binding parameters: \n";
			print_r($stmt->error_list);
			exit;
		}
		if(!$stmt->execute()){
			echo "Error Inserting: \n";
			echo nl2br(print_r($stmt->error_list, true), false);
			exit;
		}
		echo "Article: " . $title . " inserted successfully.";
		redirectTools();
	}

	function updateArticle($articleid, $title, $body, $category, $image, $is_submitted, $db, $stmt) {
		//Update the mysql
		$sql_query = "UPDATE Article SET Headline = '".$title."', Body= '".$body."', Category = '".$category."', ArticleImage = '".$image."', IsSubmitted = '".$is_submitted."' WHERE ArticleID = ?";
		if (!$stmt->prepare($sql_query)) {
			echo "Error preparing statement: \n";
			print_r($stmt->error_list);
			exit;
		}
		if (!$stmt->bind_param('i', $articleid)) {
			echo "Error binding parameters: \n";
			print_r($stmt->error_list);
			exit;
		}
		if(!$stmt->execute()){
				echo "Error Inserting: \n";
				echo nl2br(print_r($stmt->error_list, true), false);
				exit;
		}
		echo "Article: " . $title . " updated successfully.";
		redirectTools();
	}
	
	function getAuthorID() {
		$username = empty($_SESSION['username']) ? 'error' : $_SESSION['username'];
		$db = dbConnect();
		$stmt = $db->stmt_init();

		if(!$stmt->prepare("SELECT UserID FROM User WHERE Username=?")){
			echo "Error preparing statement: \n";
		  	print_r($stmt->error_list);
		  	exit;
		}

		if(!$stmt->bind_param('s', $username)){
		  	echo "Error binding parameters: \n";
		  	print_r($stmt->error_list);
		  	exit;
		}
		$stmt->execute();
		// get result
		$result = $stmt->get_result();

		if($result->num_rows != 1){
		  	echo "Error finding username: '$username'";
		  	exit;
		}
		$row = $result->fetch_assoc();
		return $row['UserID'];
	} // End of getAuthorID

	function getImage() {
		if(!isset($_POST['articleid'])) { // Thus the article is not a draft
		  	$db = dbConnect();
			$sql = "SELECT ArticleID FROM Article";
			$result = mysqli_query($db, $sql);
			$numResults = mysqli_num_rows($result);
			$counter = 0;
			while($row = mysqli_fetch_assoc($result)) {
				if (++$counter == $numResults) {
					$articleid = $row['ArticleID']+1; // last row
				}
			}
	  	}
		
		//$target_dir = "/home/ec2-user/public_html/res/img/articlePictures";
		/*Used for local host*/
		$target_dir = "/Users/rolandoruche/Desktop/test/PoacherNews/res/img/articlePictures/".$articleid."/";
		if (!file_exists($target_dir)) {
			mkdir($target_dir, 0777, true);
		}
		
		chmod($target_dir, 0777);
	
		$target_file = $target_dir . basename($_FILES['image']['name']);
		$filename = basename($_FILES['image']['name']);
		$imageFileType = basename($_FILES['image']['type']);
		$extensions_arr = ["gif", "jpeg", "jpg", "png"];
		
		if(in_array($imageFileType, $extensions_arr)) {
			echo "Valid extension: success<br>";
			
			if(is_uploaded_file($_FILES['image']['tmp_name'])) {
				echo "Image upload via HTTP POST: success<br>";
			} else {
				echo "Image upload via HTTP POST: FAILED<br>";
			}
			
			if($_FILES["image"]["size"] > 500000) {
				echo "Image correct size: FAILED<br>";
				//return FALSE;
			} else {
				echo "Image correct size: success<br>";
			}
			if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
				echo "Image moved to file: success<br>";
				
			} else {
				echo "Image moved to file: FAILED<br>";
			}

		} else {
			echo "Valid extension: FAILED<br>";
		}
		
		return $filename;
 	}
?>
