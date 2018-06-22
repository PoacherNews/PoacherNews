<?php
// TODO:
// Change profile picture
    include 'util/userUtils.php';

    $username = $_GET['Username'];

    // connection to database
    include 'util/db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
        
    $sql = "SELECT * FROM User WHERE Username = '".$username."'";
    $result = $db->query($sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $profilepicture = $row['ProfilePicture'];
    $userid = $row['UserID'];
    $usertype = $row['Usertype']; 

    // Redirect to index if blank Username or profile does not exist
    if($username == NULL || $result->num_rows != 1)
    {
        //echo "Error. User: $username does not exist";
        echo '<meta http-equiv="refresh" content="0; url=index.php">';
        exit;
    }
?>

<div class="user">
    <div class="picture">
<?php
    if($profilepicture != null)
    {
        echo "<img src='../res/img/profilePictures/".$_SESSION['username']."/".$profilepicture."'>";
    }
    else 
    {
        echo "(Profile Picture)";
        if(strtolower($username) == strtolower($_SESSION['username']))
        {
?>
            <form action="../util/uploadProfilePicture.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="profilePicture" class="profilePicture">
                <input type="submit" value="Upload Image" name="submit">
            </form>
<?php
        }
    }
?>
    </div>
            
    <div class="info">
        <?php 
            echo "<h3>$username</h3>";
        ?>
    </div>
</div>