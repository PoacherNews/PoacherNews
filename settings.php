<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Settings | General</title>
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
                $current = 'general';
                include 'includes/settingsNav.php';
            ?>
        </div>
       
        <div class="display">
            <div>
                <h2>Name</h2>
                <form action="/util/handleAccount.php" method="POST">
                <div>
                    <label for="oldPassword">First Name</label>
                    <br>
                    <input type="password" name="oldPassword">
                </div>
             
                <div>
                    <label for="newPassword">Last Name</label>
                    <br>
                    <input type="password" name="newPassword">
                </div>
                    
                <input type="submit" name="updatePassword" value="Update Name">
                </form>
            </div>

            <div>
                <h2>Location</h2>
                <form action="/util/handleAccount.php" method="POST">
                <div>
                    <label for="oldPassword">City</label>
                    <br>
                    <input type="password" name="oldPassword">
                </div>
             
                <div>
                    <label for="newPassword">State</label>
                    <br>
                    <input type="password" name="newPassword">
                </div>
                    
                <div>
                    <label for="newPassword">Country</label>
                    <br>
                    <input type="password" name="newPassword">
                </div>
                
                <input type="submit" name="updatePassword" value="Update Location">
                </form>
            </div>
        </div>
        
        <?php include 'includes/footer.html'; ?>
    </body>
</html>