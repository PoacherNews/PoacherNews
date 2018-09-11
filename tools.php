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
        <?php
            include 'includes/toolsNav.php';
        ?>
    </div>       
    <?php include 'includes/footer.html'; ?>
</body>
</html>