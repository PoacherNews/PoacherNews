<?php
	// set $username to $_SESSION['username'] if blank
	$username == $_GET['Username'];

	if($username == '')
	{
		$username = $_SESSION['username'];
	}

	// retrive usertype of $username
	include 'util/db.php';
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    
    $sql = "SELECT * FROM User WHERE Username = '".$username."'";
    $result = $db->query($sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $usertype = $row['Usertype'];   
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
    <?php if($usertype == 'W' || $usertype == 'A') { ?>
    <li <?php if($current == 'editorHistory') {echo 'class="current"';} ?>>
    <?php
    echo "<a href='/editorHistory.php?Username=".$username."'>";
    echo "Editor History</a>";
    ?>
    </li>
    <?php } ?>
</ul>
