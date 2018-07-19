<?php
  header("Location: ../editorHistory.php");
  session_start();
  $action = empty($_POST['action']) ? '' : $_POST['action'];
  $submit = empty($_POST['submit']) ? '' : $_POST['submit'];
  $save = empty($_POST['save']) ? '' : $_POST['save'];
  /*
  if(empty($_POST['title']) || empty($_POST['category']) || file_exists(!($_FILES['image']['temp_name']))){
    echo "error";
    require 'editorpage.php';
    exit;
  } else {
      print "success!";
  }
  */
  include('util/articleUtils.php');
  //$articleData = getArticleByID($_GET['articleid'], $db);

  function dbConnect() {
    include 'util/db.php';
    
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    else{
        return $db;
    }
  } // End of dbConnect

  
  if(isset($action)){
      if($submit == "Yes") { // If the user wants to submit
          submitArticle();
          // relocate
      } else if($save == "Save") { // If the user wants to save
          saveArticle();
          // relocate
      }
  } else {
    relocate(); //Go back to editorHistory.php
  }

  

  function submitArticle() {
      $title = empty($_POST['title']) ? '' : $_POST['title'];
      $category = empty($_POST['category']) ? '' : $_POST['category'];
      $body = empty($_POST['body']) ? '' : $_POST['body'];
      $authorid = getAuthorID();
      $is_draft = 1; // true
      $is_submitted = 1; // true
	  $views = 0; // true
      $db = dbConnect();
      $stmt = $db->stmt_init();
      $articleData = getArticleByID($_GET['articleid'], $db);
      
      // Check if the draft has an articleid or is a new submission
      if($articleData['ArticleID']) { // The draft was saved upon edit
          //Update the mysql
          $sql_query = "UPDATE Article SET Headline = '".$title."', Body= '".$body."', Category = '".$category."', IsSubmitted = '".$is_submitted."' WHERE ArticleID = ? ";
          if (!$stmt->prepare($sql_query)) {
              echo "Error preparing statement: \n";
              print_r($stmt->error_list);
              exit;
          }
          if (!$stmt->bind_param('i', $articleData['ArticleID'])) {
              echo "Error binding parameters: \n";
              print_r($stmt->error_list);
              exit;
          }
          if(!$stmt->execute()){
                echo "Error Inserting: \n";
                echo nl2br(print_r($stmt->error_list, true), false);
                exit;
          }
          echo "Article: " . $title . " submitted successfully.";
      } else { // The draft is new and was not saved upon edit
          if (!$stmt->prepare("INSERT INTO Article(UserID, Headline, Body, Category, IsDraft, IsSubmitted) VALUES(?, ?, ?, ?, ?, ?)")) {
              echo "Error preparing statement: \n";
              print_r($stmt->error_list);
              exit;
          }
          if (!$stmt->bind_param('isssii', $authorid, $title, $body, $category, $is_draft, $is_submitted)) {
              echo "Error binding parameters: \n";
              print_r($stmt->error_list);
              exit;
          }
          if(!$stmt->execute()){
                echo "Error Inserting: \n";
                echo nl2br(print_r($stmt->error_list, true), false);
                exit;
          }
          echo "Article: " . $title . " submitted successfully.";
      }
  } //End of submitArticle

  function saveArticle() { //TODO: $views = 0
      $title = empty($_POST['title']) ? '' : $_POST['title'];
      $category = empty($_POST['category']) ? '' : $_POST['category'];
      $body = empty($_POST['body']) ? '' : $_POST['body'];
	  $result = htmlspecialchars($body);
      $authorid = getAuthorID();
      //$filepath = uploadImage();
      $is_draft = 1; // true
      $is_submitted = 0; // false
      $db = dbConnect();
      $stmt = $db->stmt_init();
      $articleData = getArticleByID($_GET['articleid'], $db);
      
      // Check if the draft has an articleid or is a new submission
      if($articleData['ArticleID']) { // The draft was saved upon edit
          //Update the mysql
          $sql_query = "UPDATE Article SET Headline = '".$title."', Body= '".$result."', Category = '".$category."' WHERE ArticleID = ? ";
          if (!$stmt->prepare($sql_query)) {
              echo "Error preparing statement: \n";
              print_r($stmt->error_list);
              exit;
          }
          if (!$stmt->bind_param('i', $articleData['ArticleID'])) {
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
      } else { // The draft is new and was not saved upon edit
          if (!$stmt->prepare("INSERT INTO Article(UserID, Headline, Body, Category, IsDraft, IsSubmitted) VALUES(?, ?, ?, ?, ?, ?)")) {
              echo "Error preparing statement: \n";
              print_r($stmt->error_list);
              exit;
          }
          if (!$stmt->bind_param('isssii', $authorid, $title, $body, $category, $is_draft, $is_submitted)) {
              echo "Error binding parameters: \n";
              print_r($stmt->error_list);
              exit;
          }
          if(!$stmt->execute()){
                echo "Error Inserting: \n";
                echo nl2br(print_r($stmt->error_list, true), false);
                exit;
          }
          echo "Article: " . $title . " saved successfully.";
      }
  } //End of saveArticle

  function uploadImage() {
      // Limit file size
      if ($_FILES['image']['size'] > 10000000) {
          throw new RuntimeException('Exceeded filesize limit.');
      }

      // Check if image is valid
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      if (false === $ext = array_search(
        $finfo->file($_FILES['image']['tmp_name']),
        array(
            //valid file extensions
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
      )) {
        throw new RuntimeException('Invalid file format.');
      }


      $filepath = sprintf('res/img/%s.%s',
          // Encrypt file name to avoid user selected name
          sha1_file($_FILES['image']['tmp_name']),
          // Use valid file extension
          $ext
      );

      $db = dbConnect();
      $stmt = $db->stmt_init();

      if(!$stmt->prepare("SELECT ArticleImage FROM Article WHERE ArticleImage=?")){
        echo "Error preparing statement: \n";
        print_r($stmt->error_list);
        exit;
      }

      if(!$stmt->bind_param('s', $filepath)){
        echo "Error binding parameters: \n";
        print_r($stmt->error_list);
        exit;
      }

      $stmt->execute();
      // get result
      $result = $stmt->get_result();

      if($result->num_rows > 0){
        echo "File name already exists. Please choose different name";
        exit;
      }

      // Move file form temp folder
      if (!move_uploaded_file(
          $_FILES['image']['tmp_name'],
          $filepath
      )) {
          throw new RuntimeException('Failed to move uploaded file.');
      }

      return $filepath;
  } // End of uploadImage

  function getAuthorID(){
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

  function relocate() {
        header("Location: /editorHistory.php");
        exit;
  }
?>
