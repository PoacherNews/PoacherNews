<?php
session_start();
include('db.php');
include('userUtils.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
	if(empty($_POST['action']) || !isset($_POST['action'])) { // Disallow access without specifing an aciton.
		exit;
	}
	if(empty($_SESSION) || !isset($_SESSION)) { // Disallow access if not logged in.
		exit;
	}

    $user = getUserById($_SESSION['userid'], $db);
	switch($_POST['action']) {
		case "updateGeneral":
			if(!is_null($_POST['bio'])) {
				if(strcmp($_POST['bio'], $user['Bio']) != 0) { // Bio has been changed from one on record
					if(!updateBio($_SESSION['userid'], $_POST['bio'], $db)) {
						print("Failed to update user bio.");
						exit;
					}
				}
			}
			if(!is_null($_POST['firstName']) || !is_null($_POST['lastName'])) {
				$fname = strcmp($_POST['firstName'], $user['FirstName']) == 0 ? NULL : $_POST['firstName'];
				$lname = strcmp($_POST['lastName'], $user['LastName']) == 0 ? NULL : $_POST['lastName'];
				if(!updateName($_SESSION['userid'], $fname, $lname, $db)) {
					print("Failed to update display name.");
					exit;
				}
			}
			if(!is_null($_POST['city']) || !is_null($_POST['state'])) {
				$city = strcmp($_POST['city'], $user['City']) == 0 ? NULL : $_POST['city'];
				$state = strcmp($_POST['state'], $user['State']) == 0 ? NULL : $_POST['state'];
				if(!updateLocation($_SESSION['userid'], $city, $state, $db)) {
					print("Failed to update location.");
					exit;
				}
			}			
			print "Success";
			break;
		case "updatePreferences":
			if(!is_null($_POST['timezone'])) {
				if(strcmp($_POST['timezone'], $user['TimeZone']) != 0) { // Time zone has been changed from the one on record
					if(!updateTimezone($_SESSION['userid'], $_POST['timezone'], $db)) {
						print("Failed to update time zone.");
						exit;
					}
				}
			}
			if(!is_null($_POST['dateformat'])) {
				if(strcmp($_POST['dateformat'], $user['DateFormat']) != 0) { // Date format has been changed from the one on record
					if(!updateDateformat($_SESSION['userid'], $_POST['dateformat'], $db)) {
						print("Failed to update date format.");
						exit;
					}
				}
			}			
			print "Success";
			break;
		case "updateAccount":
			// User pressed save without requesting any changes
			if(empty($_POST['currentPassword']) && empty($_POST['newEmail'])) {
				print("Please fill out any fields you wish to update.");
				break;
			}
			// User tried to save either a new password or email without entering their current one
			if(!empty($_POST['newPassword']) || !empty($_POST['confirmPassword'])) {
				if(empty($_POST['currentPassword'])) {
					print("Please fill out all password fields");
					break;
				}
			}

			// Email change functionality
			if(!empty($_POST['newEmail'])) {
				if(empty($_POST['confirmEmail'])) {
					print("Please fill out all email fields.");
					break;
				}
				// Disabled this check, as form field for current email is marked as disabled, so not sent in the POST request.
				// if(strcmp($_POST['currentEmail'], $_SESSION['email']) != 0) {
				// 	print("Current email is incorrect.");
				// 	break;
				// }
				if (!filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)) {
					print("Please enter a valid email address.");
					break;
				}
				if(strcmp($_POST['newEmail'], $_POST['confirmEmail']) != 0) {
					print("New emails do not match.");
					break;
				}

				if(!updateEmail($_SESSION['userid'], $_POST['newEmail'], $db)) {
					print("Failed to update email.");
					break;
				}
				$_SESSION['email'] = $_POST['newEmail']; // Update session so changes take effect without them having to log out and back in
			}

			// Password change functionality
			if(!empty($_POST['currentPassword'])) { 
				if(empty($_POST['newPassword']) || empty($_POST['confirmPassword'])) { // Make sure that if the new password field is set, the other password fields are set
					print("Please fill out all password fields.");
					break;
				}
				if(!password_verify($_POST['currentPassword'], getHashedPassword($_SESSION['userid'], $db))) {
					print("Current password is incorrect.");
					break;
				}
				if(strcmp($_POST['newPassword'], $_POST['confirmPassword']) != 0) {
					print("Passwords do not match.");
					break;
				}
				if(password_verify($_POST['newPassword'], getHashedPassword($_SESSION['userid'], $db))) {
					print("New password must be different than your current password.");
					break;
				}
				$verifyResult = verifyValidPassword($_POST['newPassword']);
    			if(!($verifyResult === TRUE)) { // If not TRUE, verifyResult will hold a specific error string.
    				print($verifyResult);
    				break;
    			}

				$newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
				if(!updateUserPassword($_SESSION['userid'], $newPassword, $db)) {
					print("Failed to update password.");
					break;
				}
			}

			print "Success";
			break;
            
        // Added by Bruce Head    
        // Two-Factor Authentication Functionality
        case "twoFactorAuthentication":
            if(!updateTFA($_SESSION['userid'], $db)) {
				print("Failed to update two-factor authentication. Please contact the site administrator.");
				break;
			}
            
        // Update session so changes take effect without them having to log out and back in
        if($_SESSION['2fa'] === 0) {
            $_SESSION['2fa'] = 1;            
        }
        else if($_SESSION['2fa'] == 1) {
             $_SESSION['2fa'] = 0;            
        }
        print ("Success");
        break;    
        // Added by Bruce Tail
            
		case "deleteAccount":
		// print_r($_POST);
			if(empty($_POST['deleteConfirm'])) {
				print("You must confirm account deletion.");
				break;
			}
			if(!($_POST['deleteConfirm'] === "on")) {
				print("You must confirm account deletion");
				break;
			}
			if(!deleteUser($_SESSION['userid'], $db)) {
				print("Failed to delete user. Please contact the site administrator.");
				break;
			}

			// Delete their account
			unset($_SESSION);
        	session_destroy();
			print("Success");
			// header('Location: login.php');
			break;
	}
}

?>
