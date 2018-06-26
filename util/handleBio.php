<?php
session_start();

    include 'db.php';

    $bio =$_POST['text'];
    //echo $test;
    $sql="UPDATE User SET Bio = '".$bio."' WHERE Username = '".$_SESSION['username']."'"; 
    //echo $sql;
   
if ($db->query($sql) === FALSE) {
    echo "ERROR";
}            
    
    //set session
    $_SESSION['Bio'] = $bio;

?>