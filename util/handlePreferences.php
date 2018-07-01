<?php
// Check for special chars?
// Errors for both cases?
include 'loginCheck.php';

//echo "at the top";
$action = empty($_POST['action']) ? '' : $_POST['action'];
//echo $action;
if ($action == 'updateTimeZone') 
{
    handle_timeZone();
}
else
{
    new_form();
}

function new_form()
{
    //$username = "";
    $errorTimeZone = "";
    include '../preferences.php';
    exit;
}

function handle_timeZone() 
{
    // connection to database
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    // http://php.net/manual/en/mysqli.real-escape-string.php
    $city = $db->real_escape_string($city);

    $selected_option = $_POST['timeZone'];
    
    // print test
    $errorTimeZone = "Print test. Time Zone = $selected_option";
    
    $query = "UPDATE User SET TimeZone = '".$selected_option."' WHERE Username = ?";

    // build new statement to insert new user in Users table
    $stmt = $db->stmt_init();
    // prepare
    if (!$stmt->prepare($query))
    {
        echo "Error preparing INSERT statement: \n";
        echo nl2br(print_r($stmt_users->error_list, true), false);
        exit;
    }
    // bind parameters to new statement
    if (!$stmt->bind_param('s', $_SESSION['username']))
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
    // done
    $stmt->close();

    // $_SESSION UPDATE
    $_SESSION['timezone'] = $selected_option;
    
    // updateTimeZone success
    $errorTimeZone = "Time Zone successfully updated to $selected_option";

    include '../preferences.php';
}
?>
