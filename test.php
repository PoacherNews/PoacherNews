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
    <link rel="stylesheet" href="/res/css/settings.css">

    <title>Enable 2FA</title>
    <style>
        .accountContainer {
            max-width: 530px;
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

			<?php if($_SESSION['2fa'] == 0) { ?>
				<h1>Enable Two-Factor Authentication</h1>
				<span class="subheader">Enter your Google Authenticator code to enable two-factor authentication</span>
            <?php } ?>
			<?php if($_SESSION['2fa'] == 1) { ?>
				<h1>Disable Two-Factor Authentication</h1>
				<span class="subheader">Enter your password to disable two-factor authentication</span>
			<?php } ?>
			
            <div class="formFields">    
            <?php
                require "util/GoogleAuthenticator.php";

                $authenticator = new GoogleAuthenticator();

                if(!isset($_SESSION['auth_secret'])) {
                    $secret = $authenticator->createSecret();
                    $_SESSION['auth_secret'] = $secret;
                }

				
                $secret = $authenticator->createSecret();
				
				if($_SESSION['2fa'] == 0) {

                	echo "Please download the Google Authenticator app to continue";
                	echo "<br>";
                	echo "(Google Authenticator link downloads)";
                	echo "<br>";
                	echo"<br>";

					/*
                	echo "Set secret is: ".$_SESSION['auth_secret']."";
					echo "<br>";
                	echo "Secret is: ".$secret."";
                	echo "<br>";
                	echo "<br>";
					*/
                
                	/*
                	$qrCode = $authenticator->getQR('PoacherNews', $secret);
                	echo "<img src='{$qrCode}' >";
                	echo "<br>";
					*/
                	$email = $_SESSION['email'];
                	$qrCodeUrl = $authenticator->getQRCodeGoogleUrl($email, $_SESSION['auth_secret'], 'PoacherNews');
                	//echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";
                	//echo "<br>";
					
                	echo "<img src='{$qrCodeUrl}'>";
                	echo "<br>";
                	echo "<br>";
					echo "Code is: ".$_SESSION['auth_secret']."";
					
					/*
                	$oneCode = $authenticator->getCode($secret);
				
                	echo "Checking Code '$oneCode' and Secret '$secret':\n";
                	echo "<br>";
                	echo "<br>";
                
                	$checkResult = $authenticator->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
                	if ($checkResult) {
						echo 'OK';
                	} else {
                    	echo 'FAILED';
                	}
					*/
				} else if($_SESSION['2fa'] == 1) {
					// Print nothing if 2FA is enabled
				}
            ?>
				<?php if($_SESSION['2fa'] == 0) { ?>
                	<input id="code" type="text" name="code" placeholder="Verify Code">
                	<input id="verifySubmitButton" type="submit" name="submit" value="Submit">
				<?php } ?>
				<?php if($_SESSION['2fa'] == 1) { ?>
					<input id="password" type="password" name="password" placeholder="password">
                	<input id="verifySubmitButton" type="submit" name="submit" value="Submit">
				<?php } ?>
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
				$("#code").hide();
				$("#password").hide();
				$("#verifySubmitButton").hide();
                $("#errorMessage").addClass("success");
				<?php if($_SESSION['2fa'] == 0) { ?>
                	$("#errorMessage").text("Authentication successfully enabled. <br />You will be redirected momentarily..");
				<?php } ?>
				<?php if($_SESSION['2fa'] == 1) { ?>
                	$("#errorMessage").text("Authentication successfully disabled. <br />You will be redirected momentarily..");
				<?php } ?>
                $("#errorMessage").fadeIn();
				
				// Redirect (set in milliseconds)
				window.setTimeout(function() {
    				window.location.href = 'http://localhost:8000/settings.php?tab=account';
				}, 3000);
            }
        });
    });
        </script>
        
    </div>
    <?php include('includes/footer.html'); ?>
</body>
</html>