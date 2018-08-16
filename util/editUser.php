<?php
// TODO:
// Add error for empty field when submitting - also do the same for editArticle.php
// Changing Users/Writers to Admins permissions
// Changing Admins permissions

include 'loginCheck.php';
include 'userUtils.php';

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
    if (!isset($_GET['UserID']))
    {
        echo "Error: No user specified. ";
        return;
    }
 
   // Connect to the database
//    require_once ('util/db.php');
    // prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT User.UserID, FirstName, LastName, Email, Username, Usertype, CommentText FROM User LEFT JOIN Comment ON User.UserID = Comment.UserID WHERE User.UserID =?"))
    {
        echo "Error preparing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // bind parameters
    if (!$stmt->bind_param('i', $_GET['UserID']))
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
    /*
    if ($result->num_rows != 1)
    {
        echo "Username incorrect.";
        return false;
    }
    */
    $row = $result->fetch_assoc();
    $result->free();
    $stmt->close();
    return $row;
}
// get user data as an array
$data = getUserData($db);
if (!isset($data) || !$data)
    die("UserID incorrect or database error.");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	   <?php
		include '../includes/globalHead.html' ?>
        <link rel="stylesheet" href="../res/css/tools.css"/>
        <title><?php echo $data['Username']; ?> | Edit User</title>
        <style>
            .wrap { margin: auto;
                width: 50%; }
        </style>
    </head>
    
    <body>
        <?php
//include $_SERVER['DOCUMENT_ROOT'].'/sprint3/includes/header.php';
	    	include '../includes/header.php';
            include '../includes/nav.php';
        ?>    

        <div class="pageContent">
            <?php
                $toolsTab = 'usermanagement';
                include '../includes/toolsNav.php';
            ?>
            <div class="wrap">
            <h1>Edit User &#8216;<?php
                echo "<a href='/profile.php?uid={$data['UserID']}'>"; 
				echo $data['Username'];
				echo "</a>"; ?>&#8217;</h1>

            <table>
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Usertype</th>
                        <th></th>
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

<?php } ?>

<?php
if($data['Usertype'] == 'A') { 
echo "<br>You must have persmissions to edit this user";
 } ?>

<?php 
if(isset($_POST['submit'])){ 
$selected_radio = $_POST['role'];
$query = "UPDATE User SET Usertype = '".$selected_radio."' WHERE UserID = ?";

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
if (!$stmt->bind_param('i', $data['UserID']))
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
            
<h2>Comments</h2>
<?php
    $comments = getUserComments($data['UserID'], null, $db);

    	if($comments === null) 
        {
                print "<div class=\"columnError\">No comments yet.</div>";
                return;
        }
            
        // Sort comments by DESC
        foreach ($comments as $key => $r) {
            $sort[$key] = strtotime($r['CommentDate']);
        }
        array_multisort($sort, SORT_DESC, $comments);
            
        foreach ($comments as $key => $r) 
        {
            if($r['CommentText'] != NULL)
            {
                print "<p>{$r['CommentText']}</p>";
        ?>
<form method="post" action="">
<div>
<input type="radio" name="delete" class="deleteComment" value="1" /><label for="delete">Delete</label><br />
</div>

<div>
<input type="submit" name="deleteSubmit[<?php echo $r['CommentID']; ?>]" id="submit" value="Submit" />
</div>
</form>
         
<?php
if(isset($_POST['deleteSubmit'][''.$r['CommentID'].''])){ 
$selected_radio = $_POST['delete'];
$query = "UPDATE Comment SET CommentText = NULL WHERE CommentText = '".$r['CommentText']."' AND UserID = ?";

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
if (!$stmt->bind_param('s', $data['UserID']))
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
}
    }
    }
?>
            </div>
        </div>
        <?php include '../includes/footer.html'; ?>
    </body>
</html>
