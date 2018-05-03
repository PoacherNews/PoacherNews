<?php
// the @ suppresses session warnings (we can print it later with error_get_last()
if(!@session_start()) {
    // special case if we're in a directory index
    if (preg_match('/[^(.php)]$/', $_SERVER['REQUEST_URI']))
    {
        // header() doesn't work when the file
        // is included in Apache's mod_autoindex
        // thus we simply return
        return;
    }
    // If the session couldn't start, present an error
    header("Location: error.php");
    exit;
}
if (session_status() == PHP_SESSION_NONE) {
}
else  {
}
// got rid of ternary operator for clarity
if (empty($_SESSION['loggedin'])) {
    $loggedin = false;
}
else {
    $loggedin = $_SESSION['loggedin'];
    //$name = $_SESSION['name'];
}
?>
