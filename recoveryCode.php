<?php
    include 'util/loginCheck.php';
?>

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
        // Redirect unregisterd users or users with two-factor authentication disabled to index.php
        if(empty($_SESSION['loggedin']) || $_SESSION['tfastatus'] == 0) {
            echo '<meta http-equiv="refresh" content="0; url=/index.php">';
            exit;
        }
        // Redirect users unless $_SESSION['tfaURL'] is set
        // $_SESSION['tfaURL'] set upon acceess of TFA.php (TFA.php line 84)	
        if(!isset($_SESSION['tfaURL'])) {
            echo '<meta http-equiv="refresh" content="0; url=/index.php">';
            exit;
        }
		// Redirect users with two-factor authentication enabled to index.php
        // $_SESSION['enabledTFACheck'] set upon successful login through recoveryCode.php (util/settingsHandler.php line 233)
		else if(!empty($_SESSION['enabledTFACheck'])) {       
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
                <input id="RCode" type="text" name="RCode" placeholder="Recovery Code">
                <input id="verifySubmitButton" type="submit" name="submit" value="Submit">
				<a id="authenticationCode" href="TFA.php">Authentication Code</a>
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
						$("#RCode").hide();
						$("#authenticationCode").hide();				
						$("#verifySubmitButton").hide();
						$("#errorMessage").addClass("success");
						$("#errorMessage").text("Authentication success. You will be redirected momentarily..");
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
