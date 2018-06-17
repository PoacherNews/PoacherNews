<?php
    include 'util/loginCheck.php';
    $username = $_GET['Username'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile | Overview</title>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
    </head>

    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="user">
            <div class="picture">
                (Profile Picture)
            </div>
            
            <div class="info">
                <?php 
                	echo "<h3>$username</h3>";
                ?>
            </div>
        </div>
        
        <div class="nav">
            <?php
                $current = 'overview';
                include 'includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>