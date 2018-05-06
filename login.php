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
    	include 'util/loginCheck.php';
    	
// Check to see if the user has already logged in
if(empty($_SESSION['loggedin'])) {
    $loggedIn = false;
} else { // The user is already logged in, so send them back to the index
    echo "You are already logged in";
    echo '<meta http-equiv="refresh" content="0; url=/index.php">';
    exit;
}
    	include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <form action="/util/handleLogin.php" method="POST">
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
                    <a href="/createUser.php">Create an account</a>
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
