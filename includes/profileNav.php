<ul>
    <li <?php if($current == 'overview') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/profile.php?uid=".$userid."'>";
    echo "General</a>";
    ?>
    </li>
    
    <li <?php if($current == 'comments') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/comments.php?uid=".$userid."'>";
    echo "Comments</a>";
    ?>
    </li>
    
    <li <?php if($current == 'bookmarks'){echo 'class="current"';} ?>>
    <?php
    echo "<a href='/bookmarks.php?uid=".$userid."'>";
    echo "Bookmarks</a>";
    ?>
    </li>
    
    <!-- Editor Tools -->
    <?php if($usertype == 'W' || $usertype == 'A') { ?>
    <li <?php if($current == 'editorHistoryProfile') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/editorHistory.php?uid=".$userid."'>";
    echo "Editor History</a>";
    ?>
    </li>
    <?php } ?>
</ul>