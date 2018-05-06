<?php
include 'loginCheck.php';
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
            echo '<td>';
            if ($key == 'Headline') 
		echo "<a href='util/editArticle.php?Headline=$r'>";
            echo $r;
            if ($key == 'Headline')
                echo '</a>';
            echo '</td>'; 
        }
	// PENDING
	if($r==0){
	    echo "<td style='text-align:center;'><i class='fa fa-circle' style='color:red'></i></td>";
	}
	else {
//            echo "<td style='text-align:center;'><input type='checkbox' name='check_list[]' id='pending' value='0'></td>";
	echo"<td></td>";
	}
	// APPROVED
        if($r==1){
            echo "<td style='text-align:center;'><i class='fa fa-check' style='color:green'></i></td>";
        }
        else {
//            echo "<td style='text-align:center'><input type='checkbox' name='check_list[]' id='approved' value='1'></td>";
		echo"<td></td>";
        }
	// SUBMIT
//	    echo "<td>    <input type='submit' name='submit'/> </td>";	
        
        echo "</tr>\n";
    }
    // close table
    echo "</tbody>\n</table>\n";
	echo "</form>";
//    $result->free();

//if(isset($_POST['submit'])){ 
//$selected_radio = $_POST['checkbox'];
//$selected_radio = $_POST['status'];
//echo "$selected_radio"; 
//}

}

// displays Users as a table
function list_users()
{
    include 'util/db.php';
    // query Users
    $query = "SELECT ArticleID, Headline, Category, IsPublished FROM Articles";
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
<?php
list_users();
?>
            
            
            </div>
        </main>
        <?php include 'includes/footer.html'; ?>
    </body>
</html>
