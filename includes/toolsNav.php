<ul>
    <?php if($_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A') { ?>
    <li <?php if($current == 'editorHistoryTools') {echo 'class="current"';} ?>><a href="/editorHistory.php">Editor History</a></li>    
    <li <?php if($current == 'editorPage') {echo 'class="current"';} ?>><a href="/editorpage.php">Editor Page</a></li>
    <?php } ?>
    
    <!-- Admin Tools -->
    <?php if($_SESSION['usertype'] == 'A') { ?>
    <li <?php if($current == 'manageUsers'){echo 'class="current"';} ?>><a href="/userManagement.php">Manage Users</a></li>
    <li <?php if($current == 'manageArticles'){echo 'class="current"';} ?>><a href="/articleManagement.php">Manage Articles</a></li>
    <li <?php if($current == 'manageComments'){echo 'class="current"';} ?>><a href="/commentManagement.php">Manage Comments</a></li>

    <?php } ?>
</ul>