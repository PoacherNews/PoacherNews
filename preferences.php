<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Settings | Preferences</title>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="res/css/settings.css">
        <link rel="stylesheet" href="res/css/settingsNav.css">
    </head>

    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        
        <h1>Settings</h1>
        
        <div class="nav">
            <?php
                $current = 'preferences';
                include 'includes/settingsNav.php';
            ?>
        </div>
        
        <div class="display">
            <div>
                <h2>Language</h2>
            </div>
            
            <div>
                <h2>Date Format</h2>
            </div>

            <div>
                <h2>Time Zone</h2>
            </div>
            
            <div>
                <h2>Theme</h2>
            </div>
            
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>