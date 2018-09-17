<?php
session_start();

// TODO:
// whitespaces check
// case sensitivity check
// send email upon account creation?
//
// SECURTIY -
// https://www.dreamhost.com/blog/php-security-user-validation-sanitization/
// https://stackoverflow.com/questions/134099/are-pdo-prepared-statements-sufficient-to-prevent-sql-injection
// https://stackoverflow.com/questions/5741187/sql-injection-that-gets-around-mysql-real-escape-string?rq=1
 
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
    $loggedin = false;
} else { // The user is already logged in, so send them back to the index
    //print "You are already logged in."; //DEBUG
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
 
// terms of agreement   
if(!isset($_POST['checkbox']))
{
    $error = "You must agree to the Terms of Service";
    include '../createUser.php';
    exit;
}    
     
// username restrictions
// prevent special characters?
    // minimum length
    if(!preg_match('/^.{4,}+$/', $username))
    {
        $error = "Username must be at least 4 characters";
        include '../createUser.php';
        exit;
    }
     
// password restrictions
    // Changed to use userUtil function by CS - 8/7
    include('userUtils.php');
    $verifyResult = verifyValidPassword($password);
    if(!($verifyResult === TRUE)) { // If not TRUE, verifyResult will hold a specific error string.
        $error = $verifyResult;
        include '../createUser.php';
        exit;
    }
     
// !!REVIEW!!
// https://stackoverflow.com/questions/401656/secure-hash-and-salt-for-php-passwords?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa   
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
 
	$num = mysqli_query($db, "SELECT MAX(UserID) FROM User");
	$row = mysqli_fetch_row($num);
		
    // Set session variables
    $_SESSION['loggedin'] = true;
    $_SESSION['userid'] = $row[0];
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    $_SESSION['usertype'] = 'U';
   
    // createUser success
    // Redirect to index
    header("Location: ../index.php");
}
?>