<?php
include 'loginCheck.php';
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
    if (!isset($_GET['Headline']))
    {
        echo "Error: No user specified. ";
        return;
    }
 
   // Connect to the database
//    require_once ('util/db.php');
    // prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT ArticleID, Headline, Category, IsPublished  FROM Articles WHERE Headline =?"))
    {
        echo "Error preparing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // bind parameters
    if (!$stmt->bind_param('s', $_GET['Headline']))
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
	   <?php include '../includes/globalHead.html' ?>
        <title><?php echo $data['Headline']; ?> | Edit Article</title>
    </head>
    <body>
        <?php
	    include '../includes/header.php';
            include '../includes/nav.php';
            //include '../includes/footer.html';
        ?>
        <main>
            <h1>Edit Article &#8216;<?php echo $data['Headline']; ?>&#8217;</h1>
            <table>
                <thead>
                    <tr>
                        <th>ArticleID</th>
                        <th>Headline</th>
                        <th>Category</th>
                        <th>IsPublished</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['ArticleID']; ?></td>
                        <td><?php echo $data['Headline']; ?></td>
                        <td><?php echo $data['Category']; ?></td>
                        <td><?php echo $data['IsPublished']; ?></td>
                    </tr>
                </tbody>
            </table>
            <h2>Article Options</h2>


<form method="post" action="">

<legend>IsPublished</legend>
<div>
<input type="radio" name="status" id="pending" value="0" /><label for="pending">Pending</label><br />
<input type="radio" name="status" id="approved" value="1" /><label for="approved">Approved</label><br />
</div>

<div>
<input type="submit" name="submit" id="submit" value="Submit" />
</div>
</form>

<form action="../articleManagement.php">
	<input type="submit" value="Article Management" />
</form>

<?php 
if(isset($_POST['submit'])){ 
$selected_radio = $_POST['status'];
$query = "UPDATE Articles SET IsPublished = '".$selected_radio."' WHERE Headline = ?";

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
if (!$stmt->bind_param('s', $data['Headline']))
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
        <?php include '../includes/footer.html'; ?>
    </body>
</html>

