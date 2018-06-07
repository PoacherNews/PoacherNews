<ul>
    <li <?php if($current == 'overview') {echo 'class="current"';} ?>><a href="/profile.php">General</a></li>
    <li <?php if($current == 'comments') {echo 'class="current"';} ?>><a href="/comments.php">Comments</a></li>
    <li <?php if($current == 'favorites'){echo 'class="current"';} ?>><a href="/favorites.php">Favorites</a></li>
    
    <!-- Editor Tools -->
    <?php if($_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A') { ?>
    <li <?php if($current == 'editorHistory') {echo 'class="current"';} ?>><a href="/editorHistory.php">Editor History</a></li>
    <li <?php if($current == 'editorPage') {echo 'class="current"';} ?>><a href="/editorpage.php">Editor Page</a></li>
    <?php } ?>
    
    <!-- Admin Tools -->
    <?php if($_SESSION['usertype'] == 'A') { ?>
    <li <?php if($current == 'manageUsers'){echo 'class="current"';} ?>><a href="/userManagement.php">Manage Users</a></li>
    <li <?php if($current == 'manageArticles'){echo 'class="current"';} ?>><a href="/articleManagement.php">Manage Articles</a></li>
    <?php } ?>
</ul>