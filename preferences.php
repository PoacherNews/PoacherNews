<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Settings | Preferences</title>
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="/res/css/settings.css">
        <link rel="stylesheet" href="/res/css/settingsNav.css">
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
                <form action="/util/handlePreferences.php" method="POST">
                    
                <input type="hidden" name="action" value="updateTimeZone">
            <div>
                    <label for="timeZone">Time Zone</label>
                    <br>
                    <select name="timeZone">
                        <option value="HAST">Hawaii-Aleutian Time Zone (UTC−10:00)</option>
                        <option value="AKST">Alaska Time Zone (UTC−09:00)</option>
                        <option value="PST">Pacific Time Zone (UTC−08:00)</option>
                        <option value="MST">Mountain Time Zone (UTC−07:00)</option>
                        <option value="CST">Central Time Zone (UTC−06:00)</option>
                        <option value="EST">Eastern Time Zone (UTC−05:00)</option>
                    </select> 
            </div>
                    
                <?php
                if (isset($errorTimeZone)) 
                {
                    echo "<p>$errorTimeZone</p>\n";
                }
                ?>
                    
                <input type="submit" name="updateTimeZone" value="Update Time Zone">
                </form>
            
            </div>
                
            <div>
                <h2>Theme</h2>
            </div>
            
        </div>
        <?php include 'includes/footer.html'; ?>
    </body>
</html>