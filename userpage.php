<!DOCTYPE html>
<html>
    <head>
	<?php include 'includes/globalHead.html' ?>
    </head>
       
    <body>
    
    <?php
	include 'loginCheck.php';
        include 'includes/header.php';
        include 'includes/nav.php';
            //include 'includes/footer.html';
        ?>
        <br>

        <!-- Content Head -->
        <div class="PageRow">
            <div class="PageBorder"></div>

        <div class="PageColumn">
        <?php if($_SESSION['usertype'] == NULL) { ?>
            <div>
                <p class="tools">Error Not Logged In</p>
            </div>
        <?php } ?>

        <?php if($_SESSION['usertype'] == 'U' || $_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A') { ?>
	       <div>
               <h1 class="tools">Favorite Articles</h1>
           </div>
        <?php } ?>
           
        <?php if($_SESSION['usertype'] == 'W' || $_SESSION['usertype'] == 'A') { ?>
            <div>   
	       <h1 class="tools">Writer Tools</h1>
               <a href="editorpage.php">Editor Page</a>
	        </div>
        <?php } ?>
      
        <?php if($_SESSION['usertype'] == 'A') { ?>
            <div>
                <h1 class="tools">Admin Tools</h1>
		<a href="userManagement.php">Manage Users</a>
		<br>
                <a href="articleManagement.php">Manage Articles</a>
            </div>
            <?php } ?>
        </div>
            <div class="PageBorder"></div>
        </div>
        <br>
        <!-- Content Tail -->
    </body>
</html>

