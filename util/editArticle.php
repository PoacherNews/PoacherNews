<?php
// TODO:
// Check featuredType when deleting article

include 'loginCheck.php';
// quit if not logged in or not an admin
if (!$loggedin || !($_SESSION['usertype'] == 'A'))
{
    header("HTTP/1.1 403 Forbidden", true, 403);
    echo "You must be an administrator.";
    echo '<meta http-equiv="refresh" content="1"; url=/index.php">';
    exit;
}

include_once ('db.php');

function getArticleData($db)
{
    if (!isset($_GET['ArticleID']))
    {
        echo "Error: No ArticleID specified.";
        return;
    }
 
	// Connect to the database
	// require_once ('util/db.php');
    // prepare statement
    $stmt = $db->stmt_init();
    if (!$stmt->prepare("SELECT Article.ArticleID, Headline, Category, CommentsEnabled, IsDraft, IsSubmitted, FeaturedType FROM Article LEFT JOIN Featured ON Article.ArticleID = Featured.ArticleID WHERE Article.ArticleID =?"))
    {
        echo "Error preparing statement: <br>";
        echo nl2br(print_r($stmt->error_list, true), false);
        return;
    }
    // bind parameters
    if (!$stmt->bind_param('s', $_GET['ArticleID']))
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
        echo "ArticleID incorrect.";
        return false;
    }
    $row = $result->fetch_assoc();
    $result->free();
    $stmt->close();
    return $row;
}
// get user data as an array
$data = getArticleData($db);
if (!isset($data) || !$data)
    die("ArticleID incorrect or database error.");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	   <?php include '../includes/globalHead.html' ?>
        <link rel="stylesheet" href="/res/css/tools.css">
        <title><?php echo $data['Headline']; ?> | Edit Article</title>
        <style>
            .wrap {
                margin: auto;
                width: 80%;
            }
        </style>
    </head>
    
    <body>
        <?php
            include '../includes/header.php';
            include '../includes/nav.php';
        ?>
        
        <div class="pageContent">
                <?php
                    $toolsTab = 'articlemanagement';
                    include '../includes/toolsNav.php';
                ?>
        <div class="wrap">
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
                        <th>CommentsEnabled</th>
                        <th>IsDraft</th>
                        <th>IsSubmitted</th>
                        <th>Featured State</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['ArticleID']; ?></td>
                        <td><?php echo $data['Headline']; ?></td>
                        <td><?php echo $data['Category']; ?></td>
                        <td><?php echo $data['CommentsEnabled']; ?></td>
                        <td><?php echo $data['IsDraft']; ?></td>
                        <td><?php echo $data['IsSubmitted']; ?></td>
                        <td><?php 
                            if($data['FeaturedType'] == null)
                            {
                                echo "None";
                            }
                            else {
                                echo $data['FeaturedType']; 
                            } ?>
                        </td>
                    </tr>
                </tbody>
            </table>

<h2>Article State</h2>
<!-- ERROR -->
<?php if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 0) { ?>
<form method="post" action="">
    <legend>Error</legend>
    
    <div>
        <select name="errorStatus">
            <option value="0">Set to Error</option>
            <option value="1">Set to Draft</option>
            <option value="2">Set to Pending</option>
            <option value="3">Set to Approved</option>
        </select>
    </div>
    
    <div>
        <input type="submit" name="errorSubmit" class="errorSubmit" value="Submit" />
    </div>
</form>
          
<?php 
if(isset($_POST['errorSubmit']))
{
    $selected_option = $_POST['errorStatus'];
    // SET ERROR
    if($selected_option == 0)
    {
        $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 0 WHERE ArticleID = ?";
    }
    // Refactor  ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0) statements together
    // Add error message
    // ERROR TO DRAFT
    else if($selected_option == 1 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0))
    {
        $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 0 WHERE ArticleID = ?";
    }
    // ERROR TO PENDING
    else if($selected_option == 2 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0))
    {
        $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 1 WHERE ArticleID = ?";
    }
    // ERROR TO APPROVED
    else if($selected_option == 3 && ($data['IsDraft'] == 0 &&   $data['IsSubmitted'] == 0))
    {
        $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 1 WHERE ArticleID = ?";
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
    if (!$stmt->bind_param('s', $data['ArticleID']))
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
<?php } ?>            

<!-- DRAFT -->
<?php if($data['IsDraft'] == 1 && $data['IsSubmitted'] == 0) { ?>
<form method="post" action="">
    <legend>Draft</legend>
    <div>
        <select name="draftStatus">
            <option value="0">Set to Pending</option>
            <option value="1">Set to Approved</option>
        </select>    
        <br>
        <input type="checkbox" name="draftConfirm" class="draftConfirm" value="Confirm"/><label>Confirm draft state change</label>
    </div>

    <div>
        <input type="submit" name="draftSubmit" class="draftSubmit" value="Submit" />
    </div>
</form>
          
<?php 
if(isset($_POST['draftSubmit']))
{
    if(!isset($_POST['draftConfirm']))
    {
        echo"Please confirm draft state change";
    }
    else 
    {
        $selected_option = $_POST['draftStatus'];
        // Fix ERROR
        // ERROR TESTING
        if($data[FeaturedType] != '')
        {
            echo "Error. Featured type must be set to none to continue.";
        }
        else if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 0)
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
            if($selected_option == 0 && ($data['IsDraft'] == 1 && $data['IsPublish'] == 0))
            {
                $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 1 WHERE ArticleID = ?";
            }
            // DRAFT TO APPROVED
            else if($selected_option == 1 && ($data['IsDraft'] == 1 &&   $data['IsSubmitted'] == 0))
            {
                $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 1 WHERE ArticleID = ?";
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
            if (!$stmt->bind_param('s', $data['ArticleID']))
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
<?php } ?>

<!-- PENDING -->
<?php if($data['IsDraft'] == 1 && $data['IsSubmitted'] == 1) { ?>            
<form method="post" action="">
    <legend>Pending</legend>
    <div>
        <select name="pendingStatus">
            <option value="0">Set to Draft</option>
            <option value="1">Set to Approved</option>
        </select>   
    </div>

    <div>
        <input type="submit" name="pendingSubmit" id="pendingSubmit" value="Submit" />
    </div>
</form>

<?php 
if(isset($_POST['pendingSubmit']))
{
    $selected_option = $_POST['pendingStatus'];
    
    // ERROR TESTING
    if($data['FeaturedType'] != '')
    {
        echo "Error. Featured type must be set to none to continue.";
    }
    else if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 0)
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
        //PENDING TO DRAFT
        if($selected_option == 0 && ($data['IsDraft'] == 1 && $data['IsSubmitted'] == 1))
        {
            $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 0 WHERE ArticleID = ?";
        }
        //PENDING TO APPROVED
        if($selected_option == 1 && ($data['IsDraft'] == 1 && $data['IsSubmitted'] == 1))
        {
            $query = "UPDATE Article SET IsDraft = 0, IsSubmitted = 1 WHERE ArticleID = ?";
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
        if (!$stmt->bind_param('s', $data['ArticleID']))
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
} ?>
<?php } ?>            
                 
<!-- APPROVED -->
<?php if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1) { ?>            
<form method="post" action="">
    <legend>Approved</legend>
    <div>
        <select name="approvedStatus">
            <option value="0">Set to Draft</option>
            <option value="1">Set to Pending</option>
        </select>   
    </div>

    <div>
        <input type="submit" name="approvedSubmit" id="approvedSubmit" value="Submit" />
    </div>
</form>

<?php 
if(isset($_POST['approvedSubmit']))
{
    $selected_option = $_POST['approvedStatus'];
    
    // ERROR TESTING
    if($data['FeaturedType'] != '')
    {
        echo "Error. Featured type must be set to none to continue.";
    }
    else if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 0)
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
        //APPROVED TO DRAFT
        if($selected_option == 0 && ($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1))
        {
            $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 0 WHERE ArticleID = ?";
        }
        //APPROVED TO PENDING
        if($selected_option == 1 && ($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1))
        {
            $query = "UPDATE Article SET IsDraft = 1, IsSubmitted = 1 WHERE ArticleID = ?";
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
        if (!$stmt->bind_param('s', $data['ArticleID']))
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
} ?>
<br>
<?php } ?>
            
<!-- COMMENTSENABLED -->
<?php if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1) { ?>
<form method="post" action="">
    <legend>Comments</legend>
    <div>
        <select name="commentStatus">
            <option value="0">Disable Comments</option>
            <option value="1">Enable Comments</option>
        </select>   
    </div>

    <div>
        <input type="submit" name="commentSubmit" id="commentSubmit" value="Submit" />
    </div>
</form>

<?php 
if(isset($_POST['commentSubmit']))
{
    $selected_radio = $_POST['commentStatus'];
    
    // ERROR TESTING

    //DISABLE COMMENTS
    if($selected_option == 0 && $data['CommentsEnabled'] == 1)
    {
        $query = "UPDATE Article SET CommentsEnabled = 0 WHERE ArticleID = ?";
    }
    //ENABLE COMMENTS
    else if($selected_option == 1 && $data['CommentsEnabled'] == 0)
    {
        $query = "UPDATE Article SET CommentsEnabled = 1 WHERE ArticleID = ?";
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
    if (!$stmt->bind_param('s', $data['ArticleID']))
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
?>
<?php } ?>
            
<!------------------------------------------------------------------------->
            
<?php if($data['IsDraft'] == 0 && $data['IsSubmitted'] == 1) { ?>
<h2>Featured State</h2>
<!-- FeaturedType -->
<form method="post" action="">
    <legend>Featured Type</legend>
    <div>
        <select name="featuredStatus">
            <option value="0">None</option>
            <option value="1">Main</option>
            <option value="2">EditorPick</option>
        </select>   
    </div>

    <div>
        <input type="submit" name="featuredSubmit" id="featuredSubmit" value="Submit" />
    </div>
</form>

<?php 
if(isset($_POST['featuredSubmit']))
{
    $selected_option = $_POST['featuredStatus'];
    
    if(($data['IsDraft'] == 1 && $data['IsSubmitted'] == 0) || $data['IsDraft'] == 1 && $data['IsSubmitted'] == 1)
    {
        echo "Error. Article must first be approved to change feature type.";
    }
    else 
    {
        // NONE
        if($selected_option == 0)
        {
            $query = "DELETE FROM Featured WHERE ArticleID = ?";
        }
        // MAIN
        if($selected_option == 1 && $data['FeaturedType'] == null)
        {
            $sql = "SELECT * FROM Featured WHERE FeaturedType = 'Main'";
            $result = $db->query($sql);
            if($result->num_rows != 0)
            {
                echo "Error. Main article is already set.";
                exit;
            }
            else {
                $query = "INSERT INTO Featured (FeaturedType, ArticleID) VALUES ('Main', ?)";
            }
        }
        else if($selected_option == 1 && $data['FeaturedType'] == 'EditorPick')
        {
            $sql = "SELECT * FROM Featured WHERE FeaturedType = 'Main'";
            $result = $db->query($sql);
            if($result->num_rows != 0)
            {
                echo "Error. Main article is already set.";
                exit;
            }
            else {
                $query = "UPDATE Featured SET FeaturedType = 'Main' WHERE ArticleID = ?";
            }
        }
        // EdITOR PICK
        if($selected_option == 2 && $data['FeaturedType'] == null)
        {
            $query = "INSERT INTO Featured (FeaturedType, ArticleID) VALUES ('EditorPick', ?)";
        }
        else if($selected_option == 2 && $data['FeaturedType'] == 'Main')
        {
            $query = "UPDATE Featured SET FeaturedType = 'EditorPick' WHERE ArticleID = ?";
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
        if (!$stmt->bind_param('i', $data['ArticleID']))
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
}?>
<?php } ?>
            
<!------------------------------------------------------------------------->

<h2>Delete</h2>
<!-- DELETE -->            
<form method="post" action="">
    <div>
        <input type="radio" name="deleteStatus" class="deleteRadio" value="0" /><label>DELETE ARTICLE</label><br />
        <input type="checkbox" name="deleteConfirm" class="deleteConfirm" value="Confirm"/><label>CONFIRM DELETE</label>
    </div>

    <div>
        <input type="submit" name="deleteSubmit" class="deleteSubmit" value="Submit" />
    </div>
</form>

<?php 
if(isset($_POST['deleteSubmit']))
{
    if(!isset($_POST['deleteConfirm']))
    {
        echo "PLEASE CONFIRM DELETION OF ARTICLE";
    }
    else 
    {
        $selected_radio = $_POST['deleteStatus'];
    
        $query = "DELETE FROM Article WHERE ArticleID = ?";
        
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
        if (!$stmt->bind_param('i', $data['ArticleID']))
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

        // Refresh
        echo "Article successfully deleted - Redirect to articleManagement still needs to be implemented";
        echo '<meta http-equiv="refresh" content="1; url=/articleManagement.php">';
        exit;
    }
} ?>

            
        </div>
        </div>
        
        <?php include '../includes/footer.html'; ?>
    </body>
</html>

