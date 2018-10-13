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
        if(!in_array($row['Field'], array("IsDraft", "IsSubmitted", "UserID", "Body", "ArticleImage"))) {
            $columnNames[] = $row['Field'];
        }
    }
    // Add our custom fields not in the database.
    $columnNames[] = "State";
    $columnNames[] = "FeaturedType";

    // print table
    $fields = $result->fetch_fields();
    // data-order=\'[[9, "asc"]]\'
    echo '<table id="articleTable">
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
                                case 'ArticleID':
                                    echo '<td>';
                                        echo '<a href="/util/editArticle.php?ArticleID='.$col.'">'.$col."</a>";
                                    echo '</td>';
                                    break;
                                case 'CommentsEnabled':
                                    echo '<td>';
                                        echo ($col == 0 ? 'No' : 'Yes');
                                    echo '</td>';
                                    break;
                                case 'IsDraft':
                                        if($col == 0 && $row['IsSubmitted'] == 1) { // Approved
                                            print "<td><span style='display: none'>1</span><i class='fa fa-circle' style='color:green'></i></td>";
                                        } else if($col == 1 && $row['IsSubmitted'] == 0) { // Draft
                                            print "<td><span style='display: none'>2</span><i class='fa fa-circle' style='color:red'></i></td>";
                                        } else if($col == 1 && $row['IsSubmitted'] == 1) { // Pending
                                            print "<td><span style='display: none'>3</span><i class='fa fa-circle' style='color:yellow'></i></td>";
                                        }
                                    break;
                                case 'IsSubmitted':
                                    // Do nothing. This field is checked by the 'IsDraft' code in this switch.
                                    break;

                                case 'FeaturedType':
                                    print '<td>';
                                        echo ($col ? $col : '-');
                                    print '</td>';
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

// displays Articles as a table
function list_articles()
{
    include 'util/db.php';
    // query Users
    $query = "SELECT Article.ArticleID, Headline, Category, PublishDate, Views, ArticleRating, CommentsEnabled, IsDraft, IsSubmitted, FeaturedType FROM Article LEFT JOIN Featured ON Featured.ArticleID = Article.ArticleID ORDER BY FeaturedType DESC, ArticleID DESC";
    // display
    display_table($db, $query, "Article");
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

        #articleTable {
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
            $toolsTab = 'articlemanagement';
            include 'includes/toolsNav.php';
        ?>
        <h1>Article Management</h1>
		<?php list_articles(); ?>
    </div>
    <script>
        $(document).ready( function () {
            $("#articleTable").DataTable({
                "order" : [[8, "desc"], [0, "desc"]],
            });
        } );
    </script>
    <?php include 'includes/footer.html'; ?>
</body>
</html>
