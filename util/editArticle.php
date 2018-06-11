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
    if (!$stmt->prepare("SELECT ArticleID, Headline, Category, IsDraft, IsSubmitted  FROM Article WHERE Headline =?"))
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
        <link rel="stylesheet" href="../res/css/profile.css">
        <link rel="stylesheet" href="../res/css/profileNav.css">
        <title><?php echo $data['Headline']; ?> | Edit Article</title>
    </head>
    <body>
        <?php
	    include '../includes/header.php';
            include '../includes/nav.php';
            //include '../includes/footer.html';
        ?>
        
        <div class="user">
            <div class="picture">
                (Profile Picture)
            </div>
            
            <div class="info">
                (User Information)
            </div>
        </div>
        
        <div class="nav">
            <?php
                $current = 'manageArticles';
                include '../includes/profileNav.php';
            ?>
        </div>
        
        <div class="display">
        <main>
            <h1>Edit Article &#8216;<?php
            	echo "<a href='/article.php?articleid={$data['ArticleID']}'>"; 
				echo $data['Headline'];
				echo "</a>";  ?>&#8217;</h1>
            <table>
                <thead>
                    <tr>
                        <th>ArticleID</th>
                        <th>Headline</th>
                        <th>Category</th>
                        <th>IsDraft</th>
                        <th>IsSubmitted</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['ArticleID']; ?></td>
                        <td><?php echo $data['Headline']; ?></td>
                        <td><?php echo $data['Category']; ?></td>
                        <td><?php echo $data['IsDraft']; ?></td>
                        <td><?php echo $data['IsSubmitted']; ?></td>
                    </tr>
                </tbody>
            </table>
            <h2>Article Options</h2>

<!-- ERROR -->
<form method="post" action="">
    <legend>Error testing</legend>
    <div>
        <input type="radio" name="status" id="error" value="0" /><label for="error">Set Error</label><br />
        <input type="radio" name="status" id="error" value="1" /><label for="error">Error to Draft</label><br />
        <input type="radio" name="status" id="error" value="2" /><label for="error">Error to Pending</label><br />
        <input type="radio" name="status" id="error" value="3" /><label for="error">Error to Approved</label><br />
    </div>

    <div>
        <input type="submit" name="ErrorSubmit" id="ErrorSubmit" value="Submit" />
    </div>
</form>
          
<?php 
if(isset($_POST['ErrorSubmit']))
{
    $selected_radio = $_POST['status'];
    // SET ERROR
    if($selected_radio == 0)
    {
        $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 0 WHERE Headline = ?";
    }
    // Refactor  ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0) statements together
    // Add error message
    // ERROR TO DRAFT
    else if($selected_radio == 1 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0))
    {
        $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 0 WHERE Headline = ?";
    }
    // ERROR TO PENDING
    else if($selected_radio == 2 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0))
    {
        $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 1 WHERE Headline = ?";
    }
    // ERROR TO APPROVED
    else if($selected_radio == 3 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0))
    {
        $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 1 WHERE Headline = ?";
    }
    
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
}?>
<br>
<br> 

<!-- ISDRAFT -->
<form method="post" action="">
    <legend>IsDraft</legend>
    <div>
        <input type="radio" name="status" id="draft" value="0" /><label for="draft">Draft to Pending</label><br />
        <input type="radio" name="status" id="draft" value="1" /><label for="draft">Pending to Draft</label><br />
        <input type="radio" name="status" id="draft" value="2" /><label for="draft">Approved to Draft</label><br />
        <input type="checkbox" name="confirm" id="confirm" value="Confirm"/><label>Confirm draft state change</label>
    </div>

    <div>
        <input type="submit" name="DraftSubmit" id="DraftSubmit" value="Submit" />
    </div>
</form>
          
<?php 
if(isset($_POST['DraftSubmit']))
{
    if(!isset($_POST['confirm']))
    {
        echo"Please confirm draft state change";
    }
    else 
    {
        $selected_radio = $_POST['status'];
        // Fix ERROR
        // ERROR TESTING
        if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 0)
        {
            echo "Error. Article is in error state. Please update to continue.";
        }              
        //ERROR
//       else if(($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1))
//       {
//          echo "Error. Article is in approved state. Please update to continue.";
//       }  
        else 
        {
            // DRAFT TO PENDING
            if($selected_radio == 0 && ($data['IsDraft'] == 1 && $data['IsPublish'] == 0))
            {
                $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 1 WHERE Headline = ?";
            }
            // PENDING TO DRAFT
            else if($selected_radio == 1 && ($data['IsDraft'] == 1 &&   $data['IsSubmitted'] == 1))
            {
                $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 0 WHERE Headline = ?";
            }
            // APPROVED TO DRAFT
            else if($selected_radio == 2 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 1))
            {
                $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 0 WHERE Headline = ?";
            }
    
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
        }
    }
}?>
<br>
<br>            
            
<!-- ISPUBLISHED -->            
<form method="post" action="">
    <legend>IsSubmitted</legend>
    <div>
        <input type="radio" name="status" id="published" value="0" /><label for="published">Approved to Pending</label><br />
        <input type="radio" name="status" id="published" value="1" /><label for="published">Pending to Approved</label><br />
    </div>

    <div>
        <input type="submit" name="PublishedSubmit" id="PublishedSubmit" value="Submit" />
    </div>
</form>

<form action="../articleManagement.php">
	<input type="submit" value="Article Management" />
</form>

<?php 
if(isset($_POST['PublishedSubmit']))
{
    $selected_radio = $_POST['status'];
    
    // ERROR TESTING
    if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 0)
    {
        echo "Error. Article is in error state. Please update to continue.";
    }              
    //ERROR
    else if($data['IsDraft'] == 1 && $data['IsSubmitted'] == 0)
    {
        echo "Error. Article is in draft state. Please update to continue.";
    }   
    else 
    {
        //APPROVED TO PENDING
        if($selected_radio == 0 && ($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1))
        {
            $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 1 WHERE Headline = ?";
        }
        //PENDING TO APPROVED
        else if($selected_radio == 1 && ($data['IsDraft'] == 1 && $data['IsSubmitted'] == 1))
        {
            $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 1 WHERE Headline = ?";
        }
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
    }
} ?>

        </main>
        </div>
        
        <?php include '../includes/footer.html'; ?>
    </body>
</html>

