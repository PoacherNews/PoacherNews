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
        echo "<th>$field->name</th>";
    }
    echo "<th>Draft</th>";
    echo "<th>Pending</th>";
    echo "<th>Approved</th>";
//    echo "<th>Submit</th>";
    echo "<tr>\n";
    echo "</thead>\n<tbody>\n";
    // get row as an array
    while ($row = $result->fetch_assoc())
    {
        echo "<tr>\n";

        foreach ($row as $key => $r)
        {
            if($key != 'IsPublished'){
            echo '<td>';
            if ($key == 'Headline') 
            echo "<a href='util/editArticle.php?Headline=$r'>";
            echo $r;
            if ($key == 'Headline')
                echo '</a>';
            echo '</td>'; 
            }
            
            foreach ($row as $nextKey => $nextR)
            {
                if($key=='IsDraft' && $r==1)
                {
                    // DRAFT
                    if($nextKey=='IsPublished' && $nextR==0)
                    {
                        echo '<td>';
                        echo $nextR;
                        echo '</td>';
                        //echo "<td>Draft</td>";
                        echo "<td style='text-align:center;'><i class='fa fa-circle' style='color:red'></i></td>";
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    // PENDING
                    else if($nextKey=='IsPublished' && $nextR==1)
                    {
                        echo '<td>';
                        echo $nextR;
                        echo '</td>';
                        echo '<td></td>';
                        //echo"<td>Pending</td>";
                        echo "<td style='text-align:center;'><i class='fa fa-circle' style='color:yellow'></i></td>";
                        echo '<td></td>';
                    }
                }
                
                if($key=='IsDraft' && $r==0)
                {
                    // ERROR
                    if($nextKey=='IsPublished' && $nextR==0)
                    {
                        echo '<td>';
                        echo $nextR;
                        echo '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        //echo "<td>Error</td>";
                        echo "<td style='text-align:center;'><i class='fa fa-remove' style='color:black'></i></td>";
                    }
                    // APPROVED
                    else if($nextKey=='IsPublished' && $nextR==1)
                    {
                        echo '<td>';
                        echo $nextR;
                        echo '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        //echo"<td>Approved</td>";
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
    $query = "SELECT ArticleID, Headline, Category, IsDraft, IsPublished FROM Article";
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
            <h1>Manage Articles</h1>
            <div>
				<?php list_articles(); ?>
            </div>
        </main>
        <?php include 'includes/footer.html'; ?>
    </body>
</html>
