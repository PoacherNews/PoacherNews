<!-- included in:
	../index.php line 4
	../includes/header.php line 3
-->
<?php
	// If user set $_SESSION['tfaURL'] upon access of TFA.php (../TFA.php line 85)
	// And URL is not $_SESSION['tfaURL'] (TFA.php)
	// Destroy the session and redirect user to ../login.php
	if (isset($_SESSION['tfaURL']) && basename($_SERVER['PHP_SELF']) != $_SESSION['tfaURL']) {
			session_destroy();
			header("location: /login.php");
	}
?>