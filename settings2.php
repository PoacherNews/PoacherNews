<?php
    include 'util/loginCheck.php';
    if(empty($_GET['tab']) || !isset($_GET['tab'])) {
        $currentTab = "general"; // Default starting tab
    } else { $currentTab = $_GET['tab']; }

    //TODO: Reject page load if not logged in
?>
 
<!DOCTYPE html>
<html>
    <head>
        <title>Settings | General</title> <!-- TODO: Update title based on current tab -->
        <?php include 'includes/globalHead.html' ?>
        <link rel="stylesheet" href="/res/css/settings2.css">
    </head>
 
    <body>
        <?php
            include 'includes/header.php';
            include 'includes/nav.php';
        ?>

        <div id="settingsContainer">
            <span class="settingsHeader">Settings</span>
            <ul class="nav">
                <li id="generalTab" <?php print($currentTab === "general" ? "class='active'" : "") ?>><a href="?tab=general">General</a></li>
                <li id="preferencesTab" <?php print($currentTab === "preferences" ? "class='active'" : "") ?>><a href="?tab=preferences">Preferences</a></li>
                <li id="accountTab" <?php print($currentTab === "account" ? "class='active'" : "") ?>><a href="?tab=account">Account</a></li>
            </ul>
            <div class="content">
                <?php
                    switch($currentTab) {
                        case "general":
                            include('includes/settingsGeneral.html');
                            break;
                        case "preferences":
                            include('includes/settingsPreferences.html');
                            break;
                        case "account":
                            include('includes/settingsAccount.html');
                            break;
                    }
                ?>
            </div>
        </div>

        <?php include 'includes/footer.html'; ?>
    </body>
</html>