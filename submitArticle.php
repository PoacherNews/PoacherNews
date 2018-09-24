<?php
	ob_start();
	session_start();
	function redirectTools() {
	  //header("Location: ../tools.php");
	}
	$action = empty($_POST['action']) ? '' : $_POST['action'];
	$submit = empty($_POST['submit']) ? '' : $_POST['submit'];
	$save = empty($_POST['save']) ? '' : $_POST['save'];

	include('util/articleUtils.php');

	function dbConnect() {
		/* Checks the connection of the database. Abort databse if the connection was NOT successful, 
		otherwise return db */
		include 'util/db.php';
		if ($db->connect_error) {
		   die("Connection failed: " . $db->connect_error);
		} else{
			return $db;
		}
	}
	
	/* Calls input[name = action] from editorpage.php. If user selects "Yes", article is submitted.
	If user selects "Save", article is saved. Otherwise redirect to ../tools.php */
	if(isset($action)){
		if($submit == "Yes") {
			submitArticle();
		} else if($save == "Save") {
			saveArticle();
		}
	} else {
		redirectTools();
	}

	function submitArticle() {
		/* Submits the article whether it is a draft or new document */
	  	$title = empty($_POST['title']) ? '' : $_POST['title'];
	  	$category = empty($_POST['category']) ? '' : $_POST['category'];
	  	$body = empty($_POST['body']) ? '' : $_POST['body'];
	  	$authorid = getAuthorID();
	  	$image = getImage();
	  	$is_draft = 1; 
	  	$is_submitted = 1;
		
		if(isset($_POST['article_id'])) { 
			$db = dbConnect();
			$stmt = $db->stmt_init();
		  	$articleData = getArticleByID($_POST['article_id'], $db);
		  	$isAuthor = ($articleData['UserID'] && $_SESSION['userid'] ? TRUE : FALSE);
	  	} 
		if($articleData['ArticleID'] && $isAuthor) {
			updateArticle($articleData['ArticleID'], $title, $body, $category, $image, $is_submitted, $db, $stmt);
		} else {
		  	insertArticle($authorid, $title, $body, $category, $image, $is_draft, $is_submitted);
	  	}
	}

	function saveArticle() {
		/* Saves the article whether it is a draft or new document */
	    $title = empty($_POST['title']) ? '' : $_POST['title'];
	    $category = empty($_POST['category']) ? '' : $_POST['category'];
	    $body = empty($_POST['body']) ? '' : $_POST['body'];
	    $authorid = getAuthorID();
		$image = getImage();
	  	$is_draft = 1;
	  	$is_submitted = 0;

	  	if(isset($_POST['article_id'])) {
			$db = dbConnect();
			$stmt = $db->stmt_init();
		  	$articleData = getArticleByID($_POST['article_id'], $db);
		  	$isAuthor = ($articleData['UserID'] && $_SESSION['userid'] ? TRUE : FALSE);
	  	}
		if($articleData['ArticleID'] || $isAuthor) {
			updateArticle($articleData['ArticleID'], $title, $body, $category, $image, $is_submitted, $db, $stmt);
		} else {
			insertArticle($authorid, $title, $body, $category, $image, $is_draft, $is_submitted);
	  	}
	}

	function insertArticle($authorid, $title, $body, $category, $image, $is_draft, $is_submitted) {
		/* Inserts a new document based on authorid, title, body, category, image, if its a draft, and if its submitted */
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
		/* Updates the drafted article based on article id, body contents, 
		category of choice, article image, and status of submission state */
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
		/* Returns the author id for a new article to be submitted */
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
		$result = $stmt->get_result();

		if($result->num_rows != 1){
		  	echo "Error finding username: '$username'";
		  	exit;
		}
		$row = $result->fetch_assoc();
		return $row['UserID'];
	}

	function getImage() {
		/* Gets the article image and returns the filename if it is valid */
		if(!isset($_POST['article_id'])) {
		  	$db = dbConnect();
			$sql = "SELECT ArticleID FROM Article";
			$result = mysqli_query($db, $sql);
			$numResults = mysqli_num_rows($result);
			$counter = 0;
			while($row = mysqli_fetch_assoc($result)) {
				if (++$counter == $numResults) {
					$_POST['article_id'] = $row['ArticleID']+1;
				}
			}
	  	}

		$target_dir = "/home/ec2-user/public_html/res/img/articlePictures/".$_POST['article_id']."/";
		//Used for local host
		//$target_dir = "/Users/rolandoruche/Desktop/test/PoacherNews/res/img/articlePictures/".$_POST['article_id']."/";
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
