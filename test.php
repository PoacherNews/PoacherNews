<?php 
    include 'util/loginCheck.php';

// https://github.com/PHPGangsta/GoogleAuthenticator
// https://www.youtube.com/watch?v=t49zjBGD75U
// https://www.9lessons.info/2016/06/google-two-factor-authentication-login.html

// Recovery code

/*
$checkResult = $authenticator->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
if ($checkResult) {
    echo 'OK';
} else {
    echo 'FAILED';
}*/
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'includes/globalHead.html' ?>
    <title>Login</title>
    <style>
        .accountContainer {
            max-width: 520px;
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
    	include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div id="mainContent">
        <form id="GA" class="accountContainer">
                <input type="hidden" name="action" value="GA"/>

            <h1>Enable Two-Factor Authentication</h1>
            
            <div class="formFields">
                
            <?php
                require "util/GoogleAuthenticator.php";

                $authenticator = new GoogleAuthenticator();

                if(!isset($_SESSION['auth_secret'])) {
                    $secret = $authenticator->createSecret();
                    $_SESSION['auth_secret'] = $secret;
                }

                $secret = $authenticator->createSecret();

                echo "Please download the Google Authenticator app to continue";
                echo "<br>";
                echo "(Google Authenticator link downloads)";
                echo "<br>";
                echo"<br>";

                /*
                echo "Secret is: ".$secret."";
                echo "<br>";
                echo "<br>";
                */
                
                /*
                $qrCode = $authenticator->getQR('PoacherNews', $secret);
                echo "<img src='{$qrCode}' >";
                echo "<br>";*/
                $email = $_SESSION['email'];
                $qrCodeUrl = $authenticator->getQRCodeGoogleUrl($email, $_SESSION['auth_secret'], 'PoacherNews');
                //echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";
                //echo "<br>";

                echo "<img src='{$qrCodeUrl}'>";
                echo "<br>";
                echo "<br>";
            
                /*
                $oneCode = $authenticator->getCode($secret);
                echo "Checking Code '$oneCode' and Secret '$secret':\n";
                echo "<br>";
                echo "<br>";
                */
                
                /*
                $checkResult = $authenticator->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
                if ($checkResult) {
                    echo 'OK';
                } else {
                    echo 'FAILED';
                }
                */
            ?>
                <input type="2FACode" name="code" placeholder="Verify Code">
                <input id="verifySubmitButton" type="submit" name="submit" value="Submit">
            </div>
        
            <div id="errorMessage" class="settingsMessage"></div>
        </form>
        
        <script>
        $("#GA").submit(function(event) {
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
                $("#errorMessage").addClass("success");
                $("#errorMessage").text("Two-factor Authentication successfully updated.");
                $("#errorMessage").fadeIn();
            }
        });
    });
        </script>
        
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>