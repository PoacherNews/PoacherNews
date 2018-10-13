<?php
    include 'util/loginCheck.php';
    if($_SESSION['usertype'] == "U") { // Disallow regular users from accessing tools page.
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tools</title>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" href="res/css/tools.css">
</head>

<body>
    <?php
        include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div class="pageContent">
        <div class="toolsHomepageNav">
            <h1>Tools</h1>
            <ul>
                <li><a <?php print($toolsTab === "editorpage" ? 'class="active"' : ''); ?> href="/editorpage.php">Editor Page</a></li>
                <?php
                    if($_SESSION['usertype'] === 'A') {
                        print '
                        <li><a '.($toolsTab === "usermanagement" ? 'class="active"' : '').' href="/userManagement.php">Manage Users</a></li>
                        <li><a '.($toolsTab === "articlemanagement" ? 'class="active"' : '').' href="/articleManagement.php">Manage Articles</a></li>
                        <li><a '.($toolsTab === "commentmanagement" ? 'class="active"' : '').' href="/commentManagement.php">Manage Comments</a></li>
                        ';
                    }
                ?>
            </ul>
        </div>
    </div>       
    <?php include 'includes/footer.html'; ?>
</body>
</html>