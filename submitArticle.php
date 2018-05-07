<?php

  $action = empty($_POST['action']) ? '' : $_POST['action'];

  if(empty($_POST['title']) || empty($_POST['category']) || file_exists($_FILES['image']['temp_name'])){
    echo "error";
    require("editorpage.php");
    exit;
  }

  function dbConnect() {
    include 'util/db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    else{
	   echo "Connected successfully to the database";
     return $db;
    }
  }

  if($action == "saveArticle"){
    saveArticle();
  }
  else {
    new_form();
  }


  function saveArticle() {

      $title = empty($_POST['title']) ? '' : $_POST['title'];
      $category = empty($_POST['category']) ? '' : $_POST['category'];
      $body = empty($_POST['body']) ? '' : $_POST['body'];
      $authorid = getAuthorID();
      $filepath = uploadImage();
      $body = readTextFile();
      $db = dbConnect();
      $stmt = $db->stmt_init();

      if (!$stmt->prepare("INSERT INTO Articles(Headline, Body, Category, AuthorID, Img) VALUES(?, ?, ?, ?, ?)"))
      {
          echo "Error preparing statement: \n";
          print_r($stmt->error_list);
          exit;
      }

      if (!$stmt->bind_param('sssis', $title, $body, $category, $authorid, $filepath))
      {
          echo "Error binding parameters: \n";
          print_r($stmt->error_list);
          exit;
      }

      if(!$stmt->execute()){
        {
            echo "Error Inserting: \n";
            echo nl2br(print_r($stmt->error_list, true), false);
            exit;
        }
      }

      echo "Article: " . $title . " submitted successfully";
  }

  function readTextFile() {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
      $finfo->file($_FILES['file']['tmp_name']),
      array(
          //valid file extensions
          'txt' => 'text/plain',
      ),
      true
    )) {
      throw new RuntimeException('Invalid file format.');
    }

    $body = file_get_contents($_FILES['file']['tmp_name']);
    return $body;
  }

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

      if(!$stmt->prepare("SELECT Img FROM Articles WHERE Img=?")){
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
  }

  function getAuthorID(){
    $username = empty($_SESSION['username']) ? 'error' : $_SESSION['username'];
    $db = dbConnect();
    $username = "bob";

    $stmt = $db->stmt_init();

    if(!$stmt->prepare("SELECT UserID FROM Users WHERE Username=?")){
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
  }

  function new_form() {
    $error = "";
    header("Location: index.php");
    exit;
  }
?>
