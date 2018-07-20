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
                //include 'includes/toolsNav.php';
            ?>
        </div>
        
        <div class="display">
        </div>
        <div style="margin: auto auto; min-height: 1000px">
            <ul>
                <li><a href="/editorHistory.php">Editor History</a></li>
                <li><a href="/editorpage.php">Editor Page</a></li>
                <li><a href="/userManagement.php">Manage Users</a></li>
                <li><a href="/articleManagement.php">Manage Articles</a></li>
                <li><a href="/commentManagement.php">Manage Comments</a></li>
            </ul>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>