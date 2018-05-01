<?php
// Applied to the current pages so far:
// index.php
// section.php
// userpage.php
// editorpage.php

	include 'loginCheck.php';
?>

        <header>
            <div class="hdrLeft">
            </div>
            <div class="hdrCenter">
                <img class="hdrLogo" src="res/img/logo.png">
                <h1 class="hdrTitle"><a class="hdrA" href="index.php">The Poacher</a></h1>
            </div>
            <div class="hdrRight">
		        <p><?php echo "Username: {$_SESSION['username']}"; ?></p>
                <a class="hdrA" href="search.php"><i class="hdrSearch fa fa-search"></i></a>
                <a class="hdrA" href="logout.php"><i class="hdrLogout fa fa-sign-out"></i></a>
                <a class="hdrA" href="userpage.php"><i class="hdrUserPage fa fa-gavel"></i></a>
            </div>
        </header>

