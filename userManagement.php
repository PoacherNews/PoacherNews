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

    // Get column names
    if(!$cols = $db->query("SHOW COLUMNS FROM {$tablename}")) {
        echo "Error executing statement: <br>";
        echo $db->error;
        return;
    }
    while($row = $cols->fetch_array()) {
        if(!in_array($row['Field'], array("Password", "ProfilePicture", "Bio", "City", "TimeZone", "State", "DateFormat", "tfaStatus", "qrCode", "RecoveryCode"))) {
            $columnNames[] = $row['Field'];
        }
    }

    // print table
    $fields = $result->fetch_fields();
    // data-order=\'[[9, "asc"]]\'
    echo '<table id="userTable">
            <thead>
                <tr>';
                    foreach ($columnNames as $col) {
                        echo '<th>'.$col.'</th>';
                    }
    echo '      </tr>
            </thead>
            <tbody>';
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                        foreach($row as $key => $col) {
                            switch($key) {
                                case 'UserID':
                                    echo '<td>';
                                        echo '<a href="/util/editUser.php?UserID='.$col.'">'.$col."</a>";
                                    echo '</td>';
                                    break;
                                default:
                                    echo '<td>'.$col.'</td>';
                                    break;
                            }
                        }
                    echo '</tr>';
                }
    echo    '</tbody>
        </table>';
    $result->free();
}

// displays Users as a table
function list_users()
{
    include 'util/db.php';
    // query Users
    $query = "SELECT UserID, FirstName, LastName, Email, Username, Usertype FROM User ORDER BY UserID DESC";
    // display
    display_table($db, $query, "User");
    // done
    $db->close();
}
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/globalHead.html' ?>
    <link rel="stylesheet" href="res/css/tools.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <style>
        h1 {
            text-align: center;
        }
        .pageContent {
            margin: auto 8% 75px 8%;
        }

        #userTable {
            margin: 0px auto 0px auto;
            border-collapse: collapse;
        }

        table a {
            font-weight: bold;
        }
        table td {
            border-bottom: 1px solid black;
            text-align: center;
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
    <script>
        $(document).ready( function () {
            $("#userTable").DataTable({
            //    "order" : [[8, "desc"], [0, "desc"]],
            });
        } );
    </script>
    <?php include 'includes/footer.html'; ?>
</body>
</html>
