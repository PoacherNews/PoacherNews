<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Settings | Security</title>
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
                $current = 'security';
                include 'includes/settingsNav.php';
            ?>
        </div>
        
        <div class="display">
            <div>
                <h2>Change Email</h2>
                <form action="/util/handleSecurity.php" method="POST">
                    
                <input type="hidden" name="action" value="updateEmail">

                <div>
                    <label for="currentEmail"><b>Current Email</b></label>
                    <input type="text" placeholder="Current Email" name="currentEmail" required>
                </div>
             
                <div>
                    <label for="newEmail"><b>New Email</b></label>
                    <input type="text" placeholder="New Email" name="newEmail" required>
                </div>
             
                <div>
                    <label for="confirmNewEmail"><b>Confirm New Email</b></label>
                    <input type="text" placeholder="Confirm New Email" name="confirmNewEmail" required>
                </div>
             
                <?php
	            if (isset($errorEmail)) 
                {
                    echo "<p>$errorEmail</p>\n";
                }
                ?>
                    
                <input type="submit" name="updateEmail" value="Update Email">
                </form>
            </div>
            
            <div>
                <h2>Change Password</h2>
                <form action="/util/handleSecurity.php" method="POST">
                    
                <input type="hidden" name="action" value="updatePassword">   
                    
                <div>
                    <label for="currentPassword">Current Password</label>
                    <br>
                    <input type="password" name="currentPassword">
                </div>
             
                <div>
                    <label for="newPassword">New Password</label>
                    <br>
                    <input type="password" name="newPassword">
                </div>
             
                <div>
                    <label for="confirmNewPassword">Confirm New Password</label>
                    <br>
                    <input type="password" name="confirmNewPassword">
                </div>
                    
                <?php
	            if (isset($errorPassword)) 
                {
                    echo "<p>$errorPassword</p>\n";
                }
                ?>                    
             
                <input type="submit" name="updatePassword" value="Update Password">
                </form>
            </div>

            <div>
                <h2>Delete Account</h2>
                <form action="/util/handleSecurity.php" method="POST">
                    
                <input type="hidden" name="action" value="deleteAccount">   

                <?php
	            if (isset($errorDeleteAccount)) 
                {
                    echo "<p>$errorDeleteAccount</p>\n";
                }
                ?>   
                    
                    <input type="submit" name="deleteAccount" value="Delete Account">
                    <input type="checkbox" name="deleteConfirm" class="deleteConfirm" value="Confirm"/><label>CONFIRM DELETE</label>

                </form>
            </div>            

        </div>
            
        <?php include 'includes/footer.html'; ?>
    </body>
</html>