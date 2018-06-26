<?php
include 'loginCheck.php';

//echo "at the top";
$action = empty($_POST['action']) ? '' : $_POST['action'];
//echo $action;
if ($action == 'updateEmail') 
{
    handle_email();
} 
else if ($action == 'updatePassword')
{
    handle_password();
}
else if ($action == 'deleteAccount')
{
    handle_deleteAccount();
}
else
{
    new_form();
}

function new_form()
{
    //$username = "";
    $errorEmail = "";
    $errorPassword = "";
    $errorDeleteAccount = "";
    include '../security.php';
    exit;
}

function handle_email() 
{
    // get all vars from form
    $currentEmail = empty($_POST['currentEmail']) ? '' : $_POST['currentEmail'];
    $newEmail = empty($_POST['newEmail']) ? '' : $_POST['newEmail'];
    $confirmNewEmail = empty($_POST['confirmNewEmail']) ? '' : $_POST['confirmNewEmail'];

    // empty fields not allowed
    if (empty($currentEmail) || empty($newEmail) || empty($confirmNewEmail))
    {
        $errorEmail = "Fields cannot be empty";
        include '../security.php';
        exit;
    }

    // connection to database
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $currentEmail = $db->real_escape_string($currentEmail);
    $newEmail = $db->real_escape_string($newEmail);
    $confirmNewEmail = $db->real_escape_string($confirmNewEmail);
    
    // current email check
    if($currentEmail != $_SESSION['email'])
    {
        $errorEmail = "Current Email does not match";
        include '../security.php';
        exit;
    }
    
    // email confirmation check
    if ($newEmail != $confirmNewEmail)
    {
        $errorEmail = "Emails must match";
        include '../security.php';
        exit;
    }
    
    // newEmail = currentEmail
    if($newEmail == $currentEmail)
    {
        $errorEmail = "Your email is already set to $currentEmail";
        include '../security.php';
        exit;
    }

    // email validation
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL))
    {
        $errorEmail = "$newEmail is not a valid email address";
        include '../security.php';
        exit;
    }

    // existing email check
    $sql = "SELECT * FROM User WHERE Email = '".$newEmail."'";
    $result = $db->query($sql);
    if($result->num_rows != 0)
    {
            $errorEmail = "$newEmail already exists";
            include '../security.php';
            exit;
    }
    
    
    // build new statement to insert new user in Users table
    $stmt_users = $db->stmt_init();
    // prepare
    if (!$stmt_users->prepare("UPDATE User SET Email = '".$newEmail."' WHERE Username = ?"))
    {
        echo "Error preparing INSERT statement: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    // bind parameters to new statement
    if (!$stmt_users->bind_param('s', $_SESSION['username']))
    {
        echo "Error binding parameters to INSERT: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    //print_r($result);
    
    // execute statement
    if (!$stmt_users->execute())
    {
        echo "Error INSERTing: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }

    // $_SESSION UPDATE
    $_SESSION['email'] = $newEmail;
    
    // createUser success
    $errorEmail = "Email successfully updated";
    // Redirect page for createUser success
    //    include '../createUser.php';
    include '../security.php';
}

function handle_password() 
{
    // get all vars from form
    $currentPassword = empty($_POST['currentPassword']) ? '' : $_POST['currentPassword'];
    $newPassword = empty($_POST['newPassword']) ? '' : $_POST['newPassword'];
    $confirmNewPassword = empty($_POST['confirmNewPassword']) ? '' : $_POST['confirmNewPassword'];

    // empty fields not allowed
    if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword))
    {
        $errorPassword = "Fields cannot be empty";
        include '../security.php';
        exit;
    }

    // connection to database
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $currentPassword = $db->real_escape_string($currentPassword);
    $newPassword = $db->real_escape_string($newPassword);
    $confirmNewPassword = $db->real_escape_string($confirmNewPassword);
    
// password restrictions
    // minimum length
    if(!preg_match('/^.{6,}+$/', $newPassword))
    {
        $errorPassword = "Password must be at least 6 characters";
        include '../security.php';
        exit;
    }
    // uppercase letter
    if(!preg_match('/[A-Z]/', $newPassword))
    {
        $errorPassword = "Password must contain an uppercase letter";
        include '../security.php';
        exit;
    }
    // lowercase letter
    if(!preg_match('/[a-z]/', $newPassword))
    {
        $errorPassword = "Password must contain a lowercase letter";
        include '../security.php';
        exit;
    }
    // number
    if(!preg_match('/[0-9]/', $newPassword))
    {
        $errorPassword = "Password must contain a number letter";
        include '../security.php';
        exit;
    }
    
    $sql = "SELECT * FROM User WHERE Username = '".$_SESSION['username']."'";
    $result = $db->query($sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $hash = $row['Password'];
    if (!password_verify($currentPassword, $hash))
    {
        $errorPassword = "Current password is incorrect";
        include '../security.php';
        exit;
    }
    
    // hash given password
    $password_hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // passwords must match
    if (!password_verify($confirmNewPassword, $password_hashed))
    {
        $errorPassword = "Passwords must match";
        include '../security.php';
        exit;
    }

    // newEmail = currentEmail
    if($newPassword == $currentPassword)
    {
        $errorPassword = "New password entered cannot be set to current password";
        include '../security.php';
        exit;
    }
    
    // build new statement to insert new user in Users table
    $stmt_users = $db->stmt_init();
    // prepare
    if (!$stmt_users->prepare("UPDATE User SET Password = '".$password_hashed."' WHERE Username = ?"))
    {
        echo "Error preparing INSERT statement: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    // bind parameters to new statement
    if (!$stmt_users->bind_param('s', $_SESSION['username']))
    {
        echo "Error binding parameters to INSERT: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    //print_r($result);
    
    // execute statement
    if (!$stmt_users->execute())
    {
        echo "Error INSERTing: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }

    // $_SESSION UPDATE
    
    // createUser success
    $errorPassword = "Password successfully updated";
    // Redirect page for createUser success
    //    include '../createUser.php';
    include '../security.php';    

}

function handle_deleteAccount()
{
    if(!isset($_POST['deleteConfirm']))
    {
        $errorDeleteAccount = "PLEASE CONFIRM DELETION OF ACCOUNT";
        include '../security.php';
        exit;
    }
    else 
    {
        // connection to database
        include 'db.php';
        // Check connection
        if ($db->connect_error)
        {
           die("Connection failed: " . $db->connect_error);
        }

        // build new statement to insert new user in Users table
        $stmt = $db->stmt_init();
        // prepare
        if (!$stmt->prepare("DELETE FROM User WHERE UserID = ?"))
        {
            echo "Error preparing INSERT statement: \n";
            echo nl2br(print_r($stmt->error_list, true), false);
            exit;
        }
        // bind parameters to new statement
        if (!$stmt->bind_param('i', $_SESSION['userid']))
        {
            echo "Error binding parameters to INSERT: \n";
            echo nl2br(print_r($stmt->error_list, true), false);
            exit;
        }
        //print_r($result);

        // execute statement
        if (!$stmt->execute())
        {
            echo "Error INSERTing: \n";
            echo nl2br(print_r($stmt->error_list, true), false);
            exit;
        }

        // $_SESSION UPDATE
        unset($_SESSION);
        session_destroy();

        // delete account success
        $errorDeleteAccount = "Account successfully deleted";
        // Redirect page for delete account success
        //    include '../createUser.php';
        include '../login.php';    
    }
}
?>
