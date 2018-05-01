<?php
// TODO:
// Change redirect?

if(!session_start()) {
    header("Location: error.php");
    exit;
}
/*
        Destroying all session data
        http://www.php.net/manual/en/function.session-destroy.php
*/

// Unset all session variables
session_unset();

// If the session was propagated using a cookie, remove that cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 1,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]);
}

// Destroy the session
session_destroy();

// Redirect
// To previous page
//header('Location: ' . $_SERVER['HTTP_REFERER']);
// To login.php
header("Location: login.php");
exit;
?>
