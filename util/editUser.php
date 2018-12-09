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
    if (!$stmt->prepare("SELECT * FROM User WHERE UserID =?"))
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
                
<!-- USER -->                
<h2>User Options</h2>
<?php 
if ($data['Usertype'] == 'U' || $data['Usertype'] == 'W' || $_SESSION['username'] == 'testAdmin') { ?>
<form method="post" action="">

<legend>Usertype</legend>
<div>
        <select name="role">
            <option value="U">User</option>
            <option value="W">Writer</option>
            <option value="A">Admin</option>
        </select>   
</div>

<div>
<input type="submit" name="submit" id="submit" value="Submit" />
</div>
</form>

<?php }
else if($data['Usertype'] == 'A') { 
echo "You must have persmissions to edit this user";
 } ?>

<?php
if(isset($_POST['submit'])) { 
$selected_option = $_POST['role'];
$query = "UPDATE User SET Usertype = '".$selected_option."' WHERE UserID = ?";

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
        
<!-- ARTICLES -->
<h2>Articles</h2>
<?php
    $articles = getArticlesByUserID($data['UserID'], $db);

    if($articles === NULL) {
        echo "No articles";
    }   
                
    $nullCheck = array();
    // Store all CommentText into array $nullCheck
    foreach($articles as $r) {
        $nullCheck[] = $r['Headline'];
    }
    
    // If all CommentText in $nullCheck is NULL            
    if(!array_filter($nullCheck)) {
        echo "No articles";
    } else { // If there is CommentText in $nullCheck that is not NULL
    // Sort comments by DESC
    foreach ($articles as $key => $r) {
        $sort[$key] = strtotime($r['Headline']);
    }
    array_multisort($sort, SORT_DESC, $articles);
                       
        echo "<table>";
        echo "<tr><th>Headline</th></tr>";
            
        foreach ($articles as $key => $r) 
        {
            if($r['Headline'] != NULL)
            {
                echo "<tr>";
                echo "<td>";
                echo "<a href='/util/editArticle.php?ArticleID={$r['ArticleID']}'>";
                echo "<p>{$r['Headline']}</p>";
                echo "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
} ?> 

<!-- COMMENTS -->
<h2>Comments</h2>
<?php
    $comments = getUserComments($data['UserID'], null, $db);

    if($comments === NULL) {
        echo "No comments";
    }   
                
    $nullCheck = array();
    // Store all CommentText into array $nullCheck
    foreach($comments as $r) {
        $nullCheck[] = $r['CommentText'];
    }
    
    // If all CommentText in $nullCheck is NULL            
    if(!array_filter($nullCheck)) {
        echo "No comments";
    } else { // If there is CommentText in $nullCheck that is not NULL
    // Sort comments by DESC
    foreach ($comments as $key => $r) {
        $sort[$key] = strtotime($r['CommentDate']);
    }
    array_multisort($sort, SORT_DESC, $comments);
                       
        echo "<table>";
        echo "<tr><th>CommentText</th><th>Delete</th></tr>";
            
        foreach ($comments as $key => $r) 
        {
            if($r['CommentText'] != NULL)
            {
                echo "<tr>";
                echo "<td>";
                echo "<a href='/util/editComment.php?CommentID={$r['CommentID']}'>";
                echo "<p>{$r['CommentText']}</p>";
                echo "</td>";
                echo "<td>";
        ?>
                <form method="post" action="">
                <div>
                    <input type="checkbox" name="deleteCheckbox[]" class="deleteComment" value="<? echo $r['CommentID'] ?>"/>
                </div>

            <?php
                echo "</td>";
                echo "</tr>";
            }
        }
                echo "</table>";
            ?>
            <div>     
                <input type="submit" name="deleteSubmit" id="submit" value="Submit"/>
            </div>
            </form>
<?php } ?> 
<?php
if(!isset($_POST['deleteCheckbox']) && isset($_POST['deleteSubmit'])) {
        echo "Please select a comment to delete.";
}
else if(isset($_POST['deleteCheckbox']) && isset($_POST['deleteSubmit'])){ 
    $selected_check = $_POST['deleteCheckbox'];
    
    for ($i = 0; $i < count($selected_check); $i++) {
        $selected = $selected_check[$i];
        $query = "UPDATE Comment SET CommentText = NULL WHERE CommentID = '".$selected."' AND UserID = ?";

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
    }
    // done
    $stmt->close();
    $db->close();
}
?>
                
<h2>Delete</h2>
<!-- DELETE -->            
<form method="post" action="">
    <div>
        <input type="radio" name="deleteRadio" class="deleteRadio" value="0" /><label>DELETE USER</label><br />
        <input type="checkbox" name="deleteConfirm" class="deleteConfirm" value="Confirm"/><label>CONFIRM DELETE</label>
    </div>

    <div>
        <input type="submit" name="deleteSubmit" class="deleteSubmit" value="Submit" />
    </div>
</form>

<?php
if(!isset($_POST['deleteRadio']) && isset($_POST['deleteConfirm'])) {
    echo "PLEASE CONFIRM DELETION OF USER";
} 
else if(isset($_POST['deleteRadio'])) {
    if(!isset($_POST['deleteConfirm'])) {
        echo "PLEASE CONFIRM DELETION OF USER";
    }
    else {
        $selected_radio = $_POST['deleteRadio'];
    
        $query = "DELETE FROM User WHERE UserID = ?";
        
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

        // Refresh
        echo "User successfully deleted. You will be redirected momentarily..";
        echo '<meta http-equiv="refresh" content="3; url=/userManagement.php">';
        exit;
    }
} ?>      
            </div>
        </div>
        <?php include '../includes/footer.html'; ?>
    </body>
</html>