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
    <?php if($usertype == 'W' || $usertype == 'A') { ?>
    <li <?php if($current == 'editorHistoryProfile') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/editorHistory.php?Username=".$username."'>";
    echo "Editor History</a>";
    ?>
    </li>
    <?php } ?>
</ul>