<?php
include 'util/loginCheck.php';
// quit if not an admin or not logged in
if (!$loggedin || !($_SESSION['usertype'] == 'A'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an administrator. Redirecting in 1 second...";
    echo '<meta http-equiv="refresh" content="1; url=index.php">';
    exit;
}

function display_table($db, $query, $tablename)
{
    // execute query
    if (!$result = $db->query($query))
    {
        echo "Error executing statement: <br>";
        echo $db->error;
        return;
    }
    // print table
    $fields = $result->fetch_fields();
    echo "\n<table>\n";
    echo "<caption>$tablename in DB</caption>\n";
    echo "<thead>\n";
    echo "<tr>\n";
    foreach ($fields as $field)
    {
        echo "<th>$field->name</th>";
    }
    echo "<tr>\n";
    echo "</thead>\n<tbody>\n";
    // get row as an array
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>\n";
        foreach ($row as $key => $r)
        {
            echo '<td>';
            if ($key == 'Username')
                echo "<a href='util/editUser.php?Username=$r'>";
            echo $r;
            if ($key == 'Username')
                echo '</a>';
            echo '</td>';
        }	
        echo "</tr>\n";
    }
    // close table
    echo "</tbody>\n</table>\n";
    $result->free();
}

// displays Users as a table
function list_users()
{
    include 'util/db.php';
    // query Users
    $query = "SELECT UserID, FirstName, LastName, Email, Username, Usertype FROM User";
    // display
    display_table($db, $query, "Users");
    // done
    $db->close();
}
?>
    
<!DOCTYPE html>
<html lang="en">
    <head>
	   <?php include 'includes/globalHead.html' ?>
    </head>

<style>
h1 {
	text-align: center;
}

table {
	margin-left:auto;
	margin-right:auto;
	border-collapse:collapse;
}

table, th, td {
	border: 1px solid black;
}
</style>

    <body>
        <?php 
	    	include 'includes/header.php';
            include 'includes/nav.php';
        ?>
        <main>
            <h1>Manage Users</h1>
	<div>
<?php
list_users();
?>
            </div>
        </main>
        <?php include 'includes/footer.html'; ?>
    </body>
</html>
