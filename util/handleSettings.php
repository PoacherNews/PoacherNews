<?php
// Check for special chars?
// Errors for both cases?
include 'loginCheck.php';

//echo "at the top";
$action = empty($_POST['action']) ? '' : $_POST['action'];
//echo $action;
if ($action == 'updateName') 
{
    handle_name();
}
else if ($action == 'updateLocation')
{
    handle_location();
}
else
{
    new_form();
}

function new_form()
{
    //$username = "";
    $errorName = "";
    $errorLocation = "";
    include '../settings.php';
    exit;
}

function handle_name() 
{
    // get all vars from form
    $firstName = empty($_POST['firstName']) ? '' : $_POST['firstName'];
    $lastName = empty($_POST['lastName']) ? '' : $_POST['lastName'];

    // connection to database
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $firstName = $db->real_escape_string($firstName);
    $lastName = $db->real_escape_string($lastName);
    
    // current name check
    if(strcmp($firstName, $_SESSION['firstname']) == 0)
    {
        $errorName = "Your first name is already set to $firstName";
        include '../settings.php';
        exit;
    }
    if(strcmp($lastName, $_SESSION['lastname']) == 0)
    {
        $errorName = "Your last name is already set to $lastName";
        include '../settings.php';
        exit;
    }
    
    // empty fields check
    if (empty($firstName) && empty($lastName))
    {
        $errorName = "Fields cannot be empty";
        include '../settings.php';
        exit;
    }
    if (!empty($firstName) && empty($lastName))
    {
        $lastName = $_SESSION['lastname'];
    }
    if (empty($firstName) && !empty($lastName))
    {
        $firstName = $_SESSION['firstname'];
    }

    // whitespace check
    if (preg_match('/\\s/', $firstName))
    {
        $errorName = "Spaces not allowed in first name $firstName";
        include '../settings.php';
        exit;
    }
    if (preg_match('/\\s/', $lastName))
    {
        $errorName = "Spaces not allowed in last name $lastName";
        include '../settings.php';
        exit;    
    }
    
    // Have user capitalize first char?
    // first char capitalization check
/*
    if(strcmp($firstName, ucfirst($firstName)) != 0)
    {
        $errorName = "First name $firstName must be capitalized";
        include '../settings.php';
        exit;       
    }
    if(strcmp($lastName, ucfirst($lastName)) != 0)
    {
        $errorName = "Last name $lastName must be capitalized";
        include '../settings.php';
        exit;       
    }
*/
    // build new statement to insert new user in Users table
    $stmt_users = $db->stmt_init();
    // prepare
    if (!$stmt_users->prepare("UPDATE User SET FirstName = '".$firstName."', LastName = '".$lastName."' WHERE Username = ?"))
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
    $_SESSION['firstname'] = $firstName;
    $_SESSION['lastname'] = $lastName;
    
    // updateName success
    $errorName = "Name successfully updated";
    include '../settings.php';
}

function handle_location() 
{
    // get all vars from form
    $city = empty($_POST['city']) ? '' : $_POST['city'];

    // connection to database
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $city = $db->real_escape_string($city);

    $selected_option = $_POST['state'];
    // print test
    $errorLocation = "Print test. City = $city, State = $selected_option";
    
/*
    // build new statement to insert new user in Users table
    $stmt_users = $db->stmt_init();
    // prepare
    if (!$stmt_users->prepare("UPDATE User SET City = '".$city."', State = '".$selected_option."' WHERE Username = ?"))
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
    
    // updateLocation success
    $errorLocation = "Location successfully updated";
*/
    include '../settings.php';
}
?>
