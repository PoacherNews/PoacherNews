<?php
// TODO: line 46
// Preview / Crop / Resize img
include 'loginCheck.php';

$target_dir = "/home/ec2-user/public_html/res/img/profilePictures/".$_SESSION['userid']."/";

if (!file_exists("/home/ec2-user/public_html/res/img/profilePictures/".$_SESSION['userid']."")) {
    mkdir("/home/ec2-user/public_html/res/img/profilePictures/".$_SESSION['userid']."", 0777, true);
}

chmod($target_dir, 0777);

$target_file = $target_dir . basename($_FILES["profilePicture"]["name"]);
$Filename=basename( $_FILES["profilePicture"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
//    include '../profile.php';
//    exit;
}
// Check file size
if ($_FILES["profilePicture"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) { 
    
    // Move PDO out of if statement
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    // build new statement to insert new user in Users table
    $stmt_users = $db->stmt_init();
    // prepare
    if (!$stmt_users->prepare("UPDATE User SET ProfilePicture = '".$Filename."' WHERE Username = ?"))
    {
        echo "Error preparing INSERT statement: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    // bind parameters to new statement
    if (!$stmt_users->bind_param('s', $_SESSION['username']))
    {
        echo "Error binding parameters to INSERT: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    //print_r($result);
    
    // execute statement
    if (!$stmt_users->execute())
    {
        echo "Error INSERTing: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }

    echo "The file ". basename( $_FILES["profilePicture"]["name"]). " has been uploaded.";
    // $_SESSION UPDATE
    $_SESSION['profilepicture'] = $Filename;
    
    // Redirect page for success
    //    include '../createUser.php';
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
