<?php
    include 'util/loginCheck.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" type="text/css" href="login.css">
    <link href="https://fonts.googleapis.com/css?family=Do+Hyeon" rel="stylesheet">
</head>
	<style>
		.loginWrap {
			width: 400px;
			background-color: beige;
			height: 200px;
			border: solid 2px lightblue;
			border-radius: 20px 0px;
			padding: 10px;
		}

		img.loginImg {
			width: 140px;
			float: right;
			height: 190px;
			padding: 5px;
		}

		h1.loginH1, label.loginLbl{
			font-family: 'Do Hyeon', sans-serif
		}

		#loginUsername, #loginPassword {
			margin: 5px;
			border: solid 1px lightblue;
			width: 100%;
			border-radius: 5px;
			height: 20px;
			font-size: 12px;
		}

		.loginButtons, .loginWrap, .loginRelative {
			margin: 10px auto;
			display: flex;
		}

		.loginButtons {
			width: 426px;
			height: 50px;
			align-items: center;
		}

		.loginButtons a.loginA, .loginButtons input.loginInput {
			height: 50px;
			width: 50%;
			text-decoration: none;
			color: black;
			text-align: center;
			background-color: lightblue;
			font-family: 'Do Hyeon', sans-serif;
			font-size: 20px;
			border: solid 2px black;
			margin: 5px;
			border-radius: 5px;
		}
	</style>
<body>
    <?php

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
    <?php include('includes/footer.html'); ?>
</body>
</html>
