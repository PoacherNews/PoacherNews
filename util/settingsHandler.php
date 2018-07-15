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
	}
}

?>
