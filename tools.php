<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tools</title>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/profile.css">
        <link rel="stylesheet" href="res/css/profileNav.css">
    </head>

    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <div class="nav">
            <?php
                include 'includes/toolsNav.php';
            ?>
        </div>
        
        <div class="display">
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>