<?php
define('DB_SERVER', 'poacherdatabase.ccbtf4xhozoc.us-east-2.rds.amazonaws.com');
define('DB_USERNAME', 'mysqladmin');
define('DB_PASSWORD', 'Hunter1234');
define('DB_DATABASE', 'PoacherNews');
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
?>