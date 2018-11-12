<?php
    include 'util/loginCheck.php';
    // Set session of page URL / Used to destroy session if user does not enter GA code
    //$_SESSION['previous'] = basename($_SERVER['PHP_SELF']);
?>
<!-- Bastardized login.php / 2FA.php -->
<!-- TODO -->

<!--
// Redirect logged in users manually entering recoverCode.php
// Add 2FACheck.php to other files
// QR / Key viewable from page source
// Redirect button after successful recover
-->

<!DOCTYPE html>
<html>
<head>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" href="/res/css/settings.css">
    
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
        if(empty($_SESSION['loggedin']) || $_SESSION['2fa'] == 0) {
            $loggedIn = false;
            // Unset $_SESSION['previous'] upon manual entry of 2FA.php
            unset($_SESSION['previous']);
            echo '<meta http-equiv="refresh" content="0; url=/index.php">';
            exit;
        }
    	include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <form id="recoveryCode" class="accountContainer">
            <input type="hidden" name="action" value="recoveryCode"/>
            <h1>Recovery Code</h1>
            <span class="subheader">Phone inaccessible? Enter your recovery code.</span>
            <div class="formFields">
                <br>
                <input type="text" name="RCode" placeholder="Recovery Code">
                <input id="verifySubmitButton" type="submit" name="submit" value="Submit">
            </div>
            <div id="errorMessage" class="settingsMessage"></div>
        </form>
        
        <script>
        $("#recoveryCode").submit(function(event) {
        $("#errorMessage").hide();
        $("#errorMessage").text('');
        $("#errorMessage").removeClass("error");
        event.preventDefault();
        $.post("util/settingsHandler.php", $(this).serialize(), function(data) {
            if(data != "Success") {
                $("#errorMessage").addClass("error");
                $("#errorMessage").text(data);
                $("#errorMessage").fadeIn();
            } else {
				$("#verifySubmitButton").hide();
                $("#errorMessage").addClass("success");
                $("#errorMessage").text("Authentication success. <br />You will be redirected momentarily..");
                $("#errorMessage").fadeIn();
                
				// Redirect (set in milliseconds)
				window.setTimeout(function() {
    				window.location.href = 'index.php';
				}, 3000);                
            }
        });
    });
        </script>
        

    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>
