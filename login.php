<?php
// TODO:
// login redirect if loggedin
// Link error.php
// Review secureConnection.php
//
//    require_once ('includes/secureConnection.php');

// http://us3.php.net/manual/en/function.session-start.php
if(!session_start()) {
    // If the session couldn't start, present an error
    header("Location: error.php");
    //header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit;
}
// Check to see if the user has already logged in
//not sure what the ? : line means but it works (ternary operator)
$loggedIn = empty($_SESSION['loggedin']) ? false : $_SESSION['loggedin'];
//if they are logged in, send to the desired page
if ($loggedIn) {
    header("Location: successPage.php");
    exit;
}
//here it checks if there was an action specified, which it should be from the login_form.php
$action = empty($_POST['action']) ? '' : $_POST['action'];
//here it looks to see if the login form was loaded, since the login_form will sneakily set the action to 'do_login'
if ($action == 'do_login') {
    handle_login();
} else {
    //if not loaded, call functino to load form
    login_form();
}
//include "../DB.php";
function handle_login() {
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];

    // Connect to the database
    require_once ('util/db.php');

    if($db->connect_error) {
        $error = 'Error: ' . $db->connect_errno . ' ' . $db->connect_error;
        require "loginForm.php";
        exit;
    }
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $username = $db->real_escape_string($username);
    $password = $db->real_escape_string($password);

    // Build query
    //$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    // prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT FirstName, LastName, Email, Username, Password, Usertype FROM Users WHERE Username =?"))
    {
        echo "Error preparing statement: <br>";
        nl2br(print_r($stmt->error_list, true), false);
        exit;
    }
    // bind parameters
    if (!$stmt->bind_param('s', $username))
    {
        echo "Error binding parameters: <br>";
        nl2br(print_r($stmt->error_list, true), false);
        exit;
    }
    //$result = $db->query($query);
    // execute statement
    $stmt->execute();
    // get result
    $result = $stmt->get_result();

    // fail if username doesn't match
    if ($result->num_rows != 1)
    {
        $error = "Username does not exist";
        include 'loginForm.php';
        exit;
    }
    
    // get row from result
    $row = $result->fetch_assoc();
    
    // get Username and hashed password into variables
    $firstname = $row['FirstName'];
    $lastname = $row['LastName'];
    $email = $row['Email'];
    $username = $row['Username'];
    $passwordHash = $row['Password'];
    $usertype = $row['Usertype'];

    // close result
    $result->close();
    // close statement
    $stmt->close();
    // close database connection
    $db->close();


    if (!password_verify($password, $passwordHash))
    {
        $error = "Password does not match";
        include 'loginForm.php';
        exit;
    }
    
    // put login into session
    $_SESSION['loggedin'] = true;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    $_SESSION['usertype'] = $usertype;
    // redirect
    header('Location: index.php');
    exit;
}
function login_form()  {
    $username = "";
    $error = "";
    require "loginForm.php";
    exit;
}
?>
