<?php
if (isset($_SESSION['previous'])) {
   if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
        session_destroy();
        header("location: /logout.php");
        ### or alternatively, you can use this for specific variables:
        ### unset($_SESSION['varname']);
   }
}
?>