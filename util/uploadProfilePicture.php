<?php
// TODO: line 46
// Preview / Crop / Resize img
include 'loginCheck.php';



$target_dir = "/home/ec2-user/public_html/res/img/profilePictures/".$_SESSION['userid']."/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

chmod($target_dir, 0777);

// Rename uploaded file
$temp = explode(".", $_FILES["profilePicture"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["profilePicture"]["name"],PATHINFO_EXTENSION));

// Check if file is an actual image file
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
        exit;
    }
}
// Check if file already exists
if (file_exists($newfilename)) {
    $uploadOk = 0;
    echo "Sorry, file already exists.";
    exit;

//    include '../profile.php';
//    exit;
}
// Check file size in KB
if ($_FILES["profilePicture"]["size"] > 200000) {
    $uploadOk = 0;
    echo "Sorry, your file is too large.";
    exit;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $uploadOk = 0;
    echo "Sorry, only JPG, JPEG & PNG files are allowed.";
    exit;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    exit;
// if everything is ok, try to upload file
} 
else if ($uploadOk = 1)  {
    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_dir . $newfilename)) { 
    
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
    if (!$stmt_users->prepare("UPDATE User SET ProfilePicture = '".$newfilename."' WHERE Username = ?"))
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
    // $_SESSION UPDATE
    $_SESSION['profilepicture'] = $newfilename;

    // Redirect page for success
    //    include '../createUser.php';
    } 
    print("Success");
    exit;
}
?>
