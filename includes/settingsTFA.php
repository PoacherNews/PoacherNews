<?php
	session_start();
?>

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
	
	.code {
		text-align: center;
	}
	#qr {
		margin-right: auto;
		margin-left: auto;
	}
</style>

<!-- Generation and storing of QR / recovery code -->
<?php
	require "../util/GoogleAuthenticator.php";
	$authenticator = new GoogleAuthenticator();

	// Store QR code in $_SESSION['qrcode'] to prevent loading of new code
	if(empty($_SESSION['qrcode'])) {
		// Generate manual QR code
		$secret = $authenticator->createSecret();
		$_SESSION['qrcode'] = $secret;
	}
                
	// Store recovery code in $_SESSION['recoverycode'] to prevent loading of new recovery code
	if(empty($_SESSION['recoverycode'])) {
		// Generate random recovery code string      
		function generateRecoveryCode($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
				
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}   
			return $randomString;
		}
		
		$_SESSION['recoverycode'] = generateRecoveryCode();
	}
?>

<form id="TFAStatus" class="accountContainer">
	<input type="hidden" name="action" value="TFAStatus"/>

	<?php
		// Display if 2FA is disabled
		if($_SESSION['tfastatus'] == 0) { ?>
			<h1>Enable Two-Factor Authentication</h1>
			<span class="subheader">Enter your authenticator code to enable two-factor authentication</span>
	<?php } ?>
	<?php
		// Display if 2FA is enabled
		if($_SESSION['tfastatus'] == 1) { ?>
			<h1>Disable Two-Factor Authentication</h1>
			<span class="subheader">Enter your password to disable two-factor authentication</span>
	<?php } ?>
			
	<div class="formFields">    
		<?php
			// Display if 2FA is disabled
			if($_SESSION['tfastatus'] == 0) {
				echo "Please download a two-factor authentication app to continue.";
				echo "<br>";

				echo "<b><u>Save the following recovery code in the case that your phone is inaccessible!</u></b>";
				echo "<br>";
					
				echo "<p class='code'>Recovery Code: ".$_SESSION['recoverycode']."</p>";
				echo "<br>";

                // QR code URL
				$qrCodeUrl = $authenticator->getQRCodeGoogleUrl($_SESSION['email'], $_SESSION['qrcode'], 'PoacherNews.com');
				// Convert QR code URL to an image
				echo "<img id='qr' src='{$qrCodeUrl}'>";
				echo "<p class='code'>Manual QR Code: ".$_SESSION['qrcode']."</p>";
				echo "<br>";
		?>
				<input id="ACode" type="text" name="ACode" placeholder="Authentication Code">
		<?php
			// Display if 2FA is enabled
			} else if($_SESSION['tfastatus'] == 1) { 
				echo "<br>";
		?>
				<input id="password" type="password" name="password" placeholder="password">
		<?php } ?>
		
		<input id="verifySubmitButton" type="submit" name="submit" value="Submit">
	</div>
        	
	<div id="errorMessage" class="settingsMessage"></div>
</form>
        
<script>
	$("#TFAStatus").submit(function(event) {
		$("#errorMessage").hide();
		$("#errorMessage").text('');
		$("#errorMessage").removeClass("error");
		event.preventDefault();
		$.post("../util/settingsHandler.php", $(this).serialize(), function(data) {
			if(data != "Success") {
				$("#errorMessage").addClass("error");
				$("#errorMessage").text(data);
				$("#errorMessage").fadeIn();
			} else {
				$("#password").hide();
				$("#verifySubmitButton").hide();
				$("#errorMessage").addClass("success");			
				<?php if($_SESSION['tfastatus'] == 0) { ?>
					$("#ACode").hide();
					$("#errorMessage").text("Authentication successfully enabled. You will be redirected momentarily..");
				<?php } ?>
				<?php if($_SESSION['tfastatus'] == 1) { ?>
					$("#password").hide();			
					$("#errorMessage").text("Authentication successfully disabled. You will be redirected momentarily..");
				<?php } ?>
				$("#errorMessage").fadeIn();

				// Redirect (set in milliseconds)
				window.setTimeout(function() {
					window.location.href = '/settings.php?tab=account';
				}, 3000);
			}
		});
	});
</script>