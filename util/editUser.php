<?php

// Changing Users/Writers to Admins permissions
// Changing Admins permissions

include '../loginCheck.php';
// quit if not an admin or not logged in
if (!$loggedin || !($_SESSION['usertype'] == 'A'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an administrator.";
    echo '<meta http-equiv="refresh" content="1; url=/index.php">';
    exit;
}

include_once ('db.php');

function getUserData($db)
{
    if (!isset($_GET['Username']))
    {
        echo "Error: No user specified. ";
        return;
    }
 
   // Connect to the database
//    require_once ('util/db.php');
    // prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT UserID, FirstName, LastName, Email, Username, Usertype FROM Users WHERE Username =?"))
    {
        echo "Error preparing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // bind parameters
    if (!$stmt->bind_param('s', $_GET['Username']))
    {
        echo "Error binding parameters: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // execute statement
    if (!$stmt->execute())
    {
        echo "Error executing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // get results from query
    if (!$result = $stmt->get_result())
    {
        echo "Error getting result: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    if ($result->num_rows != 1)
    {
        echo "Username incorrect. ";
        return false;
    }
    $row = $result->fetch_assoc();
    $result->free();
    $stmt->close();
    return $row;
}
// get user data as an array
$data = getUserData($db);
if (!isset($data) || !$data)
    die("Username incorrect or database error.");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	   <?php
		include '../includes/globalHead.html' ?>
        <title><?php echo $data['Username']; ?> | Edit User</title>
    </head>
    <body>
        <?php
//include $_SERVER['DOCUMENT_ROOT'].'/sprint3/includes/header.php';
	    include '../includes/header.php';
            include '../includes/nav.php';
            //include '../includes/footer.html';
        ?>
        <main>
            <h1>Edit User &#8216;<?php echo $data['Username']; ?>&#8217;</h1>
            <table>
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Usertype</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['UserID']; ?></td>
                        <td><?php echo $data['FirstName']; ?></td>
                        <td><?php echo $data['LastName']; ?></td>
                        <td><?php echo $data['Email']; ?></td>
                        <td><?php echo $data['Username']; ?></td>
                        <td><?php echo $data['Usertype']; ?></td>
                    </tr>
                </tbody>
            </table>
            <h2>User Options</h2>

<?php 

?>
<?php 
if ($data['Usertype'] == 'U' || $data['Usertype'] == 'W') { ?>
<form method="post" action="">

<legend>Usertype</legend>
<div>
<input type="radio" name="role" id="user" value="U" /><label for="user">User</label><br />
<input type="radio" name="role" id="writer" value="W" /><label for="writer">Writer</label><br />
<input type="radio" name="role" id="admin" value="A" /><label for="admin">Admin</label><br />
</div>

<div>
<input type="submit" name="submit" id="submit" value="Submit" />
</div>
</form>

<form action="../userManagement.php">
        <input type="submit" value="User Management" />
</form>

<?php } ?>

<?php
if($data['Usertype'] == 'A') { 
echo "<br>You must have persmissions to edit this user";
 } ?>

<?php 
if(isset($_POST['submit'])){ 
$selected_radio = $_POST['role'];
$query = "UPDATE Users SET Usertype = '".$selected_radio."' WHERE Username = ?";

// Refresh
echo "<meta http-equiv='refresh' content='0'>";

//include 'util/db.php';
// prepare statement
$stmt = $db->stmt_init();
if (!$stmt->prepare($query))
{
    echo "Error preparing statement: <br>";
    echo nl2br(print_r($stmt->error_list, true), false);
    return;
}
// bind username
if (!$stmt->bind_param('s', $data['Username']))
{
    echo "Error binding parameters: <br>";
    echo nl2br(print_r($stmt->error_list, true), false);
    return;
}
// query database
if (!$stmt->execute())
{
    echo "Error executing query: <br>";
    echo nl2br(print_r($stmt->error_list, true), false);
    return;
}
// done
$stmt->close();
$db->close();
} ?>

        </main>
    </body>
</html>
