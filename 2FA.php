<?php
    include 'util/loginCheck.php';
?>
<!-- Bastardized login.php -->

<!DOCTYPE html>
<html>
<head>
    <?php include 'includes/globalHead.html' ?>
    <title>Login</title>
    <style>
        .accountContainer {
            max-width: 450px;
            border: solid 2px #83A8F0;
            border-radius: 20px 0px;
            padding: 10px;
            margin: 8% auto 8% auto;
        }

        .accountContainer h1 {
            border-bottom: 1px solid #83A8F0;
        }

        .formFields {
            margin: 0px 25px 0px 25px;
            display: grid;
            grid-template-columns: 1fr;
            grid-row-gap: 5px;
        }

        .formFields a {
            text-align: center;
            margin-bottom: 10px;
        }

        .formFields input {
            line-height: 25px;
            font-size: 15px;
            padding: 5px;
            border: 1px solid grey;
            border-radius: 15px;
        }

        .errorMessage {
            margin: 5px 0px 5px 0px;
            padding: 10px;
            border-radius: 2px;
            font-weight: bold;
            box-shadow: 0 0 0 1px #e0b4b4 inset;
            background-color: #fff6f6;
            color: #9f3a38;
        }

        #verifySubmitButton {
            background: #83A8F0;
            color: white;
            border-radius: 100px;
            cursor: pointer;
            font-size: 14px;
            min-width: 250px;
            line-height: 15px;
            padding: 6px 16px;
            position: relative;
            text-align: center;
            white-space: nowrap;
            margin: 10px;
        }
        #verifySubmitButton:hover {
            border: 1px solid #1864F7;
            opacity: 0.75;
        }
    </style>
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
        <form class="accountContainer" action="/util/handleLogin.php" method="POST">
            <h1>Two-Factor Authentication</h1>
            <div class="formFields">
                <input type="2FACode" name="2FACode" placeholder="Verify Code">
                <input id="verifySubmitButton" type="submit" name="submit" value="Submit">
                <?php
                    if (isset($error)) {
                        echo "<p class='errorMessage'>$error</p>";
                    }
                ?>
            </div>
        </form>
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>
