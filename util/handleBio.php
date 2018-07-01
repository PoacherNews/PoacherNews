<?php
    session_start();   

    // connection to database
    include 'db.php';
    // Check connection
    if ($db->connect_error)
    {
       die("Connection failed: " . $db->connect_error);
    }

    // set $bio var to ajax data text
    $bio =$_POST['text'];
    //echo $test;
    //$query="UPDATE User SET Bio = '".$bio."' WHERE UserID = '".$_SESSION['userid']."'"; 

    // build new statement to insert new bio in Users table
    $stmt = $db->stmt_init();
    // prepare
    if (!$stmt->prepare("UPDATE User SET Bio = '".$bio."' WHERE UserID = ?"))
    {
        echo "Error preparing INSERT statement: \n";
        echo nl2br(print_r($stmt->error_list, true), false);
        exit;
    }
    // bind parameters to new statement
    if (!$stmt->bind_param('i', $_SESSION['userid']))
    {
        echo "Error binding parameters to INSERT: \n";
        echo nl2br(print_r($stmt->error_list, true), false);
        exit;
    }
    //print_r($result);

    // execute statement
    if (!$stmt->execute())
    {
        echo "Error INSERTing: \n";
        echo nl2br(print_r($stmt->error_list, true), false);
        exit;
    }

    $stmt->close();
    $db->close();
    
    // $_SESSION UPDATE
    $_SESSION['Bio'] = $bio;  
?>


