<?php

// TODO:
// LOGIN LINKING
// login check redirect
// session login / redirect upon createUser success
//
// FEATURES
// username restrictions
// password restrictions
// mail()

// don't load page if they're logged in
/*
include '../login/loginCheck.php';
if ($loggedIn)
{
    header("Location: ../login/successPage.php");
    exit;
}
*/

// Check to see if the user has already logged in
if(empty($_SESSION['loggedin'])) {
    $loggedIn = false;
} else { // The user is already logged in, so send them back to the index
    print "You are already logged in."; //DEBUG
    echo '<meta http-equiv="refresh" content="0; url=/index.php">';
    exit;
}

//echo "at the top";
$action = empty($_POST['action']) ? '' : $_POST['action'];
//echo $action;
if ($action == 'make_new') 
{
    handle_create();
} 
else 
{
    new_form();
}

function new_form()
{
    //$username = "";
    $error = "";
    include '../createUser.php';
    exit;
}

function handle_create() 
{
    // get all vars from form
    $firstname = empty($_POST['firstname']) ? '' : $_POST['firstname'];
    $lastname = empty($_POST['lastname']) ? '' : $_POST['lastname'];
    $email = empty($_POST['email']) ? '' : $_POST['email'];
    $email_confirm = empty($_POST['email_confirm']) ? '' : $_POST['email_confirm'];
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    $password_confirm = empty($_POST['password_confirm']) ? '' : $_POST['password_confirm'];

    // empty fields not allowed
    if (empty($firstname) || empty($lastname) || empty($email) || empty($email_confirm) || empty($username) || empty($password) || empty($password_confirm))
    {
        $error = "Fields cannot be empty";
        include '../createUser.php';
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
    $firstname = $db->real_escape_string($firstname);
    $lastname = $db->real_escape_string($lastname);
    $email = $db->real_escape_string($email);
    $email_confirm = $db->real_escape_string($email_confirm);
    $username = $db->real_escape_string($username);
    $password = $db->real_escape_string($password);
    $password_confirm = $db->real_escape_string($password_confirm);
    
    // hash given password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
    // passwords must match
    if (!password_verify($password_confirm, $password_hashed))
    {
        $error = "Passwords must match";
        include '../createUser.php';
        exit;
    }
    
    // destroy unhashed passwords
    unset($password);
    unset($_POST['password']);
    unset($password_confirm);
    unset($_POST['password_confirm']);

    // emails must match
    if ($email != $email_confirm)
    {
        $error = "Emails must match";
        include '../createUser.php';
        exit;
    }

    // email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error = "$email is not a valid email address";
        include '../createUser.php';
        exit;
    }

    // Build query username
    //$query = "SELECT * FROM Users WHERE Username = '$username'";
    // build statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT Username FROM User WHERE Username=?"))
    {
        echo "Error preparing statement: \n";
        print_r($stmt->error_list);
        exit;
    }

    // bind parameters
    if (!$stmt->bind_param('s', $username))
    {
        echo "Error binding parameters: \n";
        print_r($stmt->error_list);
        exit;
    }
    
    // Run the query
    //$result = $mysqli->query($query);
    // execute statement
    $stmt->execute();
    // get result
    $result = $stmt->get_result();
    
    // fail if username is already in database
    if ($result->num_rows != 0)
    {
        $error = "Username $username is already in use.";
        include '../createUser.php';
        exit;
    }

    // Build query email
    if (!$stmt->prepare("SELECT Email FROM User WHERE Email=?"))
    {
        echo "Error preparing statement: \n";
        print_r($stmt->error_list);
        exit;
    }

    // bind parameters
    if (!$stmt->bind_param('s', $email))
    {
        echo "Error binding parameters: \n";
        print_r($stmt->error_list);
        exit;
    }

    // Run the query
    //$result = $mysqli->query($query);
    // execute statement
    $stmt->execute();
    // get result
    $result = $stmt->get_result();

    // fail if email is already in database
    if ($result->num_rows != 0)
    {
        $error = "Email $email is already in use.";
        include '../createUser.php';
        exit;
    }
    
    // close result and statement
    $result->close();
    $stmt->close();
    
    // build new statement to insert new user in Users table
    $stmt_users = $db->stmt_init();

    // prepare
    if (!$stmt_users->prepare("INSERT INTO User (UserID, FirstName, LastName, Email, Username, Password) VALUES (?,?,?,?,?,?)"))
    {
        echo "Error preparing INSERT statement: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    // bind parameters to new statement
    if (!$stmt_users->bind_param('isssss', $userid, $firstname, $lastname, $email, $username, $password_hashed))
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
        
/*
    // log in the user and makeLog
    $_SESSION['name'] = $username;
    $_SESSION['loggedin'] = true;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;

//  Log Table?        
//  makeLog("CreateUser");
*/

    // createUser success
    $error = "Account created with Username '$username'.";
    // Redirect page for createUser success
//    include '../createUser.php';
    header("Location: ../login.php");
}
?>
