<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
        <link rel="stylesheet" type="text/css" href="login.css">
        <link href="https://fonts.googleapis.com/css?family=Do+Hyeon" rel="stylesheet">
	<?php include 'includes/globalHead.html' ?>
</head>
<body>
    <?php
//     if($error){
//        echo "<script type='text/javascript'>
//                alert($error)
//              </script>";
//      }
    	include 'includes/header.php';
        include 'includes/nav.php';
        //include 'includes/footer.html';
    ?>
    <form action="login.php" method="POST">
        <input type="hidden" name="action" value="do_login">
        <div class="loginWrap">
            <div class="form">
                <h1>Login</h1>
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" placeholder="Username">
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" placeholder="Password">
            </div>
            <img src="logo.png">
        </div>
        <div class="buttons">
            <input type="submit" value="Submit">
            <a href="createUser_Form.php">Create an account</a>
        </div>
        <div class="relative"></div>
    </form>

    <?php
        if (isset($error)) {
            echo "<p>$error</p>\n";
        }
    ?>

</body>
</html>
