<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="login.css">
    <link href="https://fonts.googleapis.com/css?family=Do+Hyeon" rel="stylesheet">
</head>
<body>
    <?php
    	include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <form action="util/handleLogin.php" method="POST">
        <div class="loginWrap">
            <div class="form">
                <h1>Login</h1>
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" placeholder="Username">
                <br>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" placeholder="Password">
                <div class="buttons">
                    <input type="submit" name="submit" value="Submit">
                    <a href="createUser_Form.php">Create an account</a>
                </div>
                <?php
                    if (isset($error)) {
                        echo "<p>$error</p>";
                    }
                ?>
            </div>
        </div>
    </form>
</body>
</html>
