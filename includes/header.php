<header>
    <div class="hdrLeft"></div>
    <div class="hdrCenter">
        <img class="hdrLogo" src="res/img/logo.png">
        <h1 class="hdrTitle"><a class="hdrA" href="index.php">The Poacher</a></h1>
    </div>
    <div class="hdrRight">
        <a href="search.php"><i class="fa fa-search"></i></a>
        <a href="login.php"><i class="fa fa-user-circle"></i></a>
        <?php
            session_start();
            if($_SESSION['loggedin']) {
                print "<a href=\"logout.php\"><i class=\"fa fa-sign-out\"></i></a>";
                print "<a class=\"username\" href=\"#\">{$_SESSION['username']}</a>"; //TODO: Link to user's userpage 
            }
        ?>
    </div>
</header>
