<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'includes/globalHead.html' ?>
    <title>Login</title>
</head>
<body>
    <?php
        // Check to see if the user has already logged in
        if(empty($_SESSION['loggedin'])) {
            $loggedIn = false;
        } else { // The user is already logged in, so send them back to the index
             echo '<meta http-equiv="refresh" content="0; url=/index.php">';
            exit;
        }
    	include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <form action="/util/handleLogin.php" method="POST">
            <div class="loginWrap">
                <div class="form">
                    <h1>Login</h1>
                    <label for="username">Username: </label>
                    <input type="text" name="username" placeholder="Username">
                    <br>
                    <label for="password">Password: </label>
                    <input type="password" name="password" placeholder="Password">
                    <div class="buttons">
                        <input type="submit" name="submit" value="Submit">
                        <a href="/createUser.php">Create an account</a>
                    </div>
                    <?php
                        if (isset($error)) {
                            echo "<p class='loginError'>$error</p>";
                        }
                    ?>
                </div>
            </div>
        </form>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>
