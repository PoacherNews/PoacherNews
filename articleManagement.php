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
    echo "<form method='post' action=''>";
    echo "\n<table>\n";
    echo "<caption>$tablename in DB</caption>\n";
    echo "<thead>\n";
    echo "<tr>\n";
    foreach ($fields as $field)
    {
        if($field->name != 'IsDraft')
        {
            if($field->name != 'IsSubmitted')
            {
                echo "<th>$field->name</th>";
            }
            else if($field->name == 'IsSubmitted')
            {
                echo "<th>Article State</th>";
            }
        }
    }
    echo "<th>Pending</th>";
    echo "<th>Approved</th>";
    echo "<th>FeaturedType</th>";
    echo "<tr>\n";
    echo "</thead>\n<tbody>\n";
    // get row as an array
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>\n";
        foreach ($row as $key => $r)
        {
            if($key == 'ArticleID')
            {
                $articleid = $r;
            }
            
            if($key != 'IsDraft')
            {
                if($key != 'IsSubmitted')
                {
                    echo '<td>';
                    if ($key == 'ArticleID') 
                    {
                        echo "<a href='util/editArticle.php?ArticleID=$articleid'>";
                    }
                    echo $r;
                    if ($key == 'ArticleID')
                    {
                        echo '</a>';
                    }
                    echo '</td>'; 
                }
            }
            
            foreach ($row as $nextKey => $nextR)
            {
                if($key=='IsDraft' && $r==1)
                {
                    // DRAFT
                    if($nextKey=='IsSubmitted' && $nextR==0)
                    {
                        echo "<td>Draft</td>";
                        echo "<td style='text-align:center;'><i class='fa fa-circle' style='color:red'></i></td>";
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    // PENDING
                    else if($nextKey=='IsSubmitted' && $nextR==1)
                    {
                        echo "<td>Pending</td>";
                        echo '<td></td>';
                        echo "<td style='text-align:center;'><i class='fa fa-circle' style='color:yellow'></i></td>";
                        echo '<td></td>';
                    }
                }
                
                if($key=='IsDraft' && $r==0)
                {
                    // ERROR
                    if($nextKey=='IsSubmitted' && $nextR==0)
                    {
                        echo "<td>Error</td>";
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo "<td style='text-align:center;'><i class='fa fa-remove' style='color:black'></i></td>";
                    }
                    // APPROVED
                    else if($nextKey=='IsSubmitted' && $nextR==1)
                    {
                        echo"<td>Approved</td>";
                        echo '<td></td>';
                        echo '<td></td>';
                        echo "<td style='text-align:center;'><i class='fa fa-circle' style='color:green'></i></td>";
                    }
                }
            }
        }
        echo "</tr>\n";
    }
    // close table
    echo "</tbody>\n</table>\n";
	echo "</form>";
    $result->free();
}

// displays Articles as a table
function list_articles()
{
    include 'util/db.php';
    // query Users
    $query = "SELECT Article.ArticleID, Headline, Category, IsDraft, IsSubmitted, FeaturedType AS Draft FROM Article LEFT JOIN Featured ON Featured.ArticleID = Article.ArticleID ORDER BY FeaturedType DESC, ArticleID DESC";
    // display
    display_table($db, $query, "Articles");
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

        table {
            margin: 25px;
            border-collapse:collapse;
        }
        table a {
            font-weight: bold;
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
            $toolsTab = 'articlemanagement';
            include 'includes/toolsNav.php';
        ?>
        <h1>Article Management</h1>
		<?php list_articles(); ?>
    </div>
    <?php include 'includes/footer.html'; ?>
</body>
</html>
