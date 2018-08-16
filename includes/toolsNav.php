<div id="toolsNav">
    <ul>
<!--         <li><a <?php print($toolsTab === "editorHistory" ? 'class="active"' : ''); ?> href="/editorHistory.php">Editor History</a></li> -->
        <li><a <?php print($toolsTab === "editorpage" ? 'class="active"' : ''); ?> href="/editorpage.php">Editor Page</a></li>
        <li><a <?php print($toolsTab === "usermanagement" && $_SESSION['usertype'] === 'A' ? 'class="active"' : ''); ?> href="/userManagement.php">Manage Users</a></li>
        <li><a <?php print($toolsTab === "articlemanagement" && $_SESSION['usertype'] === 'A' ? 'class="active"' : ''); ?> href="/articleManagement.php">Manage Articles</a></li>
        <li><a <?php print($toolsTab === "commentmanagement" && $_SESSION['usertype'] === 'A' ? 'class="active"' : ''); ?> href="/commentManagement.php">Manage Comments</a></li>
    </ul>
</div>
