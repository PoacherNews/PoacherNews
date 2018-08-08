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
			print "Success";
			break;
		case "updateAccount":
			if(!empty($_POST['currentEmail'])) {
				// Check if other fields are filled
			}

			// Password change functionality
			if(!empty($_POST['newPassword']) || !empty($_POST['confirmPassword'])) { // Make sure if any password change fields are set, that the current password field is set
				if(empty($_POST['currentPassword'])) {
					print("Please fill out all password fields");
					break;
				}
			}
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
	}
}

?>
