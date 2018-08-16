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
            if($key == 'UserID')
            {
                $userid = $r;
            }
            
            echo '<td>';
            if ($key == 'UserID')
            {
                echo "<a href='util/editUser.php?UserID=$userid'>";
            }
            echo $r;
            if ($key == 'UserID')
            {
                echo '</a>';
            }
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
    <link rel="stylesheet" href="res/css/tools.css">
</head>
<body>
    <style>
        h1 {
            text-align: center;
        }
        table a {
            font-weight: bold;
        }

        table {
            margin: 0px auto;
            border-collapse:collapse;
        }

        table, th, td {
            border: 1px solid black;
        }
        tr:nth-child(even) {
            background-color: #ccc;
        }
    </style>
    <?php 
    	include 'includes/header.php';
        include 'includes/nav.php';
    ?>
    <div class="pageContent">
        <?php
            $toolsTab = 'usermanagement';
            include 'includes/toolsNav.php';
        ?>
        <h1>User Management</h1>
        <?php list_users(); ?>
    </div>
    <?php include 'includes/footer.html'; ?>
</body>
</html>
