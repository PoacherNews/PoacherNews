<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
    </head>

    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
            include 'includes/profileHeader.php';
        ?>
        
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