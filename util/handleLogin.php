<?php
// http://us3.php.net/manual/en/function.session-start.php
if(!session_start()) {
    // If the session couldn't start, present an error
    print "Error: Unable to start session.";
//     header("Location: error.php");
    exit;
}

// Check to see if the user has already logged in
if(empty($_SESSION['loggedin'])) {
    $loggedIn = false;
} else { // The user is already logged in, so send them back to the index
    print "You are already logged in."; //DEBUG
//     header("Location: index.php");
    exit;
}

if(isset($_POST['submit'])) {
    handle_login();
}

function handle_login() {
    print "Beginning login handling..<br>"; //DEBUG
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];

    // Connect to the database
    require_once('db.php');

    if($db->connect_error) {
        $error = 'Error: ' . $db->connect_errno . ' ' . $db->connect_error;
        print $error; //DEBUG
        exit;
    }
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $username = $db->real_escape_string($username);
    $password = $db->real_escape_string($password);

    // Build query: "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    // Prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT * FROM Users WHERE Username = ?"))
    {
        echo "Error preparing statement: <br>";
        nl2br(print_r($stmt->error_list, true), false);
        exit;
    }
    // Bind parameters
    if (!$stmt->bind_param('s', $username))
    {
        echo "Error binding parameters: <br>";
        nl2br(print_r($stmt->error_list, true), false);
        exit;
    }

    // Execute statement
    $stmt->execute();
    // Get result
    $result = $stmt->get_result();

    // Fail if username doesn't match
    if ($result->num_rows != 1)
    {
        $error = "Username does not exist<br>";
        print $error; //DEBUG
//         include 'loginForm.php';
        exit;
    }
    
    // get row from result
    $row = $result->fetch_assoc();
      
    // Close result and Statement
    $result->close();
    $stmt->close();

    if (!password_verify($password, $row['Password']))
    {
        $error = "Password does not match // ";
        print $error; //DEBUG
        print "provided pass: ".$password."<br>";
//         include 'loginForm.php';
        exit;
    } else {
        print "Good password<br>"; //DEBUG
    }
        
    // Put user details into session
    $_SESSION['loggedin'] = true;
    $_SESSION['userid'] = $row['UserID'];
    $_SESSION['firstname'] = $row['FirstName'];
    $_SESSION['lastname'] = $row['LastName'];
    $_SESSION['email'] = $row['Email'];
    $_SESSION['username'] = $row['Username'];
    $_SESSION['usertype'] = $row['Usertype'];
    // redirect
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
?>
