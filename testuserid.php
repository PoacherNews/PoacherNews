<?php

include "util/db.php";

$result = mysqli_query($db, "SELECT MAX(UserID) FROM User");
$row = mysqli_fetch_row($result);

   	$_SESSION['userid'] = 30;

echo "{$_SESSION['userid']}";
?>