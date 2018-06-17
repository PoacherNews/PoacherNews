<?php
// set $username to $_SESSION['username'] if blank
$username == $_GET['Username'];

if($username == '')
{
	$username = $_SESSION['username'];
}
?>

<ul>
    <li <?php if($current == 'overview') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/profile.php?Username=".$username."'>";
    echo "General</a>";
    ?>
    </li>
    
    <li <?php if($current == 'comments') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/comments.php?Username=".$username."'>";
    echo "Comments</a>";
    ?>
    </li>
    
    <li <?php if($current == 'favorites'){echo 'class="current"';} ?>>
    <?php
    echo "<a href='/favorites.php?Username=".$username."'>";
    echo "Favorites</a>";
    ?>
    </li>
    
    <!-- Editor Tools -->
    <li <?php if($current == 'editorHistory') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/editorHistory.php?Username=".$username."'>";
    echo "Editor History</a>";
    ?>
    </li>
    
    <?php if($_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A') { ?>
    <li <?php if($current == 'editorPage') {echo 'class="current"';} ?>><a href="/editorpage.php">Editor Page</a></li>
    <?php } ?>
    
    <!-- Admin Tools -->
    <?php if($_SESSION['usertype'] == 'A') { ?>
    <li <?php if($current == 'manageUsers'){echo 'class="current"';} ?>><a href="/userManagement.php">Manage Users</a></li>
    <li <?php if($current == 'manageArticles'){echo 'class="current"';} ?>><a href="/articleManagement.php">Manage Articles</a></li>
    <?php } ?>
</ul>